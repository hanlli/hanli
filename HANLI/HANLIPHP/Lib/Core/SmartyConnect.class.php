<?php
class SmartyConnect{
	 private static $smarty = NULL;
     public function __construct(){
     	if(!is_null(self::$smarty))return;
     	
     	$smarty = new Smarty();
     	//模板
     	$smarty->template_dir = APP_VIEW_PATH.'/'.CONTROLLER.'/';
     	//编译
     	$smarty->compile_dir  = APP_COMPILE_PATH;
     	//缓存
     	$smarty->cache_dir    = APP_CACHE_PATH;
     	$smarty->left_delimiter= C('LEFT_DELIMITER');
     	$smarty->right_delimiter= C('RIGHT_DELIMITER');
     	$smarty->caching      =C('CACHE_ON');
     	$smarty->cache_lifetime=C('CACHE_TIME');
     	self::$smarty = $smarty;
     }	
     protected function display($tpl=NULL){
        self::$smarty->display($tpl,$_SERVER['REQUEST_URI']);
     }
     protected function assign($var,$value){
     	self::$smarty->assign($var,$value);
     }
     protected function is_cached($tpl=NULL){
      if(!C('SMARTY_TPL_ON')) halt("请先开启Smarty！");
      $tpl = $this->get_path($tpl);
      return self::$smarty->is_cached($tpl,$_SERVER['REQUEST_URI']);
     }
}