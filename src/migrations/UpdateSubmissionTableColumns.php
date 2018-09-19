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
 * Class UpdateSubmissionTableColumns
 *
 * @author  Ether Creative
 * @package ether\formski\migrations
 */
class UpdateSubmissionTableColumns extends Migration
{

	// Properties
	// =========================================================================

	public $tableName;
	public $fields;

	// Methods
	// =========================================================================

	// Methods: Public
	// -------------------------------------------------------------------------

	public function safeUp ()
	{
		$columnNames = [];
		$existingColumns = $this->_getColumns();

		// Add new columns
		foreach ($this->fields as $uid => $settings)
		{
			$type = $settings['type'];
			$required = $settings['required'];

			$columnName = 'field_' . $uid;
			$columnNames[] = $columnName;

			switch ($type) {
				case 'text':
					$columnType = $this->text();
					break;
				case 'textarea':
					$columnType = $this->mediumText();
					break;
				case 'dropdown':
				case 'radio':
				case 'checkbox':
					$columnType = $this->char(255);
					break;
				case 'acceptance':
					$columnType = $this->boolean();
					break;
				default:
					continue 2;
			}

			if ($required) $columnType = $columnType->notNull();
			else $columnType = $columnType->null();

			if ($this->_columnExists($columnName)) {
				$this->alterColumn(
					$this->tableName,
					$columnName,
					$columnType
				);
			} else {
				$this->addColumn(
					$this->tableName,
					$columnName,
					$columnType
				);
			}
		}

		// Delete old columns
		$columnsToDelete = array_diff($existingColumns, $columnNames);
		foreach ($columnsToDelete as $columnName)
			$this->dropColumn($this->tableName, $columnName);

		return true;
	}

	// Methods: Private
	// -------------------------------------------------------------------------

	private function _getColumns ()
	{
		return array_filter(
			\Craft::$app->db->getTableSchema($this->tableName)->columnNames,
			function ($columnName) {
				return strpos($columnName, 'field_') !== false;
			}
		);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 * @throws \yii\base\NotSupportedException
	 */
	private function _columnExists ($name)
	{
		return \Craft::$app->db->columnExists(
			$this->tableName,
			$name
		);
	}

}