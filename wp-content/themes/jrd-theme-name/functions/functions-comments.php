<?php

/* ========================================================================= */
/* !WordPress Comments HTML Function */
/* ========================================================================= */

function jrd_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	//print_r($comment);

	/* Check if post is by Author for special styling */
	$is_by_author = false;
	if ( get_the_author_meta( 'email' ) === $comment->comment_author_email ) {
		$is_by_author = true;
	}
	?>

	<li>
		<h4><span><?php echo $comment->comment_author; ?></span> wrote:</h4>
		<?php comment_text(); ?>
		<p class="commentDate">
			Written on <?php printf( __( '%1$s at %2$s' ), get_comment_date( 'n/j/Y' ), get_comment_time( 'g:ia' ) ); ?> <?php edit_comment_link( __( 'Edit' ), '  ', '' ); ?>
		</p>
	</li>

	<?php
}
