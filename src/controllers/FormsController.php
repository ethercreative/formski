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
use ether\formski\elements\Form;
use ether\formski\Formski;
use ether\formski\web\assets\forms\FormEditAsset;
use yii\web\NotFoundHttpException;

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
	 * @param string|null $formId
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionEdit (string $formId = null)
	{
		$view = \Craft::$app->view;

		$variables = [
			'title' => 'New Form',
			'userElementType' => User::class,
		];

		$opts = '';

		if ($formId)
		{
			$form = Formski::getInstance()->form->getFormById($formId);

			if (!$form)
				throw new NotFoundHttpException('Unable to find form with ID: ' . $formId);

			$variables['form'] = $form;
			$variables['title'] = $form->title;

			$opts = $form->asJson();
		}

		$view->registerAssetBundle(FormEditAsset::class);
		$view->registerJs(
			'new FormskiBuilder(' . $opts . ');',
			View::POS_END
		);

		return $this->renderTemplate('formski/forms/_edit', $variables);
	}

	/**
	 * @return null|\yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function actionSave ()
	{
		$this->requirePostRequest();
		$request = \Craft::$app->request;

		// Get existing form (if ID was sent)
		if (($id = $request->getBodyParam('id'))) {
			$form = Form::findOne(['id' => $id]);

			if (!$form)
				throw new NotFoundHttpException('Unable to find form with ID: ' . $id);
		} else {
			$form = new Form();
			$form->handle = $this->_uid();
		}

		// Get fields
		$form->title = $request->getRequiredBodyParam('title');
		$form->authorId = $request->getRequiredBodyParam('author')[0];
		$form->dateDue = $request->getBodyParam('dateDue');
		$form->daysToComplete = $request->getBodyParam('daysToComplete');
		$form->enabled = !!$request->getBodyParam('enabled', false);
		$form->fieldSettings = $request->getBodyParam('fieldSettings', []);

		// Normalize layout
		$layout = [];
		$rawLayout = $request->getRequiredBodyParam('fieldLayout');

		foreach ($rawLayout as $field)
		{
			$row = key($field);
			$field = $field[$row][0];

			if (!array_key_exists($row, $layout))
				$layout[$row] = [];

			$layout[$row][] = $field;
		}

		$form->fieldLayout = $layout;

		if (!Formski::getInstance()->form->save($form)) {
			\Craft::$app->session->setError(
				\Craft::t('formski', 'Unable to save form!')
			);

			\Craft::$app->urlManager->setRouteParams([
				'form' => $form,
			]);

			return null;
		}

		\Craft::$app->session->setNotice(
			\Craft::t('formski', 'Form saved!')
		);

		return $this->redirectToPostedUrl($form);
	}

	// Helpers
	// =========================================================================

	private function _uid ($len = 5)
	{
		$token        = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$max          = strlen($codeAlphabet);

		for ($i = 0; $i < $len; $i++)
			$token .= $codeAlphabet[$this->_cryptoRandSecure(0, $max - 1)];

		return $token;
	}

	private function _cryptoRandSecure ($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log    = ceil(log($range, 2));
		$bytes  = (int)($log / 8) + 1; // length in bytes
		$bits   = (int)$log + 1; // length in bits
		$filter = (int)(1 << $bits) - 1; // set all lower bits to 1
		do
		{
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd > $range);

		return $min + $rnd;
	}

}