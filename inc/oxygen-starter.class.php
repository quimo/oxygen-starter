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

        add_action('after_setup_theme',function(){
            add_theme_support('post-thumbnails');
        });

        //impostazioni dell'installazione di WordPress
        self::addSettings();
        
        //aggiunta di immagini con dimensioni personalizzate
        self::addCustomImageSizes();

        //script e CSS
        add_action('wp_enqueue_scripts', array('OxygenStarter', 'addCustomStylesAndScripts'));

    }

    public static function addCustomStylesAndScripts() {
        
        wp_enqueue_style('oxygen-starter', plugin_dir_url(__FILE__) . '../assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('oxygen-starter', plugin_dir_url(__FILE__) . '../assets/js/init.js', array('jquery'), '1.0.0' ,true);
        
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

    /**
     * Include un template e gli passa un array di parametri
     * @param string $slug
     * @param string $name
     * @param array $vars
     * @param bool $use_cache
     */
    public static function getTemplatePart($slug, $name = '', $vars = null, $use_cache = false) {

        if (is_array($vars) && !empty($vars)) {
            //imposto i parametri da passare al template
            set_query_var('oxygen_template_vars', $vars);
        }

        //compongo la chiave per la cache
        $key = ($name == '') ? $slug : $slug . '-' . $name;

        if ($use_cache === true) {
            $template = wp_cache_get($key);
            if ($template === false) {
                //salvo il template in cache e lo mostro
                ob_start();
                get_template_part($slug, $name);
                $template = ob_get_clean();
                // template in cache per 5 minuti
                wp_cache_set($key, addslashes($template), '', 300);
                echo $template;
            } else {
                //mostro il template dalla cache
                echo stripslashes($template);
            }
        } else {
            //recupero il template
            get_template_part($slug, $name);
        }

        if (is_array($vars) && !empty($vars)) {
            // rimuovo i parametri passati
            set_query_var('oxygen_template_vars', false);
        }
    }

    /**
     * Recupera le variabili passate ad un template
     * @return array
     */

    public static function getTemplateVars() {
        $vars = get_query_var('oxygen_template_vars');
        if (count($vars) == 1) return $vars[0];
        return $vars;
    }

}

OxygenStarter::instance();