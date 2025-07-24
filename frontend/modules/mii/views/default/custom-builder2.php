<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="assets/css/demo.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css">

    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>jQuery formBuilder/formRender Demo</title>
</head>

<body>
<div class="content">
    <h1>jQuery formBuilder- Custom Control</h1>
    <div class='sel-box'>
        <select name="form_type" id="form_type">
            <option value="1">Type 1</option>
            <option value="2" selected="selected">Type 2</option>
        </select>
    </div>

    <div id="fb-editor"></div>
    <textarea name="formBuilder" id="formBuilder"></textarea>
    <button onclick="getJsonData()">get Json data</button>
</div>
<script src="assets/js/vendor.js"></script>
<script src="assets/js/form-builder.min.js"></script>
<script src="assets/js/form-render.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js"></script>
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
        disabledAttrs: [],
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
            }
        },
        prefix: 'form-builder-'

    });
    function hideActionEvents(fld){
        var fData=JSON.parse('<?php echo json_encode(\frontend\modules\mii\components\MiiGlobalConstants::returnFixedFields())?>');
        for(var i in fData){
            var id=fData[i]+"-preview";
            if($(fld).find("#"+id).length>0){
                $(fld).find("div.field-actions").remove();
            }
        }
    }
    function getJsonData(){
        var jsonData=formBuilder.actions.getData();
        $.ajax({
            url:'<?php echo \yii\helpers\Url::to(['save-data']) ?>',
            type: 'post',
            data:{data:JSON.stringify(jsonData)},
            success: function(data) {

            }
        });
    }
</script>
</body>

</html>
