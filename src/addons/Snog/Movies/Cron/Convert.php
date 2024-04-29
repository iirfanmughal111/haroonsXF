<?php

namespace Snog\Movies\Cron;

use XF\Db\Schema\Alter;
use XF\Util\File;

class Convert
{
	public static function Process()
	{
		$app = \XF::app();
		$db = \XF::db();
		$sm = $db->getSchemaManager();
		$columns = $sm->getTableColumnDefinitions('xf_thread');

		$movies = '';
		if (array_key_exists('tmdb_id', $columns))
		{
			$movies = $db->fetchAll('SELECT * FROM xf_thread WHERE tmdb_id > "" AND tmdb_id > 0 ORDER BY post_date DESC limit 6');
		}

		if (!$movies)
		{
			// REMOVE OLD XF1 FIELDS
			$sm->alterTable('xf_thread', function (Alter $table) {
				$table->dropColumns([
					'tmdb_id',
					'tmdb_title',
					'tmdb_plot',
					'tmdb_image',
					'tmdb_genres',
					'tmdb_director',
					'tmdb_cast',
					'tmdb_release',
					'tmdb_tagline',
					'tmdb_runtime',
					'tmdb_rating',
					'tmdb_votes',
					'tmdb_thread'
				]);
			});

			// DELETE OLD BB CODES
			$db->delete('xf_bb_code', 'addon_id = ?', 'Snog/Movies');

			// DISABLE CRON TASK
			$db->update('xf_cron_entry', ['active' => 0], 'entry_id = ?', 'snogMoviesConvert');
		}
		else
		{
			foreach ($movies as $thread)
			{
				$comment = '';

				/** @var \XF\Entity\Post $post */
				$post = $app->finder('XF:Post')->where('post_id', $thread['first_post_id'])->fetchOne();

				if (isset($post->message))
				{
					$message = $post->message;

					if (!strstr($message, '[/CONTAINER]'))
					{
						$comment = $message;
					}
					else
					{
						$split = explode("[/media]", $message);
						if (!isset($split[1]))
						{
							if ($thread['tmdb_plot'] > '' && $thread['tmdb_plot'] !== NULL)
							{
								$split2 = explode('[/CONTAINER]', $message);
								if (isset($split2[1]) && !stristr($split2[1], 'media'))
								{
									$comment = $split2[1];
								}
							}
						}
						else
						{
							$comment = $split[1];
						}
					}
				}

				if ($comment == '[/PLOT][/MOVIE][/CONTAINER]')
				{
					$comment = '';
				}

				/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
				$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
				$tmdbClient = $apiHelper->getClient();

				$movie = $tmdbClient->getMovie($thread['tmdb_id'])->getDetails(['casts', 'trailers', 'videos']);
				if ($tmdbClient->hasError())
				{
					continue;
				}

				if (!isset($movie['title']))
				{
					$filename = 'data/movies/conversionerror.log';
					$somecontent = 'No movie title supplied by TMDB - Thread: ' . $thread['thread_id'] . ' TMDB ID:' . $thread['tmdb_id'] . "\r\n";
					$handle = fopen($filename, 'a');
					fwrite($handle, $somecontent);
					fclose($handle);
					continue;
				}

				$trailer = '';
				foreach ($movie['trailers']['youtube'] as $source)
				{
					$trailer = $source['source'];
				}

				$posterpath = $thread['tmdb_image'];

				if (isset($movie['poster_path']))
				{
					if ($posterpath > '')
					{
						$tempPath = File::getTempDir() . $posterpath;
					}
					else
					{
						$tempPath = File::getTempDir() . $movie['poster_path'];
					}

					$posterpath = $movie['poster_path'];
					$path = 'data://movies/SmallPosters' . $posterpath;
					self::saveImage($posterpath, $app->options()->tmdbthreads_smallPosterSize, $path, $tempPath);

					$path = 'data://movies/LargePosters' . $posterpath;
					self::saveImage($posterpath, $app->options()->tmdbthreads_largePosterSize, $path, $tempPath);
				}

				$cast = $thread['tmdb_cast'];
				if ($cast === NULL)
				{
					$cast = '';
				}

				/** @var \Snog\Movies\Entity\Movie $newMovie */
				$newMovie = $app->em()->create('Snog\Movies:Movie');
				$newMovie->thread_id = $thread['thread_id'];
				$newMovie->tmdb_id = $thread['tmdb_id'];
				$newMovie->tmdb_title = $thread['tmdb_title'];
				$newMovie->tmdb_plot = $thread['tmdb_plot'];
				$newMovie->tmdb_image = $posterpath;
				$newMovie->tmdb_genres = $thread['tmdb_genres'];
				$newMovie->tmdb_director = $thread['tmdb_director'];
				$newMovie->tmdb_cast = $cast;
				$newMovie->tmdb_release = $thread['tmdb_release'];
				$newMovie->tmdb_tagline = $thread['tmdb_tagline'];
				$newMovie->tmdb_runtime = $thread['tmdb_runtime'];
				$newMovie->tmdb_rating = $thread['tmdb_rating'];
				$newMovie->tmdb_votes = $thread['tmdb_votes'];
				$newMovie->tmdb_trailer = $trailer;
				if (!$app->options()->tmdbthreads_force_comments) $newMovie->comment = $comment;
				$newMovie->save(false, false);

				if (isset($post->message))
				{
					$message = $newMovie->getPostMessage();

					if (!$app->options()->tmdbthreads_force_comments)
					{
						$message .= $comment;
					}

					$post->message = $message;
					$post->save(false, false);

					// RETAINS FIRST POST AS SECOND POST WHEN REGULAR MOVIE INFO IS NOT PRESENT IN FIRST POST
					if ($comment && $app->options()->tmdbthreads_force_comments)
					{
						/** @var \XF\Entity\Post $newpost */
						$newpost = $app->em()->create('XF:Post');
						$newpost->thread_id = $thread['thread_id'];
						$newpost->user_id = $thread['user_id'];
						$newpost->username = $thread['username'];
						$newpost->post_date = $thread['post_date'];
						$newpost->message = $comment;
						$newpost->ip_id = $post->ip_id;
						$newpost->position = 1;
						$newpost->last_edit_date = 0;
						$newpost->save();

						/** @var \XF\Entity\Post[] $postorder */
						$postorder = $app->finder('XF:Post')
							->where('thread_id', $thread['thread_id'])
							->order('post_date')
							->fetch();

						$order = 1;
						foreach ($postorder as $changeorder)
						{
							if ($changeorder->post_id <> $thread['first_post_id'])
							{
								$changeorder->position = $order;
								$changeorder->save();
								$order = $order + 1;
							}
						}
					}
				}

				$db->update('xf_thread', ['tmdb_id' => 0], 'thread_id = ?', $thread['thread_id']);
			}
		}
	}

	protected static function saveImage($srcPath, $size, $localPath, $tempPath, &$error = null)
	{
		$tmdbApi = new \Snog\Movies\Tmdb\Image();
		$poster = $tmdbApi->getImage($srcPath, $size);
		if ($tmdbApi->hasError())
		{
			$error = $tmdbApi->getError();
			return;
		}

		if (file_exists($tempPath))
		{
			unlink($tempPath);
		}
		file_put_contents($tempPath, $poster);
		File::copyFileToAbstractedPath($tempPath, $localPath);
		unlink($tempPath);
	}
}