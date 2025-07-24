<div class="panel panel-default">
    <div class="panel-heading"  ng-style="{'background-color':wizardColor}">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="panel-title" ng-repeat="step in steps | filter:{isVisible: true}" ng-style="{'color':wizardColorText}">
                    <span ng-if="step.selected">
                        {{$index +1 }}. {{step.questionTitle|| 'Question'}}
                    </span>
                </h3>
            </div>
            <div class="col-sm-4">
                <div class="progress" style="margin-bottom: 0px">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{ questionCount | percentage:(steps.length-1) }}%;background-color: greenyellow">
                        <!--<span style="position: unset; color: blue" class="sr-only">{{(questionCount*100)/steps.length}}% Complete</span>-->
                        <span ng-hide="questionCount==0" style="position: unset; color: blue" class="sr-only">{{ questionCount | percentage:(steps.length-1) }}% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="steps inner_middle" ng-transclude></div>
    </div>
</div>