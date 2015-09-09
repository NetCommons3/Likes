<?php
/**
 * Like Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * Like Helper
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\View\Helper
 */
class LikeHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Form',
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsHtml',
	);

/**
 * Before render callback. beforeRender is called before the view file is rendered.
 *
 * Overridden in subclasses.
 *
 * @param string $viewFile The view file that is going to be rendered
 * @return void
 */
	public function beforeRender($viewFile) {
		$this->NetCommonsHtml->css('/likes/css/style.css');
		$this->NetCommonsHtml->script('/likes/js/likes.js');
		parent::beforeRender($viewFile);
	}

/**
 * Output categories select element
 *
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function setting($likeFieldName, $unlikeFieldName, $attributes = array()) {
		$output = '';

		//属性の設定
		$defaultAttributes = array(
			'error' => false,
			'div' => array('class' => 'form-inline'),
			'label' => false,
			'legend' => false,
		);
		$likeAttributes = array(
			'type' => 'checkbox',
			'label' => '<span class="glyphicon glyphicon-thumbs-up"> </span> ' . __d('likes', 'Use like button')
		);
		if (isset($unlikeFieldName)) {
			$likeAttributes['ng-click'] = 'useLike()';

			$unlike = Hash::get($this->_View->request->data, $unlikeFieldName);
			$unlikeAttributes = array(
				'type' => 'checkbox',
				'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('likes', 'Use unlike button'),
				'ng-disabled' => ! (int)$unlike
			);
			$unlikeAttributes = Hash::merge($defaultAttributes, $unlikeAttributes, $attributes);
		}
		$likeAttributes = Hash::merge($defaultAttributes, $likeAttributes, $attributes);

		//共通DIVの出力
		if (isset($unlikeFieldName)) {
			$output .= '<div class="row form-group" ng-controller="LikeSettings" ' .
							'ng-init="initialize(\'' . $this->domId($likeFieldName) . '\', \'' . $this->domId($unlikeFieldName) . '\')">';
		} else {
			$output .= '<div class="row form-group">';
		}

		//いいねの出力
		$output .= '<div class="col-xs-12">';
		$output .= $this->Form->input($likeFieldName, $likeAttributes);
		$output .= '</div>';

		//わるいねの出力
		if (isset($unlikeFieldName)) {
			$output .= '<div class="col-xs-11 col-xs-offset-1">';
			$output .= $this->Form->input($unlikeFieldName, $unlikeAttributes);
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}
}
