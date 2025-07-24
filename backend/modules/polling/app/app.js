/*
* Configure all modules
*
* */

var pqs = angular.module('pqs', ['ui.bootstrap','ngRoute','xeditable','ngSanitize','mgo-angular-wizard','toggle-switch','file-upload', 'custom-datepicker']);

var baseUrl = URL_POLLING_QUIZ;
var imageUrl = null;
    imageUrl = "http://localhost/specify-app-path/images/survey_template";

pqs.constant("GlobalConstants", {
    "BASE_URL" : baseUrl,
    "IMAGE_URL" : imageUrl,
    "QUIZ_QUESTION_TYPE_CONFIRM" : -1,
    "QUIZ_QUESTION_TYPE_TEXT" : 1,
    "QUIZ_QUESTION_TYPE_NUMBER" : 2,
    "QUIZ_QUESTION_TYPE_RATING" : 3,
    "QUIZ_QUESTION_TYPE_BOOLEAN" : 4,
    "QUIZ_QUESTION_TYPE_CHECKBOX" : 6,
    "QUIZ_QUESTION_TYPE_RADIO" : 5,
    "QUIZ_QUESTION_TYPE_UPLOAD_FILE" : 7,
    "QUIZ_QUESTION_TYPE_DATE" : 8,

    "QUIZ_QUESTION_VISIBLE_ALWAYS" : 1,
    "QUIZ_QUESTION_VISIBLE_CONDITION" : 2,

    "QUIZ_QUESTIONNAIRE_TYPE_DATA_ENRICHMENT" : 1,
    "QUIZ_QUESTIONNAIRE_TYPE_WEBSITE_TOOL" : 2,

    "QUIZ_QUESTION_ACTION_COMPARE_EQUAL" : 1,
    "QUIZ_QUESTION_ACTION_COMPARE_LESS" : 2,
    "QUIZ_QUESTION_ACTION_COMPARE_GREATER" : 3,
    "QUIZ_QUESTION_ACTION_COMPARE_LESS_EQUAL" : 4,
    "QUIZ_QUESTION_ACTION_COMPARE_GREATER_EQUAL" : 5,
    "POLLING_QUIZ_TEAM_BASED_NO" : 0,
    "POLLING_QUIZ_TEAM_BASED_YES" : 1
});

pqs.config(function ($routeProvider,$locationProvider,$httpProvider) {
    $locationProvider.html5Mode(true);
    //$parseProvider.unwrapPromises(true);
    $httpProvider.interceptors.push(function($q, $rootScope) {
        //src: http://stackoverflow.com/questions/17838708/implementing-loading-spinner-using-httpinterceptor-and-angularjs-1-1-5
        var numLoadings = 0;

        return {
            request: function (config) {
                numLoadings++;

                // Show loader
                $rootScope.$broadcast("loader_show");
//                console.log('request: '+config.url);
                return config || $q.when(config)

            },
            response: function (response) {

                if ((--numLoadings) === 0) {
                    // Hide loader
                    $rootScope.$broadcast("loader_hide");
                }
//                console.log('response');
                return response || $q.when(response);

            },
            responseError: function (response) {

                if (!(--numLoadings)) {
                    // Hide loader
                    $rootScope.$broadcast("loader_hide");
                }

                return $q.reject(response);
            }
        };
    });
    /*$routeProvider.when("/", {
        controller: "Stage1Ctrl",
        templateUrl: "stage1.html"
    });
    $routeProvider.otherwise({ redirectTo: "/" });*/

});

pqs.filter('percentage', ['$filter', function($filter) {
    return function(input, total) {
        //return $filter('number')(input*100, decimals);

        var total = Math.round((input*100)/total);
        return total
    };
}]);

