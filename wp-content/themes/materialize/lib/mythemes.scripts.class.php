<?php
if( !class_exists( 'mythemes_scripts' ) ){

class mythemes_scripts
{
    static function frontend()
    {
        $ver        = mythemes_core::theme( 'version' );

        $poiret_one = 'Poiret+One';
        $raleway    = 'Raleway:400,100,200,300,500,600,700,800,900';
        $open_sans  = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,600,700,800,300&subset=latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese';

        wp_register_style( 'mythemes-google-fonts',             '//fonts.googleapis.com/css?family=' . esc_attr( $poiret_one ) . '|' . esc_attr( $raleway ) . '|' . esc_attr( $open_sans ) );

        /* COMMON */
        wp_register_style( 'mythemes-icons',                    get_template_directory_uri() . '/media/css/mythemes-icons.css', null, $ver );

        /* FRONTEND */
        wp_register_style( 'mythemes-effects',                  get_template_directory_uri() . '/media/_frontend/css/effects.css', null, $ver );
        wp_register_style( 'mythemes-header',                   get_template_directory_uri() . '/media/_frontend/css/header.css', null, $ver );
        wp_register_style( 'mythemes-materialize',              get_template_directory_uri() . '/media/_frontend/css/materialize.min.css', null, $ver );
        wp_register_style( 'mythemes-typography',               get_template_directory_uri() . '/media/_frontend/css/typography.css', null, $ver);
        wp_register_style( 'mythemes-navigation',               get_template_directory_uri() . '/media/_frontend/css/navigation.css', null, $ver );
        wp_register_style( 'mythemes-nav',                      get_template_directory_uri() . '/media/_frontend/css/nav.css', null, $ver );
        wp_register_style( 'mythemes-blog',                     get_template_directory_uri() . '/media/_frontend/css/blog.css', null, $ver );
        wp_register_style( 'mythemes-forms',                    get_template_directory_uri() . '/media/_frontend/css/forms.css', null, $ver );
        wp_register_style( 'mythemes-elements',                 get_template_directory_uri() . '/media/_frontend/css/elements.css', null, $ver );
        wp_register_style( 'mythemes-widgets',                  get_template_directory_uri() . '/media/_frontend/css/widgets.css', null, $ver );
        wp_register_style( 'mythemes-comments',                 get_template_directory_uri() . '/media/_frontend/css/comments.css', null, $ver );
        wp_register_style( 'mythemes-comments-typography',      get_template_directory_uri() . '/media/_frontend/css/comments-typography.css', null, $ver );
        wp_register_style( 'mythemes-footer',                   get_template_directory_uri() . '/media/_frontend/css/footer.css', null, $ver );
        wp_register_style( 'mythemes-pretty-photo',             get_template_directory_uri() . '/media/_frontend/css/prettyPhoto.css', null, $ver );
        wp_register_style( 'mythemes-plugins',                  get_template_directory_uri() . '/media/_frontend/css/plugins.css', null, $ver );

        $dependency = array( 
            'mythemes-google-fonts', 
            'mythemes-icons', 
            'mythemes-effects', 
            'mythemes-header',
            'mythemes-materialize',
            'mythemes-typography',
            'mythemes-navigation',
            'mythemes-nav',
            'mythemes-blog',
            'mythemes-forms',
            'mythemes-elements',
            'mythemes-widgets',
            'mythemes-comments',
            'mythemes-comments-typography',
            'mythemes-footer',
            'mythemes-pretty-photo',
            'mythemes-plugins',
        );

        /* MAIN STYLE */
        wp_enqueue_style( 'mythemes-style', get_stylesheet_directory_uri() . '/style.css', $dependency );

        /* REGISTER SCRIPTS */
        wp_register_script( 'mythemes-materialize',             get_template_directory_uri() . '/media/_frontend/js/materialize.min.js', array( 'jquery' ), $ver, true );
        wp_register_script( 'mythemes-functions',               get_template_directory_uri() . '/media/_frontend/js/functions.js', array( 'masonry' ) , $ver, true );
        
        wp_register_script( 'mythemes-pretty-photo',            get_template_directory_uri() . '/media/_frontend/js/jquery.prettyPhoto.js', null, $ver, true );
        wp_register_script( 'mythemes-pretty-photo-settings',   get_template_directory_uri() . '/media/_frontend/js/jquery.prettyPhoto.settings.js', null, $ver, true );

        wp_enqueue_script( 'mythemes-functions' );
        wp_enqueue_script( 'mythemes-materialize' );
        wp_enqueue_script( 'mythemes-pretty-photo' );
        wp_enqueue_script( 'mythemes-pretty-photo-settings' );

        /* INCLUDE FOR REPLY COMMENTS */
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
                wp_enqueue_script( 'comment-reply' );
    }

    static function backend()
    {
        $ver = mythemes_core::theme( 'version' );

        wp_register_style( 'mythemes-icons',                            get_template_directory_uri() . '/media/css/mythemes-icons.css', null, $ver );
        wp_enqueue_style( 'mythemes-icons' );

        wp_register_style( 'mythemes-_backend-customizer',              get_template_directory_uri() . '/media/_backend/css/customizer.css', null, $ver );
        wp_register_style( 'mythemes-_backend-options',                 get_template_directory_uri() . '/media/_backend/css/options.css', null, $ver );
        wp_register_style( 'mythemes-_backend-boxes',                   get_template_directory_uri() . '/media/_backend/css/boxes.css', null, $ver );
        wp_register_style( 'mythemes-_backend-ahtml',                   get_template_directory_uri() . '/media/_backend/css/ahtml.css', null, $ver );
        wp_register_style( 'mythemes-_backend-adds',                    get_template_directory_uri() . '/media/_backend/css/adds.css', null, $ver );

        wp_enqueue_style( 'mythemes-_backend-customizer' );
        wp_enqueue_style( 'mythemes-_backend-options' );
        wp_enqueue_style( 'mythemes-_backend-boxes' );
        wp_enqueue_style( 'mythemes-_backend-ahtml' );
        wp_enqueue_style( 'mythemes-_backend-adds' );
        wp_enqueue_style( 'mythemes-icons' );
    }
}

}   /* END IF CLASS EXISTS */
?>