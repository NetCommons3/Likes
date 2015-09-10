<?php
/**
 * Like Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Like Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Model\Behavior
 */
class LikeBehavior extends ModelBehavior {

/**
 * Model name
 *
 * @var array
 */
	public $model;

/**
 * Key field name
 *
 * @var array
 */
	public $field;

/**
 * SetUp behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
//	public function setup(Model $model, $config = array()) {
//		if (isset($config['model'])) {
//			$this->model = $config['model'];
//		} else {
//			$this->model = $model->alias;
//		}
//		if (isset($config['field'])) {
//			$this->field = $config['field'];
//		} else {
//			$this->field = 'key';
//		}
//
//		parent::setup($model, $config);
//	}

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param Model $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
//	public function afterFind(Model $model, $results, $primary = false) {
//		$this->Like = ClassRegistry::init('Likes.Like', true);
//		$user = CakeSession::read('Auth.User');
//		foreach ($results as $i => $result) {
//			if (isset($result[$this->model][$this->field])) {
//				$likeCounts = $this->Like->getCountLike($result[$this->model][$this->field], Like::IS_LIKE);
//				$unlikeCounts = $this->Like->getCountLike($result[$this->model][$this->field], Like::IS_UNLIKE);
//			}
//			if (isset($result[$this->model][$this->field]) && isset($user['id'])) {
//				$results[$i] = Hash::merge($results[$i], $this->Like->getLike($result[$this->model][$this->field], $user['id']));
//			}
//			$results[$i][$this->model]['like_counts'] = isset($likeCounts) ? $likeCounts : 0;
//			$results[$i][$this->model]['unlike_counts'] = isset($unlikeCounts) ? $unlikeCounts : 0;
//		}
//
//		return $results;
//	}
}
