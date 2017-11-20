<?php
final class HANLIPHP{
	public static function run (){
		 self::_set_const();
     defined('DEBUG')||define('DEBUG', false);
     if(DEBUG){
          self::_create_dir();
          self::_import_file();
     }else{
          error_reporting(0);
          require TEMP_PATH.'/~boot.php';

     }
		 
	   Application::run();
	} 
	private static function _set_const(){
          $path = str_replace("\\", "/", __file__);
          define("HANLIPHP_PATH", dirname($path));
          define("CONFIG_PATH",HANLIPHP_PATH."/Config");
          define("DATA_PATH",HANLIPHP_PATH."/Data");
          define("LIB_PATH",HANLIPHP_PATH."/Lib");
          define("CORE_PATH",LIB_PATH."/Core");
          define("FUNCTION_PATN", LIB_PATH."/Function");
          define("ROOT_PATH", dirname(HANLIPHP_PATH));
          define('APP_PATH', ROOT_PATH.'/'.APP_NAME);
          define('APP_CONFIG_PATH',APP_PATH.'/Config');
          define('APP_CONTROLLER_PATH',APP_PATH.'/Controller');
          define('APP_VIEW_PATH',APP_PATH.'/View');
          define('APP_PUBLIC_PATH',APP_VIEW_PATH.'/Public');
          define('TEMP_PATH', ROOT_PATH.'/Temp');
          define('LOG_PATH',TEMP_PATH.'/Log' );
          define('HANLI_VERSION', '1.0');
          //smarty编译目录
          define('APP_COMPILE_PATH', TEMP_PATH.'/'.APP_NAME.'/Compile');
          //smarty缓存目录
          define('APP_CACHE_PATH',TEMP_PATH.'/'.APP_NAME.'/Cache');
          //扩建
          define('EXTENDS_PATH', HANLIPHP_PATH.'/Extends');
          //工具类
          define('TOOL_PATH', EXTENDS_PATH.'/Tool');
          //第三方扩展类
          define('ORG_PATH', EXTENDS_PATH.'/Org');



          define('COMMON_PATH', dirname(HANLIPHP_PATH).'/Common');

          define('COMMON_CONFIG_PATH',COMMON_PATH.'/Config');

          define('COMMON_MODEL_PATH',COMMON_PATH.'/Model' );

          define('COMMON_LIB_PATH', COMMON_PATH.'/Lib');

          

          define('IS_POST', ($_SERVER['REQUEST_METHOD']=='POST')?true:false);
          if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
            define('IS_AJAX', true);
          }else{
            define('IS_AJAX', false);
          }

		 }
	private static function _create_dir(){
		$arr = array(
            APP_PATH, 
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_VIEW_PATH,
            APP_PUBLIC_PATH,
            LOG_PATH,
            COMMON_PATH,
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LIB_PATH,
            TEMP_PATH,
            APP_COMPILE_PATH,
            APP_CACHE_PATH


		 );
		foreach ($arr as $v) {
			if(!is_dir($v)){
				 mkdir($v,007,true);
				} 
		}
    is_file(APP_VIEW_PATH.'/success.html')||copy(DATA_PATH.'/Tpl/success.html', APP_VIEW_PATH.'/success.html');
    is_file(APP_VIEW_PATH.'/error.html')||copy(DATA_PATH.'/Tpl/error.html', APP_VIEW_PATH.'/error.html');
	}	 

  private static function _import_file(){
       $arrfile = array(
        ORG_PATH."/Smarty/Smarty.class.php",
        CORE_PATH."/SmartyConnect.class.php",
        CORE_PATH."/Application.class.php",
        CORE_PATH."/Controller.class.php", 
        FUNCTION_PATN."/function.php",
        CORE_PATH."/Log.class.php"
         );
       $str = "";
      foreach ($arrfile as $v) {
        $str .= trim(substr(file_get_contents($v), 5,-2));
        require_once $v;
        
      }
        $str = "<?php\r\n".$str;
      file_put_contents(TEMP_PATH.'/~boot.php',$str)||die('access not allow');
  }

}
HANLIPHP::run();

?>