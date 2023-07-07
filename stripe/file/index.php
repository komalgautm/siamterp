<?php



if(!isset($_POST['file_text']))
{
    echo"Please provide file_text";
    die;
}
$url = '1.jpg';
$img = "img/".date('ymdHis').'test.jpg';
file_put_contents($img,$_POST['file_text']);
?>