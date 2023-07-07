<?php
if(isset($_POST))
{
    $s['data']=$_POST;
    $s['success']=true;

   echo json_encode($s);
}
else
{
   
    $s['data']="please input";
    $s['success']=false;

     echo json_encode($s);
}

