<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

/**
 * Description of INTERFACE
 *
 */
class TF_INTERFACE extends TF_TFUSE {

    public $_the_class_name = 'INTERFACE';

    function __construct() {
        parent::__construct();
    }

    function __init() {
        global $innerdocs;

        add_action('admin_menu', array(&$this, 'admin_item_menu_page'), 20);
        add_action('admin_print_styles', array(&$this, 'admin_add_css'));
        add_action('admin_print_scripts', array(&$this, 'admin_add_js'), 20);
        if (!empty($this->theme->taxonomy)) {
            add_action($this->theme->taxonomy . '_edit_form_fields', array(&$this, 'create_taxonomy_options'), 10, 2);
        }
        add_action('add_meta_boxes', array(&$this, 'create_meta_box'));

        $innerdocs = get_site_transient('themefuse_innerdocs_' . TF_THEME_PREFIX);
        if (!$innerdocs) {
            $response = wp_remote_get('http://themefuse.com/pages/innerdocs/' . TF_THEME_PREFIX . '/');
            $innerdocs = ( is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response) ) ? 'false' : ( ( maybe_unserialize(wp_remote_retrieve_body($response)) == TF_THEME_PREFIX ) ? 'true' : 'false' );
            set_site_transient('themefuse_innerdocs_' . TF_THEME_PREFIX, $innerdocs, 60 * 60 * 48); // store for 48 hours
        }
    }

    public function admin_item_menu_page() {
        if (function_exists('add_object_page')) {
            add_object_page('ThemeFuse', $this->theme->theme_name, 'manage_options', 'themefuse', array(&$this, 'page_framework_options'), TFUSE_ADMIN_IMAGES . '/framework-icon.png');
        } else {
            add_menu_page('ThemeFuse', $this->theme->theme_name, 'manage_options', 'themefuse', array(&$this, 'page_framework_options'), TFUSE_ADMIN_IMAGES . '/framework-icon.png');
        }
        add_submenu_page('themefuse', 'Fuse Framework', 'Fuse Framework', 'manage_options', 'themefuse', array(&$this, 'page_framework_options'));
        if (get_option(TF_THEME_PREFIX . '_disable_news_and_promo') != 1) {
            $this->news = tfuse_newspromo_check();
            $newspromo_title = (!empty($_COOKIE['themefuse-newspromo']) && $_COOKIE['themefuse-newspromo'] == $this->news || (isset($_GET['page']) && $_GET['page'] == 'newspromo') ) ? 'News &amp; Promos' : 'News &amp; Promos <span class="update-plugins"><span class="update-count">!</span></span>';
            add_submenu_page('themefuse', 'News &amp; Promos', $newspromo_title, 'manage_options', 'newspromo', array(&$this, 'theme_newspromo_html_page'));
        }
        if (get_option(TF_THEME_PREFIX . '_disable_support') != 1)
            add_submenu_page('themefuse', 'Support', 'Support', 'manage_options', 'support', array(&$this, 'theme_support_html_page'));
    }

    public function admin_add_css() {
        wp_enqueue_style('thickbox');

        wp_register_style('JQueryUiStyle', TFUSE_ADMIN_CSS . '/jquery-ui-1.8.14.custom.css', false, '1.0.0');
        wp_enqueue_style('JQueryUiStyle');

        $this->include->register_type('framework_css', TFUSE . '/static/css');
        $this->include->css('style', 'framework_css', 'tf_head', '1.01');
        if (!tfuse_options('deactivate_tfuse_style')) {
            $this->include->css('tfuse_style', 'framework_css', 'tf_head');
        }
        $this->include->css('prettyPhoto', 'framework_css', 'tf_head');
        $this->include->css('colorpicker', 'framework_css', 'tf_head');
        $this->include->css('datepicker', 'framework_css', 'tf_head');
    }

    public function admin_add_js() {
        global $blog_id;
        wp_enqueue_script('jquery');
        wp_enqueue_script('suggest');
        wp_enqueue_script('jquery-ui-tabs');
        if (isset($_GET['page']))
            wp_enqueue_script('post');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-effects-transfer');

        $this->include->register_type('framework_js', TFUSE . '/static/javascript');
        $this->include->js('jquery.prettyPhoto', 'framework_js', 'tf_footer');
        $this->include->js('jquery.bt', 'framework_js', 'tf_footer');
        $this->include->js('tfuse-domnoready', 'framework_js', 'tf_footer', 5);
        $this->include->js('tfuse-domready', 'framework_js', 'tf_footer');
        $this->include->js('colorpicker', 'framework_js', 'tf_footer');
        $this->include->js_enq('ajax_admin_save_options_nonce', wp_create_nonce('tfuse_framework_save_options'));
        $this->include->js_enq('ajax_admin_reset_options_nonce', wp_create_nonce('tfuse_framework_reset_options'));
        $this->include->js_enq('template_directory_uri', get_template_directory_uri());
        $this->include->js_enq('blog_id', isset($blog_id) ? $blog_id : 'undefined');
    }

    public function page_header_info() {
        $data = array();
        $out = $this->load->view('framework_header', $data, TRUE);
        echo apply_filters('tfuse_page_header_info', $out);
    }

    public function page_framework_options() {
        $data = array();
        $this->load->model('fwoptions');
        $out = $this->load->view('page_framework_options', $data, TRUE);
        echo apply_filters('tfuse_page_framework_options', $out);
    }

    public function theme_support_html_page() {
        $this->load->view('theme_support_html_page', NULL);
    }

    public function theme_newspromo_html_page() {
        @setcookie('themefuse-newspromo', $this->news, time() + 60 * 60 * 24 * 30 * 3);
        $this->load->view('theme_newspromo_html_page', NULL);
    }

    public function meta_box_row_template($row = array()) {
        global $innerdocs;
        $out = '';

        if (method_exists($this->optigen, $row['type'])) {
            $img = ( 'upload' == $row['type'] && !empty($row['value']) ) ? ' rel="' . $row['value'] . '"' : '';

            $divider = ( array_key_exists('divider', $row) && $row['divider'] === TRUE ) ? ' divider' : '';

            if ($innerdocs != 'true')
                $title = $row['name'];
            else
                $title = ( empty($row['help']) ) ? $row['name'] : '<a rel="prettyPhoto[iframes]' . $row['id'] . '" title="Click for help" href="http://themefuse.com/pages/innerdocs/' . TF_THEME_PREFIX . '/help.php?page=' . $row['help'] . '&iframe=true&width=700&height=100%">' . $row['name'] . '</a>';

            $out .= '<div class="option option-' . $row['type'] . ' ' . $row['id'] . '">';
            $out .= '<div class="option-inner">';
            $out .= '<label class="titledesc">' . $title . '</label>';
            $out .= '<div class="formcontainer">';
            $out .= $this->optigen->$row['type']($row);
            $out .= '</div>';
            if ('upload' == $row['type'] and (empty($row['media']) or $row['media'] == 'image'))
                $out .= '<div class="uploaded_thumb"' . $img . '></div>';
            if (!empty($row['desc']))
                $out .= '<div class="desc">' . $row['desc'] . '</div>';
            $out .= '<div class="tfclear"></div>';
            $out .= '</div></div>';
            $out .= '<div class="tfclear' . $divider . '"></div>' . "\n";
        }

        // Filtru pentru schimbarea unei optinui specifice dupa id, tip sau toate
        if (has_filter("tfuse_meta_box_row_template_{$row['id']}")) {
            return apply_filters("tfuse_meta_box_row_template_{$row['id']}", $out, $row);
        } else if (has_filter("tfuse_meta_box_row_template_{$row['type']}")) {
            return apply_filters("tfuse_meta_box_row_template_{$row['type']}", $out, $row);
        }
        return apply_filters('tfuse_meta_box_row_template', $out, $row);
    }

    public function meta_box_row_custom($row = array()) {
        global $innerdocs;
        $out = '';

        $divider = ( array_key_exists('divider', $row) && $row['divider'] === TRUE ) ? ' divider' : '';

        if ($innerdocs != 'true')
            $title = $row['name'];
        else
            $title = ( empty($row['help']) ) ? $row['name'] : '<a rel="prettyPhoto[iframes]' . $row['id'] . '" title="Click for help" href="http://themefuse.com/pages/innerdocs/' . TF_THEME_PREFIX . '/help.php?page=' . $row['help'] . '&iframe=true&width=700&height=100%">' . $row['name'] . '</a>';

        $out .= '<div class="option option-' . $row['type'] . ' ' . $row['id'] . '">';
        $out .= '<div class="option-inner">';
        $out .= '<label class="titledesc">' . $title . '</label>';
        $out .= '<div class="formcontainer">';
        $out .= $row['contents'];
        $out .= '</div>';
        $out .= '<div class="desc">' . $row['desc'] . '</div>';
        $out .= '<div class="tfclear"></div>';
        $out .= '</div></div>';
        $out .= '<div class="tfclear' . $divider . '"></div>' . "\n";

        // Filtru pentru schimbarea unei optinui specifice dupa id, tip sau toate
        if (has_filter("tfuse_meta_box_row_custom_{$row['id']}")) {
            return apply_filters("tfuse_meta_box_row_custom_{$row['id']}", $out, $row);
        } else if (has_filter("tfuse_meta_box_row_custom_{$row['type']}")) {
            return apply_filters("tfuse_meta_box_row_custom_{$row['type']}", $out, $row);
        }
        return apply_filters('tfuse_meta_box_row_custom', $out, $row);
    }

    public function taxonomy_row_template($row = array()) {
        global $innerdocs;
        $out = '';

        if (method_exists($this->optigen, $row['type'])) {

            if ($innerdocs != 'true')
                $title = $row['name'];
            else
                $title = ( empty($row['help']) ) ? $row['name'] : '<a rel="prettyPhoto[iframes]' . $row['id'] . '" title="Click for help" href="http://themefuse.com/pages/innerdocs/' . TF_THEME_PREFIX . '/help.php?page=' . $row['help'] . '&iframe=true&width=700&height=100%">' . $row['name'] . '</a>';

            $out .= '<tr class="form-field tfuse-tax-form-field tf_' . $row['type'] . '">';
            $out .= '<th scope="row" valign="top"><label for="' . $row['id'] . '">' . $title . '</label></th>';
            $out .= '<td>';
            if ('upload' == $row['type']) {
                $img = !empty($row['value']) ? ' rel="' . $row['value'] . '"' : '';

                $out .= '<table class="tfuse-tax-upload-form"><tr><td>';
                $out .= $this->optigen->{$row['type']}($row);
                $out .= '</td><td width="65px">';
                $out .= '<div class="uploaded_thumb"' . $img . '></div>';
                $out .= '<td></tr></table>';
            } else {
                $out .= $this->optigen->{$row['type']}($row);
            }
            if ('checkbox' != $row['type'] && 'radio' != $row['type'] && 'upload' != $row['type'] && 'multi' != $row['type'] && 'textarray' != $row['type'])
                $out .= '<br />';
            $out .= '<span class="description">' . $row['desc'] . '</span></td>';
            $out .= '</tr>';
        }

        // Filtru pentru schimbarea unei optinui specifice dupa id, tip sau toate
        if (has_filter("tfuse_taxonomy_row_template_{$row['id']}")) {
            return apply_filters("tfuse_taxonomy_row_template_{$row['id']}", $out, $row);
        } else if (has_filter("tfuse_taxonomy_row_template_{$row['type']}")) {
            return apply_filters("tfuse_taxonomy_row_template_{$row['type']}", $out, $row);
        }

        return apply_filters('tfuse_taxonomy_row_template', $out, $row);
    }

    public function cf_row_template($row = array(),$options_row=array())
    {
        global $innerdocs;
        $out = '';
        $out .= '<li class=" form-field tfuse-tax-form-field tf_' . $row['type'] . '">';
        $has_options=false;
        $option_type = 'Radio Option ';
        foreach($row as $key => $option) {
            if(!is_array($option) ) continue;
            if($option['type'] == 'button') {
                $out .= '<div class="tfuse_button_subrow">' . $this->optigen->$option['type']($option) . '</div>';
            }elseif($option['type'] == 'raw'){
                $out .= '<div class="tfuse_subrow '.$option['id'].'">' . $this->optigen->$option['type']($option) . '</div>';
            }
            else {
            if ($option['id'] == 'tf_cf_select[]'){
                          if($option['options'][$option['value']] == 'SelectBox' ){
                              $has_options = true;
                              $option_type = 'Select Option';
                          } elseif( $option['options'][$option['value']] == 'Radiobox'){
                              $has_options = true;
                          }
                        }
                $invisible = (isset($option['properties']['class']) && stripos($option['properties']['class'],'invisible')!==false)?' invisible':'';
                $out .= '<div class="tfuse_subrow '.$invisible.'">' . $this->optigen->$option['type']($option) . '</div>';
            }
        }
        if($row['type'] != 'tfuse_cf_label'){
            $style = ($has_options) ? "style='display:block;'":"";
        $out .= '<div class="tfclear divider"></div><ul '.$style.' class="ui-sortable sortable_options">';
                        foreach($options_row as $option_row){

                        $out .= '<li class=" form-field tfuse-tax-form-field "><div class="corner_sdb"></div>';
                            foreach($option_row as $option){

                                $out .= '<div class="tfuse_subrow"><span>' . $option_type . '</span>' . $this->optigen->$option['type']($option) . '<div class="tf_cf_delete_option"></div></div>';

                            }
                $out .= '<div class="tfclear divider"></div></li>';
                }
                $out .= '<div class="add_option_row"><div class="corner_sdb_add"></div><div class="cf_add_option"></div></div>';
                $out .= '<div class="tfclear divider"></div></ul>'; }
        $out .= '</li>';
        if(has_filter("tfuse_contactform_row_template_{$row['type']}")) {
            return apply_filters("tfuse_contactform_row_template_{$row['type']}", $out, $row);
        }

        return apply_filters('tfuse_contacform_row_template', $out, $row);
    }

    public function create_taxonomy_options($tag, $taxonomy) {
        $taxonomy_options = (array) get_option(TF_THEME_PREFIX . '_tfuse_taxonomy_options');
        $taxonomy_options = array_key_exists($tag->term_id, $taxonomy_options) ? $taxonomy_options[$tag->term_id] : array();
        $options = $this->get->options($taxonomy);
        foreach ($options as $row) {
            if (array_key_exists($row['id'], $taxonomy_options) && !is_array($taxonomy_options[$row['id']]))
                $row['value'] = stripslashes($taxonomy_options[$row['id']]);
            echo $this->taxonomy_row_template($row);
        }
    }

    public function get_meta_box_options($post_type) {
        global $post;
        $meta_boxes = array();
        $k = 0;
        $tfuse_post_options = !$post ? array() : ((array) get_post_meta($post->ID, TF_THEME_PREFIX . '_tfuse_post_options', true));
        $options = $this->get->options($post_type);
        foreach ($options as $box) {
            if ($box['type'] == 'metabox') {
                $k++;
                $meta_boxes[$k]['meta_box'] = $box;
                $meta_boxes[$k]['rows'] = '';
            } else {
                if (array_key_exists($box['id'], $tfuse_post_options))
                    $box['value'] = $tfuse_post_options[$box['id']];
                $meta_boxes[$k]['rows'] .= $this->meta_box_row_template($box);
            }
        }

        return $meta_boxes;
    }

    /**
     * Adds a box to the main column on the Post and Page edit screens.
     *
     * @param string $post_type
     *
     * @since 2.0
     */
    public function create_meta_box($post_type) {
        $meta_boxes = $this->get_meta_box_options($post_type);
        $normal_boxes = array();
        foreach ($meta_boxes as $box) {
            if ($box['meta_box']['context'] == 'side')
                add_meta_box($box['meta_box']['id'], $this->theme->theme_name . ' - ' . $box['meta_box']['name'], array(&$this, 'inner_custom_box'), $post_type, $box['meta_box']['context'], $box['meta_box']['priority'], $box['rows']);
            elseif ($box['meta_box']['context'] == 'normal') {
                $normal_boxes[] = $box;
            }
        }
        if (count($normal_boxes) > 0) {
            $tabs_header = '<ul>';
            foreach ($normal_boxes as $tab) {
                $tabs_header .= '<li><a href="#tfusetab-' . $tab['meta_box']['id'] . '">' . $tab['meta_box']['name'] . '</a></li>';
            }
            $tabs_header .= '</ul>';
            $content = '';
            foreach ($normal_boxes as $box) {
                $content.='<div id="tfusetab-' . $box['meta_box']['id'] . '">' . $box['rows'] . '</div>';
            }
            $content = '<div class="tf_load_meta_tabs">&nbsp;</div><div class="tf_meta_tabs">' . $tabs_header . $content . '</div>';
            add_meta_box(TF_THEME_PREFIX . '_framework_options_metabox', $this->theme->theme_name . ' - Framework options', array(&$this, 'inner_custom_box'), $post_type, $box['meta_box']['context'], 'high', $content);
        }
    }

    /**
     * Prints the box content.
     *
     * @since 2.0
     */
    public function inner_custom_box($post, $args) {
        static $i = 0;
        /* Filtru pentru a putea modifica in totalitate continutul pentru metabox cu ID concret */
        echo apply_filters("{$args['id']}_custom_box_content", $args['args'], $post, $args);
        //TODO - varianta mai buna de static i
        if ($i == 0) {
            wp_nonce_field("_tfuse_meta_box", "_tfuse_noncename");
            $i++;
        }
    }

}
