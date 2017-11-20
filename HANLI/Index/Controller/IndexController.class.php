<?php
class IndexController extends Controller{
  public function index(){
    if(!$this->is_cached()){
      $this->assign('var',time());
    }
    
    $this->display();
  	
  }
}   
?>  