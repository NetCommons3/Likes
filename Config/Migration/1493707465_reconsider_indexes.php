<?php
/**
 * インデックスの見直し
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * インデックスの見直し
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Config\Migration
 */
class ReconsiderIndexes extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'reconsider_indexes';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'likes' => array(
					'plugin_key' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'プラグインKey', 'charset' => 'utf8'),
					'content_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '各プラグインのコンテンツKey', 'charset' => 'utf8'),
				),
				'likes_users' => array(
					'like_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'いいねID'),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index', 'comment' => 'ユーザID'),
					'session_key' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'セッションKey', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'likes' => array('indexes' => array('content_key')),
				'likes_users' => array('indexes' => array('like_id')),
			),
			'create_field' => array(
				'likes' => array(
					'indexes' => array(
						'content_key' => array('column' => array('plugin_key', 'content_key'), 'unique' => 0),
					),
				),
				'likes_users' => array(
					'indexes' => array(
						'session_key' => array('column' => array('session_key', 'like_id'), 'unique' => 0),
						'like_id' => array('column' => array('user_id', 'like_id'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'likes' => array(
					'plugin_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'プラグインKey', 'charset' => 'utf8'),
					'content_key' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => '各プラグインのコンテンツKey', 'charset' => 'utf8'),
				),
				'likes_users' => array(
					'like_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index', 'comment' => 'いいねID'),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
					'session_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'セッションKey', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'likes' => array(
					'indexes' => array(
						'content_key' => array(),
					),
				),
				'likes_users' => array(
					'indexes' => array(
						'like_id' => array(),
					),
				),
			),
			'drop_field' => array(
				'likes' => array('indexes' => array('content_key')),
				'likes_users' => array('indexes' => array('session_key', 'like_id')),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
