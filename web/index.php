<?php

const IS_DEV = true;

if(IS_DEV){
    require_once('app_dev.php');
}else{
    require_once('app.php');
}

?>