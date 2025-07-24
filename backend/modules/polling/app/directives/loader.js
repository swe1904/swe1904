/**
 * Created with JetBrains PhpStorm.
 * User: Rahul
 * Date: 3/28/14
 * Time: 5:35 PM
 * To change this template use File | Settings | File Templates.
 */
/*
* srce: http://stackoverflow.com/questions/17838708/implementing-loading-spinner-using-httpinterceptor-and-angularjs-1-1-5
* */
pqs.directive("loader", function ($rootScope) {
        return function ($scope, element, attrs) {
            $scope.$on("loader_show", function (event, args) {
                if(!angular.isUndefined(args) && !angular.isUndefined(args.loaderFor) && !angular.isUndefined(attrs.loader)){
                    if(args.loaderFor == attrs.loader){
                        element.show();
                    }
                }
            });
            $scope.$on("loader_hide", function (event, args) {
                if(!angular.isUndefined(args) && !angular.isUndefined(args.loaderFor) && !angular.isUndefined(attrs.loader)){
                    if(args.loaderFor == attrs.loader){
                        element.hide();
                    }
                }
            });
        };
    }
)