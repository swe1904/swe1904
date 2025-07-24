<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

?>

<?php
$img=\frontend\models\RoomListing::getNoPropertyImage();
if(!empty($model->renterUploads)){
    $img=$model->renterUploads[0]->attachment;
}

?>
<div class="global_listing_all global_listing_search msg_room_list">
    <div class="row margin_unset view">
        <div class="col-md-4 col-sm-12 col-xs-12 pad_unset border-r main_c">
            <div class="img_cont_2 hover_effect">
                <a href="<?php echo Url::to(['/room-listing/room-detail','id'=>$model->id])?>" class="hover-effect">
                    <img class="img-responsive img-list" src="<?=$img?>">
                </a>
                <?php if($model->is_verified){
                    ?>
                    <img class="img-responsive verified-img" style="position: absolute;top: 0px;" src="<?=Yii::$app->urlManager->baseUrl."/img/verified.png";?>">
                    <?php
                }
                ?>
            </div>

        </div>
        <div class="col-md-8 col-xs-12 col-sm-12">
            <table class="table">
                <thead>
                <tr>
                    <td width="85%">
                        <div class="container main_cont">
                            <div class="row margin_unset">
                                <a href="<?php echo Url::to(['/room-listing/room-detail','id'=>$model->id])?>">
                                    <h2 class="property_title_"><?php echo $model->title;?></h2>
                                </a>

                                <address class="property-address"><?php echo $model->address;?></address>
                                <div class="col-md-12 pad_unset">
                                    <span class="availableFrom">
                                        Available From :- <?php echo $model->available_from;?>
                                    </span>
                                </div>
                                <div class="col-md-12 pad_unset date">
                                    <p><i class="fa fa-calendar"></i><?php echo Yii::$app->formatter->format( $model->date_created, 'relativeTime') ?></p>
                                </div>
                            </div>

                        </div>
                    </td>
                    <td>

                    </td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row margin_unset">

    </div>
    <div class="row margin_unset">
        <div class="col-md-5 col-xs-12 pull-right _pad_bottom">
            <div class="row margin_unset ">


            </div>
        </div>
    </div>
</div>