<?php
/**
 * Likes Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('LikesAppController', 'Likes.Controller');

/**
 * Likes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
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
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('like');
	}

/**
 * like
 *
 * @return void
 */
	public function like() {
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
