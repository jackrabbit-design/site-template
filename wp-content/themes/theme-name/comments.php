<div id="comments">
    <h3>Comments</h3>
<?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.' ); ?></p>
</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if(comments_open()) : ?>
<div class="leaveComment clearfix">
     <h3>Write a Comment</h3>
	<?php if(get_option('comment_registration') && !$user_ID) : ?>  
        <p>You must be <a href="#" class="loginlink">logged in</a> to post a comment.</p>
    <?php else : ?> 
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="gform_wrapper" id="commentform">
	<div class="contact-block">

	<?php if($user_ID) : ?>  
                <p style="margin-bottom:20px">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>
            
	<?php else : ?>
	   <p>
          <label for="author_field">Name <?php if($req){ ?><span class="gfield_required">*</span><?php } ?></label>
          <input type="text" class="input-text txt medium clearFieldBlurred" value="<?php if(!Empty($comment_author)){ echo $comment_author; } ?>" id="author_field" name="author" rel=">Name <?php if($req) echo "<span>*</span>";  ?>" tabindex="51" />
	   </p>
	   <p>
          <label for="email_field">Email Address <?php if($req){ ?><span class="gfield_required">*</span><?php } ?></label>
          <input type="text" class="input-text txt medium clearFieldBlurred" value="<?php if(!Empty($comment_author_email)){ echo $comment_author_email; } ?>" id="email_field" name="email" rel="Email (not published) <?php if($req) echo "<span>*</span>"; ?>" tabindex="52" />
	   </p>
        <?php endif; ?>
        <p class="text-area comment">
          <label for="comment_field">Your Comment <?php if($req){ ?><span class="gfield_required">*</span><?php } ?></label>
          <textarea class="textarea medium clearFieldBlurred" id="comment_field" name="comment" rel="Comment" tabindex="53"></textarea>
        </p>
        <div class="clear"></div>
        <div class="btn">
        <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
          <button type="submit" class="link-common"><span>Submit</span></button>
        </div>
      </div>
      <?php do_action('comment_form', $post->ID); ?>  
    </form>
    <?php endif; ?> 
</div>	
<?php else : ?>  
    <p>The comments are closed.</p>  
<?php endif; ?> 	
	
<?php if ( have_comments() ) : ?>
            <h4>
            <?php
			printf( _n( '1 Comment', '%1$s Comments', get_comments_number(), 'jhfn_comment' ),
			number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
			?></h4>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			
<?php else: endif; // check for comment navigation ?>

			<ul class="commentList">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use twentyten_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define twentyten_comment() and that will be used instead.
					 * See twentyten_comment() in twentyten/functions.php for more.
					 */
					wp_list_comments( array( 'callback' => 'jhfn_comment' ) );
				?>
			</ul>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'jhfn_comment' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'jhfn_comment' ) ); ?></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments: ?>
<div class="heading"> <span class="title">0 Comments</span> </div>
<p>There aren't currently any comments on this blog entry.</p>
<?	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>
</div><!-- #comments -->
