<?php
/*提示函数
 *韩力
 *2017.1.02
 */
function halt($error,$level='ERROR',$type=3,$dest=NULL){

   if(is_array($error)){
   	Log::write($error['message'],$level,$type,$dest);
   }else{
   	Log::write($error,$level,$type,$dest);
   }

   $e = array();
   //debug开启

   if(DEBUG){

   	 if(!is_array($error)){
   	 	$trace = debug_backtrace();
   	 	$e['message'] = $error;
   	 	$e['file']    = $trace[0]['file'];
   	 	$e['line']    = $trace[0]['line'];
   	 	$e['class']   = isset($trace[0]['class'])?$trace[0]['class']:'';
   	 	$e['function']   = isset($trace[0]['function'])?$trace[0]['function']:'';
   	 	ob_start();
   	 	debug_print_backtrace();
   	 	$e['trace']   = htmlspecialchars(ob_get_clean());
   	 }else{
   	 	$e = $error;	
   	 }
   }else{
   	if($url=C('ERROR_URL')){
   	 		GO($url);
   	 	}else{
   	 		$e['message'] = C('ERROR_MSG');
   	 	}
   }
   include DATA_PATH.'/Tpl/halt.html';
   die;
}


/*跳转函数
 *韩力
 *2017.1.02
 */
function GO($url,$time=0,$msg=''){
    if(!headers_sent()){
      $time==0?header('Location:'.$url):header("refresh:{$time};url={$url}");
      die($msg);
    }else{
      echo "<meta http-equiv='refresh' content='{$time};URL={$url}'>";
      if($time)die($msg);
    }
}

/*打印数组
 *韩力
 *2016.12.07
 */
function P($arr){
	if(is_bool($arr)){
		var_dump($arr);
	}else if(is_null($arr)){
		var_dump(NULL);
	}else{
	echo '<pre style="padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #ccc;font-size:14px;">'.print_r($arr,true).'</pre>';
    }
}
/*加载配置项,改变，设置
 *韩力
 *2016.12.08
 *
 */

function C($var=NULL,$value=NUll){

	static $config = array();
	if(is_array($var)){

		$config = array_merge($config,array_change_key_case($var,CASE_UPPER));
		return;	
	} 
     
	if(is_string($var)){

		$var = strtoupper($var);
		if (!is_null($value)){
			$config[$var] = $value;
			return;
		}
		
	  return isset($config[$var]) ? $config[$var] : NULL; 	
     
	}
	
	if(is_null($var) && is_null($value)){

		return $config;
	}
   

}
/*打印常量
 *韩力
 *2017.1.03
 */
 function print_const(){
      $const = get_defined_constants(true);
      p($const['user']);
    }
 function M($table){
     $obj = new Model($table);
     return $obj;
 } 
 function H($model){
    $model.="Model";
    return new $model();
      
 }
?>