/**
 * Created by rahulsinghmatharu on 18/11/15.
 */
angular.module('hs', ['ng']).directive('hsButtonToggle', function () {
    return {
        // required to make it work as an element
        restrict: 'E',
        scope: {
            toggle: '=',
            onClickFunction: '&',
            disabled: '=',
            onLabel: '@',
            offLabel: '@'
        },

        // replace <photo> with this html
        template: '<div class="boolean-buttons" style="display: block">' +
            '<a class="quiz_main_hover_color a-left" href="#" ng-disabled="disabled">' +
            '<input type="button" value="{{onLabel}}" class="input-switch-left" ng-disabled="disabled||toggle==true"></a>' +

            '<a class="quiz_main_hover_color a-right" href="#">' +
            '<input type="button" value="{{offLabel}}" class="input-switch-right" ng-disabled="disabled||toggle==false"></a>' +
            '<div class="clearfix"></div>' +
            '</div>',
        replace: true,

        link: function($scope, element, attrs) {
            if (!attrs.onLabel) { attrs.onLabel = 'On'; }
            if (!attrs.offLabel) { attrs.offLabel = 'Off'; }

            element.find('.a-left').click(function(){
                $scope.$apply(function() {
                    $scope.toggle = true;
                    $scope.onClickFunction();
                })
            });

            element.find('.a-right').click(function(){
                $scope.$apply(function() {
                    $scope.toggle = false;
                    $scope.onClickFunction();
                })
            });

            $scope.$watch("toggle", function(value) {
                if(value == true)
                    toggleIt(element.find('.a-left'));
                else if(value == false)
                    toggleIt(element.find('.a-right'));
            })

            $scope.$watch("disabled", function(value) {
                if(value == true){
                    element.find('.a-left,.input-switch-left').addClass('quiz_main_hover_color_disable');
                    element.find('.a-right,.input-switch-right').addClass('quiz_main_hover_color_disable');

                    element.find('.a-left').removeClass('quiz_main_hover_color');
                    element.find('.a-right').removeClass('quiz_main_hover_color');
                }
            })
        }
    }
});

function toggleIt(that) {
    $(that).siblings('.a-left').find('.input-switch-left').removeClass('input-color');
    $(that).siblings('.a-right').find('.input-switch-right').removeClass('input-color');
    $(that).siblings('.a-left').find('.input-switch-left').css('background-color', '#E0E0E0');
    $(that).siblings('.a-right').find('.input-switch-right').css('background-color', '#E0E0E0');
    $(that).siblings('.a-left').find('.input-switch-left').removeClass('quiz_main_color');
    $(that).siblings('.a-right').find('.input-switch-right').removeClass('quiz_main_color');
    $(that).find('input').addClass('input-color');
    $(that).find('input').css('background-color', '#3498db');
    $(that).find('input').addClass('quiz_main_color');
}