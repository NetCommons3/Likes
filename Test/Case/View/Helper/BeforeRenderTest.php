<?php
/**
 * LikeHelper Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('View', 'View');
App::uses('LikeHelper', 'Likes.View/Helper');
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * beforeRender for LikeHelper Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Test\Case\View\Helper
 */
class LikeHelperBeforeRenderTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.likes.like',
		'plugin.likes.likes_user',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Like = new LikeHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Like);
		parent::tearDown();
	}

/**
 * testBeforeRender method
 *
 * @return void
 */
	public function testBeforeRender() {
		$viewFile = array();
		$this->Like->beforeRender($viewFile);
	}

}
