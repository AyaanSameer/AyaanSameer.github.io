<?php
/**
 * Title: Menu highlights
 * Slug: cardamom-cafe/menu-highlights
 * Categories: cardamom-cafe, text
 * Description: Three short menu columns with dotted leaders between each dish and its price.
 * Keywords: menu, prices, food, drinks
 *
 * @package Cardamom_Cafe
 */

/*
 * The menu is data, so it is written once here and turned into blocks below.
 * The owner can still edit every line in the editor after inserting it.
 */
$cardamom_cafe_menu = array(
	__( 'Tea & coffee', 'cardamom-cafe' ) => array(
		array( __( 'Karak, the classic', 'cardamom-cafe' ), '3' ),
		array( __( 'Saffron karak', 'cardamom-cafe' ), '5' ),
		array( __( 'Arabic coffee & dates', 'cardamom-cafe' ), '8' ),
		array( __( 'Spanish latte', 'cardamom-cafe' ), '16' ),
	),
	__( 'From the oven', 'cardamom-cafe' ) => array(
		array( __( 'Zaatar manakish', 'cardamom-cafe' ), '7' ),
		array( __( 'Cheese & honey fatayer', 'cardamom-cafe' ), '9' ),
		array( __( 'Chips Oman paratha', 'cardamom-cafe' ), '8' ),
		array( __( 'Cardamom bun', 'cardamom-cafe' ), '10' ),
	),
	__( 'Cold & fresh', 'cardamom-cafe' ) => array(
		array( __( 'Fresh lime with mint', 'cardamom-cafe' ), '12' ),
		array( __( 'Avocado & date shake', 'cardamom-cafe' ), '18' ),
		array( __( 'Iced karak', 'cardamom-cafe' ), '8' ),
		array( __( 'Laban with mint', 'cardamom-cafe' ), '6' ),
	),
);
?>
<!-- wp:group {"align":"full","anchor":"menu","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0"}}},"layout":{"type":"constrained","contentSize":"1160px"}} -->
<div id="menu" class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'On the menu', 'cardamom-cafe' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"textColor":"cardamom"} -->
	<p class="has-cardamom-color has-text-color"><?php esc_html_e( 'Prices in Qatari riyals. Ask about today’s specials at the counter.', 'cardamom-cafe' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--50)">
		<?php foreach ( $cardamom_cafe_menu as $cardamom_cafe_section => $cardamom_cafe_items ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php echo esc_html( $cardamom_cafe_section ); ?></h3>
			<!-- /wp:heading -->
			<?php foreach ( $cardamom_cafe_items as $cardamom_cafe_item ) : ?>
			<!-- wp:paragraph {"className":"cardamom-menu-item"} -->
			<p class="cardamom-menu-item"><strong><?php echo esc_html( $cardamom_cafe_item[0] ); ?></strong><span class="cardamom-price"><?php echo esc_html( $cardamom_cafe_item[1] ); ?></span></p>
			<!-- /wp:paragraph -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
