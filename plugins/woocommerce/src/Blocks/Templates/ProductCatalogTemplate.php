<?php

namespace Automattic\WooCommerce\Blocks\Templates;

use Automattic\WooCommerce\Blocks\Utils\BlockTemplateUtils;

/**
 * ProductCatalogTemplate class.
 *
 * @internal
 */
class ProductCatalogTemplate extends AbstractTemplate {

	/**
	 * The slug of the template.
	 *
	 * @var string
	 */
	const SLUG = 'archive-product';

	/**
	 * Initialization method.
	 */
	public function init() {
		add_action( 'template_redirect', array( $this, 'render_block_template' ) );
		add_filter( 'post_type_archive_title', array( $this, 'update_product_archive_title' ), 10, 2 );
	}

	/**
	 * Returns the title of the template.
	 *
	 * @return string
	 */
	public function get_template_title() {
		return _x( 'Product Catalog', 'Template name', 'woocommerce' );
	}

	/**
	 * Returns the description of the template.
	 *
	 * @return string
	 */
	public function get_template_description() {
		return __( 'Displays your products.', 'woocommerce' );
	}

	/**
	 * Renders the default block template from Woo Blocks if no theme templates exist.
	 */
	public function render_block_template() {
		if ( ! is_embed() && ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) ) {
			$templates = get_block_templates( array( 'slug__in' => array( self::SLUG ) ) );

			if ( isset( $templates[0] ) && BlockTemplateUtils::template_has_legacy_template_block( $templates[0] ) ) {
				add_filter( 'woocommerce_disable_compatibility_layer', '__return_true' );
			}

			if ( ! BlockTemplateUtils::theme_has_template( self::SLUG ) ) {
				add_filter( 'woocommerce_has_block_template', '__return_true', 10, 0 );
			}
		}
	}

	/**
	 * Update the product archive title to "Shop".
	 *
	 * @param string $post_type_name Post type 'name' label.
	 * @param string $post_type      Post type.
	 *
	 * @return string
	 */
	public function update_product_archive_title( $post_type_name, $post_type ) {
		if (
			function_exists( 'is_shop' ) &&
			is_shop() &&
			'product' === $post_type
		) {
			return __( 'Shop', 'woocommerce' );
		}

		return $post_type_name;
	}
}
