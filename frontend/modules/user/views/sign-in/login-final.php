<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<!--Preloader-->
<!-- <div class="preloader-it">
    <div class="la-anim-1"></div>
</div> -->
<!--/Preloader-->

<div class="wrapper pa-0 login-bg">

    <!-- Main Content -->
    <div class="page-wrapper pa-0 ma-0 auth-page">
        <div class="container">
            <!-- Row -->
            <div class="table-struct full-width full-height">
                <div class="table-cell vertical-align-middle auth-form-wrap">
                    <div class="auth-form  ml-auto mr-auto no-float card-view pt-30 pb-30">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="mb-30 text-center">
                                    <a href="index.html">
                                        <span class="brand-text text-center"><img  src="<?= getenv('FRONTEND_URL') ?>images/logo.png" width="150" align="center" alt="Pangea Logo"/></span>
                                    </a>
                                    <h6 class="text-center nonecase-font txt-grey">Enter your details below</h6>
                                </div>

                                <div class="form-wrap">

                            <!-- WorkOnProgress -->
                            <?php $form = ActiveForm::begin(['id' => 'login-form','options' => ['class'=>'login-form']]); ?>

                                <?= $form->field($model, 'identity', 
                                    ['template' => '<div class="form-group"><label class="control-label mb-10">{label}</label><div class="input-group"><div class="input-group-addon"><i class="icon-envelope-open"></i></div>{input}</div></div>'
                                    ])->textInput(
                                        ['placeholder' => 'Enter username or email']
                                    ) 
                                ?>

                                <?= $form->field($model, 'password', 
                                    ['template' => '<div class="form-group"><label class="control-label mb-10">{label}</label><div class="input-group"><div class="input-group-addon"><i class="icon-lock"></i></div>{input}</div></div>'
                                    ])->passwordInput(
                                        ['placeholder' => 'password']
                                    ) 
                                ?>

                                <?php /*echo $form->field($model, 'rememberMe', ['template' => '<div class="form-group">
                                            <div class="checkbox checkbox-primary pr-10 pull-left">
                                                {input}
                                            </div><br><br>
                                        </div>'])->checkbox() */?>

                                    <br>
                                <div class="form-group text-center">
                                    <?php echo Html::submitButton(Yii::t('frontend', 'Sign in'), ['class' => 'btn btn-orange btn-rounded', 'name' => 'login-button']) ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Row -->
        </div>

    </div>
    <!-- /Main Content -->

</div>
<!-- /#wrapper -->