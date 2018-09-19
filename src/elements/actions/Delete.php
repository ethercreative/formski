<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements\actions;

use craft\base\ElementAction;
use craft\elements\db\ElementQueryInterface;
use ether\formski\Formski;

/**
 * Class Delete
 *
 * @author  Ether Creative
 * @package ether\formski\elements\actions
 */
class Delete extends ElementAction
{

	// Properties
	// =========================================================================

	public $confirmationMessage;

	public $successMessage;

	// Methods
	// =========================================================================

	// Methods: Static
	// -------------------------------------------------------------------------

	public static function isDestructive (): bool
	{
		return true;
	}

	// Methods: Public
	// -------------------------------------------------------------------------

	public function getTriggerLabel (): string
	{
		return \Craft::t('app', 'Delete…');
	}

	public function getConfirmationMessage ()
	{
		return \Craft::t(
			'formski',
			'Are you sure you want to delete those forms?'
		);
	}

	public function performAction (ElementQueryInterface $query): bool
	{
		if (Formski::getInstance()->form->delete($query->all()))
		{
			$this->setMessage(
				\Craft::t('formski', 'Forms deleted!')
			);

			return true;
		}

		$this->setMessage(
			\Craft::t('formski', 'Unable to delete forms!')
		);

		return false;
	}

}