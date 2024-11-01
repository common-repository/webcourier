jQuery(document).ready(function () {
    // Initialize Smart Wizard
    jQuery('#wizard').smartWizard({
        onLeaveStep: leaveAStepCallback,
    });

    function leaveAStepCallback(obj, context) {
        console.log(validateSteps(context.fromStep, context.toStep)); //console.log não é opcional, favor não remover
        return validateSteps(context.fromStep, context.toStep); // return false to stay on step and true to continue navigation 
    }

    function validateSteps(stepnumber, toStep) {
        var scope = angular.element(jQuery('#wizard')).scope();
        var flag = true;

        if (stepnumber == 1) {
            if (!scope.vm.surveyTitle) {
                scope.vm.isRequired('titleRequired');
                flag = false;
            } else {
                scope.vm.isNotRequired('titleRequired');
            }
            if (!scope.vm.surveyFrom) {
                scope.vm.isRequired('fromRequired');
                flag = false;
            } else {
                scope.vm.isNotRequired('fromRequired');
            }
            if (!scope.vm.surveyText) {
                scope.vm.isRequired('textRequired');
                flag = false;
            } else {
                scope.vm.isNotRequired('textRequired');
            }
            if ((!scope.vm.surveyEmail) || (scope.vm.surveyEmail.indexOf('{link}') == -1)) {
                scope.vm.isRequired('mailRequired');
                flag = false;
            } else {
                scope.vm.isNotRequired('mailRequired');
            }
            return flag;
        }
        if (stepnumber == 2 && toStep == 3 && !scope.vm.questions.length) {
            scope.vm.isRequired('questionRequired');
            return false;
            
        } else if (stepnumber == 2 && (scope.vm.isText || scope.vm.isSingleChoice || scope.vm.isMultipleChoice)) {
            scope.vm.isRequired('questionRequiredFinish');
            return false;
        } else {
            return true;
        }
    }

    jQuery('.buttonFinish').on('click', function () {
        angular.element(jQuery('#wizard')).scope().saveQuestions();
    });
}); 