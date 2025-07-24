angular.module('file-upload', ['ng']).directive('fileUpload', function () {
    return {
        link: function(scope,element, attrs) {
            var uniqueId=attrs.sessionId+"_"+scope.uId;
            scope.manage(scope.uId,uniqueId);
            var url='"../default/upload-temp-file?session_id='+attrs.sessionId+"_"+scope.uId+'"';
           var htmlText="<div id='upload_file_"+scope.uId+"' class='dropzone'></div>"+
           "<script> $(function(){" +
               "var myDropzone = new Dropzone('div#upload_file_"+scope.uId+"', { url:"+url+",paramName:'attachment',maxFilesize:'20',addRemoveLinks:true,}); " +
               "myDropzone.on('success', function(file,response) {" +
                "$(file.previewElement).find('div.dz-image').attr('data-uid',response.id);console.log(file.previewElement);"+
               "});"+
               "myDropzone.on('removedfile', function(file) {" +
               "var uid=$(file.previewElement).find('div.dz-image').attr('data-uid');"+
               "removeUploadedFile(uid);"+
               "});"+
               "});</script>";
            element.replaceWith(htmlText);
        },
        scope: {
            uId: "=",
            sessionId:"=",
            manage:"="
        }
    };
});
