<script>
    function addMultipleFields(){
        var input_field="";
        var fieldHTML = addMultipleListing(input_field);
        var wrapper = $('.field_wrapper_response'); //Input field wrapper
        var x = $('.field_wrapper_response div.multiple_parent').length; //Initial field counter is 1

        $(wrapper).append(fieldHTML);
    }
    function  addMultipleListing(input_val){
        var input_name="polling_quiz_team[]";
        var fieldHTML = '<div class="multiple_parent row" style="padding: 10px !important;">' +
            '<div class="col-lg-12">' +
            '<div class="col-lg-6">' +
            '<input type="text" class="form-control" name="'+input_name+'" value="'+input_val+'"/>'+
            '</div>'+
            '<div class="col-lg-3">' +
            '<a href="javascript:void(0);" class="btn btn-labeled btn-danger" style="float:right" onclick="removeMultipleResponse(this)">Remove</a>'+
            '</div>'+
            '</div>' +
            '</div>';
        return fieldHTML;
    }
    function removeMultipleResponse(obj){
        $(obj).parents('div.multiple_parent').remove();
    }
</script>
<div class='multiple-response col-lg-12' style="display: block">
    <!--container add options-->
    <div class='form-group col-lg-6 '>
        <div class="row" style="padding: 10px !important;">
            <div class="col-lg-10">
                <h4 class='modal-title'><u><?= Yii::t('app', 'Add multiple teams') ?></u></h4>
            </div>
            <div class="col-lg-2">
                <a href="javascript:void(0);" class='btn btn-labeled btn-primary add_button'
                   style="float:right" onclick="addMultipleFields()">
                    Add
                </a>
            </div>

        </div>
        <div class="field_wrapper_response">
            <?php
            if (!empty($models)) {
                foreach ($models as $model) {

                    ?>
                    <script>
                        var html = addMultipleListing("<?= $model->name ?>");
                        $(".field_wrapper_response").append(html);
                    </script>

                    <?php
                }
            }
            ?>
        </div>

    </div>
    <!--end container -->
</div>
