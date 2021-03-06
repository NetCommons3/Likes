<?php
/**
 * Like::ExistsLike Like()のテスト
 *
 * @property Like $Like
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * Like::ExistLike()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Test\Case\Model\Like
 */
class LikeExistsLikeTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'existsLike';

/**
 * existsLikeのテスト
 *
 * @param array $contentKey キー情報
 * @param string $expected 期待値
 * @param int $userId ユーザーID
 * @dataProvider dataProviderExistsLike
 * @return void
 */
	public function testExistsLike($contentKey, $expected, $userId = 0) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		Current::$current['User']['id'] = $userId;

		//テスト実行
		$result = $this->$model->$method($contentKey);

		$this->assertEquals($result, $expected);
	}

/**
 * existsLikeのDataProvider
 *
 * #### 戻り値
 *  - contentKey 取得データ
 *  - expected 期待値
 *  - userId ユーザーID
 *
 * @return array
 */
	public function dataProviderExistsLike() {
		return array(
			array('aaa', 0),
			array('testcontent', 0),
			array('testcontent', 1, 1),
		);
	}

}
