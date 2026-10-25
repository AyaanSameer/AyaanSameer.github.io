<?php
/**
 * Title: Hero
 * Slug: cardamom-cafe/hero
 * Categories: cardamom-cafe, banner
 * Description: The café's opening line, a short welcome and two calls to action.
 * Keywords: hero, welcome, intro
 *
 * @package Cardamom_Cafe
 */
?>
<!-- wp:group {"align":"full","className":"cardamom-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0"}}},"backgroundColor":"sand","layout":{"type":"constrained","contentSize":"1160px"}} -->
<div class="wp-block-group alignfull cardamom-hero has-sand-background-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"62%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:62%">
			<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.14em","fontWeight":"600"}},"textColor":"cardamom","fontSize":"small"} -->
			<p class="has-cardamom-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.14em;text-transform:uppercase"><?php esc_html_e( 'Al Sadd · Open from 6:30 am', 'cardamom-cafe' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading"><?php echo wp_kses_post( __( 'Karak brewed slowly, the <em>way it should be</em>.', 'cardamom-cafe' ) ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size"><?php esc_html_e( 'Cardamom Café is a small neighbourhood spot for strong tea, Arabic coffee and something warm from the oven — whether you have five minutes or the whole afternoon.', 'cardamom-cafe' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#menu"><?php esc_html_e( 'See the menu', 'cardamom-cafe' ); ?></a></div>
				<!-- /wp:button -->
				<!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"2px"}},"textColor":"espresso"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-espresso-color has-text-color wp-element-button" href="/visit/" style="border-width:2px"><?php esc_html_e( 'Hours & directions', 'cardamom-cafe' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:group {"style":{"border":{"radius":"18px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"espresso","textColor":"cream","layout":{"type":"default"}} -->
			<div class="wp-block-group has-cream-color has-espresso-background-color has-text-color has-background" style="border-radius:18px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.14em","fontWeight":"600"}},"textColor":"saffron","fontSize":"small"} -->
				<p class="has-saffron-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.14em;text-transform:uppercase"><?php esc_html_e( 'Today’s pour', 'cardamom-cafe' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
				<h2 class="wp-block-heading has-x-large-font-size"><?php esc_html_e( 'Saffron karak', 'cardamom-cafe' ); ?></h2>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><?php esc_html_e( 'Assam tea simmered with evaporated milk, crushed cardamom and a pinch of Iranian saffron. QAR 5.', 'cardamom-cafe' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
