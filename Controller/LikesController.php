<?php
/**
 * Likes Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@willbooster.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('LikesAppController', 'Likes.Controller');

/**
 * Likes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@willbooster.com>
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
		$this->Auth->allow('load');
		$this->Auth->allow('save');
	}

/**
 * load
 *
 * @return void
 */
	public function load() {
		$likes = [];
		$condsStrs = explode(',', $this->request->query('like_conds_strs'));

		$likesUserConditions = [];
		if (Current::read('User.id')) {
			$likesUserConditions['user_id'] = Current::read('User.id');
		} else {
			$likesUserConditions['session_key'] = $this->Session->id();
		}

		foreach ($condsStrs as $condsStr) {
			$conds = explode('-', $condsStr);
			$like = $this->Like->find('first', [
				'fields' => [
					'id',
					'like_count',
					'unlike_count',
				],
				'conditions' => [
					'plugin_key' => $conds[0],
					'block_key' => $conds[1],
					'content_key' => $conds[2],
				],
				'recursive' => -1,
			]);
			if (! empty($like)) {
				$like = $like['Like'];
				$likesUserConditions['like_id'] = $like['id'];
				$like['disabled'] = $this->LikesUser->find('count', [
					'conditions' => $likesUserConditions,
					'recursive' => -1,
				]);
				unset($like['id']);
				$likes[$condsStr] = $like;
			}
		}

		$this->set('likes', $likes);
		$this->set('_serialize', ['likes']);
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
		$md5SessionKey = $this->__getKey();
		if ($this->Like->existsLike($this->data['Like']['content_key'], $md5SessionKey)) {
			return;
		}

		$data = $this->data;
		$like = $this->Like->find('first', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $data['Like']['content_key'])
		));
		$data = Hash::merge($like, $data);
		if (!Current::read('User.id')) {
			$data['LikesUser']['session_key'] = $md5SessionKey;
		}
		if ($this->Like->saveLike($data)) {
			return;
		}
		$this->NetCommons->handleValidationError($this->Like->validationErrors);
	}

/**
 * session_keyを返す
 *
 * @return string
 */
	private function __getKey() {
		$md5SessionKey = $this->Session->read('Likes.md5_session_key');
		if ($md5SessionKey) {
			return $md5SessionKey;
		}
		$md5SessionKey = md5(CakeSession::id());
		$this->Session->write('Likes.md5_session_key', $md5SessionKey);
		return $md5SessionKey;
	}
}
