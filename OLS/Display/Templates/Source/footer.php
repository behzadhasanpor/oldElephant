<?php
include_once('VariableReference.php');
echo $scripts;
?>
    </body>
</html>
<?php
if($returned_details=="refresh-need"){
    return [
        'redirect_flag'=>true,
        'redirect_url'=>'dashboard'
    ];
}else{
    return [
        'redirect_flag'=>false
    ];
}
?>