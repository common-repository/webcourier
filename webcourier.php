<?php

/*
  Plugin Name: Webcourier Plugin
  Plugin URI: https://www.webcourier.com.br/
  Description: Plugin utilizado para o envio e relatório de pesquisas de satisfação
  Author: WebCourier
  Version: 2.3
  Stable tag: 2.3
  Author URI: https://www.webcourier.com.br/
  License: GPLv2
  Domain Path: languages/
  Copyright: © 2015 Webcourier
  Tested up to: 4.6
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if (!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy();
}

//path to autoloader
define('WEBCOURIER_PLUGIN_DIR', dirname(__FILE__) . '/');

$pos1 = stripos($_SERVER['QUERY_STRING'], 'page=sub-page-pesquisa');

//if(stripos($_SERVER['QUERY_STRING'], 'page=sub-page-pesquisa') !== false){
//    var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA   ' . $_SERVER['QUERY_STRING'] . '   ' .  stripos($_SERVER['QUERY_STRING'], 'page=sub-page-pesquisa'));
//} else {
//    var_dump('BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB   ' . stripos($_SERVER['QUERY_STRING'], 'page=mt-top-level-handle'));
//}

//loading scripts and js only on plugin's pages
if((stripos($_SERVER['QUERY_STRING'], 'page=mt-top-level-handle') !== false) || (stripos($_SERVER['QUERY_STRING'], 'page=sub-page-pesquisa') !== false) || 
        (stripos($_SERVER['QUERY_STRING'], 'page=sub-page-config') !== false)) {
    
    wp_enqueue_script('jquery');
    wp_enqueue_style('webcourier-general-settings', plugins_url('/css/styles.css', __FILE__), '', '', false);

    wp_enqueue_script('webcourier-pesquisa-add', plugins_url('/js/pesquisa-add.js', __FILE__), array('jquery'), '', false);
    wp_enqueue_script('webcourier-angular', plugins_url('/js/angular.min.js', __FILE__), '', '', false);
    wp_enqueue_script('webcourier-angular-controller-pesquisa-add', plugins_url('/js/ControllerPesquisaAdd.js', __FILE__), '', '', false);
    wp_enqueue_script('webcourier-angular-controller-pesquisa-list', plugins_url('/js/ControllerPesquisaList.js', __FILE__), '', '', false);
    wp_enqueue_script('webcourier-wizard', plugins_url('/js/jquery.smartWizard.js', __FILE__), '', '', false);
    wp_enqueue_script('webcourier-wizard-page-add', plugins_url('/js/pesquisaAddWizard.js', __FILE__), '', '', false);
    wp_enqueue_script('webcourier-sweet-alert', plugins_url('/js/sweetalert.min.js', __FILE__), '', '', false);
    wp_localize_script('webcourier-angular-controller', 'edit_search', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('webcourier-config', plugins_url('/js/config_ajax.js', __FILE__), array('jquery'), '', false);
}



function api_key() {
    $meta_value = get_option('webcourier_api_key');
    parse_str($meta_value, $user_user);
    if (!empty($user_user)) {
        $api = $user_user['api'];
        $status = $user_user['status'];
        $api_status["api"] = $api;
        $api_status["status"] = $status;
    }
    return $api_status;
}

function get_pesquisa() {
    $request = new WP_Http;
    $meta_value = get_option('webcourier_api_key');
    parse_str($meta_value, $user_user);
    $api = urlencode($user_user['api']);
    $headers = array('Accept-Language' => '*');
    $url = "https://app.webcourier.com.br/api/apicheck/checkapi?api=$api&tipo=1";
    $result = $request->request($url, array('headers' => $headers));
    $response = json_decode($result['body']);
    return $response;
}

function get_config($flag) {
    $request = new WP_Http;
    $meta_value = get_option('webcourier_api_key');
    parse_str($meta_value, $user_user);
    $api = urlencode($user_user['api']);
    $headers = array('Accept-Language' => '*');
    $url = "https://app.webcourier.com.br/api/survey/getCheckedConfig?api=$api";
    $result = $request->request($url, array('headers' => $headers));
    $response = json_decode($result['body']);
    $configs = $response->message;
    if (!is_null($configs)) {
        foreach ($configs as $id => $config) {
            if ($id != "cliente_idx") {
                $configs_checked[$config] = "checked";
            }
        }
    }
    return $flag ? $configs_checked : $configs;
}

add_filter('admin_footer_text', 'nao_curto_open_source');

function nao_curto_open_source() {
    echo '';
}

add_filter('update_footer', 'nao_curto_open_source_versao', 9999);

function nao_curto_open_source_versao() {
    return '';
}

//load autoloader
require_once WEBCOURIER_PLUGIN_DIR . 'src/webcourier/Loader.php';

$class_methods = get_class_methods('loader');
$loader = new Loader();

foreach ($class_methods as $function) {
    call_user_func(array($loader, $function));
}

function loadMedia() {
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'loadMedia');

add_shortcode('send_pesquisa', 'webcourier_send_pesquisa');

function webcourier_send_pesquisa($params) {
    $request = new WP_Http;
//    $user_id_from_email = get_user_by('ID', get_current_blog_id());
    // $meta_value = get_option('webcourier_api_key');
    // parse_str($meta_value, $user_user);
    // if (!empty($user_user)) {
    //     $api = $user_user['api'];
    // }
    $order_id = intval($params['order_id']);
    $order = new WC_Order($order_id);
    $items = $order->get_items();
    foreach ($items as $item) {
        $produtos[$item['name']] = intval($item['product_id']);
    };
    $to = array();
    array_push($to, $order->billing_email);
    $body = array(
        'sid' => intval($params['survey_idx']),
        'to' => $to,
        'params' => $params,
        'api' => '07a/mdf5YmsnI2v/P8eRAw/rxltVETW6FRdo9KW5kG4=',
        'produtos' => $produtos,
    );
    $url = "https://app.webcourier.com.br/api/survey/send";
    $headers = array('Accept-Language' => '*');
    $result = $request->request($url, array('method' => 'POST', 'body' => $body,
        'headers' => $headers));
    $response = json_decode($result['body']);
}

//manage requests
include_once(WEBCOURIER_PLUGIN_DIR . 'src/EventHandler.php');


