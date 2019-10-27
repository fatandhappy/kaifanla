<?php
/*
由myorder.html调用
根据客户端提交的电话号码，返回其所有订单
*/
header('Content-Type:application/json');
$output=[];

@$user_name=$_REQUEST['name'];
@$sex=$_REQUEST['sex'];
@$phone=$_REQUEST['phone'];
@$addr=$_REQUEST['addr'];
@$did=$_REQUEST['did'];
$order_time=time()*1000;    //php中的time()函数返回当前系统时间对应的整数值(s);

if( empty($phone)||empty($user_name)||empty($sex)||empty($addr)||empty($did) ){
    echo "[]";  //若客户端提交信息不足，则返回一个空数组
    return;     //并退出当前页面的执行
}

//访问数据库
$conn=mysqli_connect('127.0.0.1','root','','kaifanla');
$sql='SET NAMES utf8';
mysqli_query($conn,$sql);
$sql="INSERT INTO kf_order VALUES(NULL,'$phone','$user_name','$sex','$order_time','$addr','$did')";
$result=mysqli_query($conn,$sql);
$arr=[];
if($result){
    $arr['msg']='succ';
    $arr['oid']=mysqli_insert_id($conn); //获取最近执行的一条INSERT语句生成的自增主键
}else{
    $arr['msg']='err';
    $arr['reason']="SQL语句执行失败：$sql";
}
$output[]=$arr;

echo json_encode($output);
?>