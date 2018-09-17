<?php
/**
 * Formski for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace ether\formski\web\assets\forms;

use craft\web\AssetBundle;

/**
 * Class FormEditAsset
 *
 * @author  Ether Creative
 * @package ether\formski\web\assets\forms
 */
class FormEditAsset extends AssetBundle
{

	public function init ()
	{
		$this->sourcePath = __DIR__;

		$this->css = [
			'edit-form.css',
		];

		parent::init();
	}

}