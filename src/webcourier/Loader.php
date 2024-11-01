<?php

class Loader {

    public function showPages() {
        // Hook for adding admin menus
        add_action('admin_menu', 'webcourier_pages');

        // action function for above hook
        function webcourier_pages() {

            // Add a new top-level menu (ill-advised):
            add_menu_page('WebCourier', 'WebCourier', 'manage_options', 'mt-top-level-handle', 'mt_toplevel_page');

            // Add a submenu to the custom top-level menu:
            
            add_submenu_page('mt-top-level-handle', 'Pesquisas', 'Pesquisas', 'manage_options', 'sub-page-pesquisa', 'mt_sublevel_pesquisa');
            
            add_submenu_page('mt-top-level-handle', 'Configurações', 'Configurações', 'manage_options', 'sub-page-config', 'mt_sublevel_config');

//            add_submenu_page('mt-top-level-handle', 'Sobre', 'Sobre', 'manage_options', 'sub-page-sobre', 'mt_sublevel_sobre');
        }

        // mt_toplevel_page() displays the page content for the custom WebCourier menu
        function mt_toplevel_page() {
            do_shortcode('[webcourier_page_config]');
        }

        // mt_sublevel_page() displays the page content for the first submenu
        // of the custom WebCourier menu
        function mt_sublevel_pesquisa() {
            do_shortcode('[webcourier_page_pesquisa]');
        }
        
        function mt_sublevel_config(){
            do_shortcode('[webcourier_page_configuracoes]');
        }

//        function mt_sublevel_sobre() {
//            echo "<h2>Sobre</h2>";
//        }

    }

    public function doShortCodes() {
        
        add_shortcode('form_webcourier_settings_shortcode', 'get_form_webcourier_settings_shortcode');

        function get_form_webcourier_settings_shortcode() {
            include(WEBCOURIER_PLUGIN_DIR . 'inc/form_get_email_newsletter.php');
        }

        add_shortcode('form_webcourier_newsletter_shortcode', 'get_form_webcourier_newsletter_shortcode');

        function get_form_webcourier_newsletter_shortcode() {
            include(WEBCOURIER_PLUGIN_DIR . 'inc/form_get_email_newsletter.php');
        }

        add_shortcode('webcourier_page_config', 'webcourier_get_page_config');

        function webcourier_get_page_config() {
            include(WEBCOURIER_PLUGIN_DIR . '/views/geral_webcourier.php');
        }

        add_shortcode('webcourier_page_pesquisa', 'webcourier_get_page_pesquisa');

        function webcourier_get_page_pesquisa() {
            include(WEBCOURIER_PLUGIN_DIR . '/views/pesquisa.php');
        }
        
        add_shortcode('webcourier_page_configuracoes', 'webcourier_get_page_configuracoes');
        
        function webcourier_get_page_configuracoes(){
            include(WEBCOURIER_PLUGIN_DIR . '/views/config.php');
        }

    }

    public function loadIcon() {

        function replace_admin_menu_icons_css() {
            ?>
            <style>
                #adminmenu #toplevel_page_mt-top-level-handle div.wp-menu-image::before {
                    content: '\f465';
                }
            </style>
            <?php

        }

        add_action('admin_head', 'replace_admin_menu_icons_css');

        /**
         * Register style sheet.
         */
        function register_webcourier_styles() {
            wp_register_style('webcourier', plugins_url('webcourier/css/styles.css'));
            wp_enqueue_style('webcourier');
        }

    }
    

}

