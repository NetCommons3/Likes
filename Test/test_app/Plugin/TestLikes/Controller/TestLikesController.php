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
 * use component
 *
 * @var array
 */
	public $components = array(
		'Security' => false
	);

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
 * index method
 *
 * @throws NotFoundException
 * @return void
 */
	public function like() {
		parent::like();
	}

}
