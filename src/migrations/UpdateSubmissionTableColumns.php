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

	/**
	 * @return bool
	 * @throws \yii\base\Exception
	 * @throws \yii\base\NotSupportedException
	 */
	public function safeUp ()
	{
		$columnNames = [];
		$existingColumns = $this->_getColumns();

		// NOTE: All columns are NULL-able because making a NULL column with
		// data NOT NULL won't work

		try
		{

			// Add new columns
			foreach ($this->fields as $uid => $settings)
			{
				$type = $settings['type'];

				$columnName = 'field_' . $uid;
				$columnNames[] = $columnName;

				switch ($type)
				{
					case 'text':
						$columnType = $this->text();
						break;
					case 'textarea':
						$columnType = $this->mediumText();
						break;
					case 'dropdown':
					case 'radio':
						$columnType = $this->text();
						break;
					case 'checkbox':
						$columnType = $this->json();
						break;
					case 'acceptance':
						$columnType = $this->boolean();
						break;
					default:
						continue 2;
				}


				if ($this->_columnExists($columnName))
				{
					// $this->alterColumn is broken for Postgres in Yii2's schema
					// builder, so we have to alter the column manually
					if ($this->db->getIsPgsql())
					{
						$table = $this->db->schema->getRawTableName(
							$this->tableName
						);

						$sql = <<<SQL
ALTER TABLE "{$table}"
ALTER "{$columnName}" TYPE {$columnType},
ALTER "{$columnName}" DROP NOT NULL;
SQL;

						$this->execute($sql);
					}
					else
					{
						$this->alterColumn(
							$this->tableName,
							$columnName,
							$columnType->null()
						);
					}
				}
				else
				{
					$this->addColumn(
						$this->tableName,
						$columnName,
						$columnType->null()
					);
				}
			}

			// Delete old columns
			$columnsToDelete = array_diff($existingColumns, $columnNames);
			foreach ($columnsToDelete as $columnName)
				$this->dropColumn($this->tableName, $columnName);
		} catch (\Exception $e) {
			\Craft::dd($e);
		}

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