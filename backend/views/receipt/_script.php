<?php
use yii\helpers\Url;

?>
<script>
    var originalVatRate;
  
  function addRowReceiptItem(sectionId,vatRate = null, description = null, quantity = 1, value = null, existingServices = null ) {
   
        var data = $('#section-' + sectionId + ' :input').serializeArray();
      
        if(existingServices)
            data = existingServices;
        
        if (description !== null && value != null) {
            var temp = [
                {name: 'ReceiptItem-1[0][id]', value: ''},
                {name: 'ReceiptItem-1[0][section_id]', value: sectionId},
                {name: 'ReceiptItem-1[0][description]', value: description},
                {name: 'ReceiptItem-1[0][quantity]', value: quantity},
                {name: 'ReceiptItem-1[0][price]', value: Math.ceil(value)},
                {name: 'ReceiptItem-1[0][price_sub_total]', value: price_sub_total},
                {name: 'Children[0][id]', value: ''},
            ]

            //updating Leave Deduction and keeping other existing ones too
            data = [...temp, ...data.slice(6)];
        } else if(!existingServices){
            data = $('#section-' + sectionId + ' :input').serializeArray();
            data.push({name: '_action', value : 'add'});
            data.push({name: '_csrf', value : yii.getCsrfToken()});
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo Url::to(['add-'.'receipt-item']); ?>' + '?sectionId=' + sectionId,
            data: data,
            success: function (data) {
                $('#section-' + sectionId).html(data);
                  $('#section-' + sectionId).find('.vat_rate_input').val(0);
                  originalVatRate =   vatRate
                setTimeout(function(){ setTotalSums(); }, 500);
                $('input[id^="receiptitem-1"][id $="quantity"]').each(function() {
                    //default quantity set to 1 on new row add
                    if (!($(this).val())) {
                        $(this).val('1');
                    }
                })
                $('input[id^="receiptitem-2"][id $="quantity"]').each(function() {
                    //default quantity set to 1 on new row add
                    if (!($(this).val())) {
                        $(this).val('1');
                    }
                })
            }
        });
        setTimeout(function(){ setTotalSums(); }, 500);

    }
    function delRowReceiptItem(id, sectionId) {
        $('#section-' + sectionId + ' tr[data-key=' + id + ']').remove();
        setTimeout(function(){ setTotalSums(); }, 500);
    }
</script>