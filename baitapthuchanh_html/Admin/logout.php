<?php

$user = new Apps_Libs_UserIdentity();
$user->logout();

//$a = new Apps_Libs_Router();
//$a->loginPage(); //=>tương đương với câu dưới

(new Apps_Libs_Router)->loginPage(); //khi logout, dừng các session thì quay lại trang login