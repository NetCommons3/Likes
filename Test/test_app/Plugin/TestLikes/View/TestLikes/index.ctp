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
