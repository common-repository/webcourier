angular.module('pesquisa-list', [])
        .controller('ControllerPesquisaList', function ($scope) {
            $scope.vm = {};

            $scope.vm.searchField = "";
            $scope.vm.pesquisas = pesquisas;
            $scope.vm.url = url;
            $scope.vm.selectEvent = currentEvent;
            $scope.vm.flag = 1;

            $scope.edit_search = function (idx) {
                url = url + '&search=' + idx;
                window.location.href = url;
            }

            $scope.saveSelectEvent = function (evento, survey_idx, bool) {
                if ($scope.vm.flag) {
                    $scope.vm.flag = 0;
                    jQuery.ajax({
                        'method': 'POST',
                        'data': {
                            respostaidlist: bool ? -1 : survey_idx,
                            evento: evento
                        }
                    }).done(function (res) {
                        location.reload();
                    })
                }
            }

            $scope.delete_search = function (surveyidx, idx) {
                swal({
                    title: "Tem certeza?",
                    text: "Você não poderá recuperar sua pesquisa",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar!",
                    closeOnConfirm: false
                }, function () {
                    swal("Removida!", "Sua pesquisa foi removida com sucesso.", "success");
                    $scope.vm.pesquisas.splice(idx, 1);
                    jQuery.ajax({
                        'method': 'POST',
                        'url': 'https://app.webcourier.com.br/api/survey/remove',
                        'data': {id: surveyidx, api: api},
                        'dataType': 'JSON'
                    }).done(function (response) {
                        if (response.status) {
                            jQuery.ajax({
                                'method': 'POST',
                                'data': {'removeid': surveyidx}
                            }).done(function () {
                                 location.reload();
                            })
                        }
                    })
                });
            };

            $scope.report_search = function (idx) {
                window.open("https://app.webcourier.com.br/api/survey/report?id="
                        + idx + "&api=" + api);
            }

            $scope.preview_search = function (survey_idx) {
                window.open("https://app.webcourier.com.br/api/survey/preview?id=" + survey_idx +
                        '&displayType=' + displayType + '&listType=' + listType + '&api=' + api, "_blank");
            }
            
            $scope.copy_search = function (survey_idx) {
                jQuery.ajax({
                    'method' : 'POST',
                    'url' : 'https://app.webcourier.com.br/api/survey/copy',
                    'data' : {id: survey_idx, api: api},
                    'dataType' : 'JSON'
                }).done(function(response){
//                   if(response.status){
                       location.reload();
//                   } 
                });
            }

            $scope.filterByName = function (name) {
                return $scope.vm.searchField.length == 0 || name.indexOf($scope.vm.searchField) >= 0;
            };
        })
