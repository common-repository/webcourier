<?php
defined('ABSPATH') or exit;
if (!class_exists('WP_Http')) {
    include_once( ABSPATH . WPINC . '/class-http.php' );
}
$request = new WP_Http;
$connected = false;
$true = false;
$headers = array('Accept-Language' => '*');
$url = 'https://app.webcourier.com.br/api/apicheck/checkapi?tipo=1&api=##api##';
$api = urlencode($_POST['api_key']);
if($api != ''){
    $url = str_replace('##api##', $api, $url);
    $result = $request->request($url, array('headers' => $headers));
    $response = json_decode($result['body']);
}

if (isset($result['response']) && $result['response']['message'] == 'OK') {
    $true = true;
    $connected = $response->status;
    $connected ? $status = 1 : $status = 0;
    $api = $response->api;
}

$keyExists = get_option('webcourier_api_key');

if (empty($keyExists) && $true) {
    $user_user = array("api" => $api, "status" => $status);
    add_option('webcourier_api_key', http_build_query($user_user));
} else if ($true) {
    parse_str($keyExists, $user_user);
    $user_user['api'] = $api;
    $user_user['status'] = $status;
    update_option('webcourier_api_key', http_build_query($user_user));
} else if (!empty($keyExists)) {
    parse_str($keyExists, $user_user);
    $api = $user_user['api'];
    $status = $user_user['status'];
}
?>
<div id="webcourier-admin" class="webcourier-settings">

    <div class="row">
        <div class="col-md-12">

            <h1 class="webcourier-email-marketing-page-title">Configurações Gerais</h1>

            <h2 style="display: none;"></h2>
            <?php settings_errors(); ?>

            <form method="post">

                <h3> Configurações API Key WebCourier </h3>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            Status
                        </th>
                        <td>
                            <?php if ($status == 1) { ?>
                                <span class="status positive">CONECTADO</span>
                            <?php } else { ?>
                                <span class="status negative">NÃO CONECTADO</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            Chave API
                        </th>
                        <td>
                            <input type="text" name='api_key' class="widefat" placeholder="Sua chave API" id="webcourier-api-key" value="<?php echo $api ?>">
                            <p class="help">
                                A chave API para se conectar com a sua conta no WebCourier
                                <a target="_blank" href="https://app.webcourier.com.br/admlogin/index">Pegue sua chave API aqui.</a>
                            </p>
                        </td>
                    </tr>

                </table>

                <?php submit_button('Salvar alterações'); ?>

            </form>
        </div>
    </div>
</div>
