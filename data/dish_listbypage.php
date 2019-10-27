<?php
/*
由main.html调用
根据客户端提交的菜品的序号，分页返回后续的5条菜品
*/
header('Content-Type:application/json');
$output=[];

$count=5; //一次最多返回的记录条数
@$start=$_REQUEST['start']; //客户端提交的起始记录，@用于压制该行代码产生的错误/警告消息
if(empty($start)){      //empty()函数用于判断一个变量是否为空
    $start=0;
}

//访问数据库
$conn=mysqli_connect('127.0.0.1','root','','kaifanla');
$sql='SET NAMES utf8';
mysqli_query($conn,$sql);
$sql="SELECT did,name,img_sm,material,price FROM kf_dish LIMIT $start,$count";
$result=mysqli_query($conn,$sql);
while( ($row=mysqli_fetch_assoc($result))!==NULL ){
    $output[]=$row;
}

echo json_encode($output);
?>