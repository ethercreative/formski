<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements\db;

use craft\elements\db\ElementQuery;

/**
 * Class SubmissionQuery
 *
 * @author  Ether Creative
 * @package ether\formski\elements\db
 */
class SubmissionQuery extends ElementQuery
{

	protected function beforePrepare (): bool
	{
		$tableName = 'formski_submissions';

		$this->joinElementTable($tableName);

		$this->query->select([
			$tableName . '.title',
			$tableName . '.formId',
			$tableName . '.ipAddress',
			$tableName . '.userAgent',
		]);

		return parent::beforePrepare();
	}

}