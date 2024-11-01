<?php

$connected = $currentEvento = false;
$currentUrl = $_SERVER['REQUEST_URI'];
$middleUrl = explode('&view=', $currentUrl);
$pesquisa_url = $middleUrl[0];
$survey_idx = intval($_GET['search']);
$meta_value = get_option('webcourier_api_key');

parse_str($meta_value, $results);
if(intval($results['sendOnBuy']) == $survey_idx)
{
    $currentEvento = "OnBuy";
}
if(intval($results['sendOnFail']) == $survey_idx)
{
    $currentEvento = "OnFail";
}
if(intval($results['sendOnComment']) == $survey_idx)
{
    $currentEvento = "OnComment";
}
if(intval($results['sendOnRegister']) == $survey_idx)
{
    $currentEvento = "OnRegister";
}
$request = new WP_Http;
$headers = array('Accept-Language' => '*');
$url = "https://app.webcourier.com.br/api/apicheck/getsearch?search=$survey_idx";
$result = $request->request($url, array('headers' => $headers));
$response = json_decode($result['body']);
if (isset($result['response']) && $result['response']['message'] == 'OK') {
    $connected = true;
}
if (!$connected) {
    $survey_idx = 9999999999;
}
$admin_email = get_option('admin_email');
?>
<div class="wrap webcourier-search" ng-app="pesquisa">

    <div class="row" ng-controller="ControllerPesquisaAdd">

        <!-- Main Content -->
        <div class="main-content col col-10">

            <h1 class="page-title">Adicionar Pesquisa</h1>

            <h2 style="display: none;"></h2><?php // fake h2 for admin notices                 ?>

            <form ng-submit="saveQuestions()">

                <div class="webcourier-form-column" style="border-right: 1px solid #ccc; height:700px;">
                    <h3>Selecione em quais eventos enviar esta pesquisa</h3>
                    <div>
                        <select ng-model="vm.selectEvent">
                            <option value="0">Selecione...</option>
                            <option value="OnBuy">Quando o status da compra for concluído</option>
                            <option value="OnFail">Quando o status da compra for falhado</option>
                            <option value="OnComment">Quando um usuário comentar em um post</option>
                            <option value="OnRegister">Quando um usuário se registrar em seu site</option>
                        </select>
                    </div>
                    <div>
                        <h3>Título *</h3>
                        <input type="text" class="widefat" value="" 
                               placeholder="" ng-model="vm.surveyTitle" required>
                    </div>
                    <div class="form-search-from">
                        <h3>De *</h3>
                        <input type="text" value="" ng-model="vm.surveyFrom" style="width:300px" required>
                    </div>
                    <div>
                        <h3>Callback Url</h3>
                        <input type="text" value="" ng-model="vm.surveyUrl" style="width:300px">
                    </div>
                    <div>
                        <h3>Texto da pesquisa *</h3>
                        <textarea class="widefat" ng-model="vm.surveyText" rows="2" required></textarea>
                    </div>
                    <div>
                        <h3>Texto do email *</h3>
                        <textarea class="widefat" ng-model="vm.surveyEmail" rows="10" required></textarea>
                    </div>
                    <div style="padding-top:10px">
                        <input type="button" name="upload-btn" id="upload-btn" class="btn-default" value="Upload Logo">
                        <input ng-model="vm.logoUrl" style="width:80%" type="text" name="image_url" id="image_url" class="regular-text">
                    </div>
                    <div>
                        <h3>Tipos de questões</h3>
                        <div id="add-pesquisa-buttons-edit">
                            <a class="btn btn-default btn-text" ng-click="vm.add('text')">
                                Texto
                            </a>
                            <a class="btn btn-default btn-only-choices" ng-click="vm.add('single')">
                                Única escolha
                            </a>
                            <a class="btn btn-default btn-multiple-choices" ng-click="vm.add('multiple')">
                                Múltipla escolha
                            </a>
                        </div>
                    </div>
                    <!--Adicionando Pesquisa com Texto-->
                    <div ng-if="vm.isText">
                        <textarea class="question-title form-control" ng-model="vm.title" 
                                  placeholder="Digite o título da sua pergunta"></textarea>
                        <a class="btn btn-default" 
                           ng-click="vm.save('text')">Salvar pergunta</a>
                    </div>
                    <!--Adicionando Pesquisa com Escolhas-->
                    <div ng-if="vm.isSingleChoice || vm.isMultipleChoice">
                        <textarea class="question-title form-control" ng-model="vm.title"
                                  placeholder="Digite o título da sua pergunta"></textarea>
                        <ol style="list-style:lower-alpha outside none;">
                            <!--Mostrando as opções no array-->
                            <li ng-repeat="option in vm.options track by $index">
                                <h5>{{option}}</h5>
                            </li>
                            <li>
                                <!--Criação da option atual-->
                                <input ng-model="vm.currentOption" ng-keypress="vm.addOption($event)"
                                       placeholder="Digite a opção"><i class="glyphicon glyphicon-plus" ng-click="vm.addOptionButton()"></i>
                            </li>
                        </ol>
                        <button type="button" class="btn btn-default" ng-click="vm.save('choice')">Salvar Questão</button>
                    </div>
                    <input type="hidden" ng-model="vm.api">
                    <div style="float:left">
                    <?php submit_button('Salvar Pesquisa', 'primary', 'save-search'); ?>
                    </div>
                    <!-- Listar as questões -->
                </div>
            </form>
            <div class="webcourier-form-column">
                <h3>Questões</h3>
                <ol ng-if="vm.questions.length > 0">
                    <li ng-repeat="(idx, question) in vm.questions">
                        <h4>{{question.name}} <span class="x-style" ng-click="vm.deleteQuestionButton(idx)">X</span> </h4>
                        <ol style="list-style:lower-alpha outside none;" 
                            ng-if="question.type == 'S' || question.type == 'M'">
                            <li ng-repeat="option in question.options track by $index">
                                <h5>{{option}}</h5>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>
            
        </div><!-- / Main content -->

        <!-- Sidebar -->
        <!--        <div class="sidebar col col-2">
        <?php //include WEBCOURIER_PLUGIN_DIR . 'includes/views/parts/admin-sidebar.php';        ?>
                </div>-->

    </div>
    <script>
                var apiStatus = '<?= $api_status['api']; ?>';
                var response_search = <?= json_encode($response->pesquisaRow[0]); ?>;
                var questions_search = <?= json_encode($response->questions); ?>;
                var connected = <?= $connected ? 'true' : 'false' ?>;
                var id = <?= $survey_idx; ?>;
                var url = '<?= $pesquisa_url; ?>';
                var currentEvent = '<?= $currentEvento ? $currentEvento : '0'; ?>';
                var admin_email = '<?= $admin_email ? $admin_email : '';  ?>';
    </script>

</div>