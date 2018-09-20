<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\elements;

use craft\base\Element;
use craft\db\Query;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\helpers\ArrayHelper;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use craft\validators\DateTimeValidator;
use ether\formski\elements\actions\Delete;
use ether\formski\elements\db\FormQuery;
use ether\formski\migrations\Install;
use ether\formski\records\FormRecord;
use yii\base\InvalidConfigException;

/**
 * Class Form
 *
 * @author  Ether Creative
 * @package ether\formski
 */
class Form extends Element
{

	// Properties
	// =========================================================================

	// Properties: Public
	// -------------------------------------------------------------------------

	/** @var string */
	public $handle;

	/** @var string */
	public $title;

	/** @var array */
	public $fieldLayout;

	/** @var array */
	public $fieldSettings;

	/** @var int|null */
	public $authorId;

	/** @var \DateTime|null */
	public $dateDue;

	/** @var int|null */
	public $daysToComplete;

//	/** @var int[]|null */
//	public $userGroupIds;

	// Properties: Private
	// -------------------------------------------------------------------------

	/** @var User|null */
	private $_author;

//	/** @var UserGroup[]|null */
//	private $_userGroups;

	// Methods
	// =========================================================================

	// Methods: Static
	// -------------------------------------------------------------------------

	public static function displayName (): string
	{
		return \Craft::t('formski', 'Form');
	}

	public static function refHandle ()
	{
		return 'form';
	}

	public static function hasTitles (): bool
	{
		return true;
	}

	public static function hasUris (): bool
	{
		return false;
	}

	public static function hasStatuses (): bool
	{
		return true;
	}

	public static function find (): ElementQueryInterface
	{
		return new FormQuery(self::class);
	}

	protected static function defineActions (string $source = null): array
	{
		$actions = [];

		$actions[] = \Craft::$app->getElements()->createAction([
			'type' => Delete::class,
		]);

		return $actions;
	}

	protected static function defineSources (string $context = null): array
	{
		return [
			[
				'key' => '*',
				'label' => \Craft::t('formski', 'All forms'),
				'criteria' => [],
				'defaultSort' => ['dateCreated', 'desc'],
			]
		];
	}

	protected static function defineSortOptions (): array
	{
		return [
			'title' => \Craft::t('app', 'Title'),
			'uri' => \Craft::t('app', 'URI'),
			'dateDue' => \Craft::t('formski', 'Date Due'),
			'daysToComplete' => \Craft::t('formski', 'Days to Complete'),
			[
				'label'     => \Craft::t('app', 'Date Created'),
				'orderBy'   => 'elements.dateCreated',
				'attribute' => 'dateCreated'
			],
			[
				'label'     => \Craft::t('app', 'Date Updated'),
				'orderBy'   => 'elements.dateUpdated',
				'attribute' => 'dateUpdated'
			],
		];
	}

	protected static function defineTableAttributes (): array
	{
		return [
			'title' => ['label' => \Craft::t('app', 'Title')],
			'author' => ['label' => \Craft::t('app', 'Author')],
			'dateDue' => ['label' => \Craft::t('formski', 'Date Due')],
			'daysToComplete' => ['label' => \Craft::t('formski', 'Days to Complete')],
			'id' => ['label' => \Craft::t('app', 'ID')],
			'dateCreated' => ['label' => \Craft::t('app', 'Date Created')],
			'dateUpdated' => ['label' => \Craft::t('app', 'Date Updated')],
		];
	}

	public static function eagerLoadingMap (array $sourceElements, string $handle)
	{
		if ($handle === 'author')
		{
			$sourceElementIds = ArrayHelper::getColumn($sourceElements, 'id');

			$map = (new Query())
				->select(['id as source', 'authorId as target'])
				->from([Install::FORMS_TABLE_NAME])
				->where(['and', ['id' => $sourceElementIds], ['not', ['authorId' => null]]])
				->all();

			return [
				'elementType' => User::class,
				'map' => $map,
			];
		}

		return parent::eagerLoadingMap($sourceElements, $handle);
	}

	protected static function prepElementQueryForTableAttribute (
		ElementQueryInterface $elementQuery, string $attribute
	) {
		if ($attribute === 'author') {
			$elementQuery->andWith('author');
		} else {
			parent::prepElementQueryForTableAttribute($elementQuery, $attribute);
		}
	}

	// Methods: Public
	// -------------------------------------------------------------------------

	public function init ()
	{
		parent::init();

		// TODO: This feels shitty, find a better way to convert JSON to array
		// on form populate
		while (!is_array($this->fieldLayout))
			$this->fieldLayout = Json::decodeIfJson($this->fieldLayout);

		while (!is_array($this->fieldSettings))
			$this->fieldSettings = Json::decodeIfJson($this->fieldSettings);

		// Convert required to a bool
		foreach ($this->fieldSettings as $key => $setting)
			$this->fieldSettings[$key]['required'] = boolval(
				$this->fieldSettings[$key]['required']
			);
	}

	public function extraFields ()
	{
		$names = parent::extraFields();

		$names[] = 'author';
		$names[] = 'dateDue';
		$names[] = 'daysToComplete';

		return $names;
	}

	public function datetimeAttributes (): array
	{
		$attributes = parent::datetimeAttributes();

		$attributes[] = 'dateDue';

		return $attributes;
	}

	/**
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function rules ()
	{
		$rules = parent::rules();

		$rules[] = [['authorId', 'daysToComplete'], 'number', 'integerOnly' => true];
		$rules[] = [['dateDue'], DateTimeValidator::class];

		return $rules;
	}

	public function asJson ()
	{
		$form = [];

		$form['fieldLayout'] = $this->fieldLayout;
		$form['fieldSettings'] = $this->fieldSettings;

		return Json::encode($form);
	}

	// Methods: Getters / Setters
	// -------------------------------------------------------------------------

	/**
	 * @return \craft\elements\User|User|null
	 * @throws InvalidConfigException
	 */
	public function getAuthor ()
	{
		if ($this->_author !== null)
			return $this->_author;

		if ($this->authorId === null)
			return null;

		$this->_author = \Craft::$app->users->getUserById($this->authorId);

		if ($this->_author === null)
			throw new InvalidConfigException('Invalid author ID: ' . $this->authorId);

		return $this->_author;
	}

	public function setAuthor (User $author = null)
	{
		$this->_author = $author;
	}

	public function getIsEditable (): bool
	{
		// TODO: Permissions
		return true;
	}

	public function getCpEditUrl ()
	{
		return UrlHelper::cpUrl('formski/forms/' . $this->id);
	}

	public function setEagerLoadedElements (string $handle, array $elements)
	{
		if ($handle === 'author') {
			$this->setAuthor($elements[0] ?? null);
		} else {
			parent::setEagerLoadedElements($handle, $elements);
		}
	}

	// Methods: Indexes, etc.
	// -------------------------------------------------------------------------

	protected function tableAttributeHtml (string $attribute): string
	{
		if ($attribute === 'author') {
			$author = $this->getAuthor();

			return $author
				? \Craft::$app->view->renderTemplate(
					'_elements/element',
					['element' => $author]
				) : '';
		}

		return parent::tableAttributeHtml($attribute);
	}

	/**
	 * @return string
	 * @throws \yii\base\Exception
	 */
	public function getEditorHtml (): string
	{
		$html = \Craft::$app->getView()->renderTemplateMacro(
			'_includes/forms', 'textField', [
			[
				'label'     => \Craft::t('app', 'Title'),
//				'siteId'    => $this->siteId,
				'id'        => 'title',
				'name'      => 'title',
				'value'     => $this->title,
				'errors'    => $this->getErrors('title'),
				'first'     => true,
				'autofocus' => true,
				'required'  => true
			]
		]);

		$html .= parent::getEditorHtml();

		return $html;
	}

	// Methods: Events
	// -------------------------------------------------------------------------

	/**
	 * @param bool $isNew
	 *
	 * @throws \Exception
	 */
	public function afterSave (bool $isNew)
	{
		if ($isNew) {
			$record = new FormRecord();
			$record->id = $this->id;
		} else {
			$record = FormRecord::findOne($this->id);

			if (!$record)
				throw new \Exception('Invalid form ID: ' . $this->id);
		}

		$record->handle = $this->handle;
		$record->title = $this->title;
		$record->authorId = $this->authorId;
		$record->fieldLayout = $this->fieldLayout;
		$record->fieldSettings = $this->fieldSettings;
		$record->dateDue = Db::prepareDateForDb($this->dateDue);
		$record->daysToComplete = $this->daysToComplete;
		$record->save(false);

		parent::afterSave($isNew);
	}

}