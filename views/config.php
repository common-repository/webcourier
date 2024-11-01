<?php
defined('ABSPATH') or exit;
include_once(WEBCOURIER_PLUGIN_DIR . '/webcourier.php');
$api_status = api_key();
$configs = get_config(1);
if ($api_status['status'] == 0) {
    include_once('pesquisa_api_error.php');
    die();
}
?>

<div class="wrap webcourier-search" ng-app="pesquisa">

        <!--<h1 class="page-title">Configurações</h1>-->

        <h2 style="display:none"></h2> 

        <div>
            <form id='sendForm' method='POST'>
                <div>
                    <h4> Escolha o estilo de como suas questões vão aparecer no email</h4>
                    <input type="radio" name="option" value="inline" <?= $configs['inline']?> > Por linha <br>
                    <input type="radio" name="option" value="block" <?= $configs['block']?> > Por coluna
                </div>

                <div>
                    <h4> Como você prefere que suas questões sejam listadas no email ? </h4>
                    <input type="radio" name="option2" value="numbered" <?= $configs['numbered']?> > Numeradas <br>
                    <input type="radio" name="option2" value="alphabetically" <?= $configs['alphabetically']?> > Alfabeticamente <br>
                    <input type="radio" name="option2" value="nolist" <?= $configs['nolist']?> > Sem listagem
                </div>
                <?php submit_button('Salvar alterações','primary','submit-config');?>
            </form>
        </div>
    </div>

<script>
    var api = '<?= $api_status['api'] ?>';
</script>
