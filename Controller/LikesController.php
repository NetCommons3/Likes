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
		file_put_contents(APP . 'tmp/logs/watura.log', "LikesController.like\n", FILE_APPEND);

		if ($this->request->is('get')) {
			$this->response->header('Pragma', 'no-cache');
			$like = $this->Like->getLikeByContentKey($this->request->query['contentKey']);
			file_put_contents(APP . 'tmp/logs/watura.log', print_r($like, true), FILE_APPEND);
			// TODO: should check disabled or not
			$this->set('likeCount', $like['Like']['like_count']);
			$this->set('unlikeCount', $like['Like']['unlike_count']);
			$this->set('_serialize', array('likeCount', 'unlikeCount'));
			return;
		}

		if (! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		if ($this->Like->existsLike($this->data['Like']['content_key'])) {
			return;
		}

		$data = $this->data;
		$like = $this->Like->getLikeByContentKey($data['Like']['content_key']);
		$data = Hash::merge($like, $data);
		if ($this->Like->saveLike($data)) {
			return;
		}
		$this->NetCommons->handleValidationError($this->Like->validationErrors);
	}
}
