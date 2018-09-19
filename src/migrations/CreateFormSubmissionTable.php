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
 * Class CreateFormSubmissionTable
 *
 * @author  Ether Creative
 * @package ether\formski\migrations
 */
class CreateFormSubmissionTable extends Migration
{

	// Properties
	// =========================================================================

	public $tableName;

	// Methods
	// =========================================================================

	public function safeUp ()
	{
		$this->createTable($this->tableName, [
			'id' => $this->primaryKey(),
			'elementId'   => $this->integer()->notNull(),

			'title'       => $this->string(),

			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
			'uid'         => $this->uid(),
		]);

		$this->createIndex(
			$this->db->getIndexName($this->tableName, 'elementId'),
			$this->tableName,
			'elementId',
			true
		);

		$this->addForeignKey(
			$this->db->getForeignKeyName($this->tableName, 'elementId'),
			$this->tableName,
			'elementId',
			'{{%elements}}',
			'id',
			'CASCADE',
			null
		);
	}

	public function safeDown ()
	{
		$this->dropIndex(
			$this->db->getIndexName($this->tableName, 'elementId'),
			$this->tableName
		);

		$this->dropForeignKey(
			$this->db->getForeignKeyName($this->tableName, 'elementId'),
			$this->tableName
		);

		$this->dropTableIfExists($this->tableName);
	}

}