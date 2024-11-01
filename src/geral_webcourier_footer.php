<?php

if (!class_exists('WP_Http')) {
    include_once( ABSPATH . WPINC . '/class-http.php' );
}

global $wpdb;
$request = new WP_Http;
$connected = false;
$true = false;
$headers = array( 'Accept-Language' => '*' );
$url = 'https://app.webcourier.com.br/api/apicheck/checkapi?api=&&api&&';
$api = urlencode($_POST['api_key']);
$url = str_replace('&&api&&', $api, $url);
$result = $request->request( $url , array('headers' => $headers ) );
$response = json_decode($result['body']);
if (isset($result['response']) && $result['response']['message'] == 'OK') {
    $true = true;
    $connected = $response->status;
    $connected ? $status = 1 : $status = 0;
    $api = $response->api;
}
$user_ID = get_current_user_id();
$keyExists = $wpdb->get_results($wpdb->prepare(
                "
		SELECT meta_value, umeta_id
		FROM {$wpdb->prefix}usermeta
		WHERE user_id = %s AND meta_key = 'webcourier_api_key'
	", $user_ID
        ));
if (empty($keyExists) && $true) {
    $wpdb->insert(
            "{$wpdb->prefix}usermeta", array(
        'user_id' => $user_ID,
        'meta_key' => 'webcourier_api_key',
        'meta_value' => 'api:' . $api . 'status:' . $status,
            ), array(
        '%s',
        '%s',
        '%s'
            )
    );
} else if ($true) {
    $wpdb->update(
            "{$wpdb->prefix}usermeta", array(
        'user_id' => $user_ID,
        'meta_key' => 'webcourier_api_key',
        'meta_value' => 'api:' . $api . 'status:' . $status,
            ), array('umeta_id' => $keyExists[0]->umeta_id), array(
        '%s',
        '%s',
        '%s'
            ), array('%s')
    );
    $keyExists[0]->meta_value = 'api:' . $api . 'status:' . $status;
}
$api_status = explode('api:', $keyExists[0]->meta_value);
$api_status = explode('status:', $api_status[1]);
$api = $api_status[0];
$status = $api_status[1];

