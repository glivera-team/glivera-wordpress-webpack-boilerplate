<div class="pagination_wrap">
	<?php
	the_posts_pagination( array(
		'show_all'     => false,
		'end_size'     => 2,
		'mid_size'     => 2,
		'prev_next'    => true,
		'prev_text'    => MTDUtils::pagination_prev_icon(),
		'next_text'    => MTDUtils::pagination_next_icon(),
		'add_args'     => false,
		'add_fragment' => '',
		'screen_reader_text' => __( 'Posts navigation' ),
	) );
	?>
</div>
