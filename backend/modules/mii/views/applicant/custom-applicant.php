<!DOCTYPE html>
<html>

<head>
<!--    <link rel="stylesheet" type="text/css" href="assets/css/demo.css">-->
<!--    <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css">-->

    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>jQuery formBuilder/formRender Demo</title>
</head>

<body>
<?php

$this->title = Yii::t('backend', 'Applicant Dynamic Form');
$this->params['breadcrumbs'][] = ['label' => 'Configure / ' . $this->title];

?>
<style>
    label{position: relative}
</style>
<div class="row">
<div class="card col-md-12" style="margin:0px;">
    <div class="">
        <div class="">
            <h5 style="margin-bottom: 10px;">Create Applicant</h5>
            <div class="text-danger">Danger: Don’t delete or update any existing fields. This can cause loss of data.
However you may drag to add new fields.<br>Deleting a field will also remove it from all the case types which is using it.<br>Also after an update here the following code files are regenerated which may be added to Git by the developer:<br>
\backend\controllers\ApplicantController.php<br> 
\backend\models\Applicant.php<br>
\backend\views\applicant\_form.php<br>
\backend\views\applicant\_search.php<br>
\backend\views\applicant\index.php<br>
\backend\views\applicant\view.php<br>
\backend\views\applicant\create.php<br>
</div>

    <div id="fb-editor"></div>
    <textarea name="formBuilder" id="formBuilder"></textarea>
    <button class="btn btn-sm btn-rounded btn-success mt-15 ml-10 col-lg-pull-1" onclick="getJsonData()">Update</button>
</div>
    </div>
</div>
</div>
<!--<script src="assets/js/vendor.js"></script>-->
<!--<script src="assets/js/form-builder.min.js"></script>-->
<!--<script src="assets/js/form-render.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js"></script>-->
<script>

    var data=JSON.parse('<?=json_encode($defaultVal)?>');
    var formBuilder= $('textarea').formBuilder({

        controlPosition: 'right',
        append: false,
        actionButtons: [],
        controlOrder: [
            // 'autocomplete',
            // 'button',


            '<a href="https://www.jqueryscript.net/time-clock/">date</a>',
            'file',
            // 'header',
            // 'hidden',
            // 'paragraph',
            // 'number',

            'select',
            'text',
            'textarea'
        ],
        dataType: 'json',
        // Array of fields to disable
        disableFields: [
            'radio-group',
            'checkbox-group',
            'checkbox',
            'header',
            'hidden',
            'paragraph',
            'number',
            'autocomplete',
            'button',
        ],
        disabledAttrs: ['required', 'multiple'],
        disabledActionButtons: [],
        editOnAdd: false,
        // Uneditable fields or other content you would like to appear
        // before and after regular fields:
        // array of objects with fields values
        // ex:
        defaultFields: data,
        fields: [],
        fieldRemoveWarn: true,
        inputSets: [],
        replaceFields: [],
        // roles: {
        //     1: 'Administrator'
        // },
        notify: {
            error: message => console.error(message),
            success: message => console.log(message),
            warning: message => console.warn(message)
        },
        onSave: (evt, formData) => null,
        onClearAll: () => null,
        prepend: false,
        sortableControls: false,
        stickyControls: {
            enable: true,
            offset: {
                top: 5,
                bottom: 'auto',
                right: 'auto'
            }
        },
        templates: {},
        showActionButtons: false,
        typeUserDisabledAttrs: {},
        typeUserAttrs: {},
        typeUserEvents: {
            text: {
                onadd: function(fld) {
                    hideActionEvents(fld);
                    //$(fld).find("div.field-actions");
                }
            },
            textarea: {
                onadd: function(fld) {
                   hideActionEvents(fld);
                    //$(fld).find("div.field-actions");
                }
            },
            date: {
                onadd: function(fld) {
                    hideActionEvents(fld);
                    //$(fld).find("div.field-actions");
                }
            },
            select: {
                onadd: function(fld) {
                    hideActionEvents(fld);
                    //$(fld).find("div.field-actions");
                }
            },
            file: {
                onadd: function(fld) {
                    hideActionEvents(fld);
                    //$(fld).find("div.field-actions");
                }
            }
        },
        disabledSubtypes: {
            text: ['password'],
            file: ['fineuploader'],
        },
        prefix: 'form-builder-'

    });
    function hideActionEvents(fld){
        var fData=JSON.parse('<?php echo json_encode(\backend\modules\mii\components\MiiGlobalConstants::returnApplicantFixedFields())?>');
        for(var i in fData){
            var id=fData[i]+"-preview";
            if($(fld).find("#"+id).length>0){
                $(fld).find("div.field-actions").remove();
            }
        }
    }
    function getJsonData(){
        if ( confirm("Are you sure that you want to change structure of applicant data? If any field is deleted, it cannot be reverted and corresponding data will be deleted.")) {
            var jsonData = formBuilder.actions.getData();
            $.ajax({
                url: '<?php echo \yii\helpers\Url::to(['save-data']) ?>',
                type: 'post',
                data: {data: JSON.stringify(jsonData)},
                success: function (data) {
                //    location.reload();
//                console.log('success');
                    toastr.success("Applicant design has been updated");
                }
            });
        }
    }
</script>
</body>

</html>
