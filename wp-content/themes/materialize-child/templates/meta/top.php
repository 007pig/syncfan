<?php
/**
 *
 *  The template part for displaying top post meta
 *  The top post meta containing the post date,
 *  post categories, post author and number of coments.
 *
 *  @package WordPress
 *  @subpackage Materialize
 *  @since Materialize 1.0
 */

    if( (bool)get_theme_mod( 'mythemes-top-meta', true ) ){
?>
        <div class="mythemes-top-meta meta">

            <!-- date -->
            <?php
                $t_time = get_post_time( 'Y-m-d', false , $post -> ID  );
                $u_time = get_post_time( esc_attr( get_option( 'date_format' ) ) );
            ?>

            <time datetime="<?php echo esc_attr( $t_time ); ?>"><i class="mythemes-icon-calendar"></i><?php echo $u_time; ?></time>

            <!-- comments -->
            <?php
            if( $post -> comment_status == 'open' ) {
                $nr = absint( get_comments_number( $post -> ID ) );
                echo '<a class="comments" href="' . esc_url( get_comments_link( $post -> ID ) ) . '">';
                echo '<i class="mythemes-icon-comment-5"></i>';
                echo sprintf( _nx( '%s Comment' , '%s Comments' , absint( $nr ) , 'Number of comment(s) from post meta' , 'materialize' ) , number_format_i18n( $nr ) );
                echo '</a>';
            }
            ?>
            
            <!-- terms -->
            <?php
            $post_type = get_post_type();
            if ($post_type != 'post' && in_array($post_type, POST_LIKE_POSTTYPES)): ?>
            <i class="mythemes-icon-folder-1"></i>
            <?php
            switch ($post_type) {
                case 'movie':
                    syncfan_term('movie_type');
                    break;
                case 'software':
                    syncfan_term('software_type');
                    syncfan_term('platform');
                    break;
            }
            ?>
            <?php endif; ?>

            <div class="clear"></div>

        </div><!-- .mythemes-top-meta -->
<?php
    }
?>