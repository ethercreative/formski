<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\services;

use craft\base\Component;
use craft\web\View;
use ether\formski\elements\Submission;

/**
 * Class SubmissionService
 *
 * @author  Ether Creative
 * @package ether\formski\services
 */
class SubmissionService extends Component
{

	/**
	 * @param Submission $submission
	 *
	 * @return string
	 * @throws \Throwable
	 */
	public function renderTitle (Submission $submission)
	{
		$craft = \Craft::$app;

		$template = $submission->form->titleFormat;
		$variables = $this->asArray($submission);

		try
		{
			// If this is a CP request, render the title as if it was the frontend
			if ($craft->request->isCpRequest)
			{
				$site   = $craft->sites->currentSite;
				$tpMode = $craft->view->templateMode;
				$craft->sites->setCurrentSite($submission->site);
				$craft->view->setTemplateMode(View::TEMPLATE_MODE_SITE);
				$ret = \Craft::$app->view->renderObjectTemplate(
					$template,
					$variables
				);
				$craft->sites->setCurrentSite($site);
				$craft->view->setTemplateMode($tpMode);
			}
			else
			{
				$ret = \Craft::$app->view->renderObjectTemplate(
					$template,
					$variables
				);
			}
		} catch (\Exception $e) {
			$ret = 'ERROR: ' . $e->getMessage();
		}

		return $ret;
	}

	public function asArray (Submission $submission)
	{
		$ret = [
			'title' => $submission->title,
			'ipAddress' => $submission->ipAddress,
			'userAgent' => $submission->userAgent,
		];

		foreach ($submission->form->fieldSettings as $uid => $settings)
			if (!empty($settings['handle']))
				$ret[$settings['handle']] = $submission->fields[$uid];

		return $ret;
	}

	public function getSubmissionById ($formId, $submissionId)
	{
		$query = Submission::find();
		$query->id = $submissionId;
		$query->formId = $formId;

		return $query->one();
	}

}