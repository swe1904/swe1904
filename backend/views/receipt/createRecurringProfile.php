<div class="container" style="padding: 20px;">
<?php
if($createRPProfileResponse->Ack=='Success'){
    echo '<b>Monthly subscription activated</b>';
    echo '<br/>';
    echo '<br/>';
    echo '<b>Profile Id: </b>'.$createRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID;;
}else{
    echo '<h3>Monthly subscription Failure</h3>';
}
?>
</div>