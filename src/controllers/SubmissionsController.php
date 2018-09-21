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
use ether\formski\Formski;
use yii\web\NotFoundHttpException;

/**
 * Class SubmissionsController
 *
 * @author  Ether Creative
 * @package ether\formski\controllers
 */
class SubmissionsController extends Controller
{

	public function actionEdit ($formId, $submissionId)
	{
		$submission = Formski::getInstance()->submission->getSubmissionById(
			$formId,
			$submissionId
		);

		if (!$submission)
			throw new NotFoundHttpException('Unable to find submission with ID: ' . $submissionId . ', Form ID: ' . $formId);

		$variables = [
			'title' => $submission->title,
			'userElementType' => User::class,
			'submission' => $submission,
		];

		return $this->renderTemplate('formski/submissions/_edit', $variables);
	}

}