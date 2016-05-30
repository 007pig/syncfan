<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ThemeGrill
 * @subpackage ColorMag
 * @since ColorMag 1.0
 */
?>

<?php
$nav_label = '软件';
$nav_color = colormag_category_color(get_cat_ID($nav_label));
?>

<h3 class="nav" style="border-bottom-color:<?=$nav_color?>;"><span style="background:<?=$nav_color?>;"><?=$nav_label?></span></h3>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php do_action( 'colormag_before_post_content' ); ?>

    <header class="entry-header">
        <h1 class="entry-title">
            <?php the_title(); ?>
            <?php the_field('software_version'); ?>
        </h1>
        <?php colormag_entry_meta(); ?>
    </header>

    <?php
    $image_popup_id = get_post_thumbnail_id();
    $image_popup_url = wp_get_attachment_url( $image_popup_id );
    ?>

    <?php if ( has_post_thumbnail() ) { ?>
        <div class="featured-image">
            <?php if (get_theme_mod('colormag_featured_image_popup', 0) == 1) { ?>
                <a href="<?php echo $image_popup_url; ?>" class="image-popup"><?php the_post_thumbnail( 'colormag-featured-image' ); ?></a>
            <?php } else { ?>
                <?php the_post_thumbnail( 'colormag-featured-image' ); ?>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="article-content clearfix">

        <?php syncfan_show_download_resources(); ?>

        <h5>软件介绍</h5>

        <div class="entry-content clearfix">
            <?php
            the_content();

            wp_link_pages( array(
                'before'            => '<div style="clear: both;"></div><div class="pagination clearfix">'.__( 'Pages:', 'colormag' ),
                'after'             => '</div>',
                'link_before'       => '<span>',
                'link_after'        => '</span>'
            ) );
            ?>
        </div>

        <?php
        $images = get_field('software_screenshot');
        if ($images):
            ?>

            <h5>软件截图</h5>

            <ul class="movie-screenshot-container clearfix">
                <?php foreach( $images as $image ): ?>
                    <li>
                        <?php if (get_theme_mod('colormag_featured_image_popup', 0) == 1) { ?>
                            <a href="<?php echo $image['url']; ?>" class="image-popup">
                                <img src="<?php echo $image['sizes']['syncfan-movie-screenshot']; ?>"
                                     width="<?php echo $image['sizes']['syncfan-movie-screenshot-width']; ?>"
                                     height="<?php echo $image['sizes']['syncfan-movie-screenshot-height']; ?>"
                                     alt="<?php echo $image['alt']; ?>" />
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo $image['url']; ?>">
                                <img src="<?php echo $image['sizes']['syncfan-movie-screenshot']; ?>"
                                     width="<?php echo $image['sizes']['syncfan-movie-screenshot-width']; ?>"
                                     height="<?php echo $image['sizes']['syncfan-movie-screenshot-height']; ?>"
                                     alt="<?php echo $image['alt']; ?>" />
                            </a>
                        <?php } ?>
                        <p><?php echo $image['caption']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

    <?php do_action( 'colormag_after_post_content' ); ?>
</article>