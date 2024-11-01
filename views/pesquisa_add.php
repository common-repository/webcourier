<?php
$currentUrl = $_SERVER['REQUEST_URI'];
$middleUrl = explode('&view=', $currentUrl);
$pesquisa_url = $middleUrl[0];

$admin_email = get_option('admin_email');
?>
<html>
    <div id="wizard" class="swMain" ng-app="pesquisa" ng-controller="ControllerPesquisaAdd">
        <ul>
            <li><a href="#step-1">
                    <label class="stepNumber">1</label>
                    <span class="stepDesc">
                        Passo 1<br />
                        <small>Crie sua pesquisa</small>
                    </span>
                </a></li>
            <li><a href="#step-2">
                    <label class="stepNumber">2</label>
                    <span class="stepDesc">
                        Passo 2<br />
                        <small>Crie suas questões</small>
                    </span>
                </a></li>
            <li><a href="#step-3">
                    <label class="stepNumber">3</label>
                    <span class="stepDesc">
                        Passo 3<br />
                        <small>Selecione o evento</small>
                    </span>                   
                </a></li>
            <li><a href="#step-4">
                    <label class="stepNumber">4</label>
                    <span class="stepDesc">
                        Passo 4<br />
                        <small>Pronto !</small>
                    </span>                   
                </a></li>
        </ul>
        <div id="step-1">   
            <h2 class="StepTitle widenotthatfat">Configurações de sua pesquisa</h2>
                <div class="webcourier-form-column" style="border-right: 1px solid #ccc;width:100%">
                    <div>
                        <h3>Título *</h3>
                        <input type="text" name="step1-title" class="widenotthatfat" value="" 
                               placeholder="" ng-model="vm.surveyTitle" required>
                        <h5 class="isRequired" ng-if="vm.titleRequired">Favor preencher este campo</h5>
                    </div>
                    <div class="form-search-from">
                        <h3>De *</h3>
                        <input type="text" name="step1-from" value="" ng-model="vm.surveyFrom" style="width:300px" required>
                        <h5 class="isRequired" ng-if="vm.fromRequired">Favor preencher este campo</h5>
                    </div>
                    <div>
                        <h3>Callback Url</h3>
                        <input type="text" value="" ng-model="vm.surveyUrl" style="width:300px">
                    </div>
                    <br style="clear: both">
                    <div>
                        <h3>Texto da pesquisa *</h3>
                        <textarea class="widenotthatfat" name="step1-surveytext" ng-model="vm.surveyText" rows="2" required></textarea>
                        <h5 class="isRequired" ng-if="vm.textRequired">Favor preencher este campo</h5>
                    </div>
                    <div>
                        <h3>Texto do email *</h3>
                        <textarea class="widenotthatfat" name="step1-mailtext" ng-model="vm.surveyEmail" rows="10" required></textarea>
                        <h5 class="isRequired" ng-if="vm.mailRequired">Favor preencher este campo e certificar-se que contém a palavra '{link}'</h5>
                    </div>
                </div>
        </div>
        <div id="step-2">
            <h2 class="StepTitle widenotthatfat">Tipos de questões</h2> 
            <div class="width40">
                <div>
                    <div id="add-pesquisa-buttons">
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
                    <!--Adicionando Pesquisa com Texto-->
                    <div ng-if="vm.isText">
                        <textarea class="question-title webcourier-form-control widenotthatfat" ng-model="vm.title" 
                                  placeholder="Digite o título da sua pergunta"></textarea>
                        <a class="btn btn-default" 
                           ng-click="vm.save('text')">Salvar pergunta</a>
                        <a class="btn btn-default" 
                           ng-click="vm.cancel()">Cancelar</a>
                    </div>
                    <!--Adicionando Pesquisa com Escolhas-->
                    <div ng-if="vm.isSingleChoice || vm.isMultipleChoice">
                        <textarea class="question-title webcourier-form-control widenotthatfat" ng-model="vm.title"
                                  placeholder="Digite o título da sua pergunta"></textarea>
                        <ol style="list-style:lower-alpha outside none;">
                            <!--Mostrando as opções no array-->
                            <li ng-repeat="option in vm.options track by $index">
                                <h5 ng-if="vm.optionSelected != $index" title="Editar opção" style="cursor:pointer; margin:0" ng-click="vm.editOptionButton(option,$index)">
                                    {{option}} 
                                    <i title="Remover opção" class="x-style" title="Remover opção" ng-click="vm.deleteOptionButton($index)">X</i>
                                </h5>
                                <div ng-if="vm.optionSelected == $index">
                                    <input type="text" ng-model="vm.optionSelectedModel">
                                    <button style="cursor:pointer" ng-click="vm.editOption($index)">Salvar</button>
                                </div>
                            </li>
                            <li>
                                <!--Criação da option atual-->
                                <input ng-model="vm.currentOption" ng-keypress="vm.addOption($event)"
                                       placeholder="Digite a opção"><i class="glyphicon glyphicon-plus" ng-click="vm.addOptionButton()"></i>
                            </li>
                        </ol>
                        <button type="button" class="btn btn-default" ng-click="vm.save('choice')">Salvar pergunta</button>
                        <button type="button" class="btn btn-default" ng-click="vm.cancel()">Cancelar</button>
                    </div>
                </div>
                <h5 class="isRequiredQuestion" ng-if="vm.questionRequired">Favor colocar pelo menos uma pergunta.</h5>
                <h5 class="isRequired" ng-if="vm.questionRequiredFinish">Favor concluir sua questão antes de continuar.</h5>
            </div>
            <div id="add-pesquisa-questions">
                <div class="webcourier-form-column">
                    <h3>Questões</h3>
                    <ol ng-if="vm.questions.length > 0">
                        <li ng-repeat="(idx, question) in vm.questions">
                            <h4>{{question.name}} <i class="x-style" title="Remover questão" ng-click="vm.deleteQuestionButton(idx)">X</i> </h4>
                            <ol style="list-style:lower-alpha outside none;" 
                                ng-if="question.type == 'S' || question.type == 'M'">
                                <li ng-repeat="option in question.options track by $index">
                                    <h5>{{option}}</h5>
                                </li>
                            </ol>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div id="step-3">
            <h2 class="StepTitle widenotthatfat">Configurações</h2>
            <h3>Escolha uma imagem para aparecer em sua pesquisa</h3>
            <div style="padding-top:10px">
                <input type="button" name="upload-btn" id="upload-btn" class="btn-default" value="Upload Logo">
                <input ng-model="vm.logoUrl" style="width:77% !important" type="text" name="image_url" id="image_url" class="regular-text">
            </div>
            <h3>Selecione em quais eventos enviar esta pesquisa</h3>
            <div>
                <i ng-click="changeClass('OnBuy')" ng-class="vm.classBuy" title="Compra concluída" class="dashicons-before onShop"></i>
                <i ng-click="changeClass('OnFail')" ng-class="vm.classFail" title="Compra falhada" class="dashicons-before onFail"></i>
                <i ng-click="changeClass('OnComment')" ng-class="vm.classPost" title="Comentário postado" class="dashicons-before onPost"></i>
                <i ng-click="changeClass('OnRegister')" ng-class="vm.classRegister" title="Usuário registrado" class="dashicons-before onRegister"></i>
            </div>
            <h5 class="footer"><i>Obs: Você pode pular este passo e selecionar o evento de sua pesquisa depois.</i></h5>
        </div>
        <div id="step-4">
            <h2 class="StepTitle widenotthatfat">Finalizando</h2>
            <div>
                <h3>Sua pesquisa está quase concluída, apenas alguns lembretes:</h3>
                <p><br></p>
                <h4>* Você pode ver uma preview de sua pesquisa na página de Pesquisas.</h4>
                <h4>* Você pode editar sua pesquisa a qualquer momento, desde que ela não tenha sido enviada ainda</h4>
                <h4><i>(isto ocorre para evitar que os relatórios gerados na aplicação webcourier venham de forma inesperada).</i></h4>
                <br><br><br><br><h2 style="color:red" id="required-message"></h2>
            </div>
        </div>
    </div>
</html>

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