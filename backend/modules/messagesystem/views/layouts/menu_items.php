<?php
use yii\helpers\Url;
use common\models\User;
?>

<ul>
    <li>
        <a  href="<?php echo Url::to(['message/inbox']);?>"><i class="fa fa-inbox"></i> Inbox <span class="label label-danger">4</span></a>
    </li>
    <li>
        <a href="page-inbox.html#"><i class="fa fa-star"></i> Stared</a>
    </li>
    <li>
        <a href="<?php echo Url::to(['message/outbox']);?>"><i class="fa fa-rocket"></i> Sent</a>
    </li>
    <li>
        <a href="page-inbox.html#"><i class="fa fa-trash-o"></i> Trash</a>
    </li>
    <li>
        <a href="page-inbox.html#"><i class="fa fa-bookmark"></i> Important<span class="label label-info">5</span></a>
    </li>
    <li class="title">
        Labels
    </li>
    <li>
        <a href="page-inbox.html#">Home <span class="label label-danger"></span></a>
    </li>
    <li>
        <a href="page-inbox.html#">Job <span class="label label-info"></span></a>
    </li>
    <li>
        <a href="page-inbox.html#">Clients <span class="label label-success"></span></a>
    </li>
    <li>
        <a href="page-inbox.html#">News <span class="label label-warning"></span></a>
    </li>
</ul>