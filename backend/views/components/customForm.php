<!-- Path: @app/views/components/filterForm.php -->
<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="<?=$formId ?>" class="customForm-container">
        <div class="form-content">
            <div class="form-header">
                <h2><?= $formHeading ?></h2><span class="close-button close-form">&times;</span>
            </div>


            <?php $form = ActiveForm::begin( [
                    
                    'options' => [
                        'class' => $formClass,
                        'id'=> $formId,
                    ],
                ],
                ); ?>
                <div class="row">

                <?php foreach ($options as $option): ?>
                    <div class="col-md-12">
                        <?php 
                        $defaultInputOptions = [
                            'class' => 'form-control',
                        ];
                       
                       if ((isset($option['condition'])) && $option['condition'])  : ?>
                        <!-- When conditions are specified -->

                            <?php if($option['value']) : ?>
                                <!-- Hidden when value is specified -->
                              <?= $form->field($option['model'], $option['attribute'])->label(false)->hiddenInput(['value' => $option['value']]) ?>
                            
                            <?php else : ?>
                                <?= $form->field($option['model'], $option['attribute'])->label($option['label']) ?>
                            <?php endif; ?>

                        <?php elseif ((isset($option['widgetClass']))): ?>
                            <!-- When widgets are specified -->
                            <?= $form->field($option['model'], $option['attribute'])->label($option['label'])->widget($option['widgetClass'], $option['widgetOptions']) ?>

                        <?php elseif ((isset($option['dropdown']))): ?>
                           <!-- When widgets are specified -->
                           <?= $form->field($option['model'], $option['attribute'])->dropDownList($option['dropdown'][0], $option   ['dropdown'][1]); ?>

                        <?php else : ?>

                            <?php 
                             $customInputOptions = isset($option['inputOptions']) ? $option['inputOptions'] : [];
                             $finalInputOptions = array_merge($defaultInputOptions, $customInputOptions);
                            ?>
                            <?= $form->field($option['model'], $option['attribute'], ['template' => isset($option['template']) ? $option['template'] : "{label}\n{input}\n{hint}\n{error}",])->textInput($finalInputOptions); ?>


                        <?php endif; ?>


                    </div>
                <?php endforeach; ?>

                    
                    <?php if (isset($instruction)): ?>
                        <div class = "col-md-12">
                           <?= $instruction?>
                        </div>
                    <?php endif; ?>
                            
               
            
                    <div class="col-md-12">
                         <?= Html::submitButton(
                             $submitButtonTextCondition == 1 ? Yii::t('backend', $submitButtonTextConditionIfYes) : Yii::t('backend', $submitButtonTextConditionIfNo),[ 'class' => $submitButtonClass . ' btn btn-sm btn-rounded mt-20', 
                             ]
                         ) ?>
                    </div>

                </div>
            <?php ActiveForm::end(); ?>
        </div>

                    
</div>
<script>
     document.addEventListener("DOMContentLoaded", function () {
            // Open the form from the right
            var form = document.getElementById("<?= $formId ?>");
            // form.style.right = "0";

            var btn = document.getElementById("open-<?= $formId ?>");
            btn.addEventListener('click', function () {
                form.style.right = "0";

            })

            // Close the form
           

            document.querySelector(".close-modal").addEventListener("click", function () {
                var modal = new bootstrap.Modal(document.getElementById("exampleModal"));
                modal.hide();

            });
           



        });
</script>