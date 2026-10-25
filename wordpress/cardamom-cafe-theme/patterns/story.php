<?php
/**
 * Title: Our story
 * Slug: cardamom-cafe/story
 * Categories: cardamom-cafe, about
 * Description: Two columns: the café's story beside three short promises.
 * Keywords: about, story
 *
 * @package Cardamom_Cafe
 */
?>
<!-- wp:group {"align":"full","anchor":"story","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0"}}},"backgroundColor":"white","layout":{"type":"constrained","contentSize":"1160px"}} -->
<div id="story" class="wp-block-group alignfull has-white-background-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'A corner of the neighbourhood', 'cardamom-cafe' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'We started with one idea: karak should never be rushed. The tea simmers until it turns the colour of caramel, the cardamom is crushed by hand every morning, and the bread comes out of the oven in small batches all day.', 'cardamom-cafe' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Pull up a chair after school pick-up, bring your laptop for a quiet hour, or call ahead and we will have your usual ready at the window.', 'cardamom-cafe' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:list {"className":"is-style-default","style":{"spacing":{"blockGap":"1rem"},"typography":{"lineHeight":"1.5"}},"fontSize":"large","fontFamily":"fraunces"} -->
			<ul style="line-height:1.5" class="wp-block-list is-style-default has-fraunces-font-family has-large-font-size">
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Cardamom crushed fresh every morning', 'cardamom-cafe' ); ?></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Bread baked in small batches all day', 'cardamom-cafe' ); ?></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Drive-up window for your usual', 'cardamom-cafe' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
