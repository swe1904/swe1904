<div class="container pt-30">
    <!-- Title -->
    <div class="row heading-bg">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">inbox</h5>
        </div>
        <!-- Breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="index.html">Dashboard</a></li>
                <li class="active"><span>inbox</span></li>
            </ol>
        </div>
        <!-- /Breadcrumb -->
    </div>
    <!-- /Title -->

    <!-- Row -->

    <div class="col-lg-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="mail-box">
                            <div class="">
                                <aside class="col-lg-2 col-md-3 col-xs-12">
                                    <div class="mb-15">
                                        <button type="button" class="btn btn-orange btn-sm btn-block" onclick="composeMessage()">Compose</button>
                                        <!--                                        <a href="#myModal" data-toggle="modal"  title="Compose"    class="btn btn-orange btn-sm btn-block">Compose</a>-->
                                        <div aria-hidden="true" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                        <h4 class="modal-title">Compose</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form role="form" class="form-horizontal">
                                                            <div class="form-group">
                                                                <label class="col-lg-2 control-label">To</label>
                                                                <div class="col-lg-10">
                                                                    <input type="text" placeholder="" id="inputEmail1" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-lg-2 control-label">Cc / Bcc</label>
                                                                <div class="col-lg-10">
                                                                    <input type="text" placeholder="" id="cc" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-lg-2 control-label">Subject</label>
                                                                <div class="col-lg-10">
                                                                    <input type="text" placeholder="" id="inputPassword1" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-lg-2 control-label">Message</label>
                                                                <div class="col-lg-10">
                                                                    <textarea class="textarea_editor form-control" rows="15" placeholder="Enter text ..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-lg-offset-2 col-lg-10">
                                                                    <div class="fileupload btn btn-orange btn-anim mr-10">
                                                                        <i class="fa fa-paperclip"></i>
                                                                        <span class="btn-text">attachments</span>
                                                                        <input type="file" class="upload">
                                                                    </div>
                                                                    <button class="btn btn-default" type="submit">Send</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </div>
                                    <ul class="inbox-nav mb-30">
<!--                                        <li class="active"><a href="#"><i class="zmdi zmdi-inbox"></i> Inbox <span class="label label-danger ml-10">2</span></a></li>-->
<!--                                        <li><a href="#"><i class="zmdi zmdi-email-open"></i> Sent Mail</a></li>-->
<!--                                        <li><a href="#"><i class="zmdi zmdi-bookmark-outline"></i> Important</a></li>-->
<!--                                        <li><a href="#"><i class="zmdi zmdi-folder-outline"></i> Drafts <span class="label label-info ml-10">30</span></a></li>-->
<!--                                        <li><a href="#"><i class="zmdi zmdi-delete"></i> Trash</a></li>-->
                                    </ul>
                                </aside>

                                <aside class="col-lg-10 col-md-9 col-xs-12 pl-0">
                                    <div class="panel panel-refresh pa-0">
                                        <div class="refresh-container">
                                            <div class="la-anim-1"></div>
                                        </div>
                                        <div class="panel-heading pt-20 pb-20 pl-15 pr-15">
                                            <div class="pull-left">
                                                <h6 class="panel-title txt-dark">inbox</h6>
                                            </div>
                                            <div class="pull-right">
                                                <form role="search" class="inbox-search inline-block pull-left mr-15">
                                                    <div class="input-group">
                                                        <input name="example-input1-group2" class="form-control" placeholder="Search" type="text">
                                                        <span class="input-group-btn">
																	<button type="button" class="btn  btn-default" data-target="#search_form" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="zmdi zmdi-search"></i></button>
                                                        </span>
                                                    </div>
                                                </form>
                                                <a href="#" class="pull-left inline-block refresh">
                                                    <i class="zmdi zmdi-replay"></i>
                                                </a>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body inbox-body pa-0">
                                                <div class="mail-option pl-15 pr-15">
                                                    <div class="chk-all">
                                                        <div class="checkbox checkbox-default inline-block">
                                                            <input type="checkbox" id="checkbox051"/>
                                                            <label for="checkbox051"></label>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a data-toggle="dropdown" href="#" class="btn  all" aria-expanded="false">All
                                                                <i class="fa fa-angle-down "></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="#"> None</a></li>
                                                                <li><a href="#"> Read</a></li>
                                                                <li><a href="#"> Unread</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a data-toggle="dropdown" href="#" class="btn  blue">Move to
                                                                <i class="fa fa-angle-down "></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="#">Personal</a></li>
                                                                <li><a href="#">Social</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="#">Promotional</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="#">Updates</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a data-toggle="dropdown" href="#" class="btn  blue" aria-expanded="false">More
                                                                <i class="fa fa-angle-down "></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
                                                                <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <ul class="unstyled inbox-pagination">
                                                        <li><span>1-10 of 234</span></li>
                                                        <li><a class="pl-15 pr-15" href="#"><i class="fa fa-angle-left pagination-left"></i></a></li>
                                                        <li><a href="#"><i class="fa fa-angle-right pagination-right"></i></a></li>
                                                    </ul>
                                                </div>

                                                <div class="table-responsive mb-0">
                                                    <table class="table table-inbox table-hover mb-0">
                                                        <tbody>

                                                        <?php

                                                        use yii\widgets\ListView;

                                                        echo ListView::widget([
                                                            'dataProvider' => $dataProvider,
                                                            'itemView' => '_inbox_row',
                                                            'viewParams' => [
                                                                'fullView' => true,
                                                                'context' => 'main-page',
                                                                // ...
                                                            ],
                                                            'pager' => [
                                                                'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
                                                                'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
                                                                'maxButtonCount' => 0,
                                                            ],
                                                        ]);
                                                        ?>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- /Row -->
</div>