
<?php
/**
 *
 *  The template part for displaying blog post classic view
 *
 *  @package WordPress
 *  @subpackage Materialize
 *  @since Materialize 1.0
 */

    global $post, $posts_total, $posts_index;
?>

    <!-- post content wrapper -->
    <article <?php post_class('row'); ?>>

        <?php the_category(); ?>

        <div class="post-thumbnail col s3">
            <a href="<?php echo get_permalink( $post -> ID ); ?>">
            <?php
            echo get_the_post_thumbnail($post->ID, 'syncfan-featured-image-in-list');
            ?>
            </a>
        </div>

        <div class="col s9">

            <!-- the post title -->
            <h2 class="post-title">
                <?php if( !empty( $post -> post_title ) ) { ?>

                    <a href="<?php the_permalink() ?>" title="<?php echo mythemes_post::title( $post -> ID, true ); ?>">
                        <?php if (get_post_type() == 'movie') the_field('movie_name_chinese'); ?>

                        <?php the_title(); ?>

                        <?php if (get_post_type() == 'movie'): ?>
                        <span class="year">(<?php the_field('movie_year'); ?>)</span>
                        <?php endif; ?>

                        <?php if (get_post_type() == 'software') the_field('software_version'); ?>
                    </a>

                <?php } else { ?>

                    <!-- if the title is empty show "Read more about .." text -->
                    <a href="<?php the_permalink() ?>"><?php _e( 'Read more about ..' , 'materialize' ) ?></a>

                <?php } ?>
            </h2>

            <?php

                /**
                 *
                 *  The template part for displaying top post meta
                 *  If you want to override this in a child theme, then include a file
                 *  called top.php and that will be used instead.
                 */

                get_template_part( 'templates/meta/top' );
            ?>

            <!-- the post content -->
            <div class="post-content">

                <?php

                    // the content for button read more
                    $read_more_link =   '<span class="hide-on-small-only">' . __( 'Read More' , 'materialize' ) . '</span>'.
                                        '<span class="hide-on-med-and-up">&rarr;</span>';

                    // show the excerpt ( if exists )
                    if( !empty( $post -> post_excerpt ) ){
                        the_excerpt();
                    }

                    // show the content
                    else{
                        the_content();
                    }

                    echo '<a href="' . get_permalink( $post -> ID ) . '" class="more-link waves-effect waves-light green white-text">';
                    echo $read_more_link;
                    echo '&nbsp;&nbsp;<i class="mythemes-icon-right-big"></i></a>';
                ?>

                <div class="clearfix"></div>
            </div>
        </div>
    </article>