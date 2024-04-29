<?php

namespace nick97\TraktMovies;

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

		foreach ($this->getTables() as $tableName => $callback) {
			$sm->createTable($tableName, $callback);
		}
	}

	public function installStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getAlters() as $table => $schema) {
			if ($sm->tableExists($table)) {
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function installStep3()
	{
		$db = $this->db();
		$db->insert('xf_forum_type', [
			'forum_type_id' => 'trakt_movies_movie',
			'handler_class' => 'nick97\TraktMovies:Movie',
			'addon_id' => 'nick97/TraktMovies'
		]);

		$db->insert('xf_thread_type', [
			'thread_type_id' => 'trakt_movies_movie',
			'handler_class' => 'nick97\TraktMovies:Movie',
			'addon_id' => 'nick97/TraktMovies'
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
		$src = 'src/addons/nick97/TraktMovies/defaultdata';
		$this->copyContents($src);
	}

	public function installStep5()
	{
		$this->schemaManager()->createTable('nick97_trakt_movies_url', function (Create $table) {
			$table->addColumn('id', 'int')->autoIncrement();

			$table->addColumn('tmdb_id', 'int');
			$table->addColumn('trakt_slug', 'mediumtext')->nullable();

			$table->addPrimaryKey('id');
		});
	}

	// ################################## UNINSTALL ###########################################

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName) {
			$sm->dropTable($tableName);
		}
	}

	public function uninstallStep2()
	{
		$sm = $this->schemaManager();
		foreach ($this->getReverseAlters() as $table => $schema) {
			if ($sm->tableExists($table)) {
				$sm->alterTable($table, $schema);
			}
		}
	}

	public function uninstallStep3()
	{
		$db = $this->db();
		$db->delete('xf_forum_type', 'forum_type_id = ?', 'trakt_movies_movie');
		$db->delete('xf_thread_type', 'thread_type_id = ?', 'trakt_movies_movie');

		/** @var \XF\Repository\ForumType $forumTypeRepo */
		$forumTypeRepo = $this->app->repository('XF:ForumType');
		$forumTypeRepo->rebuildForumTypeCache();

		/** @var \XF\Repository\ThreadType $threadTypeRepo */
		$threadTypeRepo = $this->app->repository('XF:ThreadType');
		$threadTypeRepo->rebuildThreadTypeCache();
	}

	public function uninstallStep4()
	{
		$sm = $this->schemaManager();
		$sm->dropTable('nick97_trakt_movies_url');
	}

	// ################################## DATA ###########################################

	protected function getTables(): array
	{
		$tables = [];

		$tables['nick97_trakt_movies_thread'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('thread_id', 'INT', 10);
			$table->addColumn('trakt_id', 'INT', 10)->setDefault(0);
			$table->addColumn('imdb_id', 'VARCHAR', 32)->setDefault(0);

			$table->addColumn('trakt_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('trakt_plot', 'TEXT')->setDefault(NULL);
			$table->addColumn('trakt_image', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('backdrop_path', 'varchar', 150)->setDefault('');

			$table->addColumn('trakt_genres', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('trakt_director', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('trakt_cast', 'TEXT')->setDefault(NULL);
			$table->addColumn('trakt_release', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('trakt_tagline', 'TEXT')->setDefault(NULL);
			$table->addColumn('trakt_runtime', 'INT', 10)->setDefault(0);
			$table->addColumn('trakt_rating')->type('decimal', '3,2')->setDefault(0);
			$table->addColumn('trakt_votes', 'INT', 10)->setDefault(0);
			$table->addColumn('trakt_trailer', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('trakt_status', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('comment', 'TEXT')->setDefault(NULL);
			$table->addColumn('trakt_popularity')->type('decimal', '19,3')->setDefault(0);

			$table->addColumn('trakt_watch_providers', 'MEDIUMBLOB');
			$table->addColumn('trakt_production_company_ids', 'MEDIUMBLOB')->nullable()->setDefault(null);
			$table->addColumn('trakt_last_change_date', 'BIGINT')->setDefault(0)->unsigned(false);

			$table->addPrimaryKey('thread_id');
			$table->addKey('trakt_id');
		};

		$tables['nick97_trakt_movies_ratings'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('rating_id', 'INT', 10)->autoIncrement();
			$table->addColumn('thread_id', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('rating', 'TINYINT', 1)->setDefault(0);
			$table->addPrimaryKey('rating_id');
			$table->addUniqueKey(['thread_id', 'user_id']);

			$table->addKey('thread_id');
			$table->addKey('user_id');
		};

		$tables['nick97_trakt_movies_person'] = function (Create $table) {
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

			$table->addColumn('popularity')->type('decimal', '19,3')->setDefault(0);

			$table->addKey('imdb_person_id');
		};

		$tables['nick97_trakt_movies_cast'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('trakt_id', 'INT', 10);
			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('cast_id', 'INT', 10)->setDefault(0);

			$table->addColumn('character', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('known_for_department', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0)->setDefault(0);

			$table->addUniqueKey(['trakt_id', 'person_id']);
			$table->addKey('trakt_id');
			$table->addKey('person_id');
		};

		$tables['nick97_trakt_movies_crew'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('trakt_id', 'INT', 10);
			$table->addColumn('person_id', 'INT', 10);

			$table->addColumn('known_for_department', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('department', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('job', 'VARCHAR', 255)->setDefault('');

			$table->addColumn('credit_id', 'VARCHAR', 24)->setDefault('');
			$table->addColumn('order', 'INT', 0)->setDefault(0);

			$table->addUniqueKey(['trakt_id', 'person_id']);
			$table->addKey('trakt_id');
			$table->addKey('person_id');
		};

		$tables['nick97_trakt_movies_video'] = function (Create $table) {
			$table->checkExists(true);

			$table->addColumn('trakt_id', 'INT', 10);
			$table->addColumn('video_id', 'VARCHAR', 24);

			$table->addColumn('name', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('key', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('site', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('size', 'INT')->setDefault(0);
			$table->addColumn('type', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('official', 'TINYINT', 3)->setDefault(0);
			$table->addColumn('published_at', 'INT')->setDefault(0);

			$table->addColumn('iso_639_1', 'VARCHAR', 2)->setDefault('');
			$table->addColumn('iso_3166_1', 'VARCHAR', 2)->setDefault('');

			$table->addPrimaryKey(['trakt_id', 'video_id']);
			$table->addKey('trakt_id');
			$table->addKey('published_at');
		};

		$tables['nick97_trakt_movies_company'] = function (Create $table) {
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

		return $tables;
	}

	protected function getAlters()
	{
		$alters = [];

		$alters['xf_user'] = function (Alter $table) {
			$table->addColumn('trakt_movies_thread_count', 'int')->setDefault(0);
		};

		$alters['xf_user_option'] = function (Alter $table) {
			$table->addColumn('nick97_movies_trakt_watch_region', 'CHAR', 2)->setDefault('US');
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
				'trakt_movies_thread_count'
			]);
		};

		$alters['xf_user_option'] = function (Alter $table) {
			$table->dropColumns([
				'nick97_movies_trakt_watch_region'
			]);
		};

		return $alters;
	}

	// ################################## UPGRADE ###########################################



	// ################################## HELPERS ###########################################

	public function copyContents($src, $sub = false)
	{
		if ($sub) $basePath = str_ireplace('src/addons/nick97/TraktMovies/defaultdata/', '', $src);
		$dir = opendir($src);

		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					$newSrc = $src . '/' . $file;
					$this->copyContents($newSrc, true);
				} else {
					$oldPath = $src . '/' . $file;

					if ($sub) {
						$newFile = $basePath . '/' . $file;
					} else {
						$newFile = $file;
					}

					$newPath = sprintf('data://movies/%s', $newFile);
					File::copyFileToAbstractedPath($oldPath, $newPath);
				}
			}
		}

		closedir($dir);
	}

	public function checkRequirements(&$errors = [], &$warnings = [])
	{
		if (\XF::$versionId < 2010031) {
			$errors[] = 'This add-on may only be used on XenForo 2.1 or higher';
			return $errors;
		}

		$versionId = $this->addOn->version_id;

		if ($versionId && $versionId < '28') {
			$errors[] = 'Upgrades can only be to the XF 1.x Trakt Movie Thread Starter version 2.1.13 or later';
			return $errors;
		}

		return $errors;
	}
}
