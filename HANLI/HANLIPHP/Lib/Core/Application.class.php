<?php
final class Application{
	public static function run(){
	  self::_init();
    set_error_handler(array(__CLASS__,'error'));
    register_shutdown_function(array(__CLASS__,'fatal_error'));
    self::_user_import();
    self::_set_url();
    spl_autoload_register(array(__CLASS__ ,'_autoload'));
    self::_create_demo();
    self::_app_run();
	}
  //致命错误
  public static function fatal_error(){
    if($e = error_get_last()){
      self::error($e['type'],$e['message'],$e['file'],$e['line']);
    }
  }
  //错误提示
  public static function error($errno,$error,$file,$line){
    switch ($errno) {
      case E_ERROR:
      case E_PARSE:
      case E_CORE_ERROR:
      case E_COMPILE_ERROR:
      case E_USER_ERROR:
       $msg = $error.$file."第{$line}行";
       halt($msg);
        break;
      case E_STRICT:
      case E_USER_WARNING:
      case E_USER_NOTICE:
      default:
        if(DEBUG){
          include DATA_PATH.'/Tpl/notice.html';
        }
        break;
    }
  }
  //实例化控制器
  private static function _app_run(){
    $c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';

    $a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';
    define('CONTROLLER', $c);
    define('ACTION', $a);

    $c.= 'Controller';
      if(class_exists($c)){
         $obj = new $c;
         if(!method_exists($obj, $a)){
            if(method_exists($obj, '__empty')){
              $obj->__empty();
            }else{
              halt($c.'控制器中的'.$a.'方法不存在!');
            }
         }else{
         $obj->$a();  
         }

      
      }else{
         
         $obj = new EmptyController();

         $obj->$a();  
     }

    
  }







	//初始化框架
	private static function _init(){
      C(include CONFIG_PATH.'/config.php');
      

      $userPATH = APP_CONFIG_PATH.'/config.php';

      $commonPATH = COMMON_CONFIG_PATH.'/config.php';

      $userConfig = <<<str
<?php
   return array(
    //配置项=>配置值

   );
?>  
str;
     is_file($commonPATH) || file_put_contents($commonPATH, $userConfig);

     C(include $commonPATH);

     is_file($userPATH) || file_put_contents($userPATH,$userConfig);

     C(include $userPATH);

     

     date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
     

     C('SESSION_AUTO_START') && session_start();


	}

  //设置外部路径
  private static function _set_url(){
    //P($_SERVER);
     $path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

     $path = str_replace('\\', '/', $path);

     define('__APP__', $path);

     define('__ROOT__', dirname($path));

     define('__VIEW__', __ROOT__.'/'.APP_NAME.'/View');

     define('__PUBLIC__',__VIEW__.'/Public');

     
  }
    //创建demo
  private static function  _create_demo(){
    $path = APP_CONTROLLER_PATH.'/IndexController.class.php';
   

      $str = <<<str
<?php
class IndexController extends Controller{
  public function index(){
    header('Content-type:text/html;charset=utf-8');
    echo "<h2>欢迎来到韩力的框架:)!</h2>";
  }
}   
?>  
str;
    is_file($path) || file_put_contents($path,$str);

  }



 //自动载入
  private static function _autoload($className){

    switch (true) {
      case strlen($className)>10 && substr($className, -10)=='Controller':
        $path = APP_CONTROLLER_PATH.'/'.$className.'.class.php';
        if(!is_file($path)){
           $EmptyPath = APP_CONTROLLER_PATH.'/EmptyController.class.php';
           if(is_file($EmptyPath)){
            include $EmptyPath;
            return;
           }else{
            halt($path.'控制器未找到！');       
          } 
        }
        include $path; 
        break;
      case strlen($className)>5 && substr($className, -5)=='Model':
      $path = COMMON_MODEL_PATH.'/'.$className.'.class.php';
      if(!is_file($path)){
           halt($path.'模型类未找到！');
        }else{
          include $path; 
        }
        break;
      default:
       $path = TOOL_PATH.'/'.$className.'.class.php';
       if (!is_file($path)) halt($path.'类未找到！');
       include $path;
        break;
    }
   
  }

//载入Common里的文件
private static function _user_import(){
  $arrFile = C('AUTO_LOAD_FILE');
  
  if(is_array($arrFile) && !empty($arrFile)){
    foreach ($arrFile as $k => $v) {
      require_once COMMON_LIB_PATH.'/'.$v;
    }
  }
}
}

?>