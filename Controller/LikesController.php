<?php
/**
 * Likes Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('LikesAppController', 'Likes.Controller');

/**
 * Likes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@gmail.com>
 * @package NetCommons\Likes\Controller
 */
class LikesController extends LikesAppController {

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Likes.Like',
		'Likes.LikesUser',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('save');
	}

/**
 * load
 *
 * @return void
 */
	public function load() {
		if (! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		$contentKey = $this->data['Like']['content_key'];
		$like = $this->Like->find('first', array(
			'recursive' => -1,
			'fields' => array('id', 'like_count', 'unlike_count'),
			'conditions' => array('content_key' => $contentKey)
		));

		$likesUserConditions = array(
			'like_id' => $like['Like']['id'],
		);
		if (Current::read('User.id')) {
			$likesUserConditions['user_id'] = Current::read('User.id');
		} else {
			$likesUserConditions['session_key'] = $this->Session->id();
		}

		$likesUserCount = $this->LikesUser->find('count', array(
			'recursive' => -1,
			'conditions' => $likesUserConditions
		));

		$this->set('disabled', $likesUserCount);
		$this->set('likeCount', (int)$like['Like']['like_count']);
		$this->set('unlikeCount', (int)$like['Like']['unlike_count']);
		$this->set('_serialize', array('disabled', 'likeCount', 'unlikeCount'));
	}

/**
 * save
 *
 * @return void
 */
	public function save() {
		if (! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		if ($this->Like->existsLike($this->data['Like']['content_key'])) {
			return;
		}

		$data = $this->data;
		$like = $this->Like->find('first', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $data['Like']['content_key'])
		));
		$data = Hash::merge($like, $data);
		if ($this->Like->saveLike($data)) {
			return;
		}
		$this->NetCommons->handleValidationError($this->Like->validationErrors);
	}
}
