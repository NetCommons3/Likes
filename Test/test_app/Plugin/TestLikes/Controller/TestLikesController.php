<?php
/**
 * TestLikes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('LikesController', 'Likes.Controller');
//App::uses('TestLikesController', 'Likes.Controller');

/**
 * TestLikes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Test\test_app\Plugin\Likes\Controller
 */
class TestLikesController extends LikesController {

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Likes.Like',
	);

/**
 * uses
 *
 * @var array
 */
	public $uses = array(
		'Likes.Like',
		//'Containers.Container',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('like');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}

/**
 * index method
 *
 * @param string $id boxId
 * @throws NotFoundException
 * @return void
 */
	public function index() {
		$this->like();
	}

}
