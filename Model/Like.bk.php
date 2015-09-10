<?php
/**
 * Like Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('LikesAppModel', 'Likes.Model');

/**
 * Like Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Model
 */
class Like extends LikesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'is_liked' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				)
			),
		));
		return parent::beforeValidate($options);
	}

/**
 * Get count of is_liked
 *
 * @param string $contentKey Content key of each plugin.
 * @param bool $isLiked Is liked value
 * @return int number
 */
	public function getCountLike($contentKey, $isLiked) {
		return $this->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'content_key' => $contentKey,
				'is_liked' => $isLiked
			),
		));
	}

/**
 * Exists like data
 *
 * @param string $contentKey Content key of each plugin.
 * @param int $userId Users.id
 * @return bool
 */
	public function existsLike($contentKey, $userId) {
		$count = $this->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'content_key' => $contentKey,
				'user_id' => $userId
			),
		));

		return ($count > 0 ? true : false);
	}

/**
 * Get data of like
 *
 * @param string $contentKey Content key of each plugin.
 * @param int $userId Users.id
 * @return array Like data
 */
	public function getLike($contentKey, $userId) {
		return $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'content_key' => $contentKey,
				'user_id' => $userId
			),
		));
	}

/**
 * Save is_liked
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveLike($data) {
		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//バリデーション
			if (!$this->validateLike($data)) {
				return false;
			}

			//登録処理
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}

		return true;
	}

/**
 * validate bbs_frame_setting
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateLike($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}
		return true;
	}

}
