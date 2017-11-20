<?php
class Controller extends SmartyConnect{
    private $var =array();
    public function __construct (){
        if(C('SMARTY_TPL_ON')){
            parent::__construct();
        }
        
    	if(method_exists($this, "__init")){
    		$this->__init();
    	}
    	if(method_exists($this, "__auto")){
    		$this->__auto(); 
    	}
    	
    }
    protected function get_path($tpl){
        if(is_null($tpl)){
            $path = APP_VIEW_PATH.'/'.CONTROLLER.'/'.ACTION.'.html';
        }else{
            $suffix = strrchr($tpl,'.');
            $tpl = empty($suffix)?$tpl.'.html':$tpl;
            $path = APP_VIEW_PATH.'/'.CONTROLLER.'/'.$tpl;
        }
        return $path;
    }
    protected function display($tpl=NULL){
    	$path =$this->get_path($tpl);
    	if(!is_file($path)) halt($path.'模板不存在！！！');
        if(C('SMARTY_TPL_ON')){
             parent::display($path);
        }else{
             extract($this->var);

             include($path);  
        }
    	
        

    }
   protected function assign($var,$value){
    if(C('SMARTY_TPL_ON')){
             parent::assign($var,$value);
        }else{
            $this->var[$var] = $value;   
        }
        
   }


	protected function success($msg,$url=null,$time=3){
		$url = $url?"window.location.href='".$url."'":'window.history.back(-1)';
		include APP_VIEW_PATH."/success.html";
		die;
	}
	protected function error($msg,$url=null,$time=3){
		$url = $url?"window.location.href='".$url."'":'window.history.back(-1)';
		include APP_VIEW_PATH."/error.html";
		die;
	}
}
   
?>