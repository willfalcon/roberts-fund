<div class="pagination">
	<?php
		global $wp_query;

    $big = 999999999; // need an unlikely integer

    $args = array(
	    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format' => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total' => $wp_query->max_num_pages,
      'prev_text'          => __('<i class="fa fa-angle-left fa-lg"></i>', 'cdhq'),
			'next_text'          => __('<i class="fa fa-angle-right fa-lg"></i>', 'cdhq'),
		);
    echo paginate_links( $args );
	?>
</div>
