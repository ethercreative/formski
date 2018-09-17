<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\records;

use craft\records\Element;
use craft\db\ActiveRecord;
use craft\records\User;
use ether\formski\migrations\Install;
use yii\db\ActiveQueryInterface;

/**
 * Class FormRecord
 *
 * @property int $id
 * @property int $authorId
 * @property mixed $fields
 * @property \DateTime|null $dateDue
 * @property int|null $daysToComplete
 *
 * @author  Ether Creative
 * @package ether\formski\records
 */
class FormRecord extends ActiveRecord
{

	public static function tableName (): string
	{
		return Install::FORMS_TABLE_NAME;
	}

	public function getElement (): ActiveQueryInterface
	{
		return $this->hasOne(Element::class, ['id' => 'id']);
	}

	public function getAuthor (): ActiveQueryInterface
	{
		return $this->hasOne(User::class, ['id' => 'authorId']);
	}

}