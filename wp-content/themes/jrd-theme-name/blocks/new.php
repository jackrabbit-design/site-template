<?php
/*

To use:
1. Right-click new.php, select "Open in Integrated Terminal"
2. enter "php new.php"
3. Type the slug and title for the new block

*/

print( 'Enter the SLUG of the block: ' );
$slug = fopen( 'php://stdin', 'r' );
$slug = rtrim( fgets( $slug ) );

print( 'Enter the TITLE of the block: ' );

$title = fopen( 'php://stdin', 'r' );
$title = rtrim( fgets( $title ) );

mkdir( $slug );
file_put_contents(
	"./$slug/block.json",
	'{
    "name": "jrd/' . $slug . '",
    "title": "' . $title . '",
    "description": "",
    "category": "",
    "icon": "block-default",
    "keywords": [],
    "acf": {
        "blockVersion": 3,
        "renderTemplate": "' . $slug . '.php",
        "postTypes": []
    },
    "align": "full",
    "supports": {
        "align": false,
        "align_text": false,
        "align_content": false,
        "full_height": false,
        "mode": false,
        "multiple": true
    },
    "example": {
        "attributes": {
            "mode": "preview",
            "data": {
                "_is_preview": true
            }
        }
    }
}'
);

file_put_contents(
	"./$slug/$slug.php",
	'<?php
/**
 * Block Name: ' . $title . '
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it\'s parent block.
 */

if ( ! empty( $block[\'data\'][\'_is_preview\'] ) ) {
	echo block_preview( __DIR__ );
	return false;
}

// global $post;

// Create class attribute allowing for custom "className" value.
$class_name = \'\';
if ( ! empty( $block[\'className\'] ) ) {
	$class_name .= \' \' . $block[\'className\'];
}
?>

<section class=\'<?php echo esc_attr( $class_name ); ?>\'>

</section>'
);

copy( './_example/preview.jpg', "./$slug/preview.jpg" );
