<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
include "../Models/db.php";
include "../Public/Access/dboard/header.php";

    if(isset($_GET['act'])){
        switch($_GET['act']){
            case 'danhmucsanpham':
                include "./list_danhmuc.php";
                break;
            case 'loaisanpham':
                include './list_loaisp.php';
                break;
            case 'sanpham':
                include './list_sanpham.php';
                break;
            default:
            include '../Public/Access/qlsp/maincontentqlsp.php' ;
            //include './danhsachsanpham.php';
                break;
        }
    } else{
        include '../Public/Access/qlsp/maincontentqlsp.php' ;
        //include './danhsachsanpham.php';
    }
?>