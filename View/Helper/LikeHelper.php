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
		'NetCommons.Token',
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
 * Output use like setting element
 *
 * @param string $likeFieldName This should be "Modelname.fieldname" for use_like field
 * @param string $unlikeFieldName This should be "Modelname.fieldname" for use_unlike field
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

			$like = Hash::get($this->_View->request->data, $likeFieldName);
			$unlikeAttributes = array(
				'type' => 'checkbox',
				'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('likes', 'Use unlike button'),
				'ng-disabled' => ! (int)$like
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

/**
 * Output like and unlike display element
 *
 * @param array $setting Array of use like setting data.
 * @param array $content Array of content data with like count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function display($setting, $content, $attributes = array()) {
		$output = '';
		list($outBeginDiv, $outEndDiv) = $this->__displayDiv($attributes, array('inline-block', 'like-icon', 'text-muted'));

		//いいね
		if (isset($setting['use_like']) && $setting['use_like']) {
			$output .= $outBeginDiv;
			$output .= '<span class="glyphicon glyphicon-thumbs-up"></span> ';
			$output .= (int)Hash::get($content, 'Like.like_count');
			$output .= $outEndDiv;
		}

		//わるいね
		if (isset($setting['use_unlike']) && $setting['use_unlike']) {
			$output .= $outBeginDiv;
			$output .= '<span class="glyphicon glyphicon-thumbs-down"></span> ';
			$output .= (int)Hash::get($content, 'Like.unlike_count');
			$output .= $outEndDiv;
		}

		return $output;
	}

/**
 * Output like and unlike buttons element
 *
 * @param array $model String of model name
 * @param array $setting Array of use like setting data.
 * @param array $content Array of content data with like count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function buttons($model, $setting, $content, $attributes = array()) {
		$output = '';

		if (isset($content['LikesUser']['id']) || $content[$model]['status'] !== WorkflowComponent::STATUS_PUBLISHED) {
			return $this->display($setting, $content, $attributes);
		}

		list($outBeginDiv, $outEndDiv) = $this->__displayDiv($attributes, array('inline-block', 'like-icon'));

		if (! isset($content['Like']['id'])) {
			$content['Like'] = array(
				'plugin_key' => $this->_View->request->params['plugin'],
				'block_key' => Current::read('Block.key'),
				'content_key' => $content[$model]['key'],
			);
		}
		if (! isset($content['LikesUser']['id'])) {
			$content['LikesUser'] = array(
				'like_id' => null,
				'user_id' => Current::read('User.id'),
				'is_liked' => '0',
			);
		}

		$data = array(
			'Frame' => array('id' => Current::read('Frame.id')),
			'Like' => array(
				'plugin_key' => Hash::get($content, 'Like.plugin_key'),
				'block_key' => Hash::get($content, 'Like.block_key'),
				'content_key' => Hash::get($content, 'Like.content_key'),
			),
			'LikesUser' => array(
				'like_id' => Hash::get($content, 'LikesUser.like_id'),
				'user_id' => Hash::get($content, 'LikesUser.user_id'),
				'is_liked' => Hash::get($content, 'LikesUser.is_liked'),
			),
		);
		$options = array(
			'likeCount' => (int)Hash::get($content, 'LikesUser.like_count'),
			'unlikeCount' => (int)Hash::get($content, 'LikesUser.unlike_count'),
			'disabled' => false
		);

		$tokenFields = Hash::flatten($data);
		$hiddenFields = $tokenFields;
		unset($hiddenFields['LikesUser.is_liked']);
		$hiddenFields = array_keys($hiddenFields);

		$this->_View->request->data = $data;
		$tokens = $this->Token->getToken('Like', '/likes/likes/like.json', $tokenFields, $hiddenFields);
		$data += $tokens;

		$output .= '<div class="form-inline" ng-controller="Likes" ' .
						'ng-init="initialize(' . h(json_encode($data)) . ', ' . h(json_encode($options)) . ')">';

		//いいね
		if (isset($setting['use_like']) && $setting['use_like']) {
			$output .= $outBeginDiv;
			$output .= $this->_View->element('Likes.like_button', array('isLiked' => Like::IS_LIKE));
			$output .= $outEndDiv;
		}

		//わるいね
		if (isset($setting['use_unlike']) && $setting['use_unlike']) {
			$output .= $outBeginDiv;
			$output .= $this->_View->element('Likes.like_button', array('isLiked' => Like::IS_UNLIKE));
			$output .= $outEndDiv;
		}

		$output .= '</div>';

		return $output;
	}

/**
 * Get display <div> tag
 *
 * @param array $attributes Array of attributes and HTML arguments.
 * @param array $defaultClass Array of default attributes class and HTML arguments.
 * @return array <div> tag
 */
	private function __displayDiv($attributes, $defaultClass) {
		$outBeginDiv = '';
		$outEndDiv = '';

		if (isset($attributes['div'])) {
			if (! is_array($attributes['div'])) {
				$attributes['div'] = (array)$attributes['div'];
			}
			if (! isset($attributes['div']['class'])) {
				$attributes['div']['class'] = $defaultClass;
			}
			$outBeginDiv = '<div class="' . implode(' ', (array)$attributes['div']['class']) . '">';
			$outEndDiv = '</div>';
		} else {
			$outBeginDiv = '';
			$outEndDiv = '';
		}

		return array($outBeginDiv, $outEndDiv);
	}

}
