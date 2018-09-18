<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\controllers;

use craft\elements\User;
use craft\web\Controller;
use craft\web\View;
use ether\formski\web\assets\forms\FormEditAsset;

/**
 * Class FormsController
 *
 * @author  Ether Creative
 * @package ether\formski\controllers
 */
class FormsController extends Controller
{

	public function actionIndex ()
	{
		return $this->renderTemplate('formski/forms');
	}

	/**
	 * @return \yii\web\Response
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionEdit ()
	{
		$view = \Craft::$app->view;

		$variables = [
			'title' => 'Edit Form',
			'userElementType' => User::class,
		];

		$view->registerAssetBundle(FormEditAsset::class);
		$view->registerJs('new FormskiBuilder();', View::POS_END);

		return $this->renderTemplate('formski/forms/_edit', $variables);
	}

}