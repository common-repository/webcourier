<?php

defined('ABSPATH') or exit;
include_once(WEBCOURIER_PLUGIN_DIR . '/webcourier.php');
$api_status = api_key();
$response = get_pesquisa();
$pesquisas = $response->pesquisas;
if ($pesquisas) {
    $pesquisas_name = array_map(function($p) {
        return $p->name;
    }, $response->pesquisas);
    $pesquisas_id = array_map(function($p) {
        return $p->survey_idx;
    }, $response->pesquisas);
}
if ($api_status['status'] == 0) {
    include_once('pesquisa_api_error.php');
} else if ($api_status['status'] == 1) {
    $connected = $currentEvento = false;
    if(isset($_POST['respostaidlist'])){
        $survey_idx = intval($_POST['respostaidlist']);
        $evento = $_POST['evento'];
        $meta_value = get_option('webcourier_api_key');
        parse_str($meta_value, $results);
        $results[$evento] = $survey_idx;
        update_option('webcourier_api_key',http_build_query($results));
    }
    if (isset($_POST['respostaid'])) {
        $survey_idx = intval($_POST['respostaid']);
        $eventos = $_POST['evento'];
        $meta_value = get_option('webcourier_api_key');
        parse_str($meta_value, $results);
        foreach ($eventos as $key => $evento) {
            if ($evento == 'true') {
                $results[$key] = $survey_idx;
            }
        }
        update_option('webcourier_api_key', http_build_query($results));
    }
    if (!$connected) {
        $survey_idx = 9999999999;
    }
    if (isset($_GET['view']) && isset($_GET['search'])) {
        include_once('pesquisa_edit.php');
    } else if (isset($_GET['view'])) {
        include_once('pesquisa_add.php');
    } else {
        include_once('pesquisa_list.php');
    }
}
?>