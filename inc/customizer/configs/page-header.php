<?php

class Customify_Page_Header
{
    public $name = null;
    public $description = null;
    static $is_transparent = null;
    static $_instance = null;
    static $_settings = null;

    function __construct()
    {
        add_filter('customify/customizer/config', array( $this , 'config') );
        if (!is_admin()) {
            add_action('customify_is_post_title_display', array($this, 'display_page_title'), 35);
            add_action('customify/site-start', array($this, 'render'), 35);
            add_action('wp', array($this, 'wp'), 35);
        }
        self::$_instance = $this;
    }

    function wp (){
        $this->get_settings();
    }

    static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function config( $configs = array() ){
        $section = 'page_header';
        $name = 'page_header';
        $choices = array(
            'default' => __('Default', 'customify'),
            'cover' => __('Cover', 'customify'),
            'titlebar' => __('Titlebar', 'customify'),
            'none' => __('Hide', 'customify'),
        );
        $render_cb_el = array( $this, 'render' );

        $display_fields = array(
            array(
                'name' => "page",
                'type' => 'select',
                'label' => __( 'Display on single page', 'customify' ),
                'description' => __( 'Apply when viewing single page', 'customify' ),
                'default' => 'titlebar',
                'choices' => $choices
            ),
            array(
                'name' => "post",
                'type' => 'select',
                'label' => __( 'Display on single post', 'customify' ),
                'description' => __( 'Apply when viewing single post', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),

            array(
                'name' => "category",
                'type' => 'select',
                'label' => __( 'Display on categories', 'customify' ),
                'description' => __( 'Apply when viewing a category page', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),
            array(
                'name' => "index",
                'type' => 'select',
                'label' => __( 'Display on index', 'customify' ),
                'description' => __( 'Apply when your homepage displays as latest posts', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),
            array(
                'name' => "search",
                'type' => 'select',
                'label' => __( 'Display on search', 'customify' ),
                'description' => __( 'Apply when viewing search results page', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),
            array(
                'name' => "archive",
                'type' => 'select',
                'label' => __( 'Display on archive', 'customify' ),
                'description' => __( 'Apply when viewing archive pages, e.g. Tag, Author, Date, Custom Post Type or Custom Taxonomy', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),
            array(
                'name' => "singular",
                'type' => 'select',
                'label' => __( 'Display on singular', 'customify' ),
                'description' => __( 'Apply when viewing single custom post type', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),
            array(
                'name' => "page_404",
                'type' => 'select',
                'label' => __( 'Display on 404 page', 'customify' ),
                'description' => __( 'Apply when the page not found', 'customify' ),
                'default' => '',
                'choices' => $choices
            ),

        );

        $title_fields = array(
            array(
                'name' => "index",
                'type' => 'text',
                'label' => __( 'Title for index page', 'customify' ),
                'description' => __( 'Apply when your homepage displays as latest posts', 'customify' ),
                'default' => '',
            ),
            array(
                'name' => "post",
                'type' => 'text',
                'label' => __( 'Title for single post', 'customify' ),
                'description' => __( 'Apply when viewing single post', 'customify' ),
                'default' => '',
            ),
            array(
                'name' => "page_404",
                'type' => 'text',
                'label' => __( 'Title for 404 page', 'customify' ),
                'description' => __( 'Apply when the page not found', 'customify' ),
                'default' => '',
            ),
        );

        $tagline_fields = array(
            array(
                'name' => "index",
                'type' => 'textarea',
                'label' => __( 'Tagline for index page', 'customify' ),
                'description' => __( 'Apply when your homepage displays as latest posts', 'customify' ),
                'default' => '',
            ),
            array(
                'name' => "post",
                'type' => 'textarea',
                'label' => __( 'Tagline for single post', 'customify' ),
                'description' => __( 'Apply when viewing single post', 'customify' ),
                'default' => '',
            ),
            array(
                'name' => "page_404",
                'type' => 'textarea',
                'label' => __( 'Tagline for 404 page', 'customify' ),
                'description' => __( 'Apply when the page not found', 'customify' ),
                'default' => '',
            ),
        );

        if ( Customify()->is_woocommerce_active() ) {
            $display_fields[] = array(
                'name' => "product",
                'type' => 'select',
                'label' => __( 'Display on product page', 'customify' ),
                'description' => __( 'Apply when viewing single product', 'customify' ),
                'default' => '',
                'choices' => $choices
            );
            $display_fields[] = array(
                'name' => "product_cat",
                'type' => 'select',
                'label' => __( 'Display on product category', 'customify' ),
                'description' => __( 'Apply when viewing product category', 'customify' ),
                'default' => '',
                'choices' => $choices
            );
            $display_fields[] = array(
                'name' => "product_tag",
                'type' => 'select',
                'label' => __( 'Display on product tag', 'customify' ),
                'description' => __( 'Apply when viewing product tag', 'customify' ),
                'default' => '',
                'choices' => $choices
            );

            $title_fields[] = array(
                'name' => "product",
                'type' => 'text',
                'label' => __( 'Title for product', 'customify' ),
                'description' => __( 'Apply when viewing single product', 'customify' ),
                'default' => '',
            );

            $tagline_fields[] = array(
                'name' => "product",
                'type' => 'textarea',
                'label' => __( 'Tagline fo product', 'customify' ),
                'description' => __( 'Apply when viewing single product', 'customify' ),
                'default' => '',
            );
        }

        $config = array(
            array(
                'name'  => $section,
                'type'  => 'section',
                'panel' => 'layout_panel',
                'title' => __('Page Header', 'customify'),
            ),

            array(
                'name' => "{$name}_display_h",
                'type' => 'heading',
                'section' =>  $section,
                'title' => __( 'Display Settings', 'customify' )
            ),

            array(
                'name' => "{$name}_display",
                'type' => 'modal',
                'section' =>  $section,
                'label' => __( 'Display', 'customify' ),
                'description' => __( 'Settings display for special pages.', 'customify' ),
                'default' => array(
                        'display' => array(
                            'page' => 'titlebar'
                        )
                ),
                'fields' => array(
                    'tabs' => array(
                        'display' => __( 'Display', 'customify' ),
                        'advanced' => __( 'Advanced', 'customify' ),
                    ),
                    'display_fields' => $display_fields,
                    'advanced_fields' => array(
                        array(
                            'name' => "post_bg",
                            'type' => 'select',
                            'label' => __( 'Single Post Cover Background', 'customify' ),
                            'description' => __( 'Apply when viewing single post and page header display as cover.', 'customify' ),
                            'default' => '',
                            'choices' => array(
                                'default' => __( 'Default', 'customify' ),
                                'blog_page' => __( 'Use featured image form bog page', 'customify' ),
                                'featured' => __( 'Use featured image of current post', 'customify' ),
                            )
                        ),
                        array(
                            'name' => "post_title_tagline",
                            'type' => 'select',
                            'label' => __( 'Single Post Title & Tagline', 'customify' ),
                            'default' => '',
                            'choices' => array(
                                'default' => __( 'Default', 'customify' ),
                                'blog_page' => __( 'Use title & tagline form bog page', 'customify' ),
                                'current' => __( 'Use title & tagline of current post', 'customify' ),
                            )
                        ),
                    )
                ),
                //'selector' => '.page-header--item',
                //'render_callback' => $render_cb_el,
            ),

            array(
                'name' => "{$name}_title_tagline",
                'type' => 'modal',
                'section' =>  $section,
                'label' => __( 'Title & Tagline', 'customify' ),
                'description' => __( 'Title & tagline for special pages.', 'customify' ),
                'default' => array(),
                'fields' => array(
                    'tabs' => array(
                        'titles' => __( 'Title', 'customify' ),
                        'taglines' => __( 'Tagline', 'customify' ),
                    ),
                    'titles_fields' => $title_fields,
                    'taglines_fields' => $tagline_fields,
                ),
                'selector' => '#page-titlebar, #page-cover',
                'render_callback' => $render_cb_el,
            ),

        );

        $configs = array_merge( $configs, $config );
        $configs = array_merge( $configs, $this->config_cover() );
        $configs = array_merge( $configs, $this->config_titlebar() );
        return $configs;
    }

    function config_titlebar(){

        $section = 'page_header';
        $render_cb_el = array( $this, 'render' );
        $selector = '#page-titlebar';
        $name = 'titlebar';
        $config = array(

            array(
                'name' => "{$name}_styling_h_tb",
                'type' => 'heading',
                'section' =>  'page_header',
                'title' => __( 'Titlebar Settings', 'customify' )
            ),

            array(
                'name' => $name.'_show_tagline',
                'type' => 'checkbox',
                'section' => $section,
                'label'  => __( 'Show Tagline', 'customify' ),
                'description'  => __( 'Tagline is pull from post excerpt, archive description.', 'customify' ),
                'checkbox_label'  => __( 'Enable', 'customify' ),
                'default' => 1,
                'selector' => "{$selector}",
                'render_callback' => $render_cb_el,
            ),

            array(
                'name' => $name.'_typo',
                'type' => 'typography',
                'section' => $section,
                'title'  => __( 'Title Typography', 'customify' ),
                'selector' => "{$selector} .titlebar-title",
                'css_format' => 'typography',
            ),

            array(
                'name' => $name.'_typo_desc',
                'type' => 'typography',
                'section' => $section,
                'title'  => __( 'Tagline Typography', 'customify' ),
                'selector' => "{$selector} .titlebar-tagline",
                'css_format' => 'typography',
            ),

            array(
                'name' => $name.'_styling',
                'type' => 'styling',
                'section' => $section,
                'title'  => __( 'Titlebar Styling', 'customify' ),
                'selector' => array(
                    'normal' => "{$selector}",
                    'normal_text_color' => "{$selector} .titlebar-title, {$selector} .titlebar-tagline",
                    'normal_padding' => "{$selector}",
                ),
                'css_format' => 'styling', // styling
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'bg_image' => false,
                        'bg_cover' => false,
                        'bg_repeat' => false,
                        'margin' => false,
                    ),
                    'hover_fields' => false
                )
            ),

            array(
                'name' => $name.'title_styling',
                'type' => 'styling',
                'section' => $section,
                'title'  => __( 'Titlebar Title Styling', 'customify' ),
                'selector' => array(
                    'normal' => "{$selector} .titlebar-title",
                ),
                'css_format' => 'styling',
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false,
                        'bg_image' => false,
                        'bg_cover' => false,
                        'bg_repeat' => false,
                        'box_shadow' => false,
                    ),
                    'hover_fields' => false
                )
            ),

            array(
                'name' => $name.'tagline_styling',
                'type' => 'styling',
                'section' => $section,
                'title'  => __( 'Titlebar Tagline Styling', 'customify' ),
                'selector' => array(
                    'normal' => "{$selector} .titlebar-tagline",
                ),
                'css_format' => 'styling',
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false,
                        'bg_image' => false,
                        'bg_cover' => false,
                        'bg_repeat' => false,
                        'box_shadow' => false,
                    ),
                    'hover_fields' => false
                )
            ),

            array(
                'name' => "{$name}_align",
                'type' => 'text_align_no_justify',
                'section' => $section,
                'device_settings' => true,
                'selector' => "$selector",
                'css_format' => 'text-align: {{value}};',
                'title'   => __( 'Text Align', 'customify' ),
            ),

        );

        $config = apply_filters( 'customify/titlebar/config', $config, $this );
        return $config;
    }

    function config_cover()
    {

        $section = 'page_header';
        $render_cb_el = array($this, 'render');
        $selector = '#page-cover';
        $name = 'header_cover';
        $config = array(

            array(
                'name'     => "{$name}_settings_h",
                'type'     => 'heading',
                'section'  => $section,
                'title'    => __('Cover Settings', 'customify')
            ),

            array(
                'name' => $name.'_show_tagline',
                'type' => 'checkbox',
                'section' => $section,
                'label'  => __( 'Show Tagline', 'customify' ),
                'description'  => __( 'Tagline is pull from post excerpt, archive description.', 'customify' ),
                'checkbox_label'  => __( 'Enable', 'customify' ),
                'default' => 1,
                'selector' => "{$selector}",
                'render_callback' => $render_cb_el,
            ),

            array(
                'name'       => $name . '_bg',
                'type'       => 'modal',
                'section'    => $section,
                'title'      => __('Cover Background', 'customify'),
                'selector'   => $selector,
                'css_format' => 'styling', // styling
                'default' => array(
                    'normal' => array(
                         'bg_image' => array(
                             'id' => '',
                             'url' => get_template_directory_uri().'/assets/images/default-cover.jpg',
                         )
                    )
                ),
                'fields'     => array(
                    'tabs'          => array(
                        'normal' => '_'
                    ),
                    'normal_fields' => array(
                        array(
                            'name'       => 'bg_image',
                            'type'       => 'image',
                            'label'      => __('Background Image', 'customify'),
                            'selector'   => "$selector",
                            'css_format' => 'background-image: url("{{value}}");'
                        ),
                        array(
                            'name'       => 'bg_cover',
                            'type'       => 'select',
                            'choices'    => array(
                                ''        => __('Default', 'customify'),
                                'auto'    => __('Auto', 'customify'),
                                'cover'   => __('Cover', 'customify'),
                                'contain' => __('Contain', 'customify'),
                            ),
                            'required'   => array('bg_image', 'not_empty', ''),
                            'label'      => __('Size', 'customify'),
                            'class'      => 'field-half-left',
                            'selector'   => "$selector",
                            'css_format' => '-webkit-background-size: {{value}}; -moz-background-size: {{value}}; -o-background-size: {{value}}; background-size: {{value}};'
                        ),
                        array(
                            'name'       => 'bg_position',
                            'type'       => 'select',
                            'label'      => __('Position', 'customify'),
                            'required'   => array('bg_image', 'not_empty', ''),
                            'class'      => 'field-half-right',
                            'choices'    => array(
                                ''              => __('Default', 'customify'),
                                'center'        => __('Center', 'customify'),
                                'top left'      => __('Top Left', 'customify'),
                                'top right'     => __('Top Right', 'customify'),
                                'top center'    => __('Top Center', 'customify'),
                                'bottom left'   => __('Bottom Left', 'customify'),
                                'bottom center' => __('Bottom Center', 'customify'),
                                'bottom right'  => __('Bottom Right', 'customify'),
                            ),
                            'selector'   => "$selector",
                            'css_format' => 'background-position: {{value}};'
                        ),
                        array(
                            'name'       => 'bg_repeat',
                            'type'       => 'select',
                            'label'      => __('Repeat', 'customify'),
                            'class'      => 'field-half-left',
                            'required'   => array(
                                array('bg_image', 'not_empty', ''),
                            ),
                            'choices'    => array(
                                'repeat'    => __('Default', 'customify'),
                                'no-repeat' => __('No repeat', 'customify'),
                                'repeat-x'  => __('Repeat horizontal', 'customify'),
                                'repeat-y'  => __('Repeat vertical', 'customify'),
                            ),
                            'selector'   => "$selector",
                            'css_format' => 'background-repeat: {{value}};'
                        ),

                        array(
                            'name'       => 'bg_attachment',
                            'type'       => 'select',
                            'label'      => __('Attachment', 'customify'),
                            'class'      => 'field-half-right',
                            'required'   => array(
                                array('bg_image', 'not_empty', '')
                            ),
                            'choices'    => array(
                                ''       => __('Default', 'customify'),
                                'scroll' => __('Scroll', 'customify'),
                                'fixed'  => __('Fixed', 'customify')
                            ),
                            'selector'   => "$selector",
                            'css_format' => 'background-attachment: {{value}};'
                        ),

                        array(
                            'name'            => "overlay",
                            'type'            => 'color',
                            'section'         => $section,
                            'class'           => 'customify--clear',
                            'device_settings' => false,
                            'selector'        => "$selector:before",
                            'label'           => __('Cover Overlay', 'customify'),
                            'css_format'      => 'background-color: {{value}};',
                        ),

                    ),
                    'hover_fields'  => false
                )
            ),

            array(
                'name'       => $name . '_title_styling',
                'type'       => 'styling',
                'section'    => $section,
                'title'      => __('Cover Title Styling', 'customify'),
                'selector'   => array(
                    'normal'            => "{$selector} .page-cover-title",
                    'normal_link_color' => "{$selector} a",
                    'hover_link_color'  => "{$selector} a:hover",
                ),
                'css_format' => 'styling', // styling
                'fields'     => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'bg_image'   => false,
                        'bg_cover'   => false,
                        'bg_repeat'  => false,
                        'box_shadow' => false,
                    ),
                    'hover_fields'  => false
                )
            ),

            array(
                'name'       => $name . '_tagline_styling',
                'type'       => 'styling',
                'section'    => $section,
                'title'      => __('Cover Tagline Styling', 'customify'),
                'selector'   => array(
                    'normal'            => "{$selector} .page-cover-tagline",
                    'normal_link_color' => "{$selector} a",
                    'hover_link_color'  => "{$selector} a:hover",
                ),
                'css_format' => 'styling', // styling
                'fields'     => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'bg_image'   => false,
                        'bg_cover'   => false,
                        'bg_repeat'  => false,
                        'box_shadow' => false,
                    ),
                    'hover_fields'  => false
                )
            ),

            array(
                'name'            => "{$name}_title_typo",
                'type'            => 'typography',
                'css_format'      => 'typography',
                'section'         => $section,
                'selector'        => "{$selector} .page-cover-title",
                'render_callback' => $render_cb_el,
                'title'           => __('Cover Title Typography', 'customify')
            ),

            array(
                'name'            => "{$name}_tagline_typo",
                'type'            => 'typography',
                'css_format'      => 'typography',
                'section'         => $section,
                'selector'        => "{$selector} .page-cover-tagline p",
                'render_callback' => $render_cb_el,
                'title'           => __('Cover Tagline Typography', 'customify')
            ),

            array(
                'name'            => "{$name}_height",
                'type'            => 'slider',
                'section'         => $section,
                'device_settings' => true,
                'render_callback' => $render_cb_el,
                'title'           => __('Cover Height', 'customify'),
                'selector'        => "{$selector} .page-cover-inner",
                'css_format'      => 'min-height: {{value}};',
                'default'         => array(
                    'desktop' => 350,
                    'tablet'  => 350,
                    'mobile'  => 350,
                ),
            ),

            array(
                'name'            => "{$name}_padding_top",
                'type'            => 'slider',
                'section'         => $section,
                'device_settings' => true,
                'render_callback' => $render_cb_el,
                'title'           => __('Cover Margin Top', 'customify'),
                'selector'        => "{$selector}",
                'css_format'      => 'padding-top: {{value}};',
            ),

            array(
                'name'            => "{$name}_align",
                'type'            => 'text_align_no_justify',
                'section'         => $section,
                'device_settings' => true,
                'selector'        => "$selector",
                'css_format'      => 'text-align: {{value}};',
                'title'           => __('Cover Text Align', 'customify'),
            ),
        );
        $config = apply_filters( 'customify/cover/config', $config, $this );
        return $config;
    }

    function get_settings()
    {

        if ( ! is_null(self::$_settings)) {
            return self::$_settings;
        }

        $args = array(
            '_page'      => 'index',
            'display'    => 'default',
            'title'      => '',
            'tagline'    => '',
            'image'      => '',
            'title_tag'  => 'h1',
            'force_display_single_title'  => '', //show || or hide
            'show_title' => false, // force show post title
            'shortcode'  => false, // force show post title
            'cover_tagline'  => 1, // Display tagline in cover
            'titlebar_tagline'  => 1, // Display tagline in titlbar
        );
        $name = 'page_header';

        $display = Customify()->get_setting_tab($name.'_display', 'display' );
        $advanced = Customify()->get_setting_tab($name.'_display', 'advanced' );

        $titles = Customify()->get_setting_tab($name.'_title_tagline', 'titles' );
        $taglines = Customify()->get_setting_tab($name.'_title_tagline', 'taglines' );

        $args['cover_tagline'] = Customify()->get_setting( 'header_cover_show_tagline' );
        $args['titlebar_tagline'] = Customify()->get_setting( 'titlebar_show_tagline' );

        $display = wp_parse_args( $display, array(
            'index' => '',
            'category' => '',
            'search' => '',
            'archive' => '',
            'page' => '',
            'post' => '',
            'singular' => '',
            'product' => '',
            'product_cat' => '',
            'product_tag' => '',
            'page_404' => '',
        ) );

        $advanced = wp_parse_args( $advanced, array(
            'post_bg' => '',
            'post_title_tagline' => '',
        ) );

        $titles = wp_parse_args( $titles, array(
            'index' => '',
            'post' => '',
            'product' => '',
            'page_404' => '',
        ) );

        $taglines = wp_parse_args( $taglines, array(
            'index' => '',
            'post' => '',
            'product' => '',
            'page_404' => '',
        ) );

        $post_thumbnail_id = false;

        $post_id = 0;
        if ( is_front_page() && is_home() ) { // index page
            // Default homepage
            $args['display'] = $display['index'];
            $args['title']  = $titles['index'];
            $args['tagline'] = $taglines['index'];
            $args['_page'] = 'index';
        } elseif ( is_front_page() ) {
            // static homepage
            $args['display'] = $display['page'];
            $post_id = get_the_ID();
            $args['_page'] = 'page';
        } elseif ( is_home() ) {
            // blog page
            $args['display'] = $display['page'];
            $post_id = get_option( 'page_for_posts' );
            $args['_page'] = 'page';
        } elseif ( is_category() ) {
            //category
            $args['display'] = $display['category'];
            $args['title'] = get_the_archive_title();
            $args['tagline'] = get_the_archive_description();
            $args['_page'] = 'category';
        } elseif ( is_page()) {
            // single page
            $args['display'] = $display['page'];
            $post_id = get_the_ID();
            $args['_page'] = 'page';
        } elseif ( is_single() ) {
            // single post
            $args['display'] = $display['post'];
            $args['title_tag'] = 'h2';

            // Setup single post bg for cover
            if ( $advanced['post_bg'] == 'blog_page' ) {
                $post_id = get_option( 'page_for_posts' );
                $post_thumbnail_id = get_post_thumbnail_id($post_id);
            } elseif ( $advanced['post_bg'] == 'featured' ) {
                $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
            } else {
                $post_id = get_option( 'page_for_posts' );
                if ( $post_id ) {
                    $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                }
            }

            if ( $advanced['post_title_tagline'] == 'blog_page' ) {
                $post_id = get_option( 'page_for_posts' );
                $args['force_display_single_title'] = 'show';
            } elseif ( $advanced['post_title_tagline'] == 'current' ) {
                $post_id = get_the_ID();
                $args['force_display_single_title'] = 'hide';
                $args['title_tag'] = 'h1';
            } else {
                $post_id = get_option( 'page_for_posts' );
                if ( ! $post_id ) {
                    $args['force_display_single_title'] = 'show';
                    if ( $titles['post'] || $taglines['post'] ) {
                        $args['title']  = $titles['post'];
                        $args['tagline'] = $taglines['post'];
                    }
                }
            }

            $args['_page'] = 'post';
        } elseif ( is_singular() ) {
            // single custom post type
            $args['display'] = $display['singular'];
            $post_id = get_the_ID();
            $args['_page'] = 'singular';
        } elseif ( is_404() ){
            // page not found
            $args['display'] = $display['page_404'];
            $args['_page'] = '404';
            $args['title']  = $titles['page_404'];
            $args['tagline'] = $taglines['page_404'];
            if ( ! $args['title'] ) {
                $args['title'] = __( "Oops! That page can't be found.", 'customify' );
            }
        } elseif ( is_search() ){
            // Search result
            $args['display'] = $display['search'];
            $args['title'] = sprintf( // WPCS: XSS ok.
                /* translators: 1: Search query name */
                __('Search Results for: %s', 'customify'),
                '<span>' . get_search_query() . '</span>'
            );
            $args['tagline'] = '';
            $args['_page'] = 'search';
        } elseif ( is_archive() ) {
            $args['display'] = $display['archive'];
            $args['title'] = get_the_archive_title();
            $args['tagline'] = get_the_archive_description();
            $args['_page'] = 'archive';
        }

        // WooCommerce Settings
        if ( Customify()->is_woocommerce_active() ) {
            if ( is_product() ) {
                $post_id = 0;
                $args['display'] = $display['product'];
                $args['title'] = $titles['product'];
                $args['tagline'] =  $taglines['product'];
                $args['_page'] = 'product';
            } elseif ( is_product_category() ) {
                $post_id = 0;
                $args['display'] = $display['product_cat'];
                $args['title'] = get_the_archive_title();
                $args['tagline'] = get_the_archive_description();
                $args['_page'] = 'product_cat';
            } elseif( is_product_tag() ) {
                $post_id = 0;
                $args['display'] = $display['product_tag'];
                $args['title'] = get_the_archive_title();
                $args['tagline'] = get_the_archive_description();
                $args['_page'] = 'product_tag';
            } elseif( is_shop() ) {
                $args['display'] = $display['page'];
                $post_id = wc_get_page_id('shop');
                $args['_page'] = 'shop';
            }
        }

        if ( $post_id ) {
            $args['title'] = get_the_title($post_id);
            $args['tagline'] = get_the_excerpt($post_id);

            if( ! $post_thumbnail_id ) {
                $post_thumbnail_id = get_post_thumbnail_id($post_id);
            }

            if ( ! $args['image'] && $post_thumbnail_id ) {
                $_i = Customify()->get_media($post_thumbnail_id);
                if ($_i) {
                    $args['image'] = $_i;
                }
            }
        }

        if ( Customify()->is_using_post() ) {
            $post_id = Customify()->get_current_post_id();

            // if Disable page title
            $disable = get_post_meta( $post_id, '_customify_disable_page_title', true );
            if ( $disable ) {
                $args['force_display_single_title'] = 'hide';
            }

            // If has custom field custom title
            $post_display = get_post_meta( $post_id, '_customify_page_header_display', true );
            if ( $post_display && $post_display != 'default' ) {
                $args['display'] = $post_display;
            }

            // If has custom field custom title
            $title = get_post_meta( $post_id, '_customify_page_header_title', true);
            if ($title) {
                $args['title'] = $title;
            }

            // If has custom field custom tagline
            $tagline = trim(get_post_meta( $post_id, '_customify_page_header_tagline', true));
            if ($tagline) {
                $args['tagline'] = $tagline;
            }

            // If has custom field header media
            $media = get_post_meta($post_id, '_customify_page_header_image', true);
            if (!empty($media)) {
                $image = Customify()->get_media($media);
                if ($image) {
                    $args['image'] = $image;
                }
            }

            //Has custom shortcode
            $args['shortcode'] = trim(get_post_meta($post_id, '_customify_page_header_shortcode', true));
            if ( $args['shortcode'] ) {
                $args['display'] = 'shortcode';
            }

        }

        if ( ! $args['display'] ) {
            $args['display'] = 'default';
        }

        self::$_settings = $args;
        return $args;
    }

    function display_page_title( $show ){
        $args = $this->get_settings();
        if ( ! $args['display'] || $args['display'] == 'default' ) {
            $show = true;
        } elseif (  $args['display'] == 'cover' || $args['display'] == 'titlebar' || $args['display'] == 'none' ) {
            $show = false;
        }
        if ( $args['force_display_single_title'] == 'hide'){
            $show = false;
        } elseif ( $args['force_display_single_title'] == 'show'){
            $show = true;
        }

        return $show;
    }

    function render_cover( $args = array() )
    {
        $args = $this->get_settings();
        extract($args, EXTR_SKIP);

        $style = '';
        if ($args['image']) {
            $style = ' style="background-image: url(\'' . esc_url($args['image']) . '\')" ';
        }

        if (!$args['title_tag']) {
            $args['title_tag'] = 'h2';
        }

        ?>
        <div id="page-cover" class="page-header--item page-cover"<?php echo $style; ?>>
            <div class="page-cover-inner customify-container">
                <?php
                if ($args['title']) {
                    // WPCS: XSS ok.
                    echo '<' . $args['title_tag'] . ' class="page-cover-title">' . apply_filters( 'customify_the_title', wp_kses_post($args['title']) ) . '</' . $args['title_tag'] . '>';
                }
                if ( $args['cover_tagline'] ) {
                    if ($args['tagline']) {
                        // WPCS: XSS ok.
                        echo '<div class="page-cover-tagline-wrapper"><div class="page-cover-tagline">' . apply_filters('customify_the_title', wp_kses_post($args['tagline'])) . '</div></div>';
                    }
                }
                do_action('customify/page-cover/after');
                ?>
            </div>
        </div>
        <?php
    }

    function render_titlebar( $args = array() ){
        ?>
        <div id="page-titlebar" class="page-header--item page-titlebar">
            <div class="page-titlebar-inner customify-container">
                <?php
                // WPCS: XSS ok.
                echo '<'.$args['title_tag'].' class="titlebar-title h3">'.apply_filters( 'customify_the_title', wp_kses_post( $args['title'] ) ).'</'.$args['title_tag'].'>';
                if ( $args['titlebar_tagline'] ) {
                    if ($args['tagline']) {
                        // WPCS: XSS ok.
                        echo '<div class="titlebar-tagline">' . apply_filters('customify_the_title', wp_kses_post($args['tagline'])) . '</div>';
                    }
                }
                ?>
                <?php do_action('customify/titlebar/after'); ?>
            </div>
        </div>
        <?php
    }

    function render()
    {
        $args = $this->get_settings();
        if ($args['display'] == 'none' ) {
            return '';
        }
        switch(  $args['display'] ) {
            case  'cover':
                $this->render_cover( $args );
                break;
            case 'titlebar':
                $this->render_titlebar( $args );
                break;
            case 'shortcode':
                echo '<div class="page-header-shortcode">'.apply_filters( 'customify_the_content', $args['shortcode'] ).'</div>';
                break;
        }

    }

}

Customify_Page_Header::get_instance();

