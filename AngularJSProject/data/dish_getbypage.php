<?php
/**
*用途：为main.html分页返回菜品列表
*数据格式：    JSON格式，形如：
*  [{"did":18,"name":"菜名","price":35.5,"material":"原材料","img_sm":"img/1.jpg"},
    {...},
    {...},
    {...}
    ]
   *详细说明，每次最多返回五条记录，需要客户端提交从哪条记录开始显示。若客户端为提交，默认从第0条开始显示。
   *作者：潘凤山
   *创建时间：2016-3-15  10:10:25
   *修改时间：2016-3-16  20:20:20
*/
header('Content-Type:application/json;charset=UTF-8');
$output = [];

@$start = $_REQUEST['start'];  //@符号压制当前行代码抛出的警告消息
if($start==NULL){
    $start = 0;
}
$count = 5;  //一次响应最多向客户端返回5条记录

include('config.php');
 $conn = mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_port);
 $sql = "SET NAMES UTF8";
 mysqli_query($conn,$sql);
 $sql = "SELECT did,name,img_sm,price,material FROM kf_dish LIMIT $start,$count";
 $result = mysqli_query($conn,$sql);
 while( ($row=mysqli_fetch_assoc($result))!=NULL ){
     $output[] = $row;
 }

echo json_encode($output);
?>