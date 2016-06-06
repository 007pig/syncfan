<?php
/**
 *
 *  The template for displaying all single posts and attachments
 *
 *  @package WordPress
 *  @subpackage Materialize
 *  @since Materialize 1.0
 */

  get_header(); ?>

    <!--  the content -->
    <div class="content">

        <!--  the container ( align to center ) -->
        <div class="container">
            <div class="row">

            <?php
                global $post;

                // get layout details
                $mythemes_layout = new mythemes_layout( 'post' );

                // left sidebar ( if exists )
                $mythemes_layout -> sidebar( 'left' );
            ?>
                <!-- the post -->
                <section class="<?php echo $mythemes_layout -> classes(); ?>">

                    <?php
                    if( (bool)get_theme_mod( 'mythemes-show-breadcrumbs', true ) ){
                        ?>
                        <!-- the breadcrumbs content -->
                        <div class="mythemes-page-header">

                            <!-- the breadcrumbs container ( align to center ) -->
                            <div class="container">
                                <div class="row">

                                    <div class="col s12">

                                        <!-- the breadcrumbs navigation path -->
                                        <nav class="mythemes-navigation">
                                            <ul>

                                                <!-- the home link -->
                                                <?php echo mythemes_breadcrumbs::home(); ?>

                                                <!-- the post categories ( hierarchical path ) -->
                                                <?php
                                                global $post;

                                                $categories = array_reverse( get_the_category( $post -> ID ) );

                                                if( !empty( $categories ) ){

                                                    foreach( $categories as $c ){
                                                        echo mythemes_breadcrumbs::categories( absint( $c -> term_id ) );
                                                        break;
                                                    }
                                                }
                                                ?>

                                                <!-- the last arrow from path -->
                                                <li><?php the_field('movie_name_chinese');?> <?php the_title(); ?></li>

                                            </ul>
                                        </nav>

                                    </div>

                                </div>
                            </div>

                        </div>
                        <?php
                    }
                    ?>


                    <?php

                    if( have_posts() ){
                        while( have_posts() ){
                            the_post();
                    ?>
                            <article <?php post_class(); ?>>

                                <!-- the post title -->
                                <h1 class="post-title">
                                    <?php the_field('movie_name_chinese');?> <?php the_title(); ?>
                                    <span class="year">(<?php the_field('movie_year'); ?>)</span>
                                </h1>

                                <!-- the post top meta: author / time / comments -->
                                <?php get_template_part( 'templates/meta/top' ); ?>

                                <div class="movie-meta row">
                                    <div class="col s5">
                                        <?php
                                            // the post thumbnail
                                            $p_thumbnail = get_post( get_post_thumbnail_id( $post -> ID ) );

                                            if( has_post_thumbnail( $post -> ID ) && isset( $p_thumbnail -> ID ) ){
                                        ?>
                                                <!-- the post thumbanil wrapper -->
                                                <div class="post-thumbnail overflow-wrapper">
                                                <?php

                                                    // the post thumbnail
                                                    echo get_the_post_thumbnail( $post -> ID , 'syncfan-featured-image-movie' , array(
                                                        'alt' => mythemes_post::title( $post -> ID, true )
                                                    ));

                                                    // the post thumbnail caption
                                                    $c_thumbnail = isset( $p_thumbnail -> post_excerpt ) ? esc_html( $p_thumbnail -> post_excerpt ) : null;

                                                    if( !empty( $c_thumbnail ) ){
                                                        echo '<div class="valign-bottom-cell-wrapper">';
                                                        echo '<footer class="valign-cell">' . $c_thumbnail . '</footer>';
                                                        echo '</div>';
                                                    }
                                                ?>
                                                </div><!-- .post-thumbnail -->
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="col s7">
                                        <table>
                                            <tr>
                                                <td>又　　名：</td>
                                                <td><?php the_field('movie_alias') ?></td>
                                            </tr>
                                            <tr>
                                                <td>类　　别：</td>
                                                <td><?php
                                                    $movie_types = get_field('movie_type');
                                                    $out = '';
                                                    foreach ($movie_types as $movie_type) {
                                                        $out .= $movie_type->name . ' / ';
                                                    }
                                                    echo substr($out, 0, -3);
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td>国　　家：</td>
                                                <td><?php
                                                    $movie_countries = get_field('movie_country');
                                                    $out = '';
                                                    foreach ($movie_countries as $movie_country) {
                                                        $out .= $movie_country->name . ' / ';
                                                    }
                                                    echo substr($out, 0, -3);
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td>语　　言：</td>
                                                <td><?php
                                                    $movie_languages = get_field('movie_language');
                                                    $out = '';
                                                    foreach ($movie_languages as $movie_language) {
                                                        $out .= $movie_language->name . ' / ';
                                                    }
                                                    echo substr($out, 0, -3);
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td>上映日期：</td>
                                                <td><?php the_field('movie_release_date') ?></td>
                                            </tr>
                                            <tr>
                                                <td>片　　长：</td>
                                                <td><?php the_field('movie_length') ?> 分钟</td>
                                            </tr>
                                            <tr>
                                                <td>导　　演：</td>
                                                <td><?php
                                                    $movie_directors = get_field('movie_director');
                                                    $out = '';
                                                    foreach ($movie_directors as $movie_director) {
                                                        $out .= $movie_director->name . ' / ';
                                                    }
                                                    echo substr($out, 0, -3);
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td>演　　员：</td>
                                                <td><?php
                                                    $movie_actors = get_field('movie_actor');
                                                    $out = '';
                                                    foreach ($movie_actors as $movie_actor) {
                                                        $out .= $movie_actor->name . ' / ';
                                                    }
                                                    echo substr($out, 0, -3);
                                                    ?></td>
                                            </tr>
                                            <?php if (get_field('movie_douban_rating')): ?>
                                                <tr>
                                                    <td>豆瓣评分：</td>
                                                    <td><?php the_field('movie_douban_rating') ?>/10</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (get_field('movie_douban_link')): ?>
                                                <tr>
                                                    <td>豆瓣链接：</td>
                                                    <td><a href="<?php the_field('movie_douban_link') ?>" target="_blank">点击这里</a></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (get_field('movie_imdb_rating')): ?>
                                                <tr>
                                                    <td>IMDb评分：</td>
                                                    <td><?php the_field('movie_imdb_rating') ?>/10</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (get_field('movie_imdb_link')): ?>
                                                <tr>
                                                    <td>IMDb链接：</td>
                                                    <td><a href="<?php the_field('movie_imdb_link') ?>" target="_blank">点击这里</a></td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>

                                <!-- the post content wrapper -->
                                <div class="post-content">

                                    <?php syncfan_show_download_resources(); ?>

                                    <h5>内容简介</h5>

                                    <div class="entry-content clearfix">
                                        <?php
                                        the_content();
                                        ?>
                                    </div>

                                    <?php
                                    $images = get_field('movie_screenshot');
                                    if ($images):
                                        ?>

                                        <h5>精彩截图</h5>

                                        <ul class="movie-screenshot-container clearfix">
                                            <?php foreach( $images as $image ): ?>
                                                <li>
                                                    <p><?php echo $image['caption']; ?></p>
                                                    <a href="<?php echo $image['url']; ?>">
                                                        <img src="<?php echo $image['sizes']['syncfan-movie-screenshot']; ?>"
                                                             width="<?php echo $image['sizes']['syncfan-movie-screenshot-width']; ?>"
                                                             height="<?php echo $image['sizes']['syncfan-movie-screenshot-height']; ?>"
                                                             alt="<?php echo $image['alt']; ?>" />
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                            </article>

                            <?php
                                // the post pagination ( if exists )
                                wp_link_pages( array( 
                                    'before'        => '<div class="mythemes-paged-post"><span class="mythemes-pagination-title">' . __( 'Pages', 'materialize' ) . ': </span>',
                                    'after'         => '<div class="clearfix"></div></div>',
                                    'link_before'   => '<span class="mythemes-pagination-item">',
                                    'link_after'    => '</span>'
                                ));
                            ?>

                            <?php
                                /**
                                 *
                                 *  The template part for displaying bottom post meta
                                 *  If you want to override this in a child theme, then include a file
                                 *  called bottom.php and that will be used instead.
                                 */

                                get_template_part( 'templates/meta/bottom' );
                            ?>

                            <!-- the post comments -->
                            <?php comments_template(); ?>

                <?php
                        } // end of post
                    }
                ?>

                </section>

            <?php
                // right sidebar ( if exists )
                $mythemes_layout -> sidebar( 'right' );
            ?>
            
            </div>
        </div><!-- .container -->
    </div><!-- .content -->


<?php get_footer(); ?>