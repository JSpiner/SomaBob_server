<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/14
    */
    
    include 'db_access.php';


    /*
    RESPONSE FUNCTION CODE
    */
    global $output;
    $output['fcode'] = $_POST['fcode'];

    /*
    base setting
    */

    header('Content-Type: application/json');
//      ini_set('memory_limit','-1');
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
//*/
     
    /*
    상수 정의    
    */ 
    //HTTP METHOD 
    define("HTTP_GET", 1, true);
    define("HTTP_POST", 2, true);
    //Parameter result
    define("PARAM_OK", 1, true);
    define("PARAM_NEED",2, true);
    define("PARAM_TYPE_ERROR",3,true);
    define("PARAM_METHOD_ERROR",4,true);
    //Result code
    define("RESULT_OK", 0, true);
    define("RESULT_SUCCESS", 0, true);
    define("RESULT_PARAM_NEED", -2, true);
    define("RESULT_PARAM_METHOD_ERROR", -2, true);
    define("RESULT_PARAM_TYPE_ERROR", -2, true);
    define("RESULT_SESSION_ERROR", -1, true);
    define("RESULT_DB_ERROR",-3,true);
    define("RESULT_INJECTION_DETECTED",-4,true);
    //util var
    define("PAGE_NUM", 30, true);
    
    //http 파라미터를 담는 클래스 
    class HttpParam {
        
        public $RESULT_CODE;
        public $METHOD_TYPE;
        public $VAR_NAME;
        public $VAR_VALUE;
        
        function __construct($method, &$var){
            $vname = variable_name($var);
            
            $this->VAR_NAME = $vname;
            $this->METHOD_TYPE = $method;
            
            //http get
            if($method == HTTP_GET){
                if(isset($_GET[$vname])){
                    $this->RESULT_CODE = PARAM_OK;
                    $var = $_GET[$vname];
                    $this->VAR_VALUE = $var;
                }
                else{
                    //param not found
                    $this->RESULT_CODE = PARAM_NEED;
                }
            }
            
            //http post
            else if($method == HTTP_POST){
                if(isset($_POST[$vname])){
                    $this->RESULT_CODE = PARAM_OK;
                    $var = $_POST[$vname];
                    $this->VAR_VALUE = $var;
                }
                else{
                    //param not found
                    $this->RESULT_CODE = PARAM_NEED;
                }
            }
            else{
                //method not found
                $this->RESULT_CODE = PARAM_METHOD_ERROR;
            }
        }
        
    }
    
    //변수명 가져오기 
    function variable_name( &$var, $scope=false, $prefix='UNIQUE', $suffix='VARIABLE' ){
        if($scope) {
            $vals = $scope;
        } else {
            $vals = $GLOBALS;
        }
        $old = $var;
        $var = $new = $prefix.rand().$suffix;
        $vname = FALSE;
        foreach($vals as $key => $val) {
            if($val === $new) $vname = $key;
        }
        $var = $old;
        return $vname;
    }

    //파라미터들 확인 
    function check_params($arr){
        foreach($arr as $object){
            check_param($object);
        }        
    }
    
    //파라미터 확인
    function check_param($object){
        
        switch($object->RESULT_CODE){
            case PARAM_OK:
            /*
                if(!is_json($object->VAR_VALUE)){
                    $output['code'] = 3;
                    $output['message'] = "json parse error";
                    echo print_json($output);
                    die();
                }       */
                /*
                if(!check_json_injection($object->VAR_VALUE)){
                    $output['code'] = RESULT_INJECTION_DETECTED;
                    $output['message'] = "sql injection detected";
                    echo print_json($output);
                    die();
                }       */
            break;
            case PARAM_NEED:
                $output['code'] = RESULT_PARAM_NEED;
                $output['message'] = "RESULT_PARAM_NEED : ".$object->VAR_NAME;
                echo print_json($output);
                die();
            break;
            case PARAM_TYPE_ERROR:
                $output['code'] = RESULT_PARAM_TYPE_ERROR;
                $output['message'] = "RESULT_PARAM_TYPE_ERROR";
                echo print_json($output);
                die();
            break;
            case PARAM_METHOD_ERROR:
                $output['code'] = RESULT_PARAM_METHOD_ERROR;
                $output['message'] = "RESULT_PARAM_METHOD_ERROR";
                echo print_json($output);
                die();
            break;
        }
    }
    
    //sql injection 방지
    function check_json_injection($json){
        $json = json_decode($json,true);
        foreach($json as $key => $value){
            $safe_key = mysql_real_escape_string($key);
            $safe_value = mysql_real_escape_string($value);
            
            if($key!=$safe_key) return false;
            if($value!=$safe_value) return false;
        }
        
        return true;
    }
 
    //json인지 판단
    function is_json($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    
    //에러시 결과 출력
    function print_error(){
        
        echo print_json($output);
        die();
    }
    
    //파라미터 에러 
    function err_param_die(){
        $output['code'] = RESULT_PARAM_NEED;
        $output['message'] = "json parameter error";

        echo print_json($output);
        die();
    }
    
    //null일경우 0을 리턴
    function nsafe($val){
        if($val==NULL){
            return 0;
            
        }
        return $val;
    }
    
    //배열 0처리
    function nsafe_array($arr){
        if($arr==NULL) return array();
        foreach ($arr as $key => $value) {
            if(is_array($value)){ 
                $arr[$key] = nsafe_array($value);
            }
            else{
                $arr[$key] = nsafe($value);
            }
        }
        return $arr;
    }
    
    //SQL 결과값의 모든값을 배열로 변화하여 리턴
    function get_row_array($row){
        $obj = array();
        foreach ($row as $key => $value) {
            if(!is_numeric($key)){
                $obj[$key] = $value;
            }
        }
        return $obj;
    }
    
    //json 출력     
    function print_json($output){
        $output = nsafe_array($output);
        echo json_encode($output);
    }

?>