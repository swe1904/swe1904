<!--***** Impersonation: START *****-->
<?php
use common\models\User;
use yii\helpers\Html;

$originalId = Yii::$app->session->get('user.idbeforeswitch');

if (isset($originalId) && !empty($originalId)):

    $originalUser = User::findOne($originalId);
    ?>


    <?php $parents = \Yii::$app->authManager->getRolesByUser($originalId);
    /*     foreach ($parents as $parent) {
             $childs = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
             foreach ($childs as $child) { */?><!--
                    <strong> '<?php /*echo Yii::$app->user->identity->username */?>
                        ' </strong>&nbsp;(<?php /*echo $child->name */?>) <?php /*echo Yii::t('backend', 'Impersonated by') */?>
                    <strong> '<?php /*echo $originalUser->username; */?>' </strong>&nbsp;(<?php /*echo $parent->name */?>)
                --><?php /*}
            }*/
    ?>
    <?php
    $unimpersonate =' <span class="impersonate-user" style="vertical-align:middle;">'.Yii::t('backend', 'Click here to Unimpersonate').'</span>';
    $url = Yii::$app->urlManager->createUrl(['/user/unimpersonate']);
    echo Html::a(' ' . $unimpersonate, $url, ['title' => Yii::$app->user->identity->username.' Impersonated by '.$originalUser->username]);
    ?>

<?php endif; ?>
<!--***** Impersonation: END *****-->
