<?php
// The Query
	$args = array(

	  'post_type' => 'product',			  
	  'post_status' => 'publish',	  
	  'orderby'=>'ID',
	  'order'=>'des',	  
	  'posts_per_page' =>-1,
	  'caller_get_posts'=> 1		
	);

	$the_query = new WP_Query( $args );
	// The Loop
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$title_product=get_the_title();
			?>
				<div class="checkbox" product_id="<?php echo get_the_ID();?>" id="product_<?php echo get_the_ID();?>">
					 <input id="<?php echo get_the_ID();?>" type="checkbox" name="chk-description" value="<?php echo get_the_ID();?>">
					<label for="<?php echo get_the_ID();?>"><?php echo trim($title_product);?></label>
				</div>
			<?php
		}
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
?>