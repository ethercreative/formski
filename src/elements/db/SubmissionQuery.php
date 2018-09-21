<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements\db;

use craft\elements\db\ElementQuery;
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

	// Methods
	// =========================================================================

	// Methods: Public
	// -------------------------------------------------------------------------

	public function formId ($value)
	{
		if ($value instanceof Form)
			$this->form = $value;
		else
			$this->form = Formski::getInstance()->form->getFormById($value);

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

		return parent::beforePrepare();
	}

}