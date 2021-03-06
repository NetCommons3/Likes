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
 * Setting for LikeHelper Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Test\Case\View\Helper
 */
class LikeHelperSettingTest extends NetCommonsCakeTestCase {

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
 * settingのテスト
 *
 * @param string $likeFieldName "Modelname.fieldname" for use_like field
 * @param string $unlikeFieldName "Modelname.fieldname" for use_unlike field
 * @dataProvider dataProviderSetting
 * @return void
 */
	public function testSetting($likeFieldName, $unlikeFieldName) {
		$attributes = array();

		$result = $this->Like->setting($likeFieldName, $unlikeFieldName, $attributes);

		$this->assertContains('<input type="checkbox" name="data[Like][use_like]"', $result);
		$this->assertContains(__d('likes', 'Use like button'), $result);
		if ($unlikeFieldName) {
			$this->assertContains(__d('likes', 'Use unlike button'), $result);
		} else {
			$this->assertNotContains(__d('likes', 'Use unlike button'), $result);
		}
	}

/**
 * settingのDataProvider
 *
 * #### 戻り値
 *  - likeFieldName "Modelname.fieldname" for use_like field
 *  - unlikeFieldName "Modelname.fieldname" for use_unlike field
 * @return array
 */
	public function dataProviderSetting() {
		$likeFieldName = 'Like.use_like';
		$unlikeFieldName = 'Like.use_unlike';

		return array(
			array($likeFieldName, $unlikeFieldName),
			array($likeFieldName, null),
		);
	}

}
