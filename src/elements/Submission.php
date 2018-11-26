<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements;

use craft\base\Element;
use craft\elements\actions\Delete;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\helpers\UrlHelper;
use ether\formski\elements\db\SubmissionQuery;
use ether\formski\Formski;

/**
 * Class Submission
 *
 * @property Form $form
 *
 * @author  Ether Creative
 * @package ether\formski\elements
 */
class Submission extends Element
{

	// Properties
	// =========================================================================

	// Properties: Public
	// -------------------------------------------------------------------------

	/** @var string */
	public $title;

	/** @var int */
	public $formId;

	/** @var int|null */
	public $userId;

	/** @var string */
	public $ipAddress;

	/** @var string */
	public $userAgent;

	/** @var array */
	public $fields;

	// Properties: Private
	// -------------------------------------------------------------------------

	/** @var Form */
	private $_form;

	/** @var User|null */
	private $_user;

	// Methods
	// =========================================================================

	public function __set ($name, $value)
	{
		// Is this the "field_handle" syntax?
		if (strncmp($name, 'field_', 6) === 0)
		{
			$this->fields[substr($name, 6)] = $value;
			return null;
		}

		if ($this->form && array_key_exists($name, $this->form->fieldSettings))
		{
			$this->fields[$name] = $value;
			return null;
		}

		return parent::__set($name, $value);
	}

	public function __get ($name)
	{
		// Is this the "field_handle" syntax?
		if (strncmp($name, 'field_', 6) === 0)
			return $this->fields[substr($name, 6)];

		if (array_key_exists($name, $this->fields))
			return $this->fields[$name];

		return parent::__get($name);
	}

	public function offsetExists ($offset)
	{
		return parent::offsetExists($offset) || array_key_exists($offset, $this->fields);
	}

	public function offsetGet ($offset)
	{
		if (array_key_exists($offset, $this->fields))
			return $this->fields[$offset];

		return parent::offsetGet($offset);
	}

	// Methods: Static
	// -------------------------------------------------------------------------

	public static function displayName (): string
	{
		return \Craft::t('formski', 'Submission');
	}

	public static function refHandle ()
	{
		return 'submission';
	}

	public static function hasTitles (): bool
	{
		return true;
	}

	/**
	 * @return SubmissionQuery
	 */
	public static function find (): ElementQueryInterface
	{
		return new SubmissionQuery(self::class);
	}

	protected static function defineActions (string $source = null): array
	{
		$actions = [];

		$actions[] = \Craft::$app->elements->createAction([
			'type' => Delete::class,
			'confirmationMessage' => \Craft::t('formski', 'Are you sure you want to delete those submissions?'),
			'successMessage' => \Craft::t('formski', 'Submissions deleted!'),
		]);

		return $actions;
	}

	protected static function defineSources (string $context = null): array
	{
		$sources[] = [
			'heading' => \Craft::t('formski', 'Forms'),
		];

		$forms = Form::findAll();

		/** @var Form $form */
		foreach ($forms as $form)
		{
			$sources[] = [
				'key' => 'form:' . $form->id,
				'label' => $form->title,
				'criteria' => ['formId' => $form->id],
			];
		}

		return $sources;
	}

	protected static function defineTableAttributes (): array
	{
		return [
			'title'       => ['label' => \Craft::t('app', 'Title')],
			'user'        => ['label' => \Craft::t('app', 'User')],
			'dateCreated' => ['label' => \Craft::t('app', 'Date Created')],
			'dateUpdated' => ['label' => \Craft::t('app', 'Date Updated')],
		];
	}

	protected static function defineSearchableAttributes (): array
	{
		return ['id', 'title'];
	}

	// Methods: Public
	// -------------------------------------------------------------------------

	/**
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 */
	public function rules ()
	{
		$rules = parent::rules();

		$rules[] = [['formId'], 'required'];

		foreach ($this->form->fieldSettings as $uid => $settings)
		{
			if (!empty($settings['required']))
				$rules[] = [[$uid], 'required'];

			if ($settings['_type'] === 'acceptance')
				$rules[] = [
					[$uid],
					'required',
					'requiredValue' => '1',
					'message'       => 'You must accept!'
				];
		}

		return $rules;
	}

	public function attributes ()
	{
		$attributes = parent::attributes();

		foreach ($this->form->fieldSettings as $uid => $field)
			$attributes[] = $uid;

		return $attributes;
	}

	public function attributeLabels ()
	{
		$labels = parent::attributeLabels();

		foreach ($this->form->fieldSettings as $uid => $field)
			$labels[$uid] = array_key_exists('label', $field) ? $field['label'] : 'Not Labeled';

		return $labels;
	}

	protected function tableAttributeHtml (string $attribute): string
	{
		if ($attribute === 'user')
		{
			$user = $this->getUser();

			return $user
				? \Craft::$app->view->renderTemplate(
					'_elements/element',
					['element' => $user]
				) : '';
		}

		return parent::tableAttributeHtml($attribute);
	}

	// Methods: Getters / Setters
	// -------------------------------------------------------------------------

	public function getUser ()
	{
		if ($this->_user)
			return $this->_user;

		if ($this->userId === null)
			return null;

		return $this->_user = \Craft::$app->users->getUserById($this->userId);
	}

	public function setUser (User $user = null)
	{
		$this->_user = $user;
	}

	public function getForm ()
	{
		if ($this->_form)
			return $this->_form;

		return $this->_form = Formski::getInstance()->form->getFormById($this->formId);
	}

	public function getCpEditUrl ()
	{
		return UrlHelper::cpUrl('formski/submissions/' . $this->formId . '-' . $this->id);
	}

	// Methods: Events
	// -------------------------------------------------------------------------

	public function afterSave (bool $isNew)
	{
		$db = \Craft::$app->db;
		$tableName = $this->form->getTableName();

		$content = [
			'title' => $this->title,
			'formId' => $this->formId,
			'userId' => $this->userId,
			'ipAddress' => $this->ipAddress,
			'userAgent' => $this->userAgent,
		];

		foreach ($this->fields as $uid => $value)
			$content['field_' . $uid] = $value;

		if ($isNew)
		{
			$content['id'] = $this->id;

			$db->createCommand()->insert(
				$tableName,
				$content
			)->execute();
		}
		else
		{
			$db->createCommand()->update(
				$tableName,
				$content,
				['id' => $this->id]
			)->execute();
		}

		parent::afterSave($isNew);
	}

}