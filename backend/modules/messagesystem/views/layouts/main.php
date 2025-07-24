<?php
/* @var $this \yii\web\View */
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $content string */

$this->beginContent('@backend/modules/messagesystem/views/layouts/base.php')
?>
        <div class="container">
            <div class="row inbox">


                <div class="col-md-3">
                    <div class="panel panel-default">

                        <div class="panel-body inbox-menu">

                            <a href="page-inbox-compose.html" class="btn btn-danger btn-block">New Email</a>
                            <?=$this->render('menu_items')?>
                        </div>

                    </div>
                    <div class="panel panel-default">

                        <div class="panel-body contacts">

                            <a href="page-inbox.html#" class="btn btn-success btn-block"> + Contact</a>

                            <ul>
                                <li><span class="label label-danger"></span> Adam Alister</li>
                                <li><span class="label label-default"></span> Alphonse Ivo</li>
                                <li><span class="label label-success"></span> Anton Phunihel</li>
                                <li><span class="label label-success"></span> Ajith Hristijan</li>
                                <li><span class="label label-warning"></span> Bao Gaspar</li>
                                <li><span class="label label-default"></span> Bernhard Shelah</li>
                                <li><span class="label label-success"></span> Bünyamin Kasper</li>
                                <li><span class="label label-danger"></span> Carlito Roffe</li>
                                <li><span class="label label-danger"></span> Chidubem Gottlob</li>
                                <li><span class="label label-warning"></span> Dederick Mihail</li>
                                <li><span class="label label-success"></span> Felice Arseniy</li>
                                <li><span class="label label-default"></span> Grahame Miodrag</li>
                                <li><span class="label label-default"></span> Hristofor Sergio</li>
                                <li><span class="label label-danger"></span> Scottie Maximilian</li>
                                <li><span class="label label-danger"></span> Sullivan Robert</li>
                                <li><span class="label label-danger"></span> Thancmar Theophanes</li>
                                <li><span class="label label-warning"></span> Tullio Luka</li>
                                <li><span class="label label-success"></span> Walerian Khwaja</li>
                            </ul>

                        </div>

                    </div>
                </div>
                <div class="col-md-9">
                    <?= $content ?>
                </div>
            </div>
        </div>

<?php $this->endContent() ?>