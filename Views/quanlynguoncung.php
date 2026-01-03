<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
include "../Models/db.php";
include "../Public/Access/qlsp/headerql.php";

    if(isset($_GET['act'])){
        switch($_GET['act']){
            case 'nhacungcap':
                include "./list_nhacungcap.php";
                break;
            case 'nhasanxuat':
                include './list_nhasanxuat.php';
                break;
            default:
                include '../Public/Access/qlsp/maincontentqlnc.php' ;
                break;
        }
    } else{
        include '../Public/Access/qlsp/maincontentqlnc.php' ;
    }
?>