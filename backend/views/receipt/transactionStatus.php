<?php
echo "<table>";
echo "<tr><td>Ack :</td><td><div id='Ack'>$DoECResponse->Ack</div> </td></tr>";
if(isset($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo)){
    echo "<tr><td>TransactionID: </td><td><div id='TransactionID'>". $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID."</div> </td></tr>";
}else{
    echo 'Sorry the transaction has been failed';
}
