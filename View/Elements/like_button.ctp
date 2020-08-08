<?php
/**
 * Like button view template
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Kazunori Sakamoto <exkazuu@willbooster.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<span class="<?php echo $condsStr; ?> like-button">
	<a href="" <?php echo $like['disabled'] ? 'style="display: none;"' : ''; ?>
			ng-click="save(<?php echo $isLiked . ', ' . h(json_encode($condsStr)); ?>)">
		<?php if ($isLiked === Like::IS_LIKE) : ?>
			<span class="glyphicon glyphicon-thumbs-up"></span>
			<span class="like-count"><?php echo $like['like_count']; ?></span>
		<?php else : ?>
			<span class="glyphicon glyphicon-thumbs-down"></span>
			<span class="unlike-count"><?php echo $like['unlike_count']; ?></span>
		<?php endif; ?>
	</a>
	<span class="text-muted" <?php echo $like['disabled'] ? '' : 'style="display: none;"'; ?>>
		<?php if ($isLiked === Like::IS_LIKE) : ?>
			<span class="glyphicon glyphicon-thumbs-up"></span>
			<span class="like-count"><?php echo $like['like_count']; ?></span>
		<?php else : ?>
			<span class="glyphicon glyphicon-thumbs-down"></span>
			<span class="unlike-count"><?php echo $like['unlike_count']; ?></span>
		<?php endif; ?>
	</span>
</span>
