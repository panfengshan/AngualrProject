<?php
/**
*用途：为main.html分页返回菜品列表
*数据格式：    JSON格式，形如：
*  [{"did":18,"name":"菜名","price":35.5,"material":"原材料","img_sm":"img/1.jpg"},
    {...},
    {...},
    {...}
    ]
   *详细说明，根据客户端提交的查询关键字，返回菜名/原材料中包含此关键字的菜品列表：若客户端未提交关键字，则返回空数组。
   *作者：潘凤山
   *创建时间：2016-3-15  10:10:25
   *修改时间：2016-3-16  20:20:20
*/
header('Content-Type:application/json;charset=UTF-8');
$output=[];

@$kw = $_REQUEST['kw'];
if($kw==NULL){
  echo '[]';  //若客户端未提交查询关键字，则返回空数组
  return;  //退出当前页面的执行
}

include('config.php');
$conn = mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_port);
$sql = "SET NAMES UTF8";
mysqli_query($conn,$sql);
$sql = "SELECT did,name,img_sm,price,material FROM kf_dish WHERE name LIKE '%$kw%' OR material LIKE '%$kw%'";
$result = mysqli_query($conn,$sql);
while( ($row=mysqli_fetch_assoc($result))!=NULL ){
    $output[] = $row;
}

echo json_encode($output);
?>