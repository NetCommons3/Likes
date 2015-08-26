<?php
/**
 * Like initialize view template
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$data = array(
	'Frame' => array('id' => $frameId),
	'Like' => array(
		//'id' => '',
		'plugin_key' => $this->request->params['plugin'],
		'block_key' => $blockKey,
		'content_key' => $contentKey,
		'is_liked' => null
	),
);

$options = array(
	'likeCounts' => $likeCounts,
	'unlikeCounts' => $unlikeCounts,
	'disabled' => $disabled
);

if (! $disabled) {
	$tokenFields = Hash::flatten($data);
	$hiddenFields = $tokenFields;
	unset($hiddenFields['Like.is_liked']);
	$hiddenFields = array_keys($hiddenFields);

	$this->request->data = $data;
	$tokens = $this->Token->getToken('Like', '/likes/likes/like/' . $frameId . '.json', $tokenFields, $hiddenFields);
	$data += $tokens;
}
?>
ng-controller="Likes"
ng-init="initialize(<?php echo h(json_encode($data)); ?>,
					<?php echo h(json_encode($options)); ?>)"
