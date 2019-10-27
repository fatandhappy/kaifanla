<?php
/*
由myorder.html调用
根据客户端提交的电话号码，返回其所有订单
*/
header('Content-Type:application/json');
$output=[];

@$phone=$_REQUEST['phone'];
if(empty($phone)){
    echo "[]";  //若客户端未提交查询关键字，则返回一个空数组
    return;     //并退出当前页面的执行
}

//访问数据库
$conn=mysqli_connect('127.0.0.1','root','','kaifanla');
$sql='SET NAMES utf8';
mysqli_query($conn,$sql);
$sql="SELECT o.user_name,o.order_time,o.did,d.did,d.img_sm FROM kf_dish d,kf_order o WHERE o.did=d.did AND o.phone='$phone'";
$result=mysqli_query($conn,$sql);
while( ($row=mysqli_fetch_assoc($result))!==NULL ){
    $output[]=$row;
}

echo json_encode($output);
?>