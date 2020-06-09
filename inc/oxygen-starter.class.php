<?php

class OxygenStarter {
    
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        //impostazioni dell'installazione di WordPress
        self::addSettings();
        
        //aggiunta di immagini con dimensioni personalizzate
        self::addCustomImageSizes();

        self::addCustomStylesAndScripts();

    }

    public static function addCustomStylesAndScripts() {
        add_action('wp_enqueue_scripts', function(){
            wp_enqueue_style('oxygen-starter', plugin_dir_url(__FILE__) . '../assets/css/style.css', array(), '1.0.0');
            wp_enqueue_script('oxygen-starter', plugin_dir_url(__FILE__) . '../assets/js/init.js', array('jquery'), '1.0.0' ,true);
        });
    }

    public static function addSettings() {

       /**
        * modificare le restrizioni con queste regole in .htaccess
        * php_value upload_max_filesize 64M
        * php_value post_max_size 64M
        * php_value memory_limit 3000M
        * php_value max_execution_time 300
        * php_value max_input_time 300
        */
        
        add_action('after_setup_theme',function(){
            add_theme_support('post-thumbnails');
        });
        
        define('RECOVERY_MODE_EMAIL', OXYGEN_STARTER_RECOVERY_EMAIL);
        define('AUTOSAVE_INTERVAL', 300);
        define('WP_POST_REVISIONS', 3);
        define('EMPTY_TRASH_DAYS',7);

    }

    public static function addCustomImageSizes() {
        
        add_image_size('hero', 1920, 600, true);
        add_filter('image_size_names_choose', array('OxygenStarter', 'setCustomImageSizes'));

    }

    public static function setCustomImageSizes($sizes) {
        
        return array_merge($sizes, array(
            'hero' => __('Hero 1920x600'),
        ));

    }

}

OxygenStarter::instance();