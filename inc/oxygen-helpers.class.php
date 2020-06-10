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
        
        //analytics
        add_action('wp_head', array('OxygenHelpers', 'addAnalyticsCode'), 1);

        //facebook pixel
        add_action('wp_head', array('OxygenHelpers', 'addFacebookPixelCode'));

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

    function addFacebookPixelCode() {
        if (!is_user_logged_in()) {
            ?>
             <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo OXYGEN_STARTER_FB_PIXEL ?>');
            fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo OXYGEN_STARTER_FB_PIXEL ?>&ev=PageView&noscript=1"
            /></noscript>
            <script>
            // tracciamento di un evento custom
            /*
            jQuery(document).ready(function($) {
                if ($('#ELEMENT_ID').length) {
                    $('#ELEMENT_ID').on('click', function() {
                        fbq('trackCustom', 'EVENT NAME');
                    });
                }
            });
            */
            </script>
            <!-- End Facebook Pixel Code -->
            <?php
        }
    }

}

OxygenHelpers::instance();