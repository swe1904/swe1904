<?php
use yii\helpers\Url;

?>
<script>
    function addRowSlipItem(sectionId, description = null, value = null) {
        var data = $('#section-' + sectionId + ' :input').serializeArray();;
        if (description !== null && value != null) {
            let temp = [
                {name: 'SlipItem-1[0][id]', value: ''},
                {name: 'SlipItem-1[0][section_id]', value: sectionId},
                {name: 'SlipItem-1[0][description]', value: description},
                {name: 'SlipItem-1[0][value]', value: Math.ceil(value)},
                {name: 'Children[0][id]', value: ''},
            ]

            //updating Leave Deduction and keeping other existing ones too
            data = [...temp, ...data.slice(6)];
        } else {
            data = $('#section-' + sectionId + ' :input').serializeArray();
            data.push({name: '_action', value : 'add'});
            data.push({name: '_csrf', value : yii.getCsrfToken()});
        }
    
        $.ajax({
            type: 'POST',
            url: '<?php echo Url::to(['add-'.'slip-item']); ?>' + '?sectionId=' + sectionId,
            data: data,
            success: function (data) {
                $('#section-' + sectionId).html(data);
                setTimeout(function(){ setTotalSums(); }, 500);
            }
        });
        setTimeout(function(){ setTotalSums(); }, 500);

    }
    function delRowSlipItem(id, sectionId) {
        $('#section-' + sectionId + ' tr[data-key=' + id + ']').remove();
        setTimeout(function(){ setTotalSums(); }, 500);
    }
</script>