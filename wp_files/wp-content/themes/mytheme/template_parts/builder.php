<?php
/**
 * Looping over Flexible Content fields values
 * and getting subfields from different layouts.
 */

if ( have_rows( 'builder' ) ) {
	while ( have_rows( 'builder' ) ) { the_row();

//		// hero_section
//		if ( get_row_layout() == 'hero_section' ) {
//			include( TEMPLATEPATH . '/template_parts/blocks/block-hero.php' );
//		}

	}
}