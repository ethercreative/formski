<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski;

use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use ether\formski\services\FormService;
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
	}

	// Events
	// =========================================================================

	public function onRegisterCpUrlRules (RegisterUrlRulesEvent $event)
	{
		$event->rules['formski/forms'] = 'formski/forms';
		$event->rules['formski/forms/new'] = 'formski/forms/edit';
		$event->rules['formski/forms/<formId:\d+>'] = 'formski/forms/edit';
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