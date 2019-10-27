<?php
/*
由main.html调用
根据客户端提交的菜品编号，返回指定菜品的详情
*/
header('Content-Type:application/json');
$output=[];

@$did=$_REQUEST['did'];
if(empty($did)){
    echo "[]";  //若客户端未提交编号，则返回一个空数组
    return;     //并退出当前页面的执行
}

//访问数据库
$conn=mysqli_connect('127.0.0.1','root','','kaifanla');
$sql='SET NAMES utf8';
mysqli_query($conn,$sql);
$sql="SELECT did,name,img_lg,material,detail,price FROM kf_dish WHERE did=$did";
$result=mysqli_query($conn,$sql);
//根据编号查询，结果集最多只有一行记录
if( ($row=mysqli_fetch_assoc($result))!==NULL ){
    $output[]=$row;
}

echo json_encode($output);
?>