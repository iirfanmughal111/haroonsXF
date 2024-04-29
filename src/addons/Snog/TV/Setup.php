<?php

namespace Snog\TV;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XF\Util\File;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	// ################################## INSTALL ###########################################

	public function installStep1()
	{
		$sm = $this->schemaManager();

		foreach ($this->getTables() as $tableName => $callback)
		{
			$sm->createTable($tableName, $callback);
		}
	}

	public function installStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function installStep3()
	{
		$db = $this->db();
		$db->insert('xf_forum_type', [
			'forum_type_id' => 'snog_tv',
			'handler_class' => 'Snog\TV:TV',
			'addon_id' => 'Snog/TV'
		]);

		$db->insert('xf_thread_type', [
			'thread_type_id' => 'snog_tv',
			'handler_class' => 'Snog\TV:TV',
			'addon_id' => 'Snog/TV'
		]);

		/** @var \XF\Repository\ForumType $forumTypeRepo */
		$forumTypeRepo = $this->app->repository('XF:ForumType');
		$forumTypeRepo->rebuildForumTypeCache();

		/** @var \XF\Repository\ThreadType $threadTypeRepo */
		$threadTypeRepo = $this->app->repository('XF:ThreadType');
		$threadTypeRepo->rebuildThreadTypeCache();
	}

	public function installStep4()
	{
		$src = 'src/addons/Snog/TV/defaultdata';
		$this->copyContents($src);
	}

	// ################################## UNINSTALL ###########################################

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName)
		{
			$sm->dropTable($tableName);
		}
	}

	public function uninstallStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getReverseAlters() as $table => $schema)
		{
			if ($sm->tableExists($table))
			{
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function uninstallStep3()
	{
		$db = $this->db();
		$db->delete('xf_forum_type', 'forum_type_id = ?', 'snog_tv');
		$db->delete('xf_thread_type', 'thread_type_id = ?', 'snog_tv');

		/** @var \XF\Repository\ForumType $forumTypeRepo */
		$forumTypeRepo = $this->app->repository('XF:ForumType');
		$forumTypeRepo->rebuildForumTypeCache();

		/** @var \XF\Repository\ThreadType $threadTypeRepo */
		$threadTypeRepo = $this->app->repository('XF:ThreadType');
		$threadTypeRepo->rebuildThreadTypeCache();
	}

	// ################################## DATA ###########################################

	protected function getTables(): array
	{
		$tables = [];

		$tables['xf_snog_tv_forum'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('node_id', 'INT', 10);
			$table->addColumn('tv_parent', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_id', 'INT', 10)->setDefault(0);
			$table->addColumn('imdb_id', 'VARCHAR', 32)->setDefault(0);
			$table->addColumn('tv_parent_id', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_image', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_genres', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_director', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_release', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_rating')->type('decimal', '3,2')->setDefault(0);
			$table->addColumn('tv_votes', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_cast', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_plot', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_thread', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('tv_issub', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('tv_checked', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);
			$table->addPrimaryKey('node_id');
			$table->addKey('tv_id');
		};

		$tables['xf_snog_tv_post'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('post_id', 'INT', 10);
			$table->addColumn('tv_id', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_image', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_plot', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_cast', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_guest', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_aired', 'VARCHAR', 10)->setDefault('');
			$table->addColumn('message', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_checked', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);
			$table->addPrimaryKey('post_id');
			$table->addKey('tv_id');
		};

		$tables['xf_snog_tv_ratings'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('rating_id', 'INT', 10)->autoIncrement();
			$table->addColumn('thread_id', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('rating', 'TINYINT', 1)->setDefault(0);
			$table->addPrimaryKey('rating_id');
			$table->addKey('thread_id');
			$table->addKey('user_id');
		};

		$tables['xf_snog_tv_ratings_node'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('rating_id', 'INT', 10)->autoIncrement();
			$table->addColumn('node_id', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('rating', 'TINYINT', 1)->setDefault(0);
			$table->addPrimaryKey('rating_id');
			$table->addKey('node_id');
			$table->addKey('user_id');
		};

		$tables['xf_snog_tv_thread'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('thread_id', 'INT', 10);
			$table->addColumn('tv_id', 'INT', 10)->setDefault(0);
			$table->addColumn('imdb_id', 'VARCHAR', 32)->setDefault(0);
			$table->addColumn('tv_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_image', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('backdrop_path', 'varchar', 150)->setDefault('');

			$table->addColumn('tv_trailer', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('tv_genres', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_director', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_cast', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_release', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_rating')->type('decimal', '3,2')->setDefault(0);
			$table->addColumn('tv_votes', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_plot', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_thread', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('tv_checked', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('comment', 'TEXT')->setDefault(NULL);
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);

			$table->addColumn('first_air_date', 'BIGINT')->setDefault(0)->unsigned(false);
			$table->addColumn('last_air_date', 'BIGINT')->setDefault(0)->unsigned(false);
			$table->addColumn('status', 'VARCHAR', 150)->setDefault('');

			$table->addColumn('tmdb_watch_providers', 'MEDIUMBLOB');
			$table->addColumn('tmdb_production_company_ids', 'MEDIUMBLOB')->nullable()->setDefault(null);
			$table->addColumn('tmdb_network_ids', 'MEDIUMBLOB')->nullable()->setDefault(null);

			$table->addColumn('tmdb_last_change_date', 'BIGINT')->setDefault(0)->unsigned(false);

			$table->addPrimaryKey('thread_id');
			$table->addKey('tv_id');
		};

		$tables['xf_snog_tv_person'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('person_id', 'INT', 10)->primaryKey();

			$table->addColumn('profile_path', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('imdb_person_id', 'VARCHAR', 32)->setDefault('');

			$table->addColumn('adult', 'TINYINT', 3)->setDefault(0);
			$table->addColumn('name', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('also_known_as', 'TEXT');
			$table->addColumn('gender', 'TINYINT')->setDefault(0);
			$table->addColumn('biography', 'TEXT');

			$table->addColumn('birthday', 'INT', 10)->setDefault(0);
			$table->addColumn('deathday', 'INT', 10)->setDefault(0);

			$table->addColumn('homepage', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('place_of_birth', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('known_for_department', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0);
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0);

			$table->addColumn('popularity')->type('decimal', '10,3')->setDefault(0);
		};

		$tables['xf_snog_tv_cast'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('cast_id', 'INT', 10)->setDefault(0);

			$table->addColumn('character', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('known_for_department', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('roles', 'MEDIUMBLOB')->nullable()->setDefault(null);
			$table->addColumn('total_episode_count', 'INT', 10)->setDefault(0);

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0)->setDefault(0);

			$table->addUniqueKey(['tv_id', 'tv_season', 'tv_episode', 'person_id']);
			$table->addKey('tv_id');
			$table->addKey('person_id');
		};

		$tables['xf_snog_tv_crew'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('known_for_department', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('department', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('job', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('jobs', 'MEDIUMBLOB')->nullable()->setDefault(null);
			$table->addColumn('total_episode_count', 'INT', 10)->setDefault(0);

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0)->setDefault(0);

			$table->addUniqueKey(['tv_id', 'tv_season', 'tv_episode', 'person_id']);
			$table->addKey('tv_id');
			$table->addKey('tv_season');
			$table->addKey('tv_episode');
			$table->addKey('person_id');
		};

		$tables['xf_snog_tv_video'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('video_id', 'VARCHAR', 24);

			$table->addColumn('name', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('key', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('site', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('size', 'INT')->setDefault(0);
			$table->addColumn('type', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('official', 'TINYINT', 3)->setDefault(0);
			$table->addColumn('published_at', 'INT')->setDefault(0);

			$table->addColumn('iso_639_1', 'VARCHAR', 2);
			$table->addColumn('iso_3166_1', 'VARCHAR', 2);

			$table->addPrimaryKey(['tv_id', 'tv_season', 'tv_episode', 'video_id']);
			$table->addKey('tv_id');
			$table->addKey('tv_season');
			$table->addKey('tv_episode');
			$table->addKey('published_at');
		};

		$tables['xf_snog_tv_company'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('company_id', 'INT', 10)->primaryKey();

			$table->addColumn('name', 'VARCHAR', 255);
			$table->addColumn('description', 'TEXT')->nullable();
			$table->addColumn('headquarters', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('homepage', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('logo_path', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('origin_country', 'VARCHAR', 2)->setDefault('');

			$table->addColumn('parent_company_id', 'INT', 10)->setDefault(0);

			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0);
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0);

			$table->addKey('parent_company_id');
		};

		$tables['xf_snog_tv_network'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('network_id', 'INT', 10)->primaryKey();

			$table->addColumn('name', 'VARCHAR', 255);

			$table->addColumn('headquarters', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('homepage', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('logo_path', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('origin_country', 'VARCHAR', 2)->setDefault('');

			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0);
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0);
		};

		return $tables;
	}

	protected function getAlters()
	{
		$alters = [];

		$alters['xf_user'] = function (Alter $table) {
			$table->addColumn('snog_tv_thread_count', 'int')->setDefault(0);
		};

		$alters['xf_user_option'] = function (Alter $table) {
			$table->addColumn('snog_tv_tmdb_watch_region', 'CHAR', 2)->setDefault('US');
		};

		return $alters;
	}

	/**
	 * @return array
	 */
	protected function getReverseAlters()
	{
		$alters = [];

		$alters['xf_user'] = function (Alter $table) {
			$table->dropColumns([
				'snog_tv_thread_count'
			]);
		};

		$alters['xf_user_option'] = function (Alter $table) {
			$table->dropColumns([
				'snog_movies_tv_watch_region'
			]);
		};

		return $alters;
	}

	// ################################## UPGRADE ###########################################


	public function upgrade1000010Step1()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_tv_forum'");
		if ($tableExists) $sm->renameTable('xf_tv_forum', 'xf_snog_tv_forum');
	}

	public function upgrade1000010Step2()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_tv_thread'");

		if ($tableExists)
		{
			$sm->renameTable('xf_tv_thread', 'xf_snog_tv_thread');

			$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
				$table->dropColumns(['tv_cast_checked']);
				$table->dropColumns(['original_id']);
			});

			$update = ['tv_checked' => 0];
			$db->update('xf_snog_tv_thread', $update, 'tv_checked = 1');
		}
	}

	public function upgrade1000010Step3()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_tv_post'");

		if ($tableExists)
		{
			$sm->renameTable('xf_tv_post', 'xf_snog_tv_post');

			$sm->alterTable('xf_snog_tv_post', function (Alter $table) {
				$table->dropColumns(['tv_cast_checked']);
				$table->addColumn('message', 'TEXT')->setDefault(NULL);
			});
		}
	}

	public function upgrade1000010Step4()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_tv_ratings'");

		if ($tableExists)
		{
			$sm->renameTable('xf_tv_ratings', 'xf_snog_tv_ratings');

			// MAY NEED TO DROP MOVIE_ID KEY
			$sm->alterTable('xf_snog_tv_ratings', function (Alter $table) {
				$table->renameColumn('movie_id', 'thread_id');
			});
		}
	}

	public function upgrade1000010Step5()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_tv_ratings_node'");

		if ($tableExists)
		{
			$sm->renameTable('xf_tv_ratings_node', 'xf_snog_tv_ratings_node');

			// MAY NEED TO DROP MOVIE_ID KEY
			$sm->alterTable('xf_snog_tv_ratings_node', function (Alter $table) {
				$table->renameColumn('movie_id', 'node_id');
			});
		}
	}

	public function upgrade1000010Step6()
	{
		$db = $this->db();
		$sm = $this->schemaManager();

		$sm->createTable('xf_snog_tv_node', function (Create $table) {
			$table->addColumn('node_id', 'INT', 10);
			$table->addColumn('tv_genre', 'BLOB')->nullable();
			$table->addPrimaryKey('node_id');
		});

		// COPY OLD FORUM FIELD TO NEW TABLE
		$tmpData = $db->fetchAll("SELECT node_id, tv_genre FROM xf_forum WHERE tv_genre > ''");

		foreach ($tmpData as $data)
		{
			$genres = explode(',', $data['tv_genre']);

			$db->insert('xf_snog_tv_node', [
				'node_id' => $data['node_id'],
				'tv_genre' => serialize($genres)
			]);
		}

		// DROP OLD COLUMN
		$sm->alterTable('xf_forum', function (Alter $table) {
			$table->dropColumns(['tv_genre']);
		});
	}

	// CONVERT ADD-ON SETTINGS TO XF2 FORMAT
	public function upgrade1000010Step7()
	{
		$db = $this->db();
		$forum = $db->fetchRow("SELECT * FROM xf_option WHERE OPTION_ID = 'TvThreads_show_forum'");
		$newValue = '';
		$step1 = str_replace('{', '', $forum['option_value']);
		$step2 = str_replace('}', '', $step1);
		$step3 = explode(',', $step2);

		foreach ($step3 as $value)
		{
			$value = str_replace('"', '', $value);
			$expValue = explode(':', $value);
			if ($newValue) $newValue .= ',';
			$newValue .= $expValue[0];
		}
		$newValue = '[' . $newValue . ']';

		$update = ['option_value' => $newValue];
		$db->update('xf_option', $update, "OPTION_ID = 'TvThreads_show_forum'");

		$thread = $db->fetchRow("SELECT * FROM xf_option WHERE OPTION_ID = 'TvThreads_show_thread'");

		$newValue = '';
		$step1 = str_replace('{', '', $thread['option_value']);
		$step2 = str_replace('}', '', $step1);
		$step3 = explode(',', $step2);

		foreach ($step3 as $value)
		{
			$value = str_replace('"', '', $value);
			$expValue = explode(':', $value);
			if ($newValue) $newValue .= ',';
			$newValue .= $expValue[0];
		}
		$newValue = '[' . $newValue . ']';

		$update = ['option_value' => $newValue];
		$db->update('xf_option', $update, "OPTION_ID = 'TvThreads_show_thread'");
	}

	// UPDATE THREAD TABLE WITH SEASON/EPISODE INFO
	public function upgrade1000010Step8()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$threadChange = $db->fetchAll("SELECT tvthread.*, thread.node_id as node_id, forum.tv_id as mainid, forum.tv_season as tvseason, post.* 
			  FROM xf_snog_tv_thread as tvthread
			  LEFT JOIN xf_thread as thread
			  ON (thread.thread_id = tvthread.thread_id)
			  LEFT JOIN xf_snog_tv_forum as forum
			  ON (forum.node_id = thread.node_id)
			  LEFT JOIN xf_thread as xfthread
			  ON (xfthread.thread_id = thread.thread_id)
			  LEFT JOIN xf_post as post
			  ON (post.post_id = xfthread.first_post_id)
			  WHERE tvthread.tv_episode > 0 AND tvthread.tv_season = 0 AND tvthread.tv_checked = 0");

		foreach ($threadChange as $change)
		{
			$comment = '';
			$episode_name = $this->getValue($change['message'], '[EPISODENAME]', '[/EPISODENAME');
			$after_episode = explode('[/EPISODE]', $change['message']);
			if (isset($after_episode[1])) $comment = $after_episode[1];
			$update = ['tv_id' => $change['mainid'], 'tv_season' => $change['tvseason'], 'tv_title' => $episode_name, 'comment' => $comment, 'tv_checked' => 1];
			$db->update('xf_snog_tv_thread', $update, 'thread_id = ?', $change['thread_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 8,
					'version' => 1000010
				];
			}
		}
	}

	public function upgrade1000010Step9()
	{
		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_thread', $update, 'tv_checked = 1');
	}

	// REMOVE BB CODES FROM XF1 EPISODE POSTS & RECOMPILE GUEST STAR LIST
	public function upgrade1000010Step10()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);
		$options = \XF::options();
		$tvForums = $options->TvThreads_forum;

		// SOME SYSTEMS MAY HAVE NO POSTER IN DATABASE - REMOVE IT
		$update = ['tv_image' => ''];
		$db->update('xf_snog_tv_post', $update, 'tv_image = "/no-poster.png"');

		$postChange = $db->fetchAll("SELECT * FROM xf_post WHERE message LIKE '[EPISODE]%'");

		foreach ($postChange as $change)
		{
			$comment = '';
			$guests = '';
			$episodeInfo = '';
			$episode_image = '';
			$tvPostDeleted = false;

			$originalThread = $db->fetchRow("SELECT * FROM xf_thread WHERE thread_id = " . $change['thread_id']);
			$thread = $db->fetchRow("SELECT * FROM xf_snog_tv_thread WHERE thread_id = " . $change['thread_id']);

			if (!$thread || !in_array($originalThread['node_id'], $tvForums))
			{
				if ($thread && !in_array($originalThread['node_id'], $tvForums))
				{
					$db->delete("xf_snog_tv_thread", 'thread_id = ' . $thread['thread_id']);
				}

				if (!$thread || !in_array($originalThread['node_id'], $tvForums))
				{
					$db->delete('xf_snog_tv_post', 'post_id = ' . $change['post_id']);
					$tvPostDeleted = true;
				}
			}

			// TRY TO ACCOUNT FOR INCORRECT MANUALLY ENTERED POSTERS
			$change['message'] = str_ireplace('[EPISODEPOSTER]  [IMG]', '[EPISODEPOSTER][IMG]', $change['message']);
			$change['message'] = str_ireplace('[EPISODEPOSTER] [IMG]', '[EPISODEPOSTER][IMG]', $change['message']);
			$change['message'] = str_ireplace('[/IMG]  [/EPISODEPOSTER]', '[/IMG][/EPISODEPOSTER]', $change['message']);
			$change['message'] = str_ireplace('[/IMG] [/EPISODEPOSTER]', '[/IMG][/EPISODEPOSTER]', $change['message']);

			$tempimg = $this->getValue($change['message'], '[EPISODEPOSTER][IMG]', '[/IMG][/EPISODEPOSTER');
			$imgparts = explode('/', $tempimg);
			$partcount = count($imgparts);

			if ($partcount > 0)
			{
				$episode_image = '/' . $imgparts[$partcount - 1];
			}

			if ($episode_image == '/no-poster.png') $episode_image = '';
			$episode_name = $this->getValue($change['message'], '[EPISODENAME]', '[/EPISODENAME');
			$season = $this->getValue($change['message'], '[SEASON]', '[/SEASON');
			if (!$season) $season = 0;
			$episode = $this->getValue($change['message'], '[EPISODENUM]', '[/EPISODENUM]');
			if (!$episode) $episode = 0;

			// TRIM AIR DATE IF IT IS TOO LONG
			// THIS MAY RESULT IN ODD AIR DATES IF THE POST WAS MANUALLY EDITED TO SOMETHING OTHER THAN A DATE
			// SUCH AS 10 jul 2000 WILL BECOME 10 jul 200 WHEN IT SHOULD HAVE BEEN LEFT AS 2000-07-10
			$airdate = $this->getValue($change['message'], '[EPISODEDATE]', '[/EPISODEDATE]');
			$airdate = trim($airdate);
			$airdate = substr($airdate, 0, 10);

			$episode_description = $this->getValue($change['message'], '[EPISODEDES]', '[/EPISODEDES]');

			$guestvalue = $this->getValue($change['message'], '[B]GUEST STARS:[/B] ', '[/EPISODEDATA]');

			if ($guestvalue)
			{
				$guestarray = explode(',', $guestvalue);

				foreach ($guestarray as $guestitem)
				{
					if ($guests) $guests .= ', ';
					$guests .= $this->getValue($guestitem, '[ACTOR]', '[/ACTOR');
				}
			}

			$after_episode = explode('[/EPISODE]', $change['message']);
			if (isset($after_episode[1])) $comment = trim($after_episode[1], " \t\n\r\0");

			if (isset($thread['tv_id']) && $thread['tv_id'] > '' && !$tvPostDeleted)
			{
				$db->insert('xf_snog_tv_post', [
					'post_id' => $change['post_id'],
					'tv_id' => $thread['tv_id'],
					'tv_season' => $season,
					'tv_episode' => $episode,
					'tv_aired' => $airdate,
					'tv_image' => $episode_image,
					'tv_title' => $episode_name,
					'tv_plot' => $episode_description,
					'tv_guest' => $guests,
					'message' => $comment
				], 'true');

				$newTitle = $thread['tv_title'];

				if ($thread['tv_season'] && $thread['tv_episode'])
				{
					$forum = $db->fetchRow("SELECT * FROM xf_snog_tv_forum WHERE tv_id = " . $thread['tv_id']);
					$parentForum = $db->fetchRow("SELECT * FROM xf_snog_tv_forum WHERE tv_id = " . $forum['tv_parent_id']);
					$newTitle = $parentForum['tv_title'];
				}
			}
			else
			{
				$newTitle = $thread['title'];
			}

			$episodeInfo .= "[B]" . $newTitle . "[/B]" . "\r\n"; // THIS SHOULD BE THE MAIN TV TITLE
			$episodeInfo .= "[IMG]" . $this->getEpisodeImageUrl(($episode_image ?: '/no-poster.png')) . "[/IMG]" . "\r\n";
			$episodeInfo .= "[B]" . $episode_name . "[/B]" . "\r\n";
			$episodeInfo .= "[B]Season: [/B] " . $season . "\r\n";
			$episodeInfo .= "[B]Episode: [/B] " . $episode . "\r\n";
			$episodeInfo .= "[B]Air Date: [/B] " . $airdate . "\r\n\r\n";
			if (!empty($guests)) $episodeInfo .= "[B]GUEST STARS:[/B] " . $guests . "\r\n\r\n";
			$episodeInfo .= $episode_description . "\r\n";
			$message = $episodeInfo . "\r\n";
			if ($comment) $message .= $comment;

			$update = ['message' => $message];
			$db->update('xf_post', $update, 'post_id = ?', $change['post_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 10,
					'version' => 1000010
				];
			}
		}
	}

	// REMOVE BB CODES FROM FIRST POST IN XF1 TV THREADS
	public function upgrade1000010Step11()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$threadChange = $db->fetchAll("SELECT thread.*, xfthread.first_post_id 
			FROM xf_snog_tv_thread as thread
			LEFT JOIN xf_thread as xfthread
			ON (xfthread.thread_id = thread.thread_id)
			WHERE thread.tv_episode = 0 AND thread.tv_season = 0 AND thread.tv_checked = 0");

		foreach ($threadChange as $change)
		{
			if ($change['first_post_id'])
			{
				$post = $db->fetchRow("SELECT * FROM xf_post WHERE post_id = " . $change['first_post_id']);
				$comment = '';

				// ACCOUNT FOR THREADS WHERE FIRST POST WAS EDITED AND TV INFO WAS REMOVED
				if (!stristr($post['message'], '[/SERIES]'))
				{
					$comment = $post['message'];
				}
				else
				{
					$after_series = explode('[/SERIES]', $post['message']);

					if (isset($after_series[1])) $comment = trim($after_series[1], " \t\n\r\0");
				}

				$seriesInfo = "[B]" . $change['tv_title'] . "[/B]" . "\r\n";
				$seriesInfo .= "[IMG]" . $this->getSeriesImageUrl(($change['tv_image'] ?: '/no-poster.png')) . "[/IMG]" . "\r\n";
				$seriesInfo .= "[B]Genre: [/B]" . $change['tv_genres'] . "\r\n\r\n";
				$seriesInfo .= "[B]First aired: [/B] " . $change['tv_release'] . "\r\n\r\n";
				$seriesInfo .= "[B]Creator: [/B] " . $change['tv_director'] . "\r\n\r\n";
				$seriesInfo .= "[B]Cast: [/B] " . $change['tv_cast'] . "\r\n\r\n";
				$seriesInfo .= "[B]Overview: [/B]" . $change['tv_plot'] . "\r\n";
				$message = $seriesInfo . "\r\n";
				if ($comment) $message .= $comment;

				$update = ['message' => $message];
				$db->update('xf_post', $update, 'post_id = ?', $change['first_post_id']);

				if ($comment > '')
				{
					$update = ['comment' => $comment, 'tv_checked' => 1];
				}
				else
				{
					$update = ['tv_checked' => 1];
				}
				$db->update('xf_snog_tv_thread', $update, 'thread_id = ?', $change['thread_id']);
			}
			else
			{
				// DELETE THREADS FROM TV SYSTEM THAT NO LONGER EXIST
				$db->delete("xf_snog_tv_thread", 'thread_id = ' . $change['thread_id']);
			}

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 11,
					'version' => 1000010
				];
			}
		}
	}

	public function upgrade1000010Step12()
	{
		if (file_exists('data/tvthreads')) @rename(\XF::getRootDirectory() . '/data/tvthreads', \XF::getRootDirectory() . '/data/tv');
	}

	public function upgrade1000010Step13()
	{
		$oldPath = 'src/addons/Snog/TV/defaultdata/LargePosters/no-poster.png';
		$newPath = 'data://tv/LargePosters/no-poster.png';
		File::copyFileToAbstractedPath($oldPath, $newPath);

		$oldPath = 'src/addons/Snog/TV/defaultdata/EpisodePosters/no-poster.png';
		$newPath = 'data://tv/EpisodePosters/no-poster.png';
		File::copyFileToAbstractedPath($oldPath, $newPath);

		$oldPath = 'src/addons/Snog/TV/defaultdata/SmallPosters/no-poster.png';
		$newPath = 'data://tv/ForumPosters/no-poster.png';
		File::copyFileToAbstractedPath($oldPath, $newPath);

		$newPath = 'data://tv/SeasonPosters/no-poster.png';
		File::copyFileToAbstractedPath($oldPath, $newPath);

		$newPath = 'data://tv/SmallPosters/no-poster.png';
		File::copyFileToAbstractedPath($oldPath, $newPath);
	}

	public function upgrade2000070Step1()
	{
		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_thread', $update, 'tv_checked = 1');
	}

	public function upgrade2000670Step1()
	{
		$db = $this->db();

		$parentChanges = $db->fetchAll("SELECT * 
			FROM xf_snog_tv_forum
			WHERE tv_season > 0");

		foreach ($parentChanges as $parentChange)
		{
			$newParent = $db->fetchRow("SELECT * FROM xf_node WHERE `node_id` = " . $parentChange['node_id']);

			$update = ['tv_parent' => $newParent['parent_node_id']];
			$db->update('xf_snog_tv_forum', $update, 'node_id = ?', $parentChange['node_id']);
		}
	}

	public function upgrade2000870Step1()
	{
		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_post', $update, 'tv_checked = 1');
	}

	// ADD POST ID TO EPISODE IMAGES
	public function upgrade2000870Step2()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);
		$app = \XF::app();

		$postChange = $db->fetchAll("SELECT * FROM xf_snog_tv_post WHERE tv_image > '' AND tv_checked = 0");

		foreach ($postChange as $change)
		{
			$imageName = $change['post_id'] . '-' . str_ireplace('/', '', $change['tv_image']);

			$filePath = 'data://tv/EpisodePosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$tempPath = File::copyAbstractedPathToTempFile('data://tv/EpisodePosters' . $change['tv_image']);
				$path = 'data://tv/EpisodePosters/' . $imageName;
				File::copyFileToAbstractedPath($tempPath, $path);
				unlink($tempPath);
			}

			$update = ['tv_checked' => 1];
			$db->update('xf_snog_tv_post', $update, 'post_id = ' . $change['post_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 2,
					'version' => 2000870
				];
			}
		}

		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_post', $update, 'tv_checked = 1');
	}

	// REMOVE ORIGINAL EPISODE IMAGES
	public function upgrade2000870Step3()
	{
		$db = $this->db();
		$app = \XF::app();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$postChange = $db->fetchAll("SELECT * FROM xf_snog_tv_post WHERE tv_image > '' AND tv_checked = 0");

		foreach ($postChange as $change)
		{
			$filePath = 'data://tv/EpisodePosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$path = sprintf('data://tv/EpisodePosters%s', $change['tv_image']);
				File::deleteFromAbstractedPath($path);
			}

			$update = ['tv_checked' => 1];
			$db->update('xf_snog_tv_post', $update, 'post_id = ' . $change['post_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 3,
					'version' => 2000870
				];
			}
		}

		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_post', $update, 'tv_checked = 1');
	}

	// ADD THREAD ID TO TV POSTER IMAGES
	public function upgrade2000870Step4()
	{
		$db = $this->db();
		$app = \XF::app();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$postChange = $db->fetchAll("SELECT * FROM xf_snog_tv_thread WHERE tv_image > '' AND tv_checked = 0");

		foreach ($postChange as $change)
		{
			$posterName = $change['thread_id'] . '-' . str_ireplace('/', '', $change['tv_image']);

			// SMALL POSTER
			$filePath = 'data://tv/SmallPosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$tempPath = File::copyAbstractedPathToTempFile('data://tv/SmallPosters' . $change['tv_image']);
				$path = 'data://tv/SmallPosters/' . $posterName;
				File::copyFileToAbstractedPath($tempPath, $path);
				unlink($tempPath);
			}

			// LARGE POSTER
			$filePath = 'data://tv/LargePosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$tempPath = File::copyAbstractedPathToTempFile('data://tv/LargePosters' . $change['tv_image']);
				$path = 'data://tv/LargePosters/' . $posterName;
				File::copyFileToAbstractedPath($tempPath, $path);
				unlink($tempPath);
			}

			$update = ['tv_checked' => 1];
			$db->update('xf_snog_tv_thread', $update, 'thread_id = ' . $change['thread_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 4,
					'version' => 2000870
				];
			}
		}

		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_thread', $update, 'tv_checked = 1');
	}

	// REMOVE ORIGINAL TV POSTER IMAGES
	public function upgrade2000870Step5()
	{
		$db = $this->db();
		$app = \XF::app();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$postChange = $db->fetchAll("SELECT * FROM xf_snog_tv_thread WHERE tv_image > '' AND tv_checked = 0");

		foreach ($postChange as $change)
		{
			// SMALL POSTER
			$filePath = 'data://tv/SmallPosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$path = sprintf('data://tv/SmallPosters%s', $change['tv_image']);
				File::deleteFromAbstractedPath($path);
			}

			// LARGE POSTER
			$filePath = 'data://tv/LargePosters' . $change['tv_image'];

			if ($app->fs()->has($filePath))
			{
				$path = sprintf('data://tv/LargePosters%s', $change['tv_image']);
				File::deleteFromAbstractedPath($path);
			}

			$update = ['tv_checked' => 1];
			$db->update('xf_snog_tv_thread', $update, 'thread_id = ' . $change['thread_id']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				return [
					'complete' => false,
					'params' => [],
					'step' => 4,
					'version' => 2000870
				];
			}
		}

		$db = $this->db();
		$update = ['tv_checked' => 0];
		$db->update('xf_snog_tv_thread', $update, 'tv_checked = 1');
	}

	public function upgrade2001070Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('tv_trailer', 'VARCHAR', 255)->setDefault('');
		});
	}

	public function upgrade2010170Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);
		});
	}

	public function upgrade2010170Step2()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_post', function (Alter $table) {
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);
		});
	}

	public function upgrade2010170Step3()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_forum', function (Alter $table) {
			$table->addColumn('tv_poster', 'TINYINT', 1)->setDefault(0);
		});
	}

	// 2.2.0

	public function upgrade2020000Step1()
	{
		$db = $this->db();
		$db->insert('xf_forum_type', [
			'forum_type_id' => 'snog_tv',
			'handler_class' => 'Snog\TV:TV',
			'addon_id' => 'Snog/TV'
		]);

		$db->insert('xf_thread_type', [
			'thread_type_id' => 'snog_tv',
			'handler_class' => 'Snog\TV:TV',
			'addon_id' => 'Snog/TV'
		]);

		/** @var \XF\Repository\ForumType $forumTypeRepo */
		$forumTypeRepo = $this->app->repository('XF:ForumType');
		$forumTypeRepo->rebuildForumTypeCache();

		/** @var \XF\Repository\ThreadType $threadTypeRepo */
		$threadTypeRepo = $this->app->repository('XF:ThreadType');
		$threadTypeRepo->rebuildThreadTypeCache();
	}

	public function upgrade2020000Step2()
	{
		/** @var \XF\Entity\Forum[] $forums */
		$forums = $this->app->finder('XF:Forum')->where([
			'node_id' => $this->app->options()->TvThreads_forum
		]);
		if (!$forums)
		{
			return;
		}

		foreach ($forums as $forum)
		{
			$forum->forum_type_id = 'snog_tv';

			if (!empty($forum->type_config_['allowed_thread_types']))
			{
				$typeConfig = $forum->type_config_;
				$typeConfig['allowed_thread_types'][] = 'snog_tv';

				$forum->set('type_config', $typeConfig);
			}

			$forum->saveIfChanged();
		}
	}

	public function upgrade2020000Step3()
	{
		/** @var \XF\Entity\Thread[] $threads */
		$threads = $this->app->finder('XF:Thread')->where([
			'node_id' => $this->app->options()->TvThreads_forum,
			'discussion_type' => 'discussion'
		]);
		if (!$threads)
		{
			return;
		}

		foreach ($threads as $thread)
		{
			$thread->discussion_type = 'snog_tv';
			$thread->saveIfChanged();
		}
	}

	public function upgrade2020000Step4()
	{
		$tvForumIds = $this->db()->fetchAllColumn("
			SELECT node_id
			FROM xf_snog_tv_forum
		");

		$forumIds = $this->app->options()->TvThreads_forum + $tvForumIds;
		if (empty($forumIds))
		{
			return;
		}

		/** @var \XF\Entity\Forum[] $forums */
		$forums = $this->app->finder('XF:Forum')->where('node_id', '=', $forumIds);
		if (!$forums)
		{
			return;
		}

		$tvForums = $this->db()->fetchAllKeyed("SELECT * FROM xf_snog_tv_node", 'node_id');

		foreach ($forums as $forumId => $forum)
		{
			$forum->forum_type_id = 'snog_tv';
			$typeConfig = $forum->type_config_;

			if (!empty($typeConfig['allowed_thread_types']))
			{
				$typeConfig['allowed_thread_types'][] = 'snog_tv';
			}
			if (isset($tvForums[$forumId]))
			{
				$typeConfig['available_genres'] = @unserialize($tvForums[$forumId]['tv_genre']);
			}

			$forum->set('type_config', $typeConfig);

			$forum->saveIfChanged();
		}
	}

	public function upgrade2020000Step5()
	{
		$sm = $this->schemaManager();
		$sm->alterTable('xf_user', function (Alter $table) {
			$table->addColumn('snog_tv_thread_count', 'int')->setDefault(0);
		});
	}

	public function upgrade2020000Step6()
	{
		$sm = $this->schemaManager();

		$sm->createTable('xf_snog_tv_person', function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('person_id', 'INT', 10)->primaryKey();

			$table->addColumn('profile_path', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('imdb_person_id', 'VARCHAR', 32)->setDefault('');

			$table->addColumn('adult', 'TINYINT', 3)->setDefault(0);
			$table->addColumn('name', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('also_known_as', 'TEXT');
			$table->addColumn('gender', 'TINYINT')->setDefault(0);
			$table->addColumn('biography', 'TEXT');

			$table->addColumn('birthday', 'INT', 10)->setDefault(0);
			$table->addColumn('deathday', 'INT', 10)->setDefault(0);

			$table->addColumn('homepage', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('place_of_birth', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('known_for_department', 'VARCHAR', 150)->setDefault('');

			$table->addColumn('popularity')->type('decimal', '10,3')->setDefault(0);
		});

		$sm->createTable('xf_snog_tv_cast', function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('cast_id', 'INT', 10)->setDefault(0);

			$table->addColumn('character', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('known_for_department', 'VARCHAR', 150)->setDefault('');

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0);

			$table->addUniqueKey(['tv_id', 'tv_season', 'tv_episode', 'person_id']);
			$table->addKey('tv_id');
			$table->addKey('person_id');
		});

		$sm->createTable('xf_snog_tv_crew', function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('known_for_department', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('department', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('job', 'VARCHAR', 150)->setDefault('');

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0);

			$table->addUniqueKey(['tv_id', 'tv_season', 'tv_episode', 'person_id']);
			$table->addKey('tv_id');
			$table->addKey('tv_season');
			$table->addKey('tv_episode');
			$table->addKey('person_id');
		});
	}

	public function upgrade2020001Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('imdb_id', 'VARCHAR', 32)->setDefault('')->after('tv_id');
		});

		$sm->alterTable('xf_snog_tv_forum', function (Alter $table) {
			$table->addColumn('imdb_id', 'VARCHAR', 32)->setDefault('')->after('tv_id');
		});
	}

	public function upgrade2020004Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_cast', function (Alter $table) {
			$table->addColumn('roles', 'MEDIUMBLOB')->nullable()->setDefault(null)->after('character');
			$table->addColumn('total_episode_count', 'INT', 10)->setDefault(0)->after('roles');
		});

		$sm->alterTable('xf_snog_tv_crew', function (Alter $table) {
			$table->addColumn('jobs', 'MEDIUMBLOB')->nullable()->setDefault(null)->after('job');
			$table->addColumn('total_episode_count', 'INT', 10)->setDefault(0)->after('jobs');
		});
	}

	public function upgrade2020006Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_person', function (Alter $table) {
			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0)->after('known_for_department');
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0)->after('known_for_department');
		});
	}

	public function upgrade2020007Step1()
	{
		$db = $this->db();
		$db->update(
			'xf_snog_tv_person',
			['small_image_date' => 0, 'large_image_date' => 0],
			"profile_path = ''"
		);
	}

	public function upgrade2020007Step2()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_person', function (Alter $table) {
			$table->changeColumn('homepage', 'VARCHAR', 255)->setDefault('');
		});
	}

	public function upgrade2020008Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_person', function (Alter $table) {
			$table->renameColumn('tmdb_person_id', 'imdb_person_id');
		});
	}

	public function upgrade2020008Step2()
	{
		$sm = $this->schemaManager();
		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('first_air_date', 'BIGINT')->setDefault(0)->unsigned(false);
			$table->addColumn('last_air_date', 'BIGINT')->setDefault(0)->unsigned(false);
			$table->addColumn('status', 'VARCHAR', 150)->setDefault('');
		});
	}

	public function upgrade2020019Step1()
	{
		$sm = $this->schemaManager();
		$sm->createTable('xf_snog_tv_video', function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('tv_id', 'INT', 10);
			$table->addColumn('tv_season', 'INT', 10)->setDefault(0);
			$table->addColumn('tv_episode', 'INT', 10)->setDefault(0);

			$table->addColumn('video_id', 'VARCHAR', 24);

			$table->addColumn('name', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('key', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('site', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('size', 'INT')->setDefault(0);
			$table->addColumn('type', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('official', 'TINYINT', 3)->setDefault(0);
			$table->addColumn('published_at', 'INT')->setDefault(0);

			$table->addColumn('iso_639_1', 'VARCHAR', 2);
			$table->addColumn('iso_3166_1', 'VARCHAR', 2);

			$table->addPrimaryKey(['tv_id', 'tv_season', 'tv_episode', 'video_id']);
			$table->addKey('tv_id');
			$table->addKey('tv_season');
			$table->addKey('tv_episode');
			$table->addKey('published_at');
		});
	}

	public function upgrade2020019Step2()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_cast', function (Alter $table) {
			$table->changeColumn('order', 'INT', 0)->setDefault(0);
		});

		$sm->alterTable('xf_snog_tv_crew', function (Alter $table) {
			$table->changeColumn('order', 'INT', 0)->setDefault(0);
		});
	}

	public function upgrade2020031Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('tmdb_watch_providers', 'MEDIUMBLOB');
		});

		$sm->alterTable('xf_user_option', function (Alter $table) {
			$table->addColumn('snog_tv_tmdb_watch_region', 'CHAR', 2)->setDefault('US');
		});
	}

	public function upgrade2020043Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_snog_tv_cast', function (Alter $table) {
			$table->changeColumn('character', 'VARCHAR', 255)->setDefault('');
			$table->changeColumn('known_for_department', 'VARCHAR', 255)->setDefault('');
		});

		$sm->alterTable('xf_snog_tv_crew', function (Alter $table) {
			$table->changeColumn('known_for_department', 'VARCHAR', 255)->setDefault('');
			$table->changeColumn('department', 'VARCHAR', 255)->setDefault('');
			$table->changeColumn('job', 'VARCHAR', 255)->setDefault('');
		});

		$sm->alterTable('xf_snog_tv_person', function (Alter $table) {
			$table->changeColumn('name', 'VARCHAR', 255)->setDefault('');
			$table->changeColumn('place_of_birth', 'VARCHAR', 255)->setDefault('');
			$table->changeColumn('known_for_department', 'VARCHAR', 255)->setDefault('');
		});
	}

	public function upgrade2020049Step1()
	{
		$sm = $this->schemaManager();

		$sm->createTable('xf_snog_tv_company', function (Create $table) {
			$table->addColumn('company_id', 'INT', 10)->primaryKey();

			$table->addColumn('name', 'VARCHAR', 255);
			$table->addColumn('description', 'TEXT')->nullable();
			$table->addColumn('headquarters', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('homepage', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('logo_path', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('origin_country', 'VARCHAR', 2)->setDefault('');

			$table->addColumn('parent_company_id', 'INT', 10)->setDefault(0);

			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0);
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0);

			$table->addKey('parent_company_id');
		});

		$sm->createTable('xf_snog_tv_network', function (Create $table) {
			$table->addColumn('network_id', 'INT', 10)->primaryKey();

			$table->addColumn('name', 'VARCHAR', 255);

			$table->addColumn('headquarters', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('homepage', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('logo_path', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('origin_country', 'VARCHAR', 2)->setDefault('');

			$table->addColumn('small_image_date', 'INT', 10)->setDefault(0);
			$table->addColumn('large_image_date', 'INT', 10)->setDefault(0);
		});

		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('tmdb_production_company_ids', 'MEDIUMBLOB')->nullable()->setDefault(null)->after('tmdb_watch_providers');
			$table->addColumn('tmdb_network_ids', 'MEDIUMBLOB')->nullable()->setDefault(null)->after('tmdb_production_company_ids');
		});
	}

	public function upgrade2020051Step1()
	{
		$sm = $this->schemaManager();
		$sm->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('tmdb_last_change_date', 'BIGINT')->setDefault(0)->unsigned(false)->after('tmdb_network_ids');
		});
	}

	public function upgrade2020062Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_tv_thread', function (Alter $table) {
			$table->addColumn('backdrop_path', 'varchar', 150)->setDefault('')->after('tv_image');
		});
	}

	public function postUpgrade($previousVersion, array &$stateChanges)
	{
		if ($previousVersion)
		{
			if ($previousVersion < 2020000)
			{
				$this->app->jobManager()->enqueueUnique(
					'snog_tv_counter',
					'Snog\TV:UserTvThreadCountRebuild',
					[],
					false
				);
			}

			if ($previousVersion < 2020008)
			{
				$this->app->jobManager()->enqueueUnique(
					'snogTvTvFirstAirDate',
					'Snog\TV:Upgrade\TvFirstAirDate2020008',
					[],
					false
				);
			}
			if ($previousVersion < 2020037)
			{
				$this->app->jobManager()->enqueueUnique(
					'snog_tv_imdb',
					'Snog\TV:Upgrade\TvImdb2020001',
					[],
					false
				);
			}
		}
	}

	// ################################## HELPERS ###########################################

	public function checkRequirements(&$errors = [], &$warnings = [])
	{
		if (\XF::$versionId < 2010031)
		{
			$errors[] = 'This add-on may only be used on XenForo 2.1 or higher';
			return $errors;
		}

		$versionId = $this->addOn->version_id;

		if ($versionId && $versionId < '24')
		{
			$errors[] = 'Upgrades can only be to the XF 1.x TMDb TV Thread Starter version 2.1.11 or later';
			return $errors;
		}

		return $errors;
	}

	public function getValue($string, $start, $end)
	{
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	public function getEpisodeImageUrl($posterpath, $canonical = true)
	{
		$app = \XF::app();
		return $app->applyExternalDataUrl("tv/EpisodePosters{$posterpath}", $canonical);
	}

	public function getSeriesImageUrl($posterpath, $canonical = true)
	{
		$app = \XF::app();
		return $app->applyExternalDataUrl("tv/LargePosters{$posterpath}", $canonical);
	}

	public function copyContents($src, $sub = false)
	{
		$basePath = '';
		if ($sub) $basePath = str_ireplace('src/addons/Snog/TV/defaultdata/', '', $src);
		$dir = opendir($src);

		while (false !== ($file = readdir($dir)))
		{
			if (($file != '.') && ($file != '..'))
			{
				if (is_dir($src . '/' . $file))
				{
					$newSrc = $src . '/' . $file;
					$this->copyContents($newSrc, true);
				}
				else
				{
					$oldPath = $src . '/' . $file;

					if ($sub)
					{
						$newFile = $basePath . '/' . $file;
					}
					else
					{
						$newFile = $file;
					}

					$newPath = sprintf('data://tv/%s', $newFile);
					File::copyFileToAbstractedPath($oldPath, $newPath);
				}
			}
		}

		closedir($dir);
	}

}