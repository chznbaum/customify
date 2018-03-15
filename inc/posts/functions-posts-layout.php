<?php

function Customify_Post_Entry(){
    return Customify_Post_Entry::get_instance();
}

if ( ! function_exists( 'customify_the_blog_item' ) ) {
    function customify_the_blog_item( $post = null ){
        ?>
        <div <?php post_class( 'entry',  $post) ?>>
            <?php
            Customify_Post_Entry()->set_post( $post );
            $items_config = Customify()->get_setting('blog_post_item' );
            Customify_Post_Entry()->build_fields( $items_config );
            ?>
        </div><!-- /.entry post --> <?php
    }
}

if( ! function_exists( 'customify_blog_posts' ) ) {
    function customify_blog_posts($args = array())
    {

        $args = wp_parse_args($args, array(
            'el_id'  => 'blog-posts',
            'prefix' => 'blog_post',
        ));

        echo '<div id="' . esc_attr($args['el_id']) . '">';
        if (customify_is_post_title_display()) {
            if (is_search()) {
                ?>
                <header>
                    <h1 class="page-title"><?php printf( // WPCS: XSS ok.
                        /* translators: 1: Search query name */
                            __('Search Results for: %s', 'customify'),
                            '<span>' . get_search_query() . '</span>'
                        ); ?></h1>
                </header>
                <?php
            } elseif (is_archive()) {
                ?>
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header><!-- .page-header -->
                <?php
            } else if (customify_is_post_title_display() && !(is_front_page() && is_home())) {
                ?>
                <header>
                    <h1 class="page-title"><?php echo get_the_title(customify_get_support_meta_id()); ?></h1>
                </header>
                <?php
            }
        }

        if (have_posts()) :
            $_args = array(
                'layout'              => Customify()->get_setting($args['prefix'] . '_layout'),
                'excerpt_length'      => Customify()->get_setting($args['prefix'] . '_excerpt_length'),
                'excerpt_more'        => Customify()->get_setting($args['prefix'] . '_excerpt_more'),
                'more_text'           => Customify()->get_setting($args['prefix'] . '_more_text'),
                'more_display'        => Customify()->get_setting($args['prefix'] . '_more_display'),
                'thumbnail_size'      => Customify()->get_setting($args['prefix'] . '_thumbnail_size'),
                'hide_thumb_if_empty' => Customify()->get_setting($args['prefix'] . '_hide_thumb_if_empty'),
                'meta_config'         => Customify()->get_setting($args['prefix'] . '_meta_config'),
                'meta_sep'            => Customify()->get_setting($args['prefix'] . '_meta_sep'),
            );
            if (!is_array($_args)) {
                $_args = $args;
            }
            $pagination = array(
                'show_paging' => Customify()->get_setting($args['prefix'] . '_pg_show_paging'),
                'show_nav'    => Customify()->get_setting($args['prefix'] . '_pg_show_nav'),
                'mid_size'    => Customify()->get_setting($args['prefix'] . '_pg_mid_size'),
                'prev_text'   => Customify()->get_setting($args['prefix'] . '_pg_prev_text'),
                'next_text'   => Customify()->get_setting($args['prefix'] . '_pg_next_text'),
            );

            $l = new Customify_Posts_Layout();
            $_args['pagination'] = is_array($pagination) ? $pagination : array();
            $l->render($_args);

        else :
            get_template_part('template-parts/content', 'none');
        endif;
        echo '</div>';
    }
}

if( ! function_exists( 'customify_archive_posts' ) ) {
    function customify_archive_posts(){
        customify_blog_posts( array(
            'el_id' => 'archive-posts',
            'prefix' => 'archive_post',
        ));
    }

}
