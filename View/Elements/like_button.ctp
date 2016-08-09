<?php
/**
 * Like button view template
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<a href="" ng-hide="(options.disabled && !sending)"
		ng-class="{'text-muted':options.disabled}"
		ng-click="save(<?php echo $isLiked; ?>)" ng-cloak>
	<?php if ($isLiked === Like::IS_LIKE) : ?>
		<span class="glyphicon glyphicon-thumbs-up"></span> {{options.likeCount}}
	<?php else : ?>
		<span class="glyphicon glyphicon-thumbs-down"></span> {{options.unlikeCount}}
	<?php endif; ?>
</a>

<span class="text-muted" ng-show="(options.disabled && !sending)">
	<?php if ($isLiked === Like::IS_LIKE) : ?>
		<span class="glyphicon glyphicon-thumbs-up"></span> {{options.likeCount}}
	<?php else : ?>
		<span class="glyphicon glyphicon-thumbs-down"></span> {{options.unlikeCount}}
	<?php endif; ?>
</span>