<div class="meta">
	<time class="published label label-primary" datetime="<?php echo get_the_time('c'); ?>">
		<?php echo get_the_date(); ?>
	</time>
	<span class="byline author vcard">
		<?php echo __(' By', 'roots'); ?> 
		<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn">
			<?php echo get_the_author(); ?>
		</a>
	</span>
</div>
