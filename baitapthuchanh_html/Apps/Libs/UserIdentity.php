<?php
session_start(); //phải có session_start ở ngay đầu tiên để dùng được session
class Apps_Libs_UserIdentity{
    public $username;
    public $password;
    protected $id;
    
    public function __construct($username = "", $password = "") { //khi khởi tạo class phải có $username và $password
        $this->username = $username;
        $this->password = $password;
    }
    
    public function encryptPassword(){
        return md5($this->password); //mã hóa password
    }
    
    public function login(){ //sử dụng 2 biến đầu vào thực hiện query trong CSDL
        $db = new Apps_Models_Users(); //LIÊN KẾT THẲNG TỚI MODEL USERS VÌ ĐÃ ĐẶT SẴN TABLENAME=USER
        
        $query = $db->buildQueryParams([    //truyền các giá trị vào hàm để tiến hành query
            "where" => "username= :username AND password = :password",//:username và :password chỉ là tên tự đặt
            "params"=> [
                ":username"=> trim($this->username),
                ":password"=> $this->password
            ]
        ])->selectOne(); //vì chỉ cần 1 row, username luôn không trùng nhau

        if ($query) { //PHẦN NÀY CHƯA HIỂU
            $_SESSION["userId"] = $query["id"];   //cú pháp lưu giá trị id vừa query vào session userid
            $_SESSION["username"] = $query ["username"]; // lưu giá trị username vừa query trong database vào session username
            return true;
        }
        return false;
    }
    
    public function logout(){ //logout là dừng session
        unset ($_SESSION["userId"]);
        unset ($_SESSION["username"]);
    }
    
    public function getSESSION($name){  //kiểm tra xem có SESSION nào đang chạy không
        if ($name !== NULL){
            return isset($_SESSION[$name]) ? $_SESSION[$name] : NULL; //truyền $name nên phải check $_SESSION[$name]
        }
        return $_SESSION;
    }
    
    public function isLogin(){ //kiểm tra đăng nhập hay chưa
        if ($this->getSESSION("userId")){ //kiểm tra có phải SESSION của userid nào đó đang chạy không
            return true;
        }
        return false;
    } //trả về giá trị là TRUE hay FALSE
    
    public function getId(){  //kiểm tra userid của SESSION đang chạy đó là gì
        
        return $this->getSESSION('userId');
    } //trả về giá trị là userId nào đó (vd 1, 2, 3...)
}