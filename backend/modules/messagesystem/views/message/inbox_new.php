<?php
use yii\widgets\ListView;
?>
<style>
    div.all_data ul.pagination{
        display: none;
    }
    .all-pagination .pagination{
        margin: unset
    }
    .all-pagination .pagination li a{
        border: 1px solid #ccc !important;
        padding: 5px;
    }
    .all-pagination .next span{
        border: 1px solid #ccc !important;
    }
    .view-message{
        height: 50px;
    }
</style>


<span class="btn-group pull-right">
    <span class="summary-page">

    </span>

    <span class="all-pagination">
        <ul class="pagination">

        </ul>
    </span>
</span>


<!--WorkOnProgress-->
<!--<div class="page-wrapper bg1">-->
<!--<div class="container pt-30">-->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="mail-box">
                        <div class="row">
                            <aside class="col-lg-3 col-md-4">
                                <div class="mb-15">
                                    <center style="padding-top: 20px">
                                        <button type="button" class="btn btn-orange btn-sm btn-block" onclick="composeMessage()" style="width: 64%">Compose</button>
                                    </center>

                                    <!-- Modal -->
                                    <div aria-hidden="true" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                    <h4 class="modal-title">Compose</h4>
                                                </div>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                </div>
                                <ul class="inbox-nav mb-30">
                                    <li class="active">
<!--                                        <a href="#"><i class="zmdi zmdi-inbox"></i> Inbox <span class="label label-danger ml-10">2</span></a></li>-->
                                    <li>
<!--                                        <a href="#"><i class="zmdi zmdi-email-open"></i> Sent Mail</a>-->
                                    </li>
                                    <li>
<!--                                        <a href="#"><i class="zmdi zmdi-bookmark-outline"></i> Important</a>-->
                                    </li>
                                    <li>
<!--                                        <a href="#"><i class="zmdi zmdi-folder-outline"></i> Drafts <span class="label label-info ml-10">30</span></a>-->
                                    </li>
                                    <li>
<!--                                        <a href="#"><i class="zmdi zmdi-delete"></i> Trash</a>-->
                                    </li>
                                </ul>
                            </aside>

                            <aside class="col-lg-9 col-md-8">
                                <div class="panel panel-refresh" style="padding:2rem;">
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
                                                    <input name="MessageInboxSearch[message]" value="<?= $searchModel->message?>" class="form-control" placeholder="Search" type="text">
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
                                    <div class="panel-wrapper collapse in mt-5">
                                        <div class="panel-body inbox-body pa-0">
                                            <div class="mail-option">
                                                <div class="chk-all">
                                                    <!--<div class="checkbox checkbox-default inline-block">
                                                        <input type="checkbox" id="checkbox051" onchange="selectAllCheckBox(this)"/>
                                                        <label for="checkbox051"></label>
                                                    </div>-->
<!--                                                    <div class="btn-group">-->
<!--                                                        <a data-toggle="dropdown" href="#" class="btn  all" aria-expanded="false">All-->
<!--                                                            <i class="fa fa-angle-down "></i>-->
<!--                                                        </a>-->
<!--                                                        <ul class="dropdown-menu">-->
<!--                                                            <li><a href="#"> None</a></li>-->
<!--                                                            <li><a href="#"> Read</a></li>-->
<!--                                                            <li><a href="#"> Unread</a></li>-->
<!--                                                        </ul>-->
<!--                                                    </div>-->
                                                  <!--  <div class="btn-group">
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
                                                    </div>-->
<!--                                                    <div class="btn-group">-->
<!--                                                        <a data-toggle="dropdown" href="#" class="btn  blue" aria-expanded="false">More-->
<!--                                                            <i class="fa fa-angle-down "></i>-->
<!--                                                        </a>-->
<!--                                                        <ul class="dropdown-menu">-->
<!--                                                            <li><a href="#"><i class="fa fa-pencil"></i></i> Mark as Read</a></li>-->
<!--                                                            <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>-->
<!--                                                            <li class="divider"></li>-->
<!--                                                            <li onclick="deleteMessages()"><a href="#"><i class="fa fa-trash-alt"></i> Delete</a></li>-->
<!--                                                        </ul>-->
<!--                                                    </div>-->
                                                </div>
                                                <ul class="unstyled inbox-pagination">
                                                    <span class="btn-group pull-right">
                                                        <span class="summary-page">

                                                        </span>

                                                       <span class="all-pagination">
                                                          <ul class="pagination">

                                                          </ul>
                                                        </span>
                                                    </span>
                                                </ul>
                                            </div>
                                            <div class="table-responsive mb-0">
                                                <table class="table table-inbox table-hover mb-0">
                                                    <tbody>
                                                    <?php
                                                    echo ListView::widget([
                                                        'dataProvider' => $dataProvider,
                                                        'itemView' => '_inbox_row',
                                                        'viewParams' => [
                                                            'fullView' => true,
                                                            'context' => 'main-page',
                                                            // ...
                                                        ],

                                                        'pager' => [
                                                            'prevPageLabel' => '<span class="fa fa-angle-left pagination-left"></span>',
                                                            'nextPageLabel' => '<span class="fa fa-angle-right pagination-right"></span>',
                                                            'maxButtonCount' => 0,
                                                        ],
                                                    ]);
                                                    ?>
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
</div>
<!--    </div>-->
<!--</div>-->

<!--End::WorkOnProgress-->

<script>
    jQuery("time.timeago").timeago();
</script>
<script>
    function deleteMessages(){
        var finalData=[];
        var parentObject=[];
        $("input:checkbox[name=action-msg]:checked").each(function(){
            finalData.push($(this).attr("data-value"));
            parentObject.push($(this).parents('a.list-group-item'));
        });
        if(finalData.length===0){
            return false;
        }
        var data = JSON.stringify( finalData );
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['/messageSystem/message/delete-thread']); ?>',
            data: {data:data},

            success: function(data) {
                for (var i in parentObject){
                    $(parentObject[i]).remove();
                    toastr.success("Deleted successfully");
                }
            },

        });
    }
    function changeStatus(status){
        var finalData=[];
        var parentObject=[];
        $("input:checkbox[name=action-msg]:checked").each(function(){
            finalData.push($(this).attr("data-value"));
            parentObject.push($(this).parents('a.list-group-item'));
        });
        if(finalData.length===0){
            return false;
        }
        var data = JSON.stringify( finalData );
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['/messageSystem/message/change-read-status']); ?>',
            data: {data:data,status:status},

            success: function(data) {
                for (var i in parentObject){
                    if(status===1){
                        $(parentObject[i]).removeClass("msg_unread");
                    }else{
                        $(parentObject[i]).addClass("msg_unread");
                    }
                    toastr.success("Status changed successfully");
                }
            },

        });
    }
    function selectAllCheckBox(obj){
        if($(obj).is(":checked")){
            // check all checkboxes
            $(".one-msg-check").prop("checked",true);
        }else{
            // uncheck all check boxes
            $(".one-msg-check").prop("checked",false);
        }
    }

    function paginationPrev(){
        $(".pagination li.prev a").click();
    }
    function paginationNext(){
        $(".pagination li.next a").click();
    }
</script>
<script>
    $(document).ready(function(){
        $(".summary-page").html($(".summary").html());

        // get pagination data
        $("span.all-pagination ul.pagination").html($("div.all_data ul.pagination").html());
    });
</script>