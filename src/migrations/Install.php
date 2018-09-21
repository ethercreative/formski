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
		// Drop form content tables
		foreach (\Craft::$app->db->schema->tableNames as $tableName)
			if (strpos($tableName, 'formski_form_') !== false)
				$this->dropTableIfExists($tableName);

		// Drop Forms Table
		$this->dropTableIfExists(self::FORMS_TABLE_NAME);
	}

	// Methods: Private
	// -------------------------------------------------------------------------

	private function _forms ()
	{
		if ($this->db->tableExists(self::FORMS_TABLE_NAME))
			return;

		$this->createTable(self::FORMS_TABLE_NAME, [
			'id'     => $this->primaryKey(),
			'handle' => $this->char(5)->notNull(),

			'authorId' => $this->integer()->notNull(),

			'title'          => $this->char(255)->notNull(),
			'slug'           => $this->char(255)->notNull(),
			'titleFormat'    => $this->string()->notNull(),
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
	}

}