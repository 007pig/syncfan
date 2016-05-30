<?php

const POST_LIKE_POSTTYPES = ['post', 'movie', 'tv', 'software'];

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

/**
 * ACF Bidirectional Relationships
 *
 * @param $value
 * @param $post_id
 * @param $field
 * @return mixed
 */
function bidirectional_acf_update_value( $value, $post_id, $field  ) {

    // vars
    $field_name = $field['name'];
    $global_name = 'is_updating_' . $field_name;


    // bail early if this filter was triggered from the update_field() function called within the loop below
    // - this prevents an inifinte loop
    if( !empty($GLOBALS[ $global_name ]) ) return $value;


    // set global variable to avoid inifite loop
    // - could also remove_filter() then add_filter() again, but this is simpler
    $GLOBALS[ $global_name ] = 1;


    // loop over selected posts and add this $post_id
    if( is_array($value) ) {

        foreach( $value as $post_id2 ) {

            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);


            // allow for selected posts to not contain a value
            if( empty($value2) ) {

                $value2 = array();

            }


            // bail early if the current $post_id is already found in selected post's $value2
            if( in_array($post_id, $value2) ) continue;


            // append the current $post_id to the selected post's 'related_posts' value
            $value2[] = $post_id;


            // update the selected post's value
            update_field($field_name, $value2, $post_id2);

        }

    }


    // find posts which have been removed
    $old_value = get_field($field_name, $post_id, false);

    if( is_array($old_value) ) {

        foreach( $old_value as $post_id2 ) {

            // bail early if this value has not been removed
            if( is_array($value) && in_array($post_id2, $value) ) continue;


            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);


            // bail early if no value
            if( empty($value2) ) continue;


            // find the position of $post_id within $value2 so we can remove it
            $pos = array_search($post_id, $value2);


            // remove
            unset( $value2[ $pos] );


            // update the un-selected post's value
            update_field($field_name, $value2, $post_id2);

        }

    }


    // reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;


    // return
    return $value;

}

// Add movie
add_filter('acf/update_value/name=related_movies', 'bidirectional_acf_update_value', 10, 3);

/**
 * Show resources
 */
function syncfan_show_download_resources() {

        $resources = get_field('resource');
        if ($resources) :
            ?>

            <h5>资源下载</h5>

            <p>强烈建议您使用 BTSync 下载，使用教程请点击这里。</p>

            <table>
                <tr>
                    <th>资源名称</th>
                    <th>下载链接（点击下载）</th>
                </tr>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td>
                            <?php echo wp_strip_all_tags($resource['resource_desc']); ?>
                            <?php
                            if ($resource['resource_filesize']) {
                                echo '（' . size_format($resource['resource_filesize']) . '）';
                            }
                            ?>
                        </td>
                        <td>
                            |
                            <?php foreach ($resource['resource_links'] as $link): ?>
                                <a href="<?php echo $link['link'] ?>" target="_blank">
                                    <?php
                                    switch ($link['link_type']) {
                                        case 'btsync':
                                            echo 'BTSync';
                                            break;
                                        case 'magnet':
                                            echo '磁力链';
                                            break;
                                        case 'ed2k':
                                            echo '电驴';
                                            break;
                                        case 'baidu':
                                            echo '百度云';
                                            break;
                                        case 'ctfile':
                                            echo '城通网盘';
                                            break;
                                        case '360':
                                            echo '360云盘';
                                            break;
                                        default:
                                            echo '其他';
                                    }
                                    ?>
                                </a>
                                <?php
                                if ($link['link_desc']) {
                                    echo '（' . wp_strip_all_tags($link['link_desc']) . '）';
                                }
                                ?>
                                |
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php
        endif;
}

/**
 * Show colored term
 */
function syncfan_colored_term($term_name) {
    global $post;
    $categories = get_the_terms( false, $term_name );
    $separator = '&nbsp;';
    $output = '';
    if($categories) {
        $output .= '<span class="cat-links">';
        foreach($categories as $category) {
            $output .= '<a href="'.get_term_link( $category->term_id ).'"  rel="category tag">'.$category->name.'</a>'.$separator;
        }
        $output .='</span>';
        echo trim($output, $separator);
    }
}
