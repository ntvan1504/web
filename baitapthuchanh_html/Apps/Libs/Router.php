<?php

class Apps_Libs_Router { //luôn phải đặt tên kiểu đường dẫn
    
    const PARAM_NAME = "r"; //định nghĩa ký tự trong phần get trên url
    const HOME_PAGE = "home";
    const INDEX_PAGE = "index";
    const LOGIN_PAGE = "login";
    
    public static $sourcePath; //biến truyền vào khi khởi tạo hàm sẽ giữ nguyên giá trị
    
    public function __construct($sourcePath = "") { //phải truyền biến $sourcePath khi khởi tạo hàm, hiện tại đang đặt là null
        if ($sourcePath)                            //tại file index, truyền biến là __DIR__ là đường dẫn link đến vị trí đặt file index
            self::$sourcePath=$sourcePath;
    }
    
    public function getGET ($name = NULL){ //nếu ko đặt null hàm sẽ tự động lấy toàn bộ dữ liệu của $_GET
        if ($name !==NULL){                //$name sẽ được viết trên đường link và truyền lại bằng phương thức GET
            return isset ($_GET[$name]) ? $_GET[$name]:NULL; 
            //nếu $_GET[$name] true thì giá trị hàm là $_GET[$name], nếu false thì giá trị là Null
        }
        return $_GET;
    }
    
    public function getPOST ($name=NULL){
        if ($name !==NULL){
            return isset ($_POST[$name]) ? $_POST[$name]:NULL;
        }
        return $_POST;
    }
    
    public function router(){
        $url = $this->getGET(self::PARAM_NAME); //$url là dữ liệu nhận được từ phương thức GET trên từ link localhost.../admin/?diachi=
        if (!is_string($url) || !$url || $url == self::INDEX_PAGE){ //nếu $url không phải dữ liệu dạng string, hoặc không có, hoặc chỉ đến file index
            $url=self::HOME_PAGE;
        }
        
        $path=self::$sourcePath."/".$url.".php"; //vị trí file index / tên file.php
        
        if (file_exists($path)){                 //nếu tìm được file trên thì require
            return require_once $path;
        }else{
            return $this->pageNotFound();
        }
    }
    
    public function pageNotFound(){
        $this->pageError("ERROR 404 : PAGE NOT FOUND");
        die();
    }
    
    public function createUrl($url, $params = []){//hàm chuyên tạo ra đường link
        if($url)
            $params[self::PARAM_NAME] = $url;
        return $_SERVER['PHP_SELF'].'?'.http_build_query($params);
    }
    //HÀM http_build_query sẽ nhận vào giá trị mảng và tự động sinh ra dạng chuỗi của mảng đó, có thể truyền lên đường link url luôn
    //biến global $_SERVER lưu trữ toàn bộ thông tin về server, mảng con PHP_SELF có đường link thẳng đến file đang mở
    
    public function redirect($url){ //giúp tự động chuyển trang (VD:login đúng về trang home, login sai sang trang khác)
        $u = $this->createUrl($url);
        header("Location:$u"); //HÀM header giúp chuyển hướng sang trang khác
    }
    
    public function homePage() { //redirect sang trang homepage
        $this->redirect(self::HOME_PAGE);
    }
    
    public function loginPage(){
        $this->redirect(self::LOGIN_PAGE);
    }
    
    public function pageError ($error){
        echo $error;
        die();
    }
}