<?php
/**
*用途：为detail.html分页返回某一道菜品详情
*数据格式：    JSON格式，形如：
*  [{"did":18,"name":"菜名","price":35.5,"material":"原材料","img_lg":"img/1.jpg","detail":"菜品详情"}]
   *详细说明，根据客户端提交的菜品编号did，返回该菜品的详情，若客户端未提交did，则返回空数组。
   *作者：潘凤山
   *创建时间：2016-3-15  10:10:25
   *修改时间：2016-3-16  20:20:20
*/
header('Content-Type:application/json;charset=UTF-8');
$output=[];

@$did=$_REQUEST['did'];
if($did==NULL){
    echo '[]';
    return;
}

include('config.php');
$conn = mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_port);
$sql = "SET NAMES UTF8";
mysqli_query($conn,$sql);
$sql = "SELECT did,name,img_lg,price,material,detail FROM kf_dish WHERE did='$did'";
$result = mysqli_query($conn,$sql);
//根据主键查询最多只能返回一行记录，所以无需循环读取记录
$row=mysqli_fetch_assoc($result);
$output[] = $row;


echo json_encode($output);
?>