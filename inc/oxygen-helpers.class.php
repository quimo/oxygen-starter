<?php

class OxygenHelpers {

    const READING_TIME = 300;

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        //account analytics
        add_action('wp_head', array('OxygenHelpers', 'addAnalyticsCode'), 1);

        //tempo di lettura
        add_shortcode('oxs_article_reading_time', array('OxygenHelpers', 'getArticleReadingTime'));
    }

    /**
     * restituisce il tempo di lettura dell'articolo
     * corrente in minuti
     */
    public static function getArticleReadingTime() {
        global $post;
        $words = str_word_count($post->post_content);
        $time_to_read = $words / self::READING_TIME;
        echo number_format($time_to_read, 1) . ' minuti';
    }

    public static function addAnalyticsCode() {
        if (!is_user_logged_in()) {
            ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo OXYGEN_STARTER_UA ?>"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo OXYGEN_STARTER_UA ?>', { 'anonymize_ip': true });
            </script>
            <?php
        }
    }

}

OxygenHelpers::instance();