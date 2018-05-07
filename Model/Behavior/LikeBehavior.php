<?php
/**
 * Like Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
App::uses('Current', 'NetCommons.Utility');

/**
 * Like Behavior
 *
 * 使用するプラグインのコンテンツモデルにLikeモデル、LikesUserモデルの
 * アソシエーションを設定します。<br>
 * fieldオプションの指定がない場合は全データを取得しますが、<br>
 * fieldオプションを個別に指定する場合は、Likeモデルのfieldも明示的に指定してください。<br>
 *
 * #### Sample code
 * ##### ContentModel
 * ```
 * class BbsArticle extends BbsesAppModel {
 * 	public $actsAs = array(
 * 		'Likes.Like'
 * 	)
 * }
 * ```
 * ##### ContentController
 * ```
 * $bbsArticle = $this->BbsArticle->find('list');
 * ```
 * ##### ResultSample
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
 * 設定オプションは[setupメソッド](#setup)を参照
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Likes\Model\Behavior
 */
class LikeBehavior extends ModelBehavior {

/**
 * SetUp behavior
 *
 * Likeモデル、LikesUserモデルのアソシエーションで、別モデル、別フィールド名を指定することがます。<br>
 * デフォルト値は、モデル名が呼び出し元名称、フィールド名が"key"になっています。
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		if (isset($config['model'])) {
			$this->settings[$model->alias]['model'] = $config['model'];
		} else {
			$this->settings[$model->alias]['model'] = $model->alias;
		}

		if (isset($config['field'])) {
			$this->settings[$model->alias]['field'] = $config['field'];
		} else {
			$this->settings[$model->alias]['field'] = 'key';
		}

		parent::setup($model, $config);
	}

/**
 * 検索時にタグの検索条件があったらJOINする
 *
 * @param Model $model タグ使用モデル
 * @param array $query find条件
 * @return array タグ検索条件を加えたfind条件
 */
	public function beforeFind(Model $model, $query) {
		if (Hash::get($query, 'recursive') > -1) {
			$joinTable = false;

			$conditions = $query['conditions'];
			if (is_array($conditions) === false) {
				return $query;
			}
			$columns = array_keys($conditions);
			// 条件あったらLikeテーブルとリンクテーブルをJOIN
			if (preg_grep('/^Like\./', $columns) || preg_grep('/^LikesUser\./', $columns)) {
				$joinTable = true;
			}

			if ($joinTable) {
				$likesUserConditions = array(
					'Like.id = LikesUser.like_id',
				);
				if (Current::read('User.id')) {
					$likesUserConditions['LikesUser.user_id'] = Current::read('User.id');
				} else {
					$likesUserConditions['LikesUser.session_key'] = CakeSession::id();
				}

				$LikesUser = ClassRegistry::init('Likes.LikesUser');
				$Like = ClassRegistry::init('Likes.Like');

				$fieldName = $this->settings[$model->alias]['model'] . '.' .
								$this->settings[$model->alias]['field'];
				$query['joins'][] = [
					'type' => 'LEFT',
					'table' => $Like->table,
					'alias' => $Like->alias,
					'conditions' => [
						'Like.plugin_key' => Inflector::underscore($model->plugin),
						$fieldName . ' = ' . 'Like.content_key',
					]
				];
				$query['joins'][] = [
					'type' => 'LEFT',
					'table' => $LikesUser->table,
					'alias' => $LikesUser->alias,
					'conditions' => $likesUserConditions,
				];
			}
		}
		return $query;
	}

}
