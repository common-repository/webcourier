<?php
$currentUrl = $_SERVER['REQUEST_URI'];
$url = $currentUrl . '&view=pesquisa-add';
$sendOnBuy = $sendOnFail = $sendOnComment = 0;
include_once(WEBCOURIER_PLUGIN_DIR . '/webcourier.php');
$configs = get_config(0);
if (isset($_POST['removeid'])) {
    $removeid = $_POST['removeid'];
    $meta_value = get_option('webcourier_api_key');
    parse_str($meta_value, $values);
    foreach ($values as $value)
    {
        if($value == $removeid){
            unset($values[$value]);
        }
    }
    update_option('webcourier_api_key', http_build_query($values));
}
$events = get_option('webcourier_api_key');
parse_str($events, $events);
$events['sendOnBuy'] ? $sendOnBuy = $events['sendOnBuy'] : $sendOnBuy = 0;
$events['sendOnFail'] ? $sendOnFail = $events['sendOnFail'] : $sendOnFail = 0;
$events['sendOnComment'] ? $sendOnComment = $events['sendOnComment'] : $sendOnComment = 0;
$events['sendOnRegister'] ? $sendOnRegister = $events['sendOnRegister'] : $sendOnRegister = 0;
?>
<div>

    <div>

        <!-- Main Content -->
        <div class="main-content">

            <h1 class="page-title">Pesquisas</h1>

            <h2 style="display: none;"></h2><?php // fake h2 for admin notices      ?>

            <!-- Wrap entire page in <form> -->
            <form action="<?= $url ?>" method="POST" ng-app="pesquisa-list" ng-controller="ControllerPesquisaList">

                <input type="hidden" name="_webcourier_action" value="add_form" />
                <?php wp_nonce_field(); ?>

                <div>
                    <h3>
                        <label>
                            Lista de Pesquisas
                        </label>
                    </h3>
                    <input type="text" name="webcourier_form[name]" ng-model="vm.searchField"
                           class="widefat" value="" spellcheck="true" autocomplete="off" placeholder="Pesquisar..." style="margin-bottom: 15px">
                </div>
                <div>
                    <table class="webcourier-table table table-condensed table-striped">
                        <thead>
                            <tr colspan="3">
                                <th class="th">Id</th>
                                <th class="th">Nome</th>
                                <th class="th" style="text-align:center">Evento Associado</th>
                                <th class="th" style="text-align:center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(idx,x) in vm.pesquisas track by $index" ng-if="filterByName(x.name)">
                                <td>{{x.survey_idx}}</td>
                                <td>{{x.name}}</td>
                                <!-- Evento associado -->
                                <td class="width20">
                                    <div ng-class="x.survey_idx == <?= $sendOnBuy ?> ? 'darkened' : 'default' ">
                                        <i ng-click="saveSelectEvent('sendOnBuy',x.survey_idx,x.survey_idx == <?= $sendOnBuy ?>)" title="Compra concluída" class="dashicons-before onShop"></i></div>
                                    <div ng-class="x.survey_idx == <?= $sendOnFail ?> ? 'darkened' : 'default' ">
                                        <i ng-click="saveSelectEvent('sendOnFail',x.survey_idx,x.survey_idx == <?= $sendOnFail ?>)" title="Compra falhada" class="dashicons-before onFail"></i></div>
                                    <div ng-class="x.survey_idx == <?= $sendOnComment ?> ? 'darkened' : 'default' ">
                                        <i ng-click="saveSelectEvent('sendOnComment',x.survey_idx,x.survey_idx == <?= $sendOnComment ?>)" title="Comentário postado" class="dashicons-before onPost"></i></div>
                                    <div ng-class="x.survey_idx == <?= $sendOnRegister ?> ? 'darkened' : 'default'" >
                                        <i ng-click="saveSelectEvent('sendOnRegister',x.survey_idx,x.survey_idx == <?= $sendOnRegister ?>)" title="Usuário registrado" class="dashicons-before onRegister"></i></div>
                                </td>
                                <!-- Mostrar o botão de editar se a campanha ainda não tiver sido enviada -->
                                <td class="width20">
                                    <i ng-if="x.boleano == 'false'" 
                                       title="Editar" ng-click="edit_search(x.survey_idx)"
                                       class="dashicons-before edit-style"></i>
                                    <i ng-if="x.boleano == 'true'" class="dashicons-before report-style"
                                       title="Relatório" ng-click="report_search(x.survey_idx)"></i>
                                    <i class="dashicons-before preview-style" title="Preview" ng-click="preview_search(x.survey_idx)"></i>
                                    <i class="dashicons-before copy-style" title="Copiar" ng-click="copy_search(x.survey_idx)"></i>
                                    <i title="Deletar" ng-click="delete_search(x.survey_idx, idx)"
                                       class="dashicons-before delete-style"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                                <?php submit_button('Criar Pesquisa'); ?>

            </form><!-- Entire page form wrap -->
        </div><!-- / Main content -->
    </div>

</div>

<script>
    var pesquisas = <?= json_encode($pesquisas); ?>;
    var url = '<?= $url; ?>';
    var api = '<?= $api_status['api']; ?>';
    var displayType = '<?= $configs->displayType; ?>';
    var listType = '<?= $configs->listType; ?>';
    var currentEvent = '<?= $currentEvento ? $currentEvento : '0'; ?>';
</script>