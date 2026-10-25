<?php
/**
 * Title: Visit call to action
 * Slug: cardamom-cafe/visit-cta
 * Categories: cardamom-cafe, call-to-action
 * Description: A dark band inviting people to visit or get in touch.
 * Keywords: visit, contact, cta
 *
 * @package Cardamom_Cafe
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0"}}},"backgroundColor":"cardamom","textColor":"cream","layout":{"type":"constrained","contentSize":"1160px"}} -->
<div class="wp-block-group alignfull has-cream-color has-cardamom-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"constrained","contentSize":"620px","justifyContent":"left"}} -->
		<div class="wp-block-group">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Planning a gathering?', 'cardamom-cafe' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'We cater majlis evenings, office mornings and school events with karak flasks and trays of fresh bakes. Tell us the date and how many guests.', 'cardamom-cafe' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"cream","textColor":"espresso"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-espresso-color has-cream-background-color has-text-color has-background wp-element-button" href="/contact/"><?php esc_html_e( 'Send an enquiry', 'cardamom-cafe' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
