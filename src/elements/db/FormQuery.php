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

/**
 * Class FormQuery
 *
 * @author  Ether Creative
 * @package ether\formski\elements\db
 */
class FormQuery extends ElementQuery
{

	// Properties
	// =========================================================================

	/** @var int|null */
	public $authorId;

	/** @var \DateTime|null */
	public $dateDue;

	// Methods
	// =========================================================================

	// Methods: Public
	// -------------------------------------------------------------------------

	public function authorId ($value)
	{
		$this->authorId = $value;
		return $this;
	}

	public function dateDue ($value)
	{
		$this->dateDue = $value;
		return $this;
	}

	// Methods: Protected
	// -------------------------------------------------------------------------

	protected function beforePrepare (): bool
	{
		$this->joinElementTable('formski_forms');

		$this->query->select([
			'formski_forms.handle',
			'formski_forms.title',
			'formski_forms.slug',
			'formski_forms.titleFormat',
			'formski_forms.authorId',
			'formski_forms.fieldLayout',
			'formski_forms.fieldSettings',
			'formski_forms.daysToComplete',
			'formski_forms.dateDue',
		]);

		if ($this->authorId) {
			$this->subQuery->andWhere(
				Db::parseParam(
					'formski_forms.authorId',
					$this->authorId
				)
			);
		}

		if ($this->dateDue) {
			$this->subQuery->andWhere(
				Db::parseDateParam(
					'formski_forms.dateDue',
					$this->dateDue
				)
			);
		}

		return parent::beforePrepare();
	}

}