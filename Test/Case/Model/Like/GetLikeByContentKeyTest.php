<?php
/**
 * Like::getLikeByContentKey()のテスト
 *
 * @property Like $Like
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * Like::getLikeByContentKey()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@gmail.com>
 * @package NetCommons\Likes\Test\Case\Model\Like
 */
class LikeGetLikeByContentKeyTest extends NetCommonsModelTestCase {

/**
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'likes';

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
 * Model name
 *
 * @var array
 */
	protected $_modelName = 'Like';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'getLikeByContentKey';

/**
 * getLikeByContentKeyのテスト
 *
 * @param array $contentKey キー情報
 * @param string $expected 期待値
 * @dataProvider dataProviderCountLikes
 * @return void
 */
	public function testGetLikeByContentKey($contentKey) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テスト実行
		$result = $this->$model->$method($contentKey);

		$this->assertEquals($result['Like']['content_key'], $expected);
	}

/**
 * getLikeByContentKeyのDataProvider
 *
 * #### 戻り値
 *  - contentKey 取得データ
 *  - expected 期待値
 *
 * @return array
 */
	public function dataProviderCountLikes() {
		return array(
			array('aaa', null),
			array('testcontent', 'testcontent'),
		);
	}

}
