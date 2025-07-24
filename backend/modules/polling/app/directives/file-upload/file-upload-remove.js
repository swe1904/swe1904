function removeUploadedFile(id){
    $.ajax({
        type: 'POST',
        url: '../default/delete-temp-file',
        data: {id:id},
        dataType:'html',

        success: function(data) {
            console.log(data);
        },

    });
}