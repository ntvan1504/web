<?php
/**Class giúp kết nối đến database
 * @author
 * @date
 */
class Apps_Libs_DbConnection{
    protected $host = 'localhost';
    protected $userName = 'root';
    protected $passWord = '';
    protected $database = 'baitapthuchanh_html';
    
    protected $queryParams = []; //biến lưu trữ các params phục vụ cho query


    protected $tablename;
    protected static $connectionInstance=null; //biến static lưu trữ kết nối đến mysql ở hàm connect, vì là hàm static nên chỉ cần connect 1 lần
    
    public function __construct() {
        $this->connect();
    }
    
    public function connect() {
        if(self::$connectionInstance===null){
            try{ //try&catch để xử lý ngoại lệ
                self::$connectionInstance= new PDO('mysql:host='.$this-> host . ';dbname='.$this->database,$this->userName, $this->passWord);
                self::$connectionInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $ex) { //xử lý lỗi ngoại lệ exception
                echo "ERROR".$ex->getMessage;
                die();
            }
        }
        return self::$connectionInstance;
    }
    
    public function query($sql, $param = []){ //truyền thêm $param để tránh lỗi sql exception
        $q = self::$connectionInstance->prepare($sql); //tạo đối tượng prepare
        
        if(is_array($param) && $param){     //kiểm tra có phải dữ liệu mảng không
            $q->execute($param); //nếu đúng thì thực thi câu truy vấn
        }else{
            $q->execute();
        }
        return $q;
    }
    
    public function buildQueryParams($params){ //khi nào gọi đến hàm buildQueryParams phải truyền $param
                                               //xử lý các param truyền vào để thực hiện select, insert, delete
        $default=[
            "select" => "*",
            "where" => "",
            "other" => "",
            "params" => "",
            "field" => "",
            "value" => [],
            "join" => ""
        ];
        $this->queryParams = array_merge($default,$params); //$param phải là dạng mảng
//        var_dump($this->queryParams);die;
        return $this;
    }
    
    public function buildCondition($Condition){ //điều kiện kiểm tra giá trị của where có tồn tại không
        if(trim($Condition)){
            return "where ".$Condition;
        }
        return "";
    }
    
    //các câu lệnh query
    public function select(){
        $sql = "select ".$this->queryParams["select"]." from ".$this->tablename." ".$this->queryParams["join"]." ".
                $this->buildCondition($this->queryParams["where"])." ".$this->queryParams["other"]; //$sql là câu lệnh truy vấn

        $query = $this->query($sql,$this->queryParams["params"]);
        return $query->fetchAll(PDO::FETCH_ASSOC); //Fetch_assoc trả về dữ liệu kiểu mảng không tuần tự
    }
    
    public function selectOne(){
        $this->queryParams["other"]= "limit 1";
        $data = $this->select();
        if ($data){
            return $data[0]; //lấy những phần tử đầu tiên, có key=0
        }
        return[];
    }
    
    public function insert(){
        $sql = "insert into ".$this->tablename." ".$this->queryParams["field"]; //câu lệnh insert
        $result = $this->query($sql,$this->queryParams["value"]);
        if($result){
//            var_dump($result);die;
            return self::$connectionInstance->lastInsertId(); //trả về Auto Incremented ID của rows được thêm gần nhất
        }else{
            return FALSE;
        }
    }
    
    public function update(){
        $sql= "update ".$this->tablename." set ".$this->queryParams["value"]." ".
                $this->buildCondition($this->queryParams["where"])." ".$this->queryParams["other"];
//        var_dump($sql);die();
        return $this->query($sql);
    }
    
    public function delete(){
        $sql="delete from ".$this->tablename." ".$this->buildCondition($this->queryParams["where"]).""
                . " ".$this->queryParams["other"];
        return $this->query($sql);
    }
}