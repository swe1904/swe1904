<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Title of the document</title>
</head>

<body>


<div class="container">



<?php if(count($data)==0){
    echo "<h6>DATA Not found</h6>";

}
else{?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Case id</th>
            <th>Case time</th>
            <th>Case history</th>
        </tr>
        </thead>
        <tbody>
<?php for($i=0;$i<count($data);$i++){?>
    <tr >
        <td style="color:red">
            <?=$data[$i]['case_id']?>
        </td>
        <td style="color:blue">
            <?=$data[$i]['case_time']?>
        </td >
        <td  style="color:green">
            <?=$data[$i]['case_history']?>
        </td>
    </tr>
<?php }?>
<?php }?>
        </tbody>
    </table>
</div>
</body>

</html>