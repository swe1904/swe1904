pqs.factory('PollingQuizService', function($http, $location, $q, PollingQuizModel, GlobalConstants, $timeout) {
    var pollingQuizService = {
        pollingQuiz: new PollingQuizModel()
    }

    pollingQuizService.imageUrl=GlobalConstants.IMAGE_URL;

    pollingQuizService.apiKeyPublic = ($location.search()).api_key_public;//from get param

    pollingQuizService.mainColor = ($location.search()).main_color;//from get param

    pollingQuizService.background = ($location.search()).bg_color;//from get param

    pollingQuizService.backgroundUrl = ($location.search()).bg_url;//from get param

    pollingQuizService.applyBackground = ($location.search()).apply_bg;//from get param

    pollingQuizService.demo_quiz=($location.search()).demo_quiz;//from get param

    pollingQuizService.uuid = ($location.search()).uuid;

    pollingQuizService.location=$location.absUrl();
    pollingQuizService.pollingId=($location.search()).id;

    pollingQuizService.demo=function(){
        var deferred = $q.defer();
        //return GlobalConstants.BASE_URL+'getQuizStyleData?api_key_public='+pollingQuizService.apiKeyPublic;
        $http({
            method : 'GET',
            url : GlobalConstants.BASE_URL+'getQuizStyleData?api_key_public='+pollingQuizService.apiKeyPublic
            //data: {"uuid":uuid}
        })
            .success(function(data) {
                if(data.status){
                    deferred.resolve(data.payload);
                }
                else{
                    deferred.reject();
                    //console.log(data.message);
                }
            })
            .error(function(){
                deferred.reject();
            })
        return deferred.promise;
    };

    pollingQuizService.getPollingQuiz = function(){
        //alert(pollingQuizService.pollingId);
        //console.log("getPollingQuiz called");
        //Fetch
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        uuid = pollingQuizService.pollingId;
        var questionnaireUuid = ($location.search()).questionnaireUuid;//from get param
        //console.log('uuid:'+uuid);
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'GET',
                url : GlobalConstants.BASE_URL+'/get?uuid='+uuid,
                data: {"uuid":uuid}
            })
                .success(function(data) {
                    //console.log(data);
                    if(data.code == 200){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
                        //console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        else if(typeof(questionnaireUuid) != 'undefined' && questionnaireUuid != null){
            $http({
                method : 'GET',
                url : GlobalConstants.BASE_URL+'pollingQuiz?api_key_public='+pollingQuizService.apiKeyPublic+'&questionnaireUuid='+questionnaireUuid
//                data: {"uuid":uuid}
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
                        //console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.pollingQuizQuestionAnswers = function(){
//Fetch
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'GET',
                url : GlobalConstants.BASE_URL+'pollingQuizQuestionAnswers?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
//console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.quizAnswer = function(payload){
//Fetch
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'POST',
                url : GlobalConstants.BASE_URL+'quizAnswer?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid,
                data: payload
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
//console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.postQuizQuestionAnswer = function(pollingQuizQuestionAnswers){
        var url_string = window.location.href; //window.location.href
        var url = new URL(url_string);
        var clientId = url.searchParams.get("c_id");

        console.log("dffn");
        console.log(pollingQuizQuestionAnswers.uploadFileData);
        console.log("final json");
        console.log(pollingQuizQuestionAnswers.pollingQuizQuestionAnswers);
        /*Below is done becuase $.param uses object instead of array*/
        var postData = {};
        postData["selectedTeamId"]=pollingQuizQuestionAnswers.selectedTeamId;
        postData["fileData"]=pollingQuizQuestionAnswers.uploadFileData;
        postData["clientId"]=clientId;
        angular.forEach(pollingQuizQuestionAnswers.pollingQuizQuestionAnswers, function(value, key){
            console.log("value -> "+value+"  key -> "+key +"  key length -->  "+key.length);
            if(key!=null || key!=""){
                postData[key] = value;
            }
        });
        console.log("final json post");
        console.log(postData);
        var deferred;
        if(deferred)
            deferred.resolve();
        deferred = $q.defer();

        var promise = $http({
            method: 'POST',
            url : GlobalConstants.BASE_URL+'/quiz-question-answer',
            timeout: deferred.promise,
            data: $.param(postData),
            //data: $("#add-listing").serialize(),
            //data: listing,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": CSRF
            }
        })
        return promise;
    }

    /*pollingQuizService.postQuizQuestionAnswer = function(quizQuestionAnswers){
     var deferred = $q.defer();
     var uuid = ($location.search()).uuid;//from get param
     $http({
     method : 'POST',
     url : GlobalConstants.BASE_URL+'/quiz-question-answer?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid,
     data: quizQuestionAnswers
     })
     .success(function(data) {
     if(data.status){
     deferred.resolve(data.payload);
     }
     else{
     deferred.reject();
     }
     })
     .error(function(){
     deferred.reject();
     })
     return deferred.promise;
     }*/

    pollingQuizService.getQuizInvite = function(payload){
//console.log('pollingQuizService quizInvite:GET called');
//Fetch
//console.log('test dataa');
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'GET',
                url : GlobalConstants.BASE_URL+'quizInvite?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid,
                data: payload
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
//console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.postQuizInvite = function(payload){
//console.log('pollingQuizService quizInvite:POST is called');
//console.log('Icalledhere');
//Fetch
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'POST',
                url : GlobalConstants.BASE_URL+'quizInvite?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid,
                data: payload
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
//console.log('data success');
                    }
                    else{
                        deferred.reject();
//console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.getQuizSetting = function(payload){
//console.log('pollingQuizService quizSetting:GET called');
//Fetch
        var deferred = $q.defer();
        var uuid = ($location.search()).uuid;//from get param
        if(typeof(uuid) != 'undefined' && uuid != null){
            $http({
                method : 'GET',
                url : GlobalConstants.BASE_URL+'quizSetting?api_key_public='+pollingQuizService.apiKeyPublic+'&uuid='+uuid,
                data: payload
            })
                .success(function(data) {
                    if(data.status){
                        deferred.resolve(data.payload);
                    }
                    else{
                        deferred.reject();
//console.log(data.message);
                    }
                })
                .error(function(){
                    deferred.reject();
                })
        }
        return deferred.promise;
    }

    pollingQuizService.questionnaireGeneratePdf = function(payload){
//console.log('pollingQuizService questionnaireGeneratePdf called');
//Fetch
        var deferred = $q.defer();
        $http({
            method : 'POST',
            url : GlobalConstants.BASE_URL+'questionnaireGeneratePdf?api_key_public='+pollingQuizService.apiKeyPublic,
            data: payload
        })
            .success(function(data) {
                if(data.status){
                    deferred.resolve(data.payload);
                }
                else{
                    deferred.reject();
//console.log(data.message);
                }
            })
            .error(function(){
                deferred.reject();
            })

        return deferred.promise;
    }

    pollingQuizService.questionnaireEmailResult = function(payload){
//console.log('pollingQuizService questionnaireEmailResult called');
//Fetch
        var deferred = $q.defer();

        /*$timeout(function(){
         deferred.resolve({
         status : 1,
         message: "Results emailed"
         });
         }, 1000);*/

        $http({
            method : 'POST',
            url : GlobalConstants.BASE_URL+'questionnaireEmailResult?api_key_public='+pollingQuizService.apiKeyPublic,
            data: payload
        })
            .success(function(data) {
                deferred.resolve(data);
            })
            .error(function(){
                deferred.reject();
            })

        return deferred.promise;
    }


    return pollingQuizService;
});