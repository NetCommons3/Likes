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
 * Is like
 *
 * @var int
 */
	const IS_LIKE = 1;

/**
 * Is unlike
 *
 * @var int
 */
	const IS_UNLIKE = 0;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'likes_users',
			'foreignKey' => 'like_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

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
		$this->validate = ValidateMerge::merge($this->validate, array(
			'plugin_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				)
			),
			'block_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				)
			),
			'content_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				)
			),
			'like_count' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'unlike_count' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		if (isset($this->data['LikesUser'])) {
			$this->LikesUser->set($this->data['LikesUser']);
			if (! $this->LikesUser->validates()) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors, $this->LikesUser->validationErrors
				);
				return false;
			}
		}
		return parent::beforeValidate($options);
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		//LikesUser登録
		if (isset($this->LikesUser->data['LikesUser'])) {
			if (! $this->LikesUser->data['LikesUser']['like_id']) {
				$this->LikesUser->data['LikesUser']['like_id'] = $this->data[$this->alias]['id'];
			}
			if (! $this->LikesUser->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$likeCount = $this->LikesUser->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'like_id' => $this->data[$this->alias]['id'],
					'is_liked' => true
				)
			));

			$unlikeCount = $this->LikesUser->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'like_id' => $this->data[$this->alias]['id'],
					'is_liked' => false
				)
			));

			$update = array(
				$this->alias . '.like_count' => $likeCount,
				$this->alias . '.unlike_count' => $unlikeCount,
				$this->alias . '.weight' => $likeCount - $unlikeCount,
			);
			$conditions = array(
				$this->alias . '.id' => $this->data[$this->alias]['id']
			);
			if (! $this->updateAll($update, $conditions)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * Exists like data
 *
 * @param string $contentKey Content key of each plugin.
 * @param string $md5SessionKey md5(SessionKey)
 * @see https://github.com/researchmap/RmNetCommons3/issues/1750#issuecomment-596823362
 * @return bool
 */
	public function existsLike($contentKey, $md5SessionKey = null) {
		$this->LikesUser = ClassRegistry::init('Likes.LikesUser');

		$joinConditions = array(
			$this->alias . '.id' . ' = ' . $this->LikesUser->alias . ' .like_id',
		);
		if (Current::read('User.id')) {
			$joinConditions[$this->LikesUser->alias . '.user_id'] = Current::read('User.id');
		} elseif (!is_null($md5SessionKey)) {
			$joinConditions[$this->LikesUser->alias . '.session_key'] = (string)$md5SessionKey;
		} else {
			// 常にインクリメント
			return false;
		}

		$count = $this->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->alias . '.content_key' => $contentKey,
			),
			'joins' => array(
				array(
					'table' => $this->LikesUser->table,
					'alias' => $this->LikesUser->alias,
					'type' => 'INNER',
					'conditions' => $joinConditions,
				),
			)
		));

		return (bool)$count;
	}

/**
 * Save is_liked
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveLike($data) {
		$this->loadModels([
			'LikesUser' => 'Likes.LikesUser',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			//登録処理
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
