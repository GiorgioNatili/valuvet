<?php
/**
 * The template for displaying content in the single.php for doctor template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since HomeQuest 1.0
 */
?>



    <div class="re-full">
        <?php //tfuse_custom_title(); ?>
        <h1><?php meta('practice_is_for'); ?> - <?php the_title(); ?> - <?php meta('suburb'); ?>, <?php meta('practice_state'); ?></h1>

        <?php get_template_part('property', 'sl_content');?>

        <div class="re-description">
            <div class="block_hr">   
                <div class="re-price"><strong><?php if(get_meta('show_asking_price') != '') { echo '$ '.number_format(get_meta('property_value')); } else { echo 'P. O. A'; }?></strong></div>    
            </div>
            <h2><?php print( sprintf( __('%s Overview','tfuse'), TF_SEEK_HELPER::get_option('seek_property_name_singular','Property') ) ); ?>:</h2>
            <?php if(get_meta('practice-confidential') != 1) { ?>
            <ul class="list-top">
                <li><strong>Business Name:</strong> <?php meta('practice_name'); ?></li>
                <li><strong>Address:</strong> <?php meta('business_address'); ?>, <?php meta('suburb'); ?>, <?php meta('practice_state'); ?>, <?php meta('post_code'); ?></li>
            </ul>
            <?php } ?>
            <ul class="list-right">
                <li><strong>Business:</strong> <?php meta('practice_is_for'); ?></li>
                <li><strong>Building:</strong> <?php meta('real_estate_available_for_sale'); ?></li>
                <li><strong>Equipment:</strong> <?php if(get_meta('equipments_on_sale') != '') { echo "Included"; } else { echo "Not Included"; } ?></li>
                <li><strong>Stock:</strong> <?php if(get_meta('stock_on_sale') != '') { echo "Included"; } else { echo "Not Included"; } ?></li>
                <li><strong>ValuVet Valuation:</strong> <?php if(get_meta('valuation_by_valuvet') != '') { echo "Available"; } else { echo "Not Available"; } ?></li>
                <li><strong>ValuVet Report:</strong> <?php if(get_meta('practice_report_by_valuvet') != '') { echo "Available"; } else { echo "Not Available"; } ?></li>
            </ul>
            

            <p><?php the_content(); ?></p>
            <div class="clear"></div>
        </div>
        <!-- Property Details -->
        <?php print( TF_SEEK_HELPER::print_zone('content') ); ?>

        <!-- Property Overal description -->
        <div class="re-overview">
            <?php if(get_meta('the_business') != '') { ?>
                <h3>The Business</h3>
                <p><?php meta('the_business'); ?></p>
            <?php } ?>
            <?php if(get_meta('the_opportunity') != '') { ?>
                <h3>The Opportunity</h3>
                <p><?php meta('the_opportunity'); ?></p>
            <?php } ?>
            <?php if(get_meta('the_location') != '') { ?>
                <h3>The Location</h3>
                <p><?php meta('the_location'); ?></p>
            <?php } ?>
        </div>

        <div class="block_hr re-meta-bot">
            <div class="inner">
                <a href="javascript:history.go(-1)" class="link-back">&lt; <?php _e('Back to Properties Listing', 'tfuse'); ?></a>
                <?php
                        $fav_saved = array();
                        if (!empty($_COOKIE['favorite_posts'])) $fav_saved = explode(',', $_COOKIE['favorite_posts']);
                        $this_saved = in_array($post->ID,$fav_saved);
                ?>
                <?php if ($this_saved) { ?>
                <a href="#" class="tooltip link-saved" rel="<?php echo $post->ID; ?>" title="<?php _e('Remove Offer','tfuse'); ?>"><?php _e('Remove Offer','tfuse'); ?></a>
                <?php } else {?>
                <a href="#" class="link-save tooltip" rel="<?php echo $post->ID; ?>" title="<?php _e('Add to Fav','tfuse'); ?>"><?php _e('Add to Fav','tfuse'); ?></a>
                    <?php } ?>
                <a href="#" class="link-print tooltip" title="<?php _e('Print this Page', 'tfuse'); ?>"><?php _e('Print this Page', 'tfuse'); ?></a>
                <?php
                    $custom_title = tfuse_custom_title($post->ID, true);
                    $subject = tfuse_options('sent_to_friend_subject', $custom_title);
                    $message = tfuse_options('sent_to_friend_message', '');

                    $prop_link = get_permalink();

                    $subject = str_replace('[title]', $custom_title, $subject);
                    $subject = str_replace('[link]', $prop_link, $subject);

                    $message = str_replace('[title]', $custom_title, $message);
                    $message = str_replace('[link]', $prop_link, $message);
                ?>
               <a href="mailto:?subject=<?php echo urlencode($subject);?>&Content-type=text/html&body=<?php echo urlencode($message);?>" target="_blank" class="link-sendemail tooltip" title="<?php _e('Send to a Friend', 'tfuse'); ?>"><?php _e('Send to a Friend', 'tfuse'); ?></a>
            </div>
        </div>

    </div>


