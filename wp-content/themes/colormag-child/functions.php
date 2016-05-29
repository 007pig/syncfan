<?php

const POST_LIKE_POSTTYPES = ['post', 'movie', 'tv'];

// Load parent css
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

// Load language file
add_action( 'after_setup_theme', function () {
    // load custom translation file for the parent theme
    load_theme_textdomain( 'colormag', get_stylesheet_directory() . '/languages/colormag' );
    // load translation file for the child theme
    load_child_theme_textdomain( 'colormag-child', get_stylesheet_directory() . '/languages' );

    add_action( 'widgets_init', function () {
        unregister_widget("colormag_featured_posts_widget");
        register_widget("syncfan_featured_posts_widget");
    });

    remove_filter('excerpt_length', 'colormag_excerpt_length');

    add_filter( 'excerpt_length', function() {
        return 40;
    });
});

// Images sizes
add_image_size( 'syncfan-featured-image-movie', 350, 493, true );
add_image_size( 'syncfan-movie-screenshot', 0, 200, true );



/****************************************************************************************/

/**
 * Featured Posts widget
 */

class syncfan_featured_posts_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'widget_featured_posts widget_featured_meta', 'description' =>__( 'Display latest posts or posts of specific category.' , 'colormag') );
        $control_ops = array( 'width' => 200, 'height' =>250 );
        parent::__construct( false,$name= __( 'Syncfan: Featured Posts (Style 1)', 'colormag' ),$widget_ops);
    }

    function form( $instance ) {
        $tg_defaults['title'] = '';
        $tg_defaults['text'] = '';
        $tg_defaults['number'] = 4;
        $tg_defaults['type'] = 'latest';
        $tg_defaults['category'] = '';
        $instance = wp_parse_args( (array) $instance, $tg_defaults );
        $title = esc_attr( $instance[ 'title' ] );
        $text = esc_textarea($instance['text']);
        $number = $instance['number'];
        $type = $instance['type'];
        $category = $instance['category'];
        ?>
        <p><?php _e( 'Layout will be as below:', 'colormag' ) ?></p>
        <div style="text-align: center;"><img src="<?php echo get_template_directory_uri() . '/img/style-1.jpg'?>"></div>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'colormag' ); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php _e( 'Description','colormag' ); ?>
        <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of posts to display:', 'colormag' ); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>

        <p><input type="radio" <?php checked($type, 'latest') ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'colormag' );?><br />
            <input type="radio" <?php checked($type,'category') ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category', 'colormag' );?><br /></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'colormag' ); ?>:</label>
            <?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) );
        $instance[ 'number' ] = absint( $new_instance[ 'number' ] );
        $instance[ 'type' ] = $new_instance[ 'type' ];
        $instance[ 'category' ] = $new_instance[ 'category' ];

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );
        extract( $instance );

        global $post;
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
        $text = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
        $number = empty( $instance[ 'number' ] ) ? 4 : $instance[ 'number' ];
        $type = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest' ;
        $category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';

        if( $type == 'latest' ) {
            $get_featured_posts = new WP_Query( array(
                'posts_per_page'        => $number,
                'post_type'             => POST_LIKE_POSTTYPES,
                'ignore_sticky_posts'   => true
            ) );
        }
        else {
            $get_featured_posts = new WP_Query( array(
                'posts_per_page'        => $number,
                'post_type'             => POST_LIKE_POSTTYPES,
                'category__in'          => $category
            ) );
        }
        echo $before_widget;
        ?>
        <?php
        if ( $type != 'latest' ) {
            $border_color = 'style="border-bottom-color:' . colormag_category_color($category) . ';"';
            $title_color = 'style="background-color:' . colormag_category_color($category) . ';"';
        } else {
            $border_color = '';
            $title_color = '';
        }
        if ( !empty( $title ) ) { echo '<h3 class="widget-title" '. $border_color .'><span ' . $title_color .'>'. esc_html( $title ) .'</span></h3>'; }
        if( !empty( $text ) ) { ?> <p> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
        <?php
        $i=1;
        while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
            ?>
            <?php if( $i == 1 ) { $featured = 'colormag-featured-post-medium'; } else { $featured = 'colormag-featured-post-small'; } ?>
            <?php if( $i == 1 ) { echo '<div class="first-post">'; } elseif ( $i == 2 ) { echo '<div class="following-post">'; } ?>
            <div class="single-article clearfix">
                <?php
                if( has_post_thumbnail() ) {
                    $image = '';
                    $title_attribute = get_the_title( $post->ID );
                    $image .= '<figure>';
                    $image .= '<a href="' . get_permalink() . '" title="'.the_title( '', '', false ).'">';
                    $image .= get_the_post_thumbnail( $post->ID, $featured, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ) ) ).'</a>';
                    $image .= '</figure>';
                    echo $image;
                }
                ?>
                <div class="article-content">
                    <?php colormag_colored_category(); ?>
                    <h3 class="entry-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_field('movie_name_chinese'); ?> <?php the_title(); ?></a>
                    </h3>
                    <div class="below-entry-meta">
                        <?php
                        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
                        $time_string = sprintf( $time_string,
                            esc_attr( get_the_date( 'c' ) ),
                            esc_html( get_the_date() )
                        );
                        printf( __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="fa fa-calendar-o"></i> %3$s</a></span>', 'colormag' ),
                            esc_url( get_permalink() ),
                            esc_attr( get_the_time() ),
                            $time_string
                        );
                        ?>
                        <span class="comments"><i class="fa fa-comment"></i><?php comments_popup_link( '0', '1', '%' );?></span>
                    </div>
                    <?php if( $i == 1 ) { ?>
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <?php if( $i == 1 ) { echo '</div>'; } ?>
            <?php
            $i++;
        endwhile;
        if ( $i > 2 ) { echo '</div>'; }
        // Reset Post Data
        wp_reset_query();
        ?>
        <!-- </div> -->
        <?php echo $after_widget;
    }
}

/**
 * Shows meta information of post.
 */
function colormag_entry_meta() {
    if ( in_array(get_post_type(), POST_LIKE_POSTTYPES)  ) :
        echo '<div class="below-entry-meta">';
        ?>

        <?php
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
        }
        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );
        printf( __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="fa fa-calendar-o"></i> %3$s</a></span>', 'colormag' ),
            esc_url( get_permalink() ),
            esc_attr( get_the_time() ),
            $time_string
        ); ?>

        <?php
        if ( ! post_password_required() && comments_open() ) { ?>
            <span class="comments"><?php comments_popup_link( __( '<i class="fa fa-comment"></i> 0 Comment', 'colormag' ), __( '<i class="fa fa-comment"></i> 1 Comment', 'colormag' ), __( '<i class="fa fa-comments"></i> % Comments', 'colormag' ) ); ?></span>
        <?php }
        $tags_list = get_the_tag_list( '<span class="tag-links"><i class="fa fa-tags"></i>', __( ', ', 'colormag' ), '</span>' );
        if ( $tags_list ) echo $tags_list;

        edit_post_link( __( 'Edit', 'colormag' ), '<span class="edit-link"><i class="fa fa-edit"></i>', '</span>' );

        echo '</div>';
    endif;
}