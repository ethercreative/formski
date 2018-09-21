<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements\db;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use ether\formski\elements\Form;
use ether\formski\Formski;
use yii\base\InvalidConfigException;

/**
 * Class SubmissionQuery
 *
 * @author  Ether Creative
 * @package ether\formski\elements\db
 */
class SubmissionQuery extends ElementQuery
{

	// Properties
	// =========================================================================

	/** @var Form */
	public $form;

	/** @var int */
	public $formId;

	/** @var int */
	public $userId;

	// Methods
	// =========================================================================

	// Methods: Public
	// -------------------------------------------------------------------------

	public function form ($value)
	{
		$this->form = $value;

		return $this;
	}

	public function formId ($value)
	{
		$this->form = Formski::getInstance()->form->getFormById($value);

		return $this;
	}

	public function userId ($value)
	{
		$this->userId = $value;

		return $this;
	}

	// Methods: Protected
	// -------------------------------------------------------------------------

	protected function beforePrepare (): bool
	{
		if (!$this->form && $this->formId)
			$this->form = Formski::getInstance()->form->getFormById($this->formId);

		if (!$this->form)
			throw new InvalidConfigException('Missing form!');

		$tableName = $this->form->getTableName(true);

		$this->joinElementTable($tableName);

		$this->query->select($tableName . '.*');

		if ($this->userId)
		{
			$this->subQuery->andWhere(
				Db::parseParam(
					'formski_forms.userId',
					$this->userId
				)
			);
		}

		return parent::beforePrepare();
	}

}