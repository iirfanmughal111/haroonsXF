<?php

namespace nick97\TraktMovies\Cron;

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
		if (array_key_exists('trakt_id', $columns))
		{
			$movies = $db->fetchAll('SELECT * FROM xf_thread WHERE trakt_id > "" AND trakt_id > 0 ORDER BY post_date DESC limit 6');
		}

		if (!$movies)
		{
			// REMOVE OLD XF1 FIELDS
			$sm->alterTable('xf_thread', function (Alter $table) {
				$table->dropColumns([
					'trakt_id',
					'trakt_title',
					'trakt_plot',
					'trakt_image',
					'trakt_genres',
					'trakt_director',
					'trakt_cast',
					'trakt_release',
					'trakt_tagline',
					'trakt_runtime',
					'trakt_rating',
					'trakt_votes',
					'trakt_thread'
				]);
			});

			// DELETE OLD BB CODES
			$db->delete('xf_bb_code', 'addon_id = ?', 'nick97/TraktMovies');

			// DISABLE CRON TASK
			$db->update('xf_cron_entry', ['active' => 0], 'entry_id = ?', 'traktMoviesConvert');
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
							if ($thread['trakt_plot'] > '' && $thread['trakt_plot'] !== NULL)
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

				/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
				$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
				$traktClient = $apiHelper->getClient();

				$movie = $traktClient->getMovie($thread['trakt_id'])->getDetails(['casts', 'trailers', 'videos']);
				if ($traktClient->hasError())
				{
					continue;
				}

				if (!isset($movie['title']))
				{
					$filename = 'data/movies/conversionerror.log';
					$somecontent = 'No movie title supplied by TRAKT - Thread: ' . $thread['thread_id'] . ' TRAKT ID:' . $thread['trakt_id'] . "\r\n";
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

				$posterpath = $thread['trakt_image'];

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
					self::saveImage($posterpath, $app->options()->traktthreads_smallPosterSize, $path, $tempPath);

					$path = 'data://movies/LargePosters' . $posterpath;
					self::saveImage($posterpath, $app->options()->traktthreads_largePosterSize, $path, $tempPath);
				}

				$cast = $thread['trakt_cast'];
				if ($cast === NULL)
				{
					$cast = '';
				}

				/** @var \nick97\TraktMovies\Entity\Movie $newMovie */
				$newMovie = $app->em()->create('nick97\TraktMovies:Movie');
				$newMovie->thread_id = $thread['thread_id'];
				$newMovie->trakt_id = $thread['trakt_id'];
				$newMovie->trakt_title = $thread['trakt_title'];
				$newMovie->trakt_plot = $thread['trakt_plot'];
				$newMovie->trakt_image = $posterpath;
				$newMovie->trakt_genres = $thread['trakt_genres'];
				$newMovie->trakt_director = $thread['trakt_director'];
				$newMovie->trakt_cast = $cast;
				$newMovie->trakt_release = $thread['trakt_release'];
				$newMovie->trakt_tagline = $thread['trakt_tagline'];
				$newMovie->trakt_runtime = $thread['trakt_runtime'];
				$newMovie->trakt_rating = $thread['trakt_rating'];
				$newMovie->trakt_votes = $thread['trakt_votes'];
				$newMovie->trakt_trailer = $trailer;
				if (!$app->options()->traktthreads_force_comments) $newMovie->comment = $comment;
				$newMovie->save(false, false);

				if (isset($post->message))
				{
					$message = $newMovie->getPostMessage();

					if (!$app->options()->traktthreads_force_comments)
					{
						$message .= $comment;
					}

					$post->message = $message;
					$post->save(false, false);

					// RETAINS FIRST POST AS SECOND POST WHEN REGULAR MOVIE INFO IS NOT PRESENT IN FIRST POST
					if ($comment && $app->options()->traktthreads_force_comments)
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

				$db->update('xf_thread', ['trakt_id' => 0], 'thread_id = ?', $thread['thread_id']);
			}
		}
	}

	protected static function saveImage($srcPath, $size, $localPath, $tempPath, &$error = null)
	{
		$traktApi = new \nick97\TraktMovies\Trakt\Image();
		$poster = $traktApi->getImage($srcPath, $size);
		if ($traktApi->hasError())
		{
			$error = $traktApi->getError();
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