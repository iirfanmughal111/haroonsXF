<?php

namespace Snog\Forms;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function checkRequirements(&$errors = [], &$warnings = [])
	{
		if (\XF::$versionId < 2010031)
		{
			$errors[] = 'This add-on may only be used on XenForo 2.1 or higher';
			return $errors;
		}

		$versionId = $this->addOn->version_id;

		if ($versionId && $versionId < '32')
		{
			$errors[] = 'Upgrades can only be done to version 1.2.13 or later';
			return $errors;
		}

		return $errors;
	}

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

	// ################################## DATA ###########################################

	/**
	 * @return array
	 */
	protected function getTables(): array
	{
		$tables = [];

		$tables['xf_snog_forms_types'] = function (Create $table) {
			$table->addColumn('appid', 'INT', 10)->autoIncrement();
			$table->addColumn('type', 'VARCHAR', 100);
			$table->addColumn('active', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('sidebar', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('navtab', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('user_criteria', 'BLOB')->nullable();
			$table->addColumn('display_parent', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('display', 'INT', 10)->setDefault(0);
			$table->addPrimaryKey('appid');
		};

		$tables['xf_snog_forms_forms'] = function (Create $table) {
			$table->addColumn('posid', 'INT', 10)->autoIncrement();
			$table->addColumn('position', 'VARCHAR', 100);
			$table->addColumn('node_id', 'INT', 10)->setDefault(0);
			$table->addColumn('secnode_id', 'INT', 10)->setDefault(0);
			$table->addColumn('active', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('subject', 'VARCHAR', 150);
			$table->addColumn('email', 'VARCHAR', 200);
			$table->addColumn('email_parent', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('inthread', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('insecthread', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('posterid', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('secposterid', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('bypm', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pmsender', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('pmdelete', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pmerror', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('pmto', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('appid', 'INT', 10);
			$table->addColumn('prefix_ids', 'MEDIUMBLOB');
			$table->addColumn('returnto', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('returnlink', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('postapproval', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('parseyesno', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('incname', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('oldthread', 'INT', 10)->setDefault(0);
			$table->addColumn('pmapp', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pmtext', 'TEXT');
			$table->addColumn('apppromote', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('promoteto', 'INT', 10)->setDefault(0);
			$table->addColumn('appadd', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('addto', 'BLOB')->nullable();
			$table->addColumn('postpoll', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pollpublic', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pollchange', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pollview', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('pollquestion', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('promote_type', 'TINYINT', 2)->setDefault(0);
			$table->addColumn('pollclose', 'SMALLINT', 5)->setDefault(0);
			$table->addColumn('decidepromote', 'INT', 10)->setDefault(0);
			$table->addColumn('pollpromote', 'VARBINARY', 512)->setDefault(0);
			$table->addColumn('removeinstant', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('approved_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('approved_text', 'TEXT');
			$table->addColumn('denied_title', 'VARCHAR', 150)->setDefault('');
			$table->addColumn('denied_text', 'TEXT');
			$table->addColumn('app_style', 'INT', 10)->setDefault(0);
			$table->addColumn('user_criteria', 'BLOB')->nullable();
			$table->addColumn('watchthread', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('make_moderator', 'TINYINT', 2)->setDefault(0);
			$table->addColumn('forum', 'INT', 10)->setDefault(0);
			$table->addColumn('instant', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('aboveapp', 'TEXT');
			$table->addColumn('belowapp', 'TEXT');
			$table->addColumn('approved_file', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('normalpoll', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('normalpublic', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('normalchange', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('normalview', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('normalclose', 'INT', 10)->setDefault(0);
			$table->addColumn('normalquestion', 'VARCHAR', 100)->setDefault('');
			$table->addColumn('threadbutton', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('threadapp', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('formlimit', 'INT', 10)->setDefault(0);
			$table->addColumn('response', 'MEDIUMBLOB')->nullable();
			$table->addColumn('thanks', 'TEXT');
			$table->addColumn('qcolor', 'VARCHAR', 20)->setDefault('');
			$table->addColumn('acolor', 'VARCHAR', 20)->setDefault('');
			$table->addColumn('forummod', 'BLOB')->nullable();
			$table->addColumn('supermod', 'BLOB')->nullable();
			$table->addColumn('quickreply', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('store', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('start', 'INT', 10)->setDefault(0);
			$table->addColumn('end', 'INT', 10)->setDefault(0);
			$table->addColumn('qroption', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('qrbutton', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('qrstarter', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('qrforums', 'MEDIUMBLOB')->nullable();
			$table->addColumn('aftererror', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('bbstart', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('bbend', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('display_parent', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('display', 'INT', 10)->setDefault(0);
			$table->addColumn('minimum_attachments', 'INT', 10)->setDefault(1);
			$table->addColumn('submit_count', 'INT', 10)->setDefault(0);
			$table->addColumn('is_public_visible', 'TINYINT')->setDefault(0)
				->comment('Visible publicly regardless user criteria');
			$table->addColumn('cooldown', 'INT')
				->setDefault(0)
				->unsigned(false)
				->comment('Seconds between submitting new form');
			$table->addPrimaryKey('posid');
			$table->addKey('appid');
			$table->addKey('oldthread');
			$table->addKey('qroption');
			$table->addKey('active');
			$table->addKey('display');
		};

		$tables['xf_snog_forms_questions'] = function (Create $table) {
			$table->addColumn('questionid', 'INT', 10)->autoIncrement();
			$table->addColumn('posid', 'INT', 10)->setDefault(0);
			$table->addColumn('text', 'TEXT');
			$table->addColumn('description', 'VARCHAR', 250)->setDefault('');
			$table->addColumn('type', 'TINYINT', 4)->setDefault(0);
			$table->addColumn('error', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('expected', 'TEXT');
			$table->addColumn('display', 'INT', 10)->setDefault(0);
			$table->addColumn('display_parent', 'INT', 10)->setDefault(0);
			$table->addColumn('regex', 'VARCHAR', 255)->setDefault('');
			$table->addColumn('regexerror', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('defanswer', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('questionpos', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('showquestion', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('showunanswered', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('inline', 'TINYINT', 1)->setDefault(1);
			$table->addColumn('format', 'TEXT')->nullable();
			$table->addColumn('hasconditional', 'BLOB')->nullable();
			$table->addColumn('conditional', 'INT', 10)->setDefault(0);
			$table->addColumn('conanswer', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('placeholder', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('checklimit', 'INT', 10)->setDefault(0);
			$table->addColumn('checkerror', 'VARCHAR', 200)->setDefault('');
			$table->addPrimaryKey('questionid');
			$table->addKey('posid');
		};

		$tables['xf_snog_forms_promotions'] = function (Create $table) {
			$table->addColumn('post_id', 'INT', 10)->setDefault(0);
			$table->addColumn('thread_id', 'INT', 10)->setDefault(0);
			$table->addColumn('poll_id', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('posid', 'INT', 10)->setDefault(0);
			$table->addColumn('close_date', 'INT', 10)->setDefault(0);
			$table->addColumn('approve', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('original_group', 'INT', 10)->setDefault(0);
			$table->addColumn('original_additional', 'BLOB')->nullable();
			$table->addColumn('new_group', 'INT', 10)->setDefault(0);
			$table->addColumn('new_additional', 'BLOB')->nullable();
			$table->addColumn('forum_node', 'INT', 10)->setDefault(0);
			$table->addUniqueKey('post_id');
			$table->addKey('user_id');
			$table->addKey('posid');
			$table->addKey('poll_id');
			$table->addKey('close_date');
			$table->addKey('thread_id');
		};

		$tables['xf_snog_forms_answers'] = function (Create $table) {
			$table->addColumn('answer_id', 'INT', 10)->autoIncrement();
			$table->addColumn('log_id', 'INT', 10)->setDefault(0);
			$table->addColumn('posid', 'INT', 10)->setDefault(0);
			$table->addColumn('questionid', 'INT', 10)->setDefault(0);
			$table->addColumn('answer_date', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('answer', 'MEDIUMTEXT')->nullable();
			$table->addPrimaryKey('answer_id');
			$table->addKey('posid');
			$table->addKey('questionid');
			$table->addKey('user_id');
			$table->addKey('log_id');
		};

		$tables['xf_snog_forms_logs'] = function (Create $table) {
			$table->addColumn('log_id', 'int')->autoIncrement();
			$table->addColumn('form_id', 'int');
			$table->addColumn('user_id', 'int');
			$table->addColumn('ip_address', 'varbinary', 16);
			$table->addColumn('log_date', 'int')->setDefault(0);
			$table->addKey('form_id');
			$table->addKey('user_id');
			$table->addKey('ip_address');
		};

		return $tables;
	}

	/**
	 * @return array
	 */
	protected function getAlters()
	{
		$alters = [];

		$alters['xf_node'] = function (Alter $table) {
			$table->addColumn('snog_posid', 'INT', 10)->setDefault(0);
			$table->addColumn('snog_label', 'VARCHAR', 50)->setDefault('');
		};

		$alters['xf_user'] = function (Alter $table) {
			$table->addColumn('snog_forms', 'BLOB')->nullable();
		};

		return $alters;
	}

	/**
	 * @return array
	 */
	protected function getReverseAlters()
	{
		$alters = [];

		$alters['xf_node'] = function (Alter $table) {
			$table->dropColumns([
				'snog_posid',
				'snog_label',
			]);
		};

		$alters['xf_user'] = function (Alter $table) {
			$table->dropColumns([
				'snog_forms',
			]);
		};

		return $alters;
	}

	// ################################## UPGRADE ###########################################

	public function upgrade1000070Step1()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_types'");

		if ($tableExists)
		{
			$sm->renameTable('xf_snog_applications_types', 'xf_snog_forms_types');

			// DROP/CHANGE COLUMNS
			$sm->alterTable('xf_snog_forms_types', function (Alter $table) {
				$table->changeColumn('appid', 'INT', 10)->autoIncrement();
				$table->dropColumns(['postcount']);
				$table->dropIndexes(['type']);
			});
		}
	}

	public function upgrade1000070Step2()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_positions'");

		if ($tableExists)
		{
			$sm->renameTable('xf_snog_applications_positions', 'xf_snog_forms_forms');

			// RENAME/CHANGE OLD COLUMNS
			$sm->alterTable('xf_snog_forms_forms', function (Alter $table) {
				$table->changeColumn('posid', 'INT', 10)->autoIncrement();
				$table->changeColumn('appid', 'INT', 10)->setDefault(0);
				$table->renameColumn('addto', 'addto_temp');
				$table->renameColumn('pmsender', 'sender_temp');
				$table->renameColumn('posterid', 'poster_temp');
				$table->renameColumn('secposterid', 'secposter_temp');
				$table->renameColumn('pmerror', 'pmerror_temp');
				$table->dropIndexes(['position']);
			});

			// ADD NEW COLUMNS TO TABLE
			$sm->alterTable('xf_snog_forms_forms', function (Alter $table) {
				$table->addColumn('decidepromote', 'INT', 10)->setDefault(0);
				$table->addColumn('addto', 'BLOB')->nullable();
				$table->addColumn('belowapp', 'TEXT');
				$table->addColumn('thanks', 'TEXT');
				$table->addColumn('formlimit', 'INT', 10)->setDefault(0);
				$table->addColumn('pmsender', 'VARCHAR', 50)->setDefault('');
				$table->addColumn('posterid', 'VARCHAR', 50)->setDefault('');
				$table->addColumn('secposterid', 'VARCHAR', 50)->setDefault('');
				$table->addColumn('pmerror', 'VARCHAR', 50)->setDefault('');
				$table->addColumn('response', 'MEDIUMBLOB', NULL);
				$table->addColumn('qcolor', 'VARCHAR', 20)->setDefault('');
				$table->addColumn('acolor', 'VARCHAR', 20)->setDefault('');
				$table->addColumn('forummod', 'BLOB')->nullable();
				$table->addColumn('supermod', 'BLOB')->nullable();
			});

			// MOVE FORM LIMIT OUT OF CRITERIA
			$criteriaChange = $db->fetchAll("SELECT posid, user_criteria FROM xf_snog_forms_forms WHERE user_criteria > 'a:0:{}'");

			foreach ($criteriaChange as $change)
			{
				$crit = unserialize($change['user_criteria']);
				if (isset($crit[0]['data']['times']))
				{
					$newTimes = $crit[0]['data']['times'];
					unset($crit[0]);
					$newCrit = serialize($crit);
					$update = ['formlimit' => $newTimes, 'user_criteria' => $newCrit];
					$db->update('xf_snog_forms_forms', $update, 'posid = ?', $change['posid']);
				}
			}

			// CHANGE PM SENDER ID TO USER NAME
			$tmp_change = $db->fetchAll("SELECT posid, sender_temp FROM xf_snog_forms_forms WHERE sender_temp > 0");

			foreach ($tmp_change as $change)
			{
				$userdata = $db->fetchRow("SELECT user_id, username FROM xf_user WHERE user_id = " . $change['sender_temp']);
				if ($userdata)
				{
					$db->update('xf_snog_forms_forms', ['pmsender' => $userdata['username']], 'posid = ?', $change['posid']);
				}
			}

			// CHANGE POSTER ID TO USER NAME
			$tmp_change = $db->fetchAll("SELECT posid, poster_temp FROM xf_snog_forms_forms WHERE poster_temp > 0");

			foreach ($tmp_change as $change)
			{
				$userdata = $db->fetchRow("SELECT user_id, username FROM xf_user WHERE user_id = " . $change['poster_temp']);
				if ($userdata)
				{
					$db->update('xf_snog_forms_forms', ['posterid' => $userdata['username']], 'posid = ?', $change['posid']);
				}
			}

			// CHANGE SECOND POSTER ID TO USER NAME
			$tmp_change = $db->fetchAll("SELECT posid, secposter_temp FROM xf_snog_forms_forms WHERE secposter_temp > 0");

			foreach ($tmp_change as $change)
			{
				$userdata = $db->fetchRow("SELECT user_id, username FROM xf_user WHERE user_id = " . $change['secposter_temp']);
				if ($userdata)
				{
					$db->update('xf_snog_forms_forms', ['secposterid' => $userdata['username']], 'posid = ?', $change['posid']);
				}
			}

			// CHANGE TIE NOTICE RECEIVER TO USER NAME
			$tmp_change = $db->fetchAll("SELECT posid, pmerror_temp FROM xf_snog_forms_forms WHERE pmerror_temp > 0");

			foreach ($tmp_change as $change)
			{
				$userdata = $db->fetchRow("SELECT user_id, username FROM xf_user WHERE user_id = " . $change['pmerror_temp']);
				if ($userdata)
				{
					$db->update('xf_snog_forms_forms', ['pmerror' => $userdata['username']], 'posid = ?', $change['posid']);
				}
			}

			// CONVERT addto TO SERIALIZED ARRAY
			$tmp_addto = $db->fetchAll("SELECT posid, addto_temp FROM xf_snog_forms_forms WHERE addto_temp > 0");

			foreach ($tmp_addto as $addto)
			{
				$changed = [];
				$changed[$addto['addto_temp']] = $addto['addto_temp'];
				$changedValue = serialize($changed);
				$db->update('xf_snog_forms_forms', ['addto' => $changedValue], 'posid = ?', $addto['posid']);
			}

			// DROP OLD COLUMNS FROM FORMS TABLE
			$sm->alterTable('xf_snog_forms_forms', function (Alter $table) {
				$table->dropColumns(['sender_temp']);
				$table->dropColumns(['poster_temp']);
				$table->dropColumns(['secposter_temp']);
				$table->dropColumns(['pmerror_temp']);
				$table->dropColumns(['addto_temp']);
			});

			// UPDATE NEW decidepromote FIELD WITH OLD pollpromote VALUES
			$tmp_promotetype = $db->fetchAll("SELECT posid, promote_type, pollpromote FROM xf_snog_forms_forms WHERE promote_type = 1");

			foreach ($tmp_promotetype as $promotetype)
			{
				$update = ['decidepromote' => $promotetype['pollpromote'], 'pollpromote' => 'a:0:{}'];
				$db->update('xf_snog_forms_forms', $update, 'posid = ?', $promotetype['posid']);
			}

			// UPDATE REMAINING pollpromote VALUES TO SERIALIZED ARRAY
			$tmp_promotetype = $db->fetchAll("SELECT posid, promote_type, pollpromote FROM xf_snog_forms_forms WHERE promote_type = 2");

			foreach ($tmp_promotetype as $promotetype)
			{
				$tmp_values = explode(',', $promotetype['pollpromote']);
				$changed = [];

				foreach ($tmp_values as $value)
				{
					$changed[$value] = $value;
				}

				$changedValue = serialize($changed);
				$db->update('xf_snog_forms_forms', ['pollpromote' => $changedValue], 'posid = ?', $promotetype['posid']);
			}

			// MOVE THANKS FROM TYPE TO FORM
			$types = $db->fetchAll("SELECT appid, thanks FROM xf_snog_forms_types");

			foreach ($types as $type)
			{
				$db->update('xf_snog_forms_forms', ['thanks' => $type['thanks']], 'appid = ?', $type['appid']);
			}

			// DROP OLD COLUMN FROM TYPES TABLE
			$sm->alterTable('xf_snog_forms_types', function (Alter $table) {
				$table->dropColumns(['thanks']);
			});
		}
	}

	public function upgrade1000070Step3()
	{
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_questions'");

		if ($tableExists)
		{
			$sm->renameTable('xf_snog_applications_questions', 'xf_snog_forms_questions');

			// ADD NEW COLUMNS TO TABLE
			$sm->alterTable('xf_snog_forms_questions', function (Alter $table) {
				$table->addColumn('display_parent', 'INT', 10)->setDefault(0);
			});
		}
	}

	public function upgrade1000070Step4()
	{
		// MOVE RESPONSES TO FORM TABLE
		$db = $this->db();
		$sm = $this->schemaManager();
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_responses'");
		$lastPosition = 1;
		$moveValues = [];

		if ($tableExists)
		{
			$responseMoves = $db->fetchAll("SELECT * FROM xf_snog_applications_responses");

			foreach ($responseMoves as $responseMove)
			{
				if ($lastPosition !== 1 && $responseMove['position_id'] !== $lastPosition)
				{
					$moveValue = serialize($moveValues);
					$db->update('xf_snog_forms_forms', ['response' => $db->escapeString($moveValue)], 'posid = ?', $lastPosition);
					$moveValues = [];
				}

				$lastPosition = $responseMove['position_id'];
				$moveValues[$responseMove['response_pos']] = $responseMove['response'];
			}

			if (!empty($moveValues))
			{
				$moveValue = serialize($moveValues);
				$db->update('xf_snog_forms_forms', ['response' => $db->escapeString($moveValue)], 'posid = ?', $lastPosition);
			}

			// DROP RESPONSE TABLE - NO LONGER NEEDED
			$sm->dropTable('xf_snog_applications_responses');
		}
	}

	public function upgrade1000070Step5()
	{
		$sm = $this->schemaManager();

		$sm->createTable('xf_snog_forms_promotions', function (Create $table) {
			$table->addColumn('post_id', 'INT', 10)->setDefault(0);
			$table->addColumn('thread_id', 'INT', 10)->setDefault(0);
			$table->addColumn('poll_id', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('posid', 'INT', 10)->setDefault(0);
			$table->addColumn('close_date', 'INT', 10)->setDefault(0);
			$table->addColumn('approve', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('original_group', 'INT', 10)->setDefault(0);
			$table->addColumn('original_additional', 'BLOB')->nullable();
			$table->addColumn('new_group', 'INT', 10)->setDefault(0);
			$table->addColumn('new_additional', 'BLOB')->nullable();
			$table->addColumn('forum_node', 'INT', 10)->setDefault(0);
			$table->addUniqueKey('post_id');
			$table->addKey('user_id');
			$table->addKey('posid');
			$table->addKey('poll_id');
			$table->addKey('close_date');
			$table->addKey('thread_id');
		});
	}

	public function upgrade1000070Step6()
	{
		$db = $this->db();
		$sm = $this->schemaManager();

		// DROP TABLES THAT ARE NO LONGER USED
		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_polls'");
		if ($tableExists) $sm->dropTable('xf_snog_applications_polls');

		$tableExists = $db->fetchRow("SHOW TABLES LIKE 'xf_snog_applications_promotions'");
		if ($tableExists) $sm->dropTable('xf_snog_applications_promotions');
	}

	/**
	 *
	 */
	public function upgrade1000070Step7()
	{
		$db = $this->db();
		$sm = $this->schemaManager();

		$sm->alterTable('xf_node', function (Alter $table) {
			$table->addColumn('snog_posid', 'INT', 10)->setDefault(0);
			$table->addColumn('snog_label', 'VARCHAR', 50)->setDefault('');
		});

		// ADD POST BUTTON CHANGE INFO WHERE NEEDED
		$forumChange = $db->fetchAll("SELECT posid, node_id, threadbutton FROM xf_snog_forms_forms WHERE threadapp > 0");

		foreach ($forumChange as $changeForum)
		{
			$db->update('xf_node', ['snog_posid' => $changeForum['posid'], 'snog_label' => $changeForum['threadbutton']], 'node_id = ?', $changeForum['node_id']);
		}

	}

	public function upgrade1000070Step8()
	{
		$this->schemaManager()->alterTable('xf_user', function (Alter $table) {
			$table->dropColumns(['advapps']);
			$table->addColumn('snog_forms', 'BLOB')->nullable();
		});
	}

	public function upgrade1000170Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->changeColumn('qcolor', 'VARCHAR', 25)->setDefault('');
			$table->changeColumn('acolor', 'VARCHAR', 25)->setDefault('');
		});
	}

	public function upgrade1000170Step2()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('wrap', 'VARCHAR', 30)->setDefault('');
		});
	}

	public function upgrade1000270Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('quickreply', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('store', 'TINYINT', 1)->setDefault(0);
		});
	}

	public function upgrade1000270Step2()
	{
		$sm = $this->schemaManager();

		$sm->createTable('xf_snog_forms_answers', function (Create $table) {
			$table->addColumn('answer_id', 'INT', 10)->autoIncrement();
			$table->addColumn('posid', 'INT', 10)->setDefault(0);
			$table->addColumn('questionid', 'INT', 10)->setDefault(0);
			$table->addColumn('answer_date', 'INT', 10)->setDefault(0);
			$table->addColumn('user_id', 'INT', 10)->setDefault(0);
			$table->addColumn('answer', 'MEDIUMTEXT')->nullable();
			$table->addPrimaryKey('answer_id');
			$table->addKey('posid');
			$table->addKey('questionid');
			$table->addKey('user_id');
		});
	}

	public function upgrade1000470Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('inline', 'TINYINT', 1)->setDefault(1);
		});
	}

	public function upgrade1000570Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('pollview', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('normalview', 'TINYINT', 1)->setDefault(0);
		});
	}

	public function upgrade1000570Step2()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('showunanswered', 'TINYINT', 1)->setDefault(1);
		});
	}

	public function upgrade1000770Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->changeColumn('description', 'VARCHAR', 250)->setDefault('');
			$table->addColumn('format', 'TEXT')->nullable();
			$table->addColumn('changed', 'TINYINT', 1)->setDefault(0);
		});
	}

	public function upgrade1000770Step2()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$questions = $db->fetchAll("SELECT * FROM xf_snog_forms_questions WHERE changed = 0");

		foreach ($questions as $question)
		{
			$tmpFormat = '';

			// QUESTION
			if (in_array($question['questionpos'], [3, 4])) $tmpFormat .= "[INDENT=2]";
			if ($question['questionpos'] == 5) $tmpFormat .= "[TAB]";
			$tmpFormat .= '[B]';
			if ($question['underline']) $tmpFormat .= '[U]';
			$tmpFormat .= '{question}';
			if ($question['underline']) $tmpFormat .= '[/U]';
			$tmpFormat .= '[/B]';
			if (in_array($question['questionpos'], [3, 4])) $tmpFormat .= "[/INDENT]";

			// ANSWER
			if ($question['answerpos'] == 2)
			{
				$tmpFormat .= "\r\n";
				// HAVE TO INDENT ANSWER ALONG WITH QUESTION
				if (in_array($question['questionpos'], [3, 4])) $tmpFormat .= "[INDENT=2]";
			}
			else
			{
				$tmpFormat .= ' ';
			}

			if ($question['wrap']) $tmpFormat .= '[' . $question['wrap'] . ']';
			$tmpFormat .= '{answer}';
			if ($question['wrap']) $tmpFormat .= '[/' . $question['wrap'] . ']';

			if (in_array($question['questionpos'], [3, 4])) $tmpFormat .= "[/INDENT]";

			// TAB HAS TO END AFTER ANSWER
			if ($question['questionpos'] == 5) $tmpFormat .= "[/TAB]";

			$questionpos = 1;
			if ($question['questionpos'] == 3) $questionpos = 1;
			if ($question['questionpos'] == 4) $questionpos = 2;
			if ($question['questionpos'] == 5) $questionpos = 5;

			$db->update('xf_snog_forms_questions',
				['format' => $tmpFormat, 'questionpos' => $questionpos, 'changed' => 1], 'questionid = ?', $question['questionid']);

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				$stepResult = [
					'complete' => false,
					'params' => [],
					'step' => 2,
					'version' => 1000770
				];

				return $stepResult;
			}
		}

		return [];
	}

	public function upgrade1000770Step3()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			// underline, answerpos & wrap NO LONGER USED AS OF VERSION 1.0.7
			// inold WAS NEVER USED IN XF1 OR XF2
			// changed WAS A TEMPORARY FIELD FOR UPDATE TO VERSION 1.0.7
			$table->dropColumns(['underline', 'answerpos', 'wrap', 'inold', 'changed']);
		});
	}

	public function upgrade1000970Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addKey('oldthread');
		});
	}

	public function upgrade1001170Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('start', 'INT', 10)->setDefault(0);
			$table->addColumn('end', 'INT', 10)->setDefault(0);
		});
	}

	public function upgrade1001270Step1()
	{
		$db = $this->db();

		// CHANGE PMTO DELIMITER
		$pmtoChanges = $db->fetchAll("SELECT posid, pmto FROM xf_snog_forms_forms WHERE pmto LIKE '%;%'");

		foreach ($pmtoChanges as $pmtoChange)
		{
			$changeTo = str_ireplace(';', ',', $pmtoChange['pmto']);
			$db->update('xf_snog_forms_forms', ['pmto' => $changeTo], 'posid = ?', $pmtoChange['posid']);
		}
	}

	public function upgrade2001570Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('hasconditional', 'BLOB')->nullable();
			$table->addColumn('conditional', 'INT', 10)->setDefault(0);
			$table->addColumn('conanswer', 'VARCHAR', 200)->setDefault('');
		});
	}

	public function upgrade2001771Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('qroption', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('qrbutton', 'VARCHAR', 50)->setDefault('');
			$table->addColumn('qrstarter', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('qrforums', 'MEDIUMBLOB')->nullable();
			$table->addColumn('aftererror', 'VARCHAR', 200)->setDefault('');
			$table->addKey('qroption');
		});
	}

	public function upgrade2001771Step2()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('placeholder', 'VARCHAR', 200)->setDefault('');
		});
	}

	// XF 2.1 FIELD CONVERSIONS - SERIALIZED ARRAY TO JSON ARRAY
	public function upgrade2001771Step3(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];

		return $this->entityColumnsToJson(
			'Snog\Forms:Type', ['user_criteria'], $position, $stepParams
		);
	}

	public function upgrade2001771Step4(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];

		return $this->entityColumnsToJson(
			'Snog\Forms:Form', ['addto', 'pollpromote', 'user_criteria', 'response', 'forummod', 'supermod'], $position, $stepParams
		);
	}

	public function upgrade2001771Step5(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];

		return $this->entityColumnsToJson(
			'Snog\Forms:Question', ['hasconditional'], $position, $stepParams
		);
	}

	public function upgrade2001771Step6(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];

		return $this->entityColumnsToJson(
			'Snog\Forms:Promotion', ['original_additional', 'new_additional'], $position, $stepParams
		);
	}

	public function upgrade2001871Step1()
	{
		$db = $this->db();

		// NEED TO STRIP EXISTING LINE FEEDS FIRST
		$db->query("UPDATE xf_snog_forms_questions SET expected = REPLACE(REPLACE(expected, '\n', ''), '\r', '') WHERE expected > ''");

		$db->query("UPDATE xf_snog_forms_questions SET expected = REPLACE(expected, ',', '\r\n') WHERE expected > ''");
	}

	public function upgrade2001874Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_questions', function (Alter $table) {
			$table->addColumn('checklimit', 'INT', 10)->setDefault(0);
			$table->addColumn('checkerror', 'VARCHAR', 200)->setDefault('');
		});
	}

	public function upgrade2001972Step1(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];

		return $this->entityColumnsToJson(
			'XF:User', ['snog_forms'], $position, $stepParams
		);
	}

	public function upgrade2001973Step1()
	{
		$db = $this->db();
		$maxRunTime = \XF::config('jobMaxRunTime');
		$s = microtime(true);

		$formNodes = $db->fetchAll("SELECT * FROM xf_node WHERE snog_posid > 0");

		foreach ($formNodes as $formNode)
		{
			$form = $db->fetchRow("SELECT * FROM xf_snog_forms_forms WHERE posid = " . $formNode['snog_posid']);

			if (!isset($form['posid']))
			{
				$update = ['snog_posid' => 0, 'snog_label' => ''];
				$db->update('xf_node', $update, 'node_id = ?', $formNode['node_id']);
			}

			if ($maxRunTime && (microtime(true) - $s) > $maxRunTime)
			{
				$stepResult = [
					'complete' => false,
					'params' => [],
					'step' => 1,
					'version' => 2001973
				];

				return $stepResult;
			}
		}

		return [];
	}

	public function upgrade2002071Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('bbstart', 'VARCHAR', 200)->setDefault('');
			$table->addColumn('bbend', 'VARCHAR', 200)->setDefault('');
		});
	}

	public function upgrade2002171Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('display_parent', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('display', 'INT', 10)->setDefault(0);
			$table->addKey('display');
			$table->addKey('active');
		});
	}

	public function upgrade2002171Step2()
	{
		$app = \XF::app();

		$forms = $app->finder('Snog\Forms:Form')->where('display', 0)->order('position', 'ASC')->fetch();
		$display = 0;

		foreach ($forms as $form)
		{
			$display++;
			$form->display = $display;
			$form->save(false);
		}
	}

	public function upgrade2002171Step3()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_types', function (Alter $table) {
			$table->addColumn('display_parent', 'TINYINT', 1)->setDefault(0);
			$table->addColumn('display', 'INT', 10)->setDefault(0);
			$table->addKey('display');
		});
	}

	public function upgrade2002171Step4()
	{
		$app = \XF::app();

		$types = $app->finder('Snog\Forms:Type')->where('display', 0)->order('type', 'ASC')->fetch();
		$display = 0;

		foreach ($types as $type)
		{
			$display++;
			$type->display = $display;
			$type->save(false);
		}
	}

	public function upgrade2003170Step1()
	{
		$sm = $this->schemaManager();

		// DROP/CHANGE COLUMNS
		$sm->alterTable('xf_node', function (Alter $table) {
			$table->changeColumn('snog_label', 'VARCHAR', 50)->setDefault('');
		});
	}

	//Modified by WebsSol: usmanakram5099@gmail.com
	public function upgrade2003670Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('minimum_attachments', 'INT', 10)->setDefault(1);
		});
	}

	public function upgrade2020030Step1()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->renameColumn('prefix_id', 'prefix_ids');
		});
	}

	public function upgrade2020030Step2()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->changeColumn('prefix_ids', 'MEDIUMBLOB');
		});
	}

	public function upgrade2020030Step3()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_forms', function (Alter $table) {
			$table->addColumn('submit_count', 'INT', 10)->setDefault(0);
			$table->addColumn('is_public_visible', 'TINYINT')->setDefault(0)
				->comment('Visible publicly regardless user criteria');
			$table->addColumn('cooldown', 'INT')
				->setDefault(0)
				->unsigned(false)
				->comment('Seconds between submitting new form');
		});
	}

	public function upgrade2020030Step4()
	{
		$sm = $this->schemaManager();
		$tables = $this->getTables();

		$sm->createTable('xf_snog_forms_logs', $tables['xf_snog_forms_logs']);
	}

	public function upgrade2020030Step5()
	{
		$this->schemaManager()->alterTable('xf_snog_forms_answers', function (Alter $table) {
			$table->addColumn('log_id', 'INT', 10)->setDefault(0);
			$table->addKey('log_id');
		});
	}
}
