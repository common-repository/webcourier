angular.module('pesquisa', [])
        .controller('ControllerPesquisaAdd', function ($scope, $http) {
            $scope.vm = {};

            $scope.vm.search_form = response_search == {} ? false : response_search;
            $scope.vm.search_form_questions = questions_search == {} ? false : questions_search;
            $scope.vm.surveyTitle = "";
            $scope.vm.surveyFrom = admin_email;
            $scope.vm.surveyUrl = "";
            $scope.vm.surveyText = "";
            $scope.vm.logoUrl = "";
            $scope.vm.surveyEmail = "Estamos realizando uma pesquisa e agradecemos sua resposta.\n\n"
                    + "Este é o link da pesquisa:\n"
                    + "{link}\n\n"
                    + "Este link está vinculado, de maneira exclusiva, a esta pesquisa e ao seu endereço de email. Não encaminhe esta mensagem.\n\n\n"
                    + "Agradecemos sua participação!";
            $scope.vm.title = "";
            $scope.vm.api = apiStatus;
            $scope.vm.currentOption = "";
            $scope.vm.options = [];
            $scope.vm.titleRequired = false;
            $scope.vm.fromRequired = false;
            $scope.vm.textRequired = false;
            $scope.vm.mailRequired = false;
            $scope.vm.questionRequired = false;
            $scope.vm.questionRequiredFinish = false;
            $scope.vm.optionSelected = -1;
            $scope.vm.optionSelectedModel = '';

            $scope.vm.classBuy = 'default';
            $scope.vm.classFail = 'default';
            $scope.vm.classPost = 'default';
            $scope.vm.classRegister = 'default';

            $scope.vm.selectEvent = currentEvent;
            $scope.vm.isText = false;
            $scope.vm.isSingleChoice = false;
            $scope.vm.isMultipleChoice = false;

            $scope.vm.questions = [];
            $scope.vm.eventos = {};

            var id_connected;

            if (connected) {
                $scope.vm.surveyTitle = response_search.name;
                $scope.vm.surveyFrom = response_search.from;
                $scope.vm.surveyUrl = response_search.response_url;
                $scope.vm.surveyText = response_search.survey_text;
                $scope.vm.surveyEmail = response_search.email_text;
                $scope.vm.logoUrl = 'https://app.webcourier.com.br/templates/srv_source_' + response_search.survey_idx + '/' + response_search.logo_img;
                $scope.vm.questions = questions_search;
            }

            $scope.changeClass = function (evento) {
                switch (evento) {
                    case 'OnBuy':
                        if ($scope.vm.classBuy == 'default') {
                            $scope.vm.classBuy = 'darkened';
                            $scope.vm.eventos['sendOnBuy'] = true;
                        } else {
                            $scope.vm.classBuy = 'default';
                            $scope.vm.eventos['sendOnBuy'] = false;
                        }
                        console.log($scope.vm.eventos);
                        break;
                    case 'OnFail':
                        if ($scope.vm.classFail == 'default') {
                            $scope.vm.classFail = 'darkened';
                            $scope.vm.eventos['sendOnFail'] = true;
                        } else {
                            $scope.vm.classFail = 'default';
                            $scope.vm.eventos['sendOnFail'] = false;
                        }
                        console.log($scope.vm.eventos);
                        break;
                    case 'OnComment':
                        if ($scope.vm.classPost == 'default') {
                            $scope.vm.classPost = 'darkened';
                            $scope.vm.eventos['sendOnComment'] = true;
                        } else {
                            $scope.vm.classPost = 'default';
                            $scope.vm.eventos['sendOnComment'] = false;
                        }
                        console.log($scope.vm.eventos);
                        break;
                    case 'OnRegister':
                        if ($scope.vm.classRegister == 'default') {
                            $scope.vm.classRegister = 'darkened';
                            $scope.vm.eventos['sendOnRegister'] = true;
                        } else {
                            $scope.vm.classRegister = 'default';
                            $scope.vm.eventos['sendOnRegister'] = false;
                        }
                        console.log($scope.vm.eventos);
                        break;
                }
            }

            $scope.vm.isRequired = function (message) {
                $scope.$apply()
                {
                    switch (message) {
                        case 'titleRequired':
                            $scope.vm.titleRequired = true;
                            break;
                        case 'fromRequired':
                            $scope.vm.fromRequired = true;
                            break;
                        case 'textRequired':
                            $scope.vm.textRequired = true;
                            break;
                        case 'mailRequired':
                            $scope.vm.mailRequired = true;
                            break;
                        case 'questionRequired':
                            $scope.vm.questionRequired = true;
                            break;
                        case 'questionRequiredFinish':
                            $scope.vm.questionRequiredFinish = true;
                            break;
                    }
                }
            };

            $scope.vm.isNotRequired = function (message) {
                $scope.$apply()
                {
                    switch (message) {
                        case 'titleRequired':
                            $scope.vm.titleRequired = false;
                            break;
                        case 'fromRequired':
                            $scope.vm.fromRequired = false;
                            break;
                        case 'textRequired':
                            $scope.vm.textRequired = false;
                            break;
                        case 'mailRequired':
                            $scope.vm.mailRequired = false;
                            break;
                        case 'questionRequired':
                            $scope.vm.mailRequired = false;
                            break;
                    }
                }
            };

            $scope.vm.add = function (type) {
                $scope.vm.isText = false;
                $scope.vm.isSingleChoice = false;
                $scope.vm.isMultipleChoice = false;
                $scope.vm.questionRequired = false;
                switch (type) {
                    case 'text':
                        $scope.vm.isText = true;
                        break;
                    case 'single':
                        $scope.vm.isSingleChoice = true;
                        break;
                    case 'multiple':
                        $scope.vm.isMultipleChoice = true;
                        break;
                }
            };

            $scope.vm.save = function (type) {
                switch (type) {
                    case 'text':
                        if ($scope.vm.title.length > 0)
                        {
                            $scope.vm.questions.push({name: $scope.vm.title, type: 'T'});
                            $scope.vm.title = "";
                            $scope.vm.isText = false;
                        }
                        break;
                    case 'choice':
                        if ($scope.vm.isSingleChoice && $scope.vm.title.length > 0 && $scope.vm.options.length > 0) {
                            var question = {
                                name: $scope.vm.title,
                                type: 'S',
                                options: $scope.vm.options
                            };
                            $scope.vm.questions.push(question);
                            $scope.vm.options = [];
                            $scope.vm.title = "";
                            $scope.vm.currentOption = "";
                            $scope.vm.isSingleChoice = false;
                        } else if ($scope.vm.title.length > 0 && $scope.vm.options.length > 0) {
                            var question = {
                                name: $scope.vm.title,
                                type: 'M',
                                options: $scope.vm.options
                            };
                            $scope.vm.questions.push(question);
                            $scope.vm.options = [];
                            $scope.vm.title = "";
                            $scope.vm.currentOption = "";
                            $scope.vm.isMultipleChoice = false;
                        }
                        break;
                }
            };

            $scope.vm.cancel = function () {
                $scope.vm.isText = false;
                $scope.vm.isSingleChoice = false;
                $scope.vm.isMultipleChoice = false;
            }

            $scope.vm.addOption = function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    if ($scope.vm.currentOption.length > 0) {
                        $scope.vm.options.push($scope.vm.currentOption);
                        $scope.vm.currentOption = "";
                    }
                }
            };

            $scope.vm.editOption = function (idx) {
                $scope.vm.options[idx] = $scope.vm.optionSelectedModel;
                $scope.vm.optionSelected = -1;
            }

            $scope.vm.addOptionButton = function () {
                if ($scope.vm.currentOption.length > 0)
                {
                    $scope.vm.options.push($scope.vm.currentOption);
                }
                $scope.vm.currentOption = "";
            };


            $scope.vm.editOptionButton = function (option, idx, event) {
                $scope.vm.optionSelected = idx;
                $scope.vm.optionSelectedModel = option;
            }

            $scope.vm.deleteOptionButton = function (idx) {
                $scope.vm.options.splice(idx, 1);
            }

            $scope.vm.deleteQuestionButton = function (idx) {
                $scope.vm.questions.splice(idx, 1)
            }

            $scope.vm.isClicked = false;


            $scope.saveQuestions = function () {
                if (connected) {
                    id_connected = id
                }
                var data = {
                    name: $scope.vm.surveyTitle,
                    from: $scope.vm.surveyFrom,
                    url: $scope.vm.surveyUrl,
                    surveyText: $scope.vm.surveyText,
                    emailText: $scope.vm.surveyEmail,
                    questions: $scope.vm.questions,
                    api: $scope.vm.api,
                    id: id_connected,
                    file: imagem
                };
                jQuery.ajax({
                    'method': 'POST',
                    'url': 'https://app.webcourier.com.br/api/survey/new',
                    'data': data,
                    'dataType': 'JSON'
                }).done(function (response) {
                    if (!connected) {
                        id_connected = response['message'];
                    }
                    jQuery.ajax({
                        'method': 'POST',
                        'data': {respostaid: id_connected,
                            evento: $scope.vm.eventos
                        },
                    }).done(function (response) {
                        window.location.href = url;
                    });
                });
            }
        })