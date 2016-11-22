<?php
/**
 * LikesUserFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * LikesUserFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Model
 */
class LikesUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'),
		'like_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'いいねID'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'ユーザID'),
		'is_liked' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => '0:わるいね、1:いいね'),
		'session_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'セッションKey', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'like_id' => 1,
			'user_id' => 1,
			'is_liked' => 1,
			'created_user' => 1,
			'created' => '2015-09-10 15:59:55',
			'modified_user' => 1,
			'modified' => '2015-09-10 15:59:55'
		),
	);

}
