<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use ether\formski\elements\Form;
use ether\formski\services\FormService;
use ether\formski\web\twig\CraftVariableBehavior;
use yii\base\Event;

/**
 * Class Formski
 *
 * @property FormService $form
 *
 * @author  Ether Creative
 * @package ether\formski
 */
class Formski extends Plugin
{

	// Properties
	// =========================================================================

	public $hasCpSection = true;

	public $hasCpSettings = true;

	public $schemaVersion = '1.0.0';

	// Init
	// =========================================================================

	public function init ()
	{
		parent::init();

		\Craft::setAlias(
			'@formskiWeb',
			__dir__ . '/web'
		);

		// Components
		// ---------------------------------------------------------------------

		$this->setComponents([
			'form' => FormService::class,
		]);

		// Events
		// ---------------------------------------------------------------------

		Event::on(
			UrlManager::class,
			UrlManager::EVENT_REGISTER_CP_URL_RULES,
			[$this, 'onRegisterCpUrlRules']
		);

		Event::on(
			CraftVariable::class,
			CraftVariable::EVENT_INIT,
			[$this, 'onVariableInit']
		);

		Event::on(
			Elements::class,
			Elements::EVENT_REGISTER_ELEMENT_TYPES,
			[$this, 'onRegisterElementTypes']
		);
	}

	// Events
	// =========================================================================

	public function onRegisterCpUrlRules (RegisterUrlRulesEvent $event)
	{
		$event->rules['formski/forms'] = 'formski/forms';
		$event->rules['formski/forms/new'] = 'formski/forms/edit';
		$event->rules['formski/forms/<formId:\d+>'] = 'formski/forms/edit';
	}

	public function onVariableInit (Event $event)
	{
		/** @var CraftVariable $variable */
		$variable = $event->sender;
		$variable->attachBehavior(
			'bookings',
			CraftVariableBehavior::class
		);
	}

	public function onRegisterElementTypes (RegisterComponentTypesEvent $event)
	{
		$event->types[] = Form::class;
	}

	// Getters
	// =========================================================================

	public function getCpNavItem ()
	{
		$parent = parent::getCpNavItem();

		$parent['subnav']['submissions'] = [
			'label' => \Craft::t('formski', 'Submissions'),
			'url'   => 'formski/submissions',
		];

		$parent['subnav']['forms'] = [
			'label' => \Craft::t('formski', 'Forms'),
			'url'   => 'formski/forms',
		];

		$parent['subnav']['settings'] = [
			'label' => \Craft::t('app', 'Settings'),
			'url'   => 'formski/settings',
		];

		return $parent;
	}

}