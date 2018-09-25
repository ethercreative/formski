<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\fields;

use craft\fields\BaseRelationField;
use ether\formski\elements\Form;

/**
 * Class FormField
 *
 * @author  Ether Creative
 * @package ether\formski\fields
 */
class FormField extends BaseRelationField
{

	public static function displayName (): string
	{
		return \Craft::t('formski', 'Forms');
	}

	public static function defaultSelectionLabel (): string
	{
		return \Craft::t('formski', 'Add a form');
	}

	protected static function elementType (): string
	{
		return Form::class;
	}

}