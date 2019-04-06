<?php
    //$link_id=mysql_connect('主机名','用户','密码');
    $link_id=mysql_connect('10.0.0.51','aixmile','123456');
    if($link_id){
        echo "mysql successful by root !\n";
    }else{
        echo mysql_error();
    }
?>
