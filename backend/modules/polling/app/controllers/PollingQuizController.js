
pqs.controller('PollingQuizController',function($scope,PollingQuizService, PollingQuizModel, PollingQuizQuestionModel, PollingQuizQuestionOptionModel, PollingQuizInviteModel, PollingQuizSettingModel, GlobalConstants, $timeout, WizardHandler, $window, $modal){

    $scope.uploadFileData = [];
    $scope.alerts = [];
    $scope.quizInvite = new PollingQuizInviteModel();
    $scope.quizSetting = new PollingQuizSettingModel();
    $scope.pollingQuiz = new PollingQuizModel();
    $scope.pollingQuizQuestionAnswers = [];//array of [questionId] = answer
    $scope.pollingQuizTeam=[{"name":"Select","id":-1}];
    $scope.GlobalConstants = GlobalConstants;
    $scope.max = 10;
    $scope.overStar = null;
    $scope.percent = null;
    $scope.ratingVal = 0;
    $scope.innerHtmlRatingVal=0;
    $scope.currentStep = 0;
    $scope.allowQuizAnswerWatch = false;
    $scope.websiteToolShowResult = false;
    $scope.showThankyou = false;
    $scope.show_result=false;
    $scope.show_result_data=false;
    $scope.thankYouText="Thank you for completing this Questionnaire";
    $scope.questionCount = 0;
    $scope.selectedTeamId=-1;

    $scope.step = 0;
    $scope.stepThank=0;
    $scope.psuedoBack=0;

    $scope.finalResult=0;

    $scope.starClass="'fa fa-star fa-3x quiz_star'";

    $scope.switchStatus;
    $scope.currentWizardStep=0;
    $scope.mainColor=PollingQuizService.mainColor;
    $scope.background=PollingQuizService.background;
    $scope.backgroundUrl=PollingQuizService.backgroundUrl;
    $scope.location=PollingQuizService.location;
    $scope.urlParams=PollingQuizService.urlParams;
    $scope.quizStylings={};
    $scope.imageUrl=PollingQuizService.imageUrl;
    $scope.uuid=PollingQuizService.uuid;
    $scope.applyBackground=PollingQuizService.applyBackground;
    $scope.demo_quiz=PollingQuizService.demo_quiz

    //****** Ctrl init start ******
    $scope.manageUploadFileData=function(questionid,sessionId){
        $scope.uploadFileData.push({'questionId':questionid,'sessionId':sessionId});
    }
    $scope.showExplanation=function(explanation){
        if((explanation)==null){
            return false;
        }else{
            return true;
        }
    }
    $scope.init=function(){
        PollingQuizService.demo().then(function(data){
            $scope.quizStylings=data;
        }).finally(function() {

        });
    };
    $scope.showStartPage=function(val){
        var currentStep = WizardHandler.wizard().currentStepPosition();
        $scope.currentWizardStep=currentStep;
        if(currentStep==1){
            $scope.psuedoBack=1;
            return true;
        }else{
            $scope.psuedoBack=0;
            return false;
        }
    }
    /*$scope.toggleIt=function(){
     $(".switch-left").click();
     }*/
    $scope.insertQuizAnswers=function(questionId){
        $scope.pollingQuizQuestionAnswers[questionId];
        //console.log($scope.pollingQuizQuestionAnswers);
    }
    $scope.updateSteps = function(val) {
        $scope.step=val;
    };

    $scope.teamBased=function(pollingQuizQuestion){
        if(parseInt(pollingQuizQuestion.team_based)==GlobalConstants.POLLING_QUIZ_TEAM_BASED_YES &&$scope.selectedTeamId==-1 ){
            console.log($scope.selectedTeamId);
            return true;
        }
        return false;
    }

    $scope.testChange = function(selectedTeamId){
        $scope.selectedTeamId = selectedTeamId
    }

    //load invite
    PollingQuizService.getQuizInvite().then(function (data) {
        $scope.quizInvite = new PollingQuizInvite(data);

        //Expired check
        if($scope.quizInvite.expired){
            $scope.pageHide = true;
            $scope.addAlert({'status': 0, 'message': 'Deze link is niet meer geldig, neem contact met ons op'});//link expired
        }

        //Already submitted check
        if($scope.quizInvite.quiz_status){
//            $scope.pageHide = true;
            $scope.addAlert({'status': 1, 'message': 'Deze vragenlijst is al ingevuld.'});//Questionnaire already filled.
        }
    });

    //load quizSetting
    PollingQuizService.getQuizSetting().then(function (data) {
        $scope.quizSetting = new PollingQuizSetting(data);
    });

    PollingQuizService.getPollingQuiz().then(function (data) {
        //console.log('fff');
        $scope.pollingQuiz = new PollingQuizModel(data);
        console.log( $scope.pollingQuiz.pollingQuizTeams);
        if($scope.pollingQuiz.show_result==1){
            $scope.show_result=true;
        }
        console.log($scope.pollingQuiz.pollingQuizQuestions);
        angular.forEach($scope.pollingQuiz.pollingQuizTeams, function(pollingQuizTeam, key, obj) {
            var obj={"name":pollingQuizTeam.name,"id":pollingQuizTeam.id};
            $scope.pollingQuizTeam.push(obj);
            /*angular.forEach($scope.pollingQuiz.pollingQuizQuestions[key], function(quizOption, keyOption) {
             quizOption = new QuizOption(quizOption);
             })*/
        });
        //Done, so that local properties of object also applied.
        angular.forEach($scope.pollingQuiz.pollingQuizQuestions, function(pollingQuizQuestion, key, obj) {
            // check for team based
            $scope.pollingQuiz.pollingQuizQuestions[key] = new PollingQuizQuestionModel(pollingQuizQuestion);
            /*angular.forEach($scope.pollingQuiz.pollingQuizQuestions[key], function(quizOption, keyOption) {
             quizOption = new QuizOption(quizOption);
             })*/
        });

        //Initialize pollingQuizQuestionAnswers array
        angular.forEach($scope.pollingQuiz.pollingQuizQuestions, function(quizQuestion, key) {
            $scope.pollingQuizQuestionAnswers[quizQuestion.id] = null;
            /*            if(quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_BOOLEAN)
             $scope.pollingQuizQuestionAnswers[quizQuestion.id] = false;*/
        });

        //Assign existing values to pollingQuizQuestionAnswers array
        PollingQuizService.pollingQuizQuestionAnswers().then(function (data) {

            angular.forEach(data, function(answer, questionId) {
                $scope.pollingQuizQuestionAnswers[questionId] = answer;
                //Checkbox case
                angular.forEach($scope.pollingQuiz.pollingQuizQuestions, function(quizQuestion, key) {
                    if(quizQuestion.id == questionId && quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_CHECKBOX){
                        if(answer != null){
                            var optionIds = answer.split(',');
                            angular.forEach(quizQuestion.pollingQuizQuestionOptions, function(quizOption, key) {
                                if(optionIds.indexOf(quizOption.id) >= 0){//in_array()
                                    quizOption.selected = true;
                                }
                            });
                        }
                    }
                });
            });

            //Update wizard's steps visibility
            angular.forEach($scope.pollingQuiz.pollingQuizQuestions, function(quizQuestion, key) {
                PollingQuizModel.updateVisible($scope.pollingQuiz.pollingQuizQuestions,quizQuestion,$scope.pollingQuizQuestionAnswers[quizQuestion.id]);
            });

            $timeout(function(){
                $scope.allowQuizAnswerWatch = true;
            },1000);

        });
    });
    //****** Ctrl init end ******


    $scope.addAlert = function(response) {
        if(response.status == 0){
            response.type = "danger";
        }
        else if(response.status == 1){
            response.type = "success";
        }
        $scope.alerts.push(response);
    };

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };
    $scope.returnString=function(num){
        switch(num){
            case 1: return "One";
                break;
            case 2: return "Two";
                break;
            case 3: return "Three";
                break;
            case 4: return "Four";
                break;
            case 5: return "Five";
                break;
            case 6: return "Six";
                break;
            case 7: return "Seven";
                break;
            case 8: return "Eight";
                break;
            case 9: return "Nine";
                break;
            case 10: return "Ten";
                break;
        }
    }

    $scope.hoveringOver = function(value) {
        $scope.ratingVal=value;
        $scope.overStar = true;
        $scope.percent = 5 * (value / $scope.max);
        $scope.number = 10 * (value / $scope.max);
        $scope.innerHtmlRatingVal=value+"/"+10;
    };
    $scope.hoveringOver2 = function(value,data) {
        var max_val=$scope.starCount(data);
        console.log(max_val);
        $scope.ratingVal=value;
        $scope.overStar = true;
        $scope.innerHtmlRatingVal=value+"/"+max_val;
    };
    $scope.starCount=function(data){
        //console.log(data);
        if(data.length>0){
            return parseInt(data[0].value);
        }
        return 10;
    }
    $scope.onLeave = function () {
        $scope.overStar = false;
    };

    $scope.showNext = function(){

        var totalSteps = WizardHandler.wizard().totalSteps();
        var currentStep = WizardHandler.wizard().currentStepPosition();
        $scope.currentWizardStep=currentStep;
        //console.log(currentStep);
        //console.log('totalstep show next='+totalSteps);

        if(currentStep == totalSteps)
        {
            //console.log('current step is equal to totalsteps in show next');
            return false;
        }

        return true;
    };

    $scope.incrementProgressbar = function(){
        $scope.questionCount = $scope.questionCount+1;
    }

    $scope.decrementProgressbar = function(){
        $scope.questionCount = $scope.questionCount-1;
    }

    $scope.showPrevious = function(){
        var currentStep = WizardHandler.wizard().currentStepNumber();
        $scope.currentWizardStep=currentStep;
        if(currentStep == 1)
            return false;
        if($scope.showThankyou)
        {// remove previous after submit
            return false;
        }
        return true;
    };

    $scope.showFinish = function(url){
        var totalSteps = WizardHandler.wizard().totalSteps();
        var currentStep = WizardHandler.wizard().currentStepPosition();
        $scope.currentWizardStep=currentStep;

//        console.log('value in show finish');
//        console.log('totalSteps'+totalSteps);
//        console.log('currentStep'+currentStep);
//        console.log('currentStepNumber'+WizardHandler.wizard().currentStepNumber());
        if($scope.quizInvite.quiz_status == 1) {
            if(url != '') {
                window.location.href = url;
            }
            return false;
        }

        if(currentStep === totalSteps){
            //console.log('current step is equal to totalsteps in show finish');
            return true;
        }
        return false;
    };
    $scope.showResultButton=function(){
        if($scope.show_result && $scope.show_result_data){
            return true;
        }
        return false;
    }
    $scope.buttonStatus={'index':[],'status':[]};

    $scope.validation = function (team_based,test_quiz,required) {
        console.log("validating");
        console.log(team_based);
        console.log(test_quiz);
        console.log(required);
        console.log("validating off");
        var isValid = false;
        var currentStepNumber = WizardHandler.wizard().currentStepNumber();
        $scope.currentWizardStep=currentStepNumber;
        var index = currentStepNumber - 1;
        var i = 0;
        angular.forEach($scope.pollingQuiz.pollingQuizQuestions, function(quizQuestion, key) {
            if(!isValid){
                if(i == index) {
                    if(quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_TEXT) {
                        isValid = true;
                        if(required == 1){
                            if(!test_quiz || test_quiz.length < 2) {
                                isValid = false;
                            }

                            if(test_quiz){

                                if(test_quiz.length > 0 && test_quiz.length < 2) {
                                    $('.only-show-when-required').show();
                                }
                                else{
                                    $('.only-show-when-required').hide();
                                }
                            }
                        }
                        //true/false allowed in boolean
                        //empty text allowed
                    }
                    else if(quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_CONFIRM){
                        isValid = true;
                    }
                    else if(quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_BOOLEAN){
                        if($scope.pollingQuizQuestionAnswers[quizQuestion.id] == true || $scope.pollingQuizQuestionAnswers[quizQuestion.id] == false)
                            isValid = true;
                    }
                    else if(typeof($scope.pollingQuizQuestionAnswers[quizQuestion.id]) != "undefined" && $scope.pollingQuizQuestionAnswers[quizQuestion.id] != "" && $scope.pollingQuizQuestionAnswers[quizQuestion.id] != null) {
                        isValid = true;
                    }
                    else if(quizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_UPLOAD_FILE){
                        isValid = true;
                    }
                }
            }
            i++;
        });

        if(team_based==1){
            if($scope.selectedTeamId==-1){
                isValid=false;
            }
        }

        return isValid;
    };

    $scope.finishedWizard = function () {
        console.log($scope.pollingQuizQuestionAnswers);
        if(!$scope.showThankyou){
            var quizInvite = new PollingQuizInviteModel({quiz_status : 1});
            PollingQuizService.postQuizQuestionAnswer({"pollingQuizQuestionAnswers" : $scope.pollingQuizQuestionAnswers,"selectedTeamId":$scope.selectedTeamId,"uploadFileData":$scope.uploadFileData}).then(function (data) {
                console.log("posted quiz");
                console.log(data);
                $scope.show_result_data=true;
                $scope.quizInvite.quiz_status = 1;
            });
        }
        $scope.showThankyou = true;
    };

    $scope.checkboxAnswer = function (quizQuestion) {
        var selected = [];
        //$scope.pollingQuizQuestionAnswers[questionId] = selected.join(',');
        angular.forEach(quizQuestion.pollingQuizQuestionOptions, function(quizOption, key) {
            if(quizOption.selected == true){
                selected.push(quizOption.id);
            }
        });
        $scope.pollingQuizQuestionAnswers[quizQuestion.id] = selected.join(',');
    };

    $scope.showThanksMessage=function(){

        if(typeof($scope.demo_quiz) != 'undefined' && $scope.demo_quiz != null){
            if($scope.demo_quiz=='true'){
                $scope.step=3;
            }else{
                $scope.showThanksInternal();
            }
        }else{
            $scope.showThanksInternal();
        }

    };

    $scope.showThanksInternal=function(){
        if(typeof($scope.uuid) != 'undefined' && $scope.uuid != null){
            $scope.step = 2;
            $scope.stepThank=1;
        }
    }

    $scope.showStartThank=function(){
        $scope.step = 1;
        /*WizardHandler.wizard().nextVisible(1);
         var currentStepNumber = WizardHandler.wizard().currentStepNumber();
         console.log(currentStepNumber);*/
        //alert(currentStepNumber)
        //$scope.currentWizardStep=currentStepNumber;
    }

    $scope.showResultUrl=function(){
        var url='https://gamificationguru.com/admin/backend/web/polling/show-result/index?id='+$scope.pollingQuiz.polling_id;
        window.open(url);
    }
    $scope.showResult = function () {

        if(typeof($scope.demo_quiz) != 'undefined' && $scope.demo_quiz != null){
            if($scope.demo_quiz=='true'){
                $scope.step=3;
            }else{
                $scope.websiteToolShowResult = true;
            }
        }else{
            $scope.websiteToolShowResult = true;
        }

    };

    $scope.hideResult = function () {
        $scope.websiteToolShowResult = false;
    };

    $scope.generatePdf = function () {
        $scope.$broadcast('loader_show', {loaderFor : 'pdf'});
        PollingQuizService.questionnaireGeneratePdf({
            questionnaireUuid : $scope.pollingQuiz.uuid,
            answers : $scope.pollingQuizQuestionAnswers
        }).then(function(data){
            $window.open(data.pdfLink, '_blank')
        }).finally(function() {
            $scope.$broadcast('loader_hide', {'loaderFor' : 'pdf'});
        });
    };

    $scope.emailResult = function (email) {
        $scope.$broadcast('loader_show', {loaderFor : 'email'});
        PollingQuizService.questionnaireEmailResult({
            "questionnaireUuid" : $scope.pollingQuiz.uuid,
            "answers" : $scope.pollingQuizQuestionAnswers,
            "email" : email
        }).then(function(data){
            $scope.addAlert(data);
        }).finally(function() {
            $scope.finalResult=1;
            $scope.$broadcast('loader_hide', {'loaderFor' : 'email'});
        });
    };

    //Watch
    var timer = false;
    //$scope.$watch('pollingQuizQuestionAnswers', function(newVal, oldVal) {
    //    /*console.log(oldVal);
    //    console.log(newVal);*/
    //    /*if(timer){
    //        $timeout.cancel(timer);//cancel previously set $timeout.
    //    }*/
    //    /*timer = $timeout(function(){*/
    //        //console.log(newVal, oldVal);
    //        angular.forEach(newVal, function(answer, questionId) {
    //            console.log(answer);
    //            console.log(questionId);
    //            if(answer != oldVal[questionId]){//Means, answer changed
    //                console.log(oldVal[questionId]);
    //                //Update answer
    //                if($scope.allowQuizAnswerWatch){
    //                    PollingQuizService.quizAnswer({
    //                        "questionId" : questionId,
    //                        "answer" : answer
    //                    });
    //                }
    //                //Visibility check on other questions
    //                var updatedQuizQuestion = PollingQuizQuestionModel.getQuestionFromId($scope.pollingQuiz.pollingQuizQuestions,questionId);
    //                PollingQuizModel.updateVisible($scope.pollingQuiz.pollingQuizQuestions, updatedQuizQuestion,answer);
    //            }
    //        });
    //    /*}, 500);*/
    //
    //}, true);

    $scope.open = function (template, size) {
        $scope.modalInstance = $modal.open({
            templateUrl: template,
            size: size,
            scope: $scope,
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });
    };

});