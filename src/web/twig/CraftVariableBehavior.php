<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\web\twig;

use ether\formski\elements\Form;
use ether\formski\elements\Submission;
use yii\base\Behavior;

/**
 * Class CraftVariableBehavior
 *
 * @author  Ether Creative
 * @package ether\formski\web\twig
 */
class CraftVariableBehavior extends Behavior
{

	public function forms ($criteria = null)
	{
		$query = Form::find();

		if ($criteria)
			\Craft::configure($query, $criteria);

		return $query;
	}

	public function submissions ($criteria = null)
	{
		$query = Submission::find();

		if ($criteria)
			\Craft::configure($query, $criteria);

		return $query;
	}

}