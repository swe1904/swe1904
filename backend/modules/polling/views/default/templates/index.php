
<script>
    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var c = url.searchParams.get("c_id");
</script>
<?php
$pollingQuizUrl = Yii::$app->urlManager->createUrl(['/polling/polling-quiz']);
$redirect = '';
if(isset($pollingQuizModel->redirect_link)){
    $redirect = $pollingQuizModel->redirect_link;
}
$csrf = Yii::$app->request->getCsrfToken();
$listingUrl = <<<JS
    const URL_POLLING_QUIZ = "$pollingQuizUrl";
    const CSRF = "$csrf";
JS;
$this->registerJs($listingUrl, \yii\web\View::POS_BEGIN);
?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"
      xmlns="http://www.w3.org/1999/html">
<style>
    .nav, .pagination, .carousel, .panel-title a { cursor: pointer; }

    .green {
        color: forestgreen;
    }
    .light-green {
        color: lightgreen;
    }
    .pad_bt{
        padding-bottom: 10px;
    }
    .pad_lt{
        /*padding-left: 20px;*/
    }
    textarea {
        overflow: auto;
        padding: 10px;
        border-radius: 5px;
        outline: none;
        height: 150px;
        border-color: #ddd;
    }
    .step_bg{
        font-size: medium;
    }
    .toggle-switch .switch-right {
        color: #fff;
        background: #428bca;
    }
    [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
        display: none !important;
    }
    .panel{
        border: 1px solid transparent;
        box-shadow: 0px 5px 9.5px 0.5px rgba(0, 0, 0, 0.08);
        -moz-box-shadow: 0px 5px 9.5px 0.5px rgba(0, 0, 0, 0.08);
        -webkit-box-shadow: 0px 5px 9.5px 0.5px rgba(0, 0, 0, 0.08);
        -o-box-shadow: 0px 5px 9.5px 0.5px rgba(0, 0, 0, 0.08);
        -ms-box-shadow: 0px 5px 9.5px 0.5px rgba(0, 0, 0, 0.08);
    }
    .heading{
        text-align: center;
        margin: 0px auto 20px auto;
    }
</style>
<div class="container" style="padding-top:20px;">
    <div class="row">
        <div ng-app="pqs" ng-cloak id="main_angular">
            <div ng-controller="PollingQuizController">

                <div style="background-image: url("")">

                </div>
                <div ng-show="pageHide">
                    <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.message}}</alert>
                </div>
                <div ng-show="!pageHide">
                    <div>
                        <span style="font-weight: 200;font-size: 30px;padding-bottom: 0px;">{{pollingQuiz.name}}</span>
                        <hr>
                        <p ng-bind-html="pollingQuiz.description"></p>
                    </div>

                    <div class="alert alert-success" ng-bind-html="thankYouText" ng-show="showThankyou">
                        Thank you for completing this Questionnaire
                    </div>


                    <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.message}}</alert>


                    <wizard
                        ng-show = "!websiteToolShowResult"
                        on-finish="finishedWizard()"
                        template="<?php echo Yii::$app->urlManager->createUrl(['/polling/polling-quiz/wizard']); ?>"
                        edit-mode="quizInvite.quiz_status"
                        wizard-color="pollingQuiz.klanten.bedrijfskleur"
                        wizard-color-text="pollingQuiz.klanten.bedrijfskleur_text"
                        wizard-logo = "pollingQuiz.klanten.logo"
                        question-count="questionCount"
                        >

                        <wz-step
                            ng-repeat="pollingQuizQuestion in pollingQuiz.pollingQuizQuestions"
                            template="<?php echo Yii::$app->urlManager->createUrl(['/polling/polling-quiz/step']); ?>"
                            question-title="{{pollingQuizQuestion.title}}"
                            is-visible="{{pollingQuizQuestion.isVisible}}"
                            >

                            <div style="min-height: 250px;">

                                <div class="">
                                    <span class="pad_bt step_bg "  ng-bind-html="pollingQuizQuestion.question"></span>

                                <span class="pad_lt "  ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_TEXT">
                                    <textarea ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]"
                                           ng-disabled="quizInvite.quiz_status" style="width: 100%" ng-if="pollingQuizQuestion.required" placeholder="Please enter a substantive answer before continuing."></textarea>

                                    <textarea ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]"
                                              ng-disabled="quizInvite.quiz_status" style="width: 100%" ng-if="pollingQuizQuestion.required == 0"></textarea>

                                    <label class="only-show-when-required" style="display: none;color: indianred;" ng-if="pollingQuizQuestion.required"> {{pollingQuizQuestion.required_error_message}} </label>
                                </span>



                                <span class="pad_lt " ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_NUMBER">
                                    <textarea type="number" ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]"
                                           ng-disabled="quizInvite.quiz_status" style="width: 100%"></textarea>
                                </span>

                                <span class="pad_lt " ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_RATING">
                                    <br>
                                    <rating ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]"
                                            max="starCount(pollingQuizQuestion.pollingQuizQuestionOptions)"
                                            on-hover="hoveringOver2(value,pollingQuizQuestion.pollingQuizQuestionOptions)"
                                            on-leave="onLeave()"
                                            readonly="quizInvite.quiz_status"
                                            state-on="'fa fa-star fa-2x green'"
                                            state-off="'fa fa-star-o fa-2x light-green'"
                                        ></rating>
                                    <span class="label"
                                          ng-class="{'label-warning': percent<30, 'label-info': percent>=30 && percent<70, 'label-success': percent>=70}"
                                          ng-show="overStar && !quizInvite.quiz_status">{{innerHtmlRatingVal}}
                                    </span>
                                </span>
                                <span class="pad_lt" ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_BOOLEAN">
                                    <br>
                                    <toggle-switch style="max-height: 32px;" ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]" on-label="True" off-label="False" ng-disabled="quizInvite.quiz_status"> <toggle-switch>

                                </span>

                                <span class="pad_lt" ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_CHECKBOX">

                                    <div class="checkbox" ng-repeat="quizOption in pollingQuizQuestion.pollingQuizQuestionOptions">
                                        <label>
                                            <input type="checkbox" ng-model="quizOption.selected"
                                                   ng-change="checkboxAnswer(pollingQuizQuestion)"
                                                   ng-disabled="quizInvite.quiz_status"/>
                                            {{quizOption.value}}
                                        </label>
                                        <br>
                                    </div>
                                </span>
                                <span class="pad_lt" ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_RADIO">
                                    <div class="checkbox" ng-repeat="quizOption in pollingQuizQuestion.pollingQuizQuestionOptions | orderBy:'order'">
                                        <label>
                                            <input type="radio" name="radio-{{quizOption.id}}" ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]" ng-value="quizOption.id"
                                                   ng-disabled="quizInvite.quiz_status"/>
                                            {{quizOption.value}}
                                        </label>
                                        <br>
                                    </div>
                                </span>
                                <span class="pad_lt" ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_UPLOAD_FILE">
                                    <div file-upload manage="manageUploadFileData" u-id="pollingQuizQuestion.id"  session-id="<?=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));?>"></div>
                                </span>

                                <span class="pad_lt" ng-if="pollingQuizQuestion.type == GlobalConstants.QUIZ_QUESTION_TYPE_DATE">
                                    <div custom-datepicker ng-model="pollingQuizQuestionAnswers[pollingQuizQuestion.id]"
                                              ng-disabled="quizInvite.quiz_status"></div>
                                </span>

                                </div>
                                <div class="col-md-4" ng-show="teamBased(pollingQuizQuestion)">
                                    <span class="pad_lt"> Team </span>
                                    <select ng-model="selectedTeamId" ng-options="x.id as x.name for x in pollingQuizTeam" ng-change="testChange(selectedTeamId)">
                                    </select>

                                </div>
                            </div>

                            <button wz-next ng-click="incrementProgressbar()" ng-show="showNext()" class="btn pull-right" ng-disabled="!validation(pollingQuizQuestion.team_based,pollingQuizQuestionAnswers[pollingQuizQuestion.id],pollingQuizQuestion.required)" ng-style="{'background-color':pollingQuiz.klanten.bedrijfskleur,'color':pollingQuiz.klanten.bedrijfskleur_text}">Next <i class="fa fa-arrow-right"></i></button>
                            <button wz-previous ng-click="decrementProgressbar()" ng-show="showPrevious()" class="btn"><i class="fa fa-arrow-left"></i>Previous</button>
                            <button wz-finish ng-if="showFinish('<?= $redirect ?>')" ng-disabled="!validation(pollingQuizQuestion.team_based,pollingQuizQuestionAnswers[pollingQuizQuestion.id],pollingQuizQuestion.required)" class="btn btn-success pull-right "><i class="fa fa-check"></i>Finish</button>
                            <button ng-if="showResultButton()" ng-hide="!validation(pollingQuizQuestion.team_based)" ng-click="showResultUrl()" class="btn btn-success pull-right"><i class="fa fa-check"></i> Show Result</button>
                        </wz-step>
                    </wizard>

                    <div ng-show="websiteToolShowResult">
                        <div class="panel panel-default">
                            <div class="panel-heading" ng-style="{'background-color':pollingQuiz.klanten.bedrijfskleur}">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h3 class="panel-title">
                                            <span>
                                                Result
                                            </span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="min-height: 250px;">
                                    <div ng-repeat="pollingQuizQuestion in pollingQuiz.pollingQuizQuestions">
                                        <ul class="fa-ul" ng-repeat="quizOption in pollingQuizQuestion.pollingQuizQuestionOptions | orderBy:'order'">
                                            <li ng-if="quizOption.id == pollingQuizQuestionAnswers[pollingQuizQuestion.id]">
                                                <i class="fa-li fa fa-check-square" style="color: forestgreen"></i>
                                                <span ng-bind-html="quizOption.explanation"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button class="btn btn-info pull-right" ng-click="hideResult()"><i class="fa fa-arrow-left"></i> Back to Wizard</button>

                                <button class="btn btn-warning " ng-click="generatePdf()" anti-loader="pdf"><i class="fa fa-arrow-down"></i> Download Pdf</button>
                                <button class="btn btn-default disabled" loader="pdf" style="display: none"><i class="fa fa-spinner fa-pulse"></i> Please wait...</button>

                                <button class="btn btn-success" ng-click="open('emailResult.html')" anti-loader="email"><i class="fa fa-envelope"></i> Email Resultaten</button>
                                <button class="btn btn-default disabled" loader="email" style="display: none"><i class="fa fa-spinner fa-pulse"></i> Please wait...</button>
                            </div>
                        </div>
                    </div>



                    <script type="text/ng-template" id="emailResult.html">
                        <div class="modal-header">
                            <h3 class="modal-title">Email your result</h3>
                        </div>
                        <div class="modal-body">

                            <form name="emailForm" novalidate class="form-horizontal">
                                <div class="col-sm-8">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" ng-model="email" required placeholder="email to send result too">
                                    <div class="has-error" ng-show="emailForm.email.$dirty && emailForm.email.$invalid">
                                        <label class="control-label" ng-show="emailForm.email.$error.required">Email is required.</label>
                                        <label class="control-label" ng-show="emailForm.email.$error.email">Invalid email address.</label>
                                    </div>
                                </div>
                            </form>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" ng-class="{disabled:emailForm.email.$pristine || emailForm.email.$invalid}" ng-click="emailResult(email);$close();">OK</button>
                            <button class="btn btn-warning" ng-click="$dismiss()">Cancel</button>
                        </div>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/vendor/angular.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/vendor/angular-route.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/vendor/angular-sanitize.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/vendor/angular-animate.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/js/lodash.min.js", ['position' => \yii\web\View::POS_END]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/vendor/ui-bootstrap-tpls-0.12.0.min.js", ['position' => \yii\web\View::POS_END]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/app.js", ['position' => \yii\web\View::POS_END]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/controllers/PollingQuizController.js", ['position' => \yii\web\View::POS_END]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/services/PollingQuizService.js", ['position' => \yii\web\View::POS_END]);

$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/angular-wizard/angular-wizard.css", ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/angular-wizard/angular-wizard.js", ['position' => \yii\web\View::POS_END]);


$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/angular-toggle-switch/angular-toggle-switch.js", ['position' => \yii\web\View::POS_END]);
$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/angular-toggle-switch/angular-toggle-switch.css", ['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/angular-toggle-switch/angular-toggle-switch-bootstrap.css", ['position' => \yii\web\View::POS_HEAD]);


$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/loader.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/antiLoader.js", ['position' => \yii\web\View::POS_END]);



$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizModel.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizQuestionModel.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizQuestionOptionModel.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizAnswerModel.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizInviteModel.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/models/PollingQuizSettingModel.js", ['position' => \yii\web\View::POS_END]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/components/angular-xeditable/js/xeditable.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/datepick/custom-datepicker.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/file-upload/file-upload-remove.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/directives/file-upload/file-upload.js", ['position' => \yii\web\View::POS_END]);
$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/dropzone/dist/dropzone.css", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/dropzone/dist/dropzone.js", ['position' => \yii\web\View::POS_BEGIN]);


?>
<script>
    //var scope = angular.element($("#main_angular")).scope();
    //console.log("new scope");
    //console.log(scope);
</script>
