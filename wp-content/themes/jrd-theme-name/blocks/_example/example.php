<?php
/**
 * Example Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

if ( ! empty( $block['data']['_is_preview'] ) ) {
	echo block_preview( __DIR__ );
	return false;
}

// global $post;

// Create class attribute allowing for custom "className" value.
$classes = array();
if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}
?>

<section class='<?php echo esc_attr( implode( ' ', $classes ) ); ?>'>

</section>