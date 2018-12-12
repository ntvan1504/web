<?php
/* echo 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "Apps\bootstrap.php";

//$a= new Apps_Libs_DbConnection();




//$a= new Apps_Models_Users();
//$result = $a->buildQueryParams([ //tạo ra mảng $param trong hàm buildQueryParams của DbConnection
//    "field" => "(username,password) values (?,?)", //định nghĩa bao nhiêu trường sẽ insert bằng câu values
//    "value" => ["anna",md5("anna")] //mã hóa một chiều
//])->insert(); //trỏ ngay đến câu lệnh select
//
//var_dump($result);



$path = __DIR__."\public";
$router = new Apps_Libs_Router($path); //khởi tạo hàm có biến là đường dẫn đến vị trí file này (cùng vị trí folder với file home và post)
$router->router();

