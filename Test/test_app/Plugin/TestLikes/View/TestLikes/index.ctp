<?php
/**
 * TestPlugin index
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(array(
	'/bbses/css/style.css',
	'/likes/css/style.css'
));

echo $this->NetCommonsHtml->script('/likes/js/likes.js');


echo $this->element('Likes.like_button', array('isLiked' => Like::IS_UNLIKE));
echo $this->Like->setting('BbsSetting.use_like', 'BbsSetting.use_unlike');
echo $this->Like->setting('BbsSetting.use_like', null);

echo $this->Like->setting('BbsSetting.use_like', null);

$setting = array(
	'use_like' => 1,
	'use_unlike' => 1);
$content = array();
echo $this->Like->display($setting, $content);

$bbsArticle = array(
	'BbsArticle' => array(
			'key' => 'bbs_article_1',
			'status' => '1')
);
echo $this->Like->buttons('BbsArticle', $setting, $bbsArticle);

$bbsArticle = array(
	'BbsArticle' => array(
			'key' => 'bbs_article_1',
			'status' => '2')
);
echo $this->Like->buttons('BbsArticle', $setting, $bbsArticle);

