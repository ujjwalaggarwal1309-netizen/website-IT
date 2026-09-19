<?php if ( post_password_required() ) { return; } ?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2><?php comments_number(); ?></h2>
		<?php wp_list_comments(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</div>
