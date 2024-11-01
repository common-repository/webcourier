<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventHandler
 *
 * @author dgledson.rabelo
 */
//do_shortcode("[send_pesquisa order_id=$order_id survey_idx=80]");

function send_search_after_purchase_is_done_woocommerce($order_id) {
    $params = array("Produto" => $order_id);
    $searchType = "OnBuy";
    sendRequest($params,$searchType);
}

add_action('woocommerce_order_status_completed', 'send_search_after_purchase_is_done_woocommerce');

function send_search_after_purchase_is_failed_woocommerce($order_id) {
    $params = array("Produto" => $order_id);
    $searchType = "OnFail";
    sendRequest($params,$searchType);     
}

add_action('woocommerce_order_status_failed', 'send_search_after_purchase_is_failed_woocommerce');

function send_search_after_comment_is_post() {
    $params = array("Comentário" => 777);
    $searchType = "OnComment";
    sendRequest($params,$searchType);
}

add_action('comment_post', 'send_search_after_comment_is_post');

function send_search_after_user_register() {
    $params = array("Register" => 777);
    $searchType = "OnRegister";
    sendRequest($params,$searchType);
}

add_action('user_register', 'send_search_after_user_register');

function sendRequest($params, $searchType) {
    $user_info = wp_get_current_user();
//    $user_id_from_email = get_user_by('ID', get_current_blog_id());
    $request = new WP_Http;
    $meta_value = get_option('webcourier_api_key');
    parse_str($meta_value, $user_user);
    if (!empty($user_user)) {
        $api = $user_user['api'];
        switch($searchType)
        {
            case 'OnBuy':
                $survey_idx = $user_user['sendOnBuy'];
                break;
            case 'OnFail':
                $survey_idx = $user_user['sendOnFail'];
                break;
            case 'OnComment':
                $survey_idx = $user_user['sendOnComment'];
                break;
            case 'OnRegister':
                $survey_idx = $user_user['sendOnRegister'];
                break;
        }
    }
    $to = array();
    if (firstKey($params) == 'Produto') {
        $order = new WC_Order(firstValue($params));
        array_push($to, $order->billing_email);
        $items = $order->get_items();
        foreach ($items as $item) {
            $produtos[$item['name']] = intval($item['product_id']);
        };
    } else {
        array_push($to, $user_info->user_email);
    }
    $body = array(
        'sid' => $survey_idx,
        'to' => $to,
        'api' => $api,
        'params' => $params,
        'produtos' => $produtos
    );
    $url = "https://app.webcourier.com.br/api/survey/send";
    $headers = array('Accept-Language' => '*');
    $result = $request->request($url, array('method' => 'POST', 'body' => $body,
        'headers' => $headers));
    $response = json_decode($result['body']);
}

function firstKey($params) {
    foreach ($params as $nome => $id)
        return $nome;
}

function firstValue($params) {
    foreach ($params as $id)
        return $id;
}
