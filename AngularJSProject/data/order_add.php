<?php
/**
*用途：接收order.html提交的订单数据，生成订单
*数据格式：    JSON格式，形如：
*  {"status":200, "reason":6}
或者
    {"status":400, "reason":"客户端提交的请求数据不足问题"}
   或者
   {"status":500, "reason":"服务器端执行出错"}
   *详细说明，接收客户端提交的订单数据，若有问题，则提示客户端请求有问题，若执行成功，保存订单数据，返回订单编号。
   *作者：潘凤山
   *创建时间：2016-3-15  10:10:25
   *修改时间：2016-3-16  20:20:20
*/
header('Content-Type:application/json;charset=UTF-8');
$output=["status"=>0,"reason"=>''];

@$user_name = $_REQUEST['user_name'];  //@符号压制当前行代码抛出的警告消息
@$sex = $_REQUEST['sex'];
@$phone = $_REQUEST['phone'];
@$addr = $_REQUEST['addr'];
@$did = $_REQUEST['did'];

if($user_name==NULL){
    $output['reason'] = '接收人不能为空';
}else if($sex==NULL){
    $output['reason'] = '性别不能为空';
}else if($phone==NULL){
    $output['reason'] = '电话号码不能为空';
}else if($addr==NULL){
    $output['reason'] = '收货地址不能为空';
}else if($did==NULL){
     $output['reason'] = '菜品编号不能为空';
 }
//只要前面的检验有任何错误，则直接向客户端返回一个表示错误信息的JSON对象
if($output['reason']){
    $output['status'] = 400;
    echo json_encode($output);
    return;
}

@$order_time = time()*1000;  //php中的time函数返回当前的系统时间（整型）


include('config.php');
 $conn = mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_port);
 $sql = "SET NAMES UTF8";
 mysqli_query($conn,$sql);
 $sql = "INSERT INTO kf_order VALUES(NULL,'$phone','$user_name','$sex','$order_time','$addr','$did')";
 $result = mysqli_query($conn,$sql);
if($result){        //执行成功
    $output['status'] = 200;
    $output['reason'] = mysqli_insert_id($conn);  //返回刚刚执行成功的INSERT语句产生的自增编号
}else{          //执行失败
    $output['status'] = 500;
    $output['reason'] = '订单提交失败！服务器端错误！请检查您的SQL语句:' .$sql;
}

echo json_encode($output);
?>