<?php

const POST_LIKE_POSTTYPES = ['post', 'movie', 'tv', 'software', 'collection'];

// Load parent css
add_action('wp_enqueue_scripts', function () {
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style)
    );

    wp_deregister_style('mythemes-google-fonts');

    $poiret_one = 'Poiret+One';
    $raleway    = 'Raleway:400,100,200,300,500,600,700,800,900';
    $open_sans  = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,600,700,800,300&subset=latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese';
    wp_register_style('mythemes-google-fonts', '//fonts.gmirror.org/css?family=' . esc_attr( $poiret_one ) . '|' . esc_attr( $raleway ) . '|' . esc_attr( $open_sans ) );

}, 11);

add_action('after_setup_theme', function () {
    // Load language file
    // load custom translation file for the parent theme
    load_theme_textdomain('materialize', get_stylesheet_directory() . '/languages/materialize');
    // load translation file for the child theme
    load_child_theme_textdomain('materialize-child', get_stylesheet_directory() . '/languages');

});

add_filter('pre_get_posts', function ($query) {
    if ($query->is_main_query() && !is_admin()) {
        $query->set('post_type', POST_LIKE_POSTTYPES);
    }
    return $query;
});

add_image_size( 'syncfan-featured-image-movie', 350, 493, true );
add_image_size( 'syncfan-movie-screenshot', 900, 0, true );
add_image_size( 'syncfan-featured-image-in-list', 200, 0, true );

add_action('init', function() {
    // custom url rewrite
    add_rewrite_rule('^movies/?', 'index.php?post_type=movie&category_name=movie', 'top');
    add_rewrite_rule('^tv/?', 'index.php?post_type=movie&category_name=tv', 'top');
});

add_action('comment_form_fields', function ($fields) {
    $comment = $fields['comment'];

    unset($fields['comment']);

    $fields['comment'] = $comment;

    return $fields;
});


/**
 * Show term
 */
function syncfan_term($term_name) {
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
                        <?php if ($resource['resource_subtitle_link']) :?>
                            <a href="<?php echo $resource['resource_subtitle_link'];?>" target="_blank">中文字幕</a> |
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    endif;
}
