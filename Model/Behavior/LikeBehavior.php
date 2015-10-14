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
 * 使用するプラグインのコンテンツモデルにLikeモデル、LikesUserモデルの
 * アソシエーションを設定します。<br>
 * fieldオプションの指定がない場合は全データを取得しますが、<br>
 * fieldオプションを個別に指定する場合は、Likeモデルのfieldも明示的に指定してください。<br>
 *
 * #### ContentModel
 * ```
 * class BbsArticle extends BbsesAppModel {
 * 	public $actsAs = array(
 * 		'Likes.Like'
 * 	)
 * }
 * ```
 * #### ContentController
 * ```
 * $bbsArticle = $this->BbsArticle->find('list');
 * ```
 * #### ResultSample
 * ```
 * $bbsArticle = array(
 * 	'BbsArticle' => array(...),
 * 	'Likes' => array(
 * 		'id' => '999',
 * 		'plugin_key' => 'abcdefg',
 * 		'block_key' => 'abcdefg',
 * 		'content_key' => 'abcdefg',
 * 		'like_count' => '9',
 * 		'unlike_count' => '9',
 * 	)
 * )
 * ```
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
	private $__model;

/**
 * Key field name
 *
 * @var array
 */
	private $__field;

/**
 * SetUp behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		if (isset($config['model'])) {
			$this->__model = $config['model'];
		} else {
			$this->__model = $model->name;
		}

		if (isset($config['field'])) {
			$this->__field = $config['field'];
		} else {
			$this->__field = 'key';
		}

		parent::setup($model, $config);
	}

/**
 * beforeFind can be used to cancel find operations, or modify the query that will be executed.
 * By returning null/false you can abort a find. By returning an array you can modify/replace the query
 * that is going to be run.
 *
 * @param Model $model Model using this behavior
 * @param array $query Data used to execute this query, i.e. conditions, order, etc.
 * @return bool|array False or null will abort the operation. You can return an array to replace the
 *   $query that will be eventually run.
 */
	public function beforeFind(Model $model, $query) {
		$model->Like = ClassRegistry::init('Likes.Like');
		$model->LikesUser = ClassRegistry::init('Likes.LikesUser');

		$conditions = $query['conditions'];
		if (is_array($query['conditions']) === false) {
			return $query;
		}

		$columns = array();
		if (! isset($query['fields'])) {
			$columns = 'Like.*';
		} else {
			$columns = $query['fields'];
		}

		$columns = Hash::merge((array)$columns, array_keys($conditions));
		// Like条件あったらJOIN
		if (! preg_grep('/^Like\./', $columns) && ! preg_grep('/^LikesUser\./', $columns)) {
			return $query;
		}

		if (! isset($query['fields'])) {
			$query['fields'] = '*';
		}
		$query['joins'][] = array(
			'table' => $model->Like->table,
			'alias' => $model->Like->alias,
			'type' => 'LEFT',
			'conditions' => array(
				'Like.plugin_key' => Inflector::underscore($model->plugin),
				$this->__model . '.' . $this->__field . ' = ' . 'Like.content_key',
			)
		);

		$likesUserConditions = array(
			'Like.id = LikesUser.like_id',
		);
		if (Current::read('User.id')) {
			$likesUserConditions['LikesUser.user_id'] = Current::read('User.id');
		} else {
			$likesUserConditions['LikesUser.session_key'] = CakeSession::id();
		}
		$query['joins'][] = array(
			'table' => $model->LikesUser->table,
			'alias' => $model->LikesUser->alias,
			'type' => 'LEFT',
			'conditions' => $likesUserConditions
		);
		return $query;
	}
}
