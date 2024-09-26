<?php

/* ========================================================================= */
/* !WordPress Comments HTML Function */
/* ========================================================================= */

if ( ! function_exists( 'jrd_comments' ) ) {
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
			<h4><span>
				<?php
				// translators: USERNAME wrote:
				printf( __( '%s wrote:', 'jrd-theme-name' ), $comment->comment_author );
				?>
				</span>
			</h4>
			<?php comment_text(); ?>
			<p class="commentDate">
				<?php
				// translators: Written on DATE at TIME
				printf( __( 'Written on %1$s at %2$s', 'jrd-theme-name' ), get_comment_date( 'n/j/Y' ), get_comment_time( 'g:ia' ) );
				?>
				<?php edit_comment_link( __( 'Edit', 'jrd-theme-name' ), '  ', '' ); ?>
			</p>
		</li>
		<?php
	}
}
