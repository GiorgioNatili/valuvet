<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to tfuse_comment() which is
 * located in the functions.php file.
 *
 */
?>

    <div id="comments" class="comment-list">

    <?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'tfuse' ); ?></p>
    </div><!-- #comments -->
<?php
    /* Stop the rest of comments.php from being processed,
    * but don't kill the script entirely -- we still have
    * to fully load the template.
    */
    return;
endif;
?>

<?php // You can start editing here -- including this comment! ?>

<?php if ( have_comments() ) : ?>
<h2><?php comments_number( 'No Comments Yet', '1 Comment Added', '% Comments Added' ); ?></h2>
<a href="#addcomments" class="link-join"><strong><?php _e('Join the conversation', 'tfuse'); ?></strong></a>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
    <nav id="comment-nav-above">
        <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tfuse' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tfuse' ) ); ?></div>
    </nav>
    <?php endif; // check for comment navigation ?>

<ol class="commentlist">
    <?php
    /* Loop through and list the comments. Tell wp_list_comments()
    * to use tfuse_comment() to format the comments.
    * If you want to overload this in a child theme then you can
    * copy file comments-template.php to child theme or
    * define your own tfuse_comment() and that will be used instead.
    * See tfuse_comment() in comments-template.php for more.
    */
    get_template_part( 'comments', 'template' );
    wp_list_comments( array( 'callback' => 'tfuse_comment' ) );
    ?>
</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
    <nav id="comment-nav-below">
        <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tfuse' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tfuse' ) ); ?></div>
    </nav>
    <?php endif; // check for comment navigation ?>

<?php elseif ( comments_open() ) : // If comments are open, but there are no comments ?>

<p class="nocomments"><?php _e('No comments yet.', 'tfuse') ?></p>

<?php endif; ?>

<div id="respond">

    <div class="add-comment" id="addcomments">
        <div class="clear"></div>
        <div class="add-comment-title">
        <h3><?php _e('Leave a Reply', 'tfuse') ?>  <?php cancel_comment_reply_link('(CANCEL REPLY)'); ?></h3>
        </div>

        <div class="comment-form">

            <?php if ( get_option('comment_registration') && !$user_ID ) : //If registration required & not logged in. ?>

            <p><?php _e('You must be', 'tfuse') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in', 'tfuse') ?></a> <?php _e('to post a comment.', 'tfuse') ?></p>

            <?php else : //No registration required ?>

            <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

                <?php if ( $user_ID ) : //If user is logged in ?>

                <p><?php _e('Logged in as', 'tfuse') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="<?php _e('Log out of this account', 'tfuse') ?>"><?php _e('Logout', 'tfuse') ?> &raquo;</a></p>

                <?php else : //If user is not logged in ?>

                <div class="row alignleft">
                    <label for="author"><strong><?php _e('Name', 'tfuse') ?></strong></label>
                    <input type="text" name="author" class="inputtext input_middle required" id="author" value="<?php echo $comment_author; ?>" tabindex="1" />
                </div>

                <div class="space"></div>

                <div class="row alignleft">
                    <label for="email"><strong><?php _e('Email', 'tfuse') ?></strong> (<?php _e('never published', 'tfuse'); ?>)</label>
                    <input type="text" name="email" class="inputtext input_middle required" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" />
                </div>


                <?php endif; // End if logged in ?>

                <!--<p><strong>XHTML:</strong> <?php _e('You can use these tags', 'tfuse'); ?>: <?php echo allowed_tags(); ?></p>-->

                <div class="row">
                    <label for="url"><strong><?php _e('Website', 'tfuse') ?></strong></label>
                    <input type="text" name="url" class="inputtext input_full" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
                </div>

                <div class="row">
                    <label><strong><?php _e('Comment', 'tfuse') ?></strong></label>
                    <textarea name="comment" class="textarea textarea_middle required" id="comment" rows="10" cols="30" tabindex="4"></textarea>
                </div>

                <div class="row">
                    <label>&nbsp;</label>
                    <input name="submit" type="submit" id="submit" class="btn-submit" tabindex="5" value="<?php _e('POSTS COMMENT', 'tfuse') ?>" />
                    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
                </div>

                <?php comment_id_fields(); ?>
                <?php do_action('comment_form', $post->ID); ?>

            </form><!-- /#commentform -->

            <?php endif; // If registration required ?>

        </div>
        <div class="fix"></div>

    </div>

</div><!-- /#respond -->

</div><!-- #comments -->
