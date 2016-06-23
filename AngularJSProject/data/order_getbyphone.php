<?php
/**
*用途：为myorder.html返回指定电话号码的所有订单
*数据格式：    JSON格式，形如：
*  [{"oid":18,"order_time":123456789456","user_name":"收货人","did":0,"name":"菜名","price":35.5,"img_sm":"img/1.jpg"},
    {...},
    {...},
    {...}
    ]
   *详细说明，根据客户的电话号码返回该电话所下的所有订单，若未提交电话，返回空数组。
   *作者：潘凤山
   *创建时间：2016-3-15  10:10:25
   *修改时间：2016-3-16  20:20:20
*/
header('Content-Type:application/json;charset=UTF-8');
$output=[];

@$phone=$_REQUEST['phone'];
if($phone==NULL){
    echo '[]';
    return;
}

include('config.php');
$conn = mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_port);
$sql = "SET NAMES UTF8";
mysqli_query($conn,$sql);
$sql = "SELECT oid,user_name,order_time,img_sm,name,kf_dish.did FROM kf_order,kf_dish WHERE phone='$phone' AND kf_order.did=kf_dish.did";  //跨表查询技术
$result = mysqli_query($conn,$sql);
while( ($row=mysqli_fetch_assoc($result))!=NULL ){
     $output[] = $row;
 }


echo json_encode($output);
?>