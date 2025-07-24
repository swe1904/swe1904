
<?php
$galleryData=$this->render('_gallery');
$galleryData1=htmlentities($galleryData);
$count=count($attachmentArray);
$class="";
if($count==0){
    $class="attachment_show";
}
if(!$cancel){
    $label="";
}

?>

<?php
$styleClassName="";
?>
<?php
if(!empty($style)):
    $styleClassName=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
?>
  <style>
      <?=".".$styleClassName?>{
        <?=$style?>
      }
  </style>
<?php
endif;
?>

<!--Gallery view html content-->
 <div id="handy_gallery_view" style="display: none;">
     <div id="handy_gallery_container">

         <div id="cent+er" class="handy_box">
             <div class="handy_box_shadow">
                 <img class="handy_img">
             </div>
         </div>
     </div>
     <div class="handy_actions">
         <i class="glyphicon glyphicon-download-alt handy_icon" onclick="downloadFile()"></i>
         <i id="close" class="glyphicon glyphicon-remove handy_icon"></i>
     </div>
 </div>
<!--Gallery view html content ends here-->
<div class=" <?=$class?>">
    <label class="control-label custom-label"><?=$label?></label>
    <div class="col-sm-12">
        <div class="attchment" id="<?=$uid?>">
            <?php
            $class="";
            $imgClass="";
            $extId="ex90886_4rf_4_";
            if(!$cancel){
                $class="_at_hide";
            }
            foreach ($attachmentArray as $attachment) {

                if ($attachment['extension'] == 'jpg' || $attachment['extension'] == 'png' || $attachment['extension'] == 'gif' || $attachment['extension'] == 'tif' || $attachment['extension'] == 'jpeg') {
                    $imgUrl=$obj->returnImageIcon($attachment);
                }else{
                    $imgUrl=$obj->returnExtICon($attachment['extension']);
                }

                $modelId=$attachment['id'];
                $aTagId=$extId."attachment_a_tag_".$modelId;
                $funcName=$clickFnName;
                $funcNameImage=$clickFnNameImage;
                ?>
                <!--<a onclick="downloadURI2('<?/*=$attachment['attachment']*/?>')" id="<?/*=$aTagId*/?>"><img onclick="startClickEventImage('<?/*=$funcNameImage */?>','<?/*=$modelId */?>','<?/*=$aTagId */?>','<?/*=$module_id*/?>',this)" class="_at_img" src="<?/*=$imgUrl*/?>" alt=""><i class="fa fa-times-circle <?/*=$class*/?>" aria-hidden="true" onclick="startClickEvent('<?/*=$funcName */?>','<?/*=$modelId */?>','<?/*=$aTagId */?>','<?/*=$module_id*/?>')"></i></a>-->
                <a id="<?=$aTagId?>" >
                    <img onclick="downloadURI2('<?=$attachment['attachment']?>')" class="_at_img  <?=$styleClassName?>" src="<?=$imgUrl?>" alt="" data-toggle="tooltip" data-placement="top" title="<?=$attachment['name']?>">
                    <?php
                    if($cancel):
                    ?>
                    <i class="fa fa-times-circle attachment-remove" aria-hidden="true"  data-file-id="<?= $modelId ?>"></i>
                    <?php
                        endif;
                        ?>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    function startClickEvent(funcName,modelId,aTagId,moduleId){
        window[funcName](modelId,aTagId,moduleId);
    }
    function startClickEventImage(funcName,modelId,aTagId,moduleId,obj){
        window[funcName](modelId,obj);
        // open image
        openImageGallery(moduleId,obj);
    }
    function openImageGallery(moduleId,obj){
        /*var imgSrc=$(obj).attr("src");
        var id=moduleId+"_handy_gallery_view";
        var main_cont=$("#handy_gallery_view").clone().prependTo('body');
        $(main_cont).attr("id",id);
        $(main_cont).addClass('gallery_');
        $(main_cont).find("img").attr("src",imgSrc);

        $("#"+id).find("#handy_gallery_container").click(function(){
           $("#"+id).remove();
        })
        $("#"+id).find("i#close").click(function(){
            $("#"+id).remove();
        })*/


        //var container=$('body').prepend($("#handy_gallery_view"));

    }
    function downloadURI2(uri) {
        var link = document.createElement("a");
        link.target = '_blank';
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
    function downloadFile(){
        downloadURI("data:text/html,HelloWorld!", "helloWorld.txt");
    }
    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }

</script>
