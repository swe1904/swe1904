
<!-- Preloader -->
<div class="preloader-it">
    <div class="la-anim-1"></div>
</div>
<!-- /Preloader -->
<div class="wrapper theme-2-active navbar-top-light horizontal-nav">

    <!-- Main Content -->

            <!-- Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default card-view border-panel panel-refresh">
                        <div class="refresh-container">
                            <div class="la-anim-1"></div>
                        </div>
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h6 class="panel-title txt-dark">Create/Update Questionnaire Question</h6>
                                <p><i class="fa fa-warning"></i> The first question must be linked to 'email' Applicant Attribute.</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body pa-0 row">
                                <div class="col-md-12">
                                    <?= $this->render('_form_final', [
                                        'model' => $model,
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Row -->
    <!-- /Main Content -->

</div>
