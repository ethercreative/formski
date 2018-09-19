<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\migrations;

use craft\db\Migration;

/**
 * Class Install
 *
 * @author  Ether Creative
 * @package ether\formski\migrations
 */
class Install extends Migration
{

	// Constants
	// =========================================================================

	const FORMS_TABLE_NAME = '{{%formski_forms}}';
//	const FORMS_USERGROUPS_JUNCTION = '{{%formski_forms_usergroups}}';

	// Methods
	// =========================================================================

	// Methods: Public
	// -------------------------------------------------------------------------

	public function safeUp ()
	{
		$this->_forms();
	}

	public function safeDown ()
	{
		// Drop Forms Table
		$this->dropTableIfExists(self::FORMS_TABLE_NAME);

		// Drop form content tables
		foreach (\Craft::$app->db->schema->tableNames as $tableName)
			if (strpos($tableName, 'formski_form_') !== false)
				$this->dropTableIfExists($tableName);
	}

	// Methods: Private
	// -------------------------------------------------------------------------

	private function _forms ()
	{
		if ($this->db->tableExists(self::FORMS_TABLE_NAME))
			return;

		$this->createTable(self::FORMS_TABLE_NAME, [
			'id' => $this->primaryKey(),
			'handle' => $this->char(5)->notNull(),

			'authorId' => $this->integer()->notNull(),

			'title'          => $this->char(255)->notNull(),
			'fieldLayout'    => $this->json()->null(),
			'fieldSettings'  => $this->json()->null(),
			'dateDue'        => $this->dateTime()->null(),
			'daysToComplete' => $this->integer()->null(),

			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
			'uid'         => $this->uid(),
		]);

		$this->addForeignKey(
			$this->db->getForeignKeyName(self::FORMS_TABLE_NAME, 'id'),
			self::FORMS_TABLE_NAME,
			'id',
			'{{%elements}}',
			'id',
			'CASCADE',
			null
		);

		// TODO: User Group relations
//		$this->createTable(self::FORMS_USERGROUPS_JUNCTION, [
//			'id'          => $this->primaryKey(),
//			'groupId'     => $this->integer()->notNull(),
//			'formId'      => $this->integer()->notNull(),
//			'dateCreated' => $this->dateTime()->notNull(),
//			'dateUpdated' => $this->dateTime()->notNull(),
//			'uid'         => $this->uid(),
//		]);
//
//		$this->createIndex(
//			null,
//			self::FORMS_USERGROUPS_JUNCTION,
//			['groupId', 'formId'],
//			true
//		);
//
//		$this->createIndex(
//			null,
//			self::FORMS_USERGROUPS_JUNCTION,
//			['formId'],
//			false
//		);
//
//		$this->addForeignKey(
//			null,
//			self::FORMS_USERGROUPS_JUNCTION,
//			['groupId'],
//			'{{%usergroups}}',
//			['id'],
//			'CASCADE',
//			null
//		);
//
//		$this->addForeignKey(
//			null,
//			self::FORMS_USERGROUPS_JUNCTION,
//			['formId'],
//			self::FORMS_TABLE_NAME,
//			['id'],
//			'CASCADE',
//			null
//		);
	}

}