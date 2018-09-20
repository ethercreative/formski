<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\services;

use craft\base\Component;
use ether\formski\elements\Form;
use ether\formski\migrations\CreateFormSubmissionTable;
use ether\formski\migrations\UpdateSubmissionTableColumns;

/**
 * Class FormService
 *
 * @author  Ether Creative
 * @package ether\formski\services
 */
class FormService extends Component
{

	public function save (Form $form)
	{
		$form->validate();

		if ($form->hasErrors())
			return false;

		$transaction = \Craft::$app->db->beginTransaction();

		try {
			$db = \Craft::$app->db;

			$tableName = $this->_getFormContentTableName($form);

			// Create the table if it doesn't exist
			if (!$db->tableExists($tableName))
			{
				$migration = new CreateFormSubmissionTable(compact('tableName'));

				ob_start();
				$migration->up();
				ob_end_clean();
			}

			// Add columns to the table
			$fields = [];
			foreach ($form->fieldSettings as $fieldUid => $setting)
				$fields[$fieldUid] = [
					'type' => $setting['_type'],
					'required' => $setting['required'],
				];

			$migration = new UpdateSubmissionTableColumns(
				compact('tableName', 'fields')
			);
			ob_start();
			$migration->up();
			ob_end_clean();

			// Save the element
			if (!\Craft::$app->elements->saveElement($form))
			{
				\Craft::$app->session->setError(
					\Craft::t('formski', 'Unable to save form!')
				);

				\Craft::dd($form->getErrors());

				return false;
			}

			$transaction->commit();

		} catch (\Exception $e) {
			\Craft::$app->session->setError(
				\Craft::t(
					'formski',
					'Unable to save form: ' . $e->getMessage()
				)
			);

			$transaction->rollBack();

			return false;
		}

		return true;
	}

	public function delete ($forms)
	{
		if (!is_array($forms))
			$forms = [$forms];

		$db = \Craft::$app->db;

		foreach ($forms as $form)
		{
			$transaction = $db->beginTransaction();

			try {
				$tableName = $this->_getFormContentTableName($form);
				$db->createCommand()->dropTable($tableName)->execute();

				if (!\Craft::$app->elements->deleteElement($form)) {
					$transaction->rollBack();

					return false;
				}
			} catch (\Exception $e) {
				$transaction->rollBack();

				return false;
			} catch (\Throwable $e) {
				$transaction->rollBack();

				\Craft::dd($e);

				return false;
			}

			$transaction->commit();
		}

		return true;
	}

	/**
	 * @param $id
	 *
	 * @return Form|null
	 */
	public function getFormById ($id)
	{
		return Form::findOne(['id' => $id]);
	}

	// Helpers
	// =========================================================================

	private function _getFormContentTableName (Form $form)
	{
		return '{{%formski_form_' . $form->handle . '}}';
	}

}