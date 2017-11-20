<?php
class Model{
	//连接数据库
	public static $link = NULL;
	//保存表名
	protected  $table = NULL;
	//初始化表信息
	private  $opt;
	//sql 语句
	public static $sqls = array();

	public function __construct($table = NULL){
		$this->table = is_null($table)?C('DB_PREFIX').$this->table:C('DB_PREFIX').$table;
		//连接数据库
        $this->connect();
        //初始化表sql语句
        $this->_opt();
	}

	public function query($sql){
        self::$sqls[] = $sql;
        $link = self::$link;
        $result = $link->query($sql);
        if($link->errno) halt('mysql错误:'.$link->error.'<br/>SQL:'.$sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
        	$rows[] = $row;
        }
        $result->free();
        $this->_opt();
        return $rows;

	}
	public function exe($sql){
		self::$sqls[] = $sql;
        $link = self::$link;
        $bool = $link->query($sql);
        $this->_opt();
        if(is_object($bool)){
        	halt("请用all()方法查询！！");
        }
        if($bool){
           return $link->insert_id?$link->insert_id:$link->affected_rows;
        }else{
           halt('mysql错误:'.$link->error.'<br/>SQL:'.$sql);
        }

	}
    //更新数据
    public function update($arr=NULL){
        if(empty($this->opt['where'])) halt("执行update语句必须有where条件！！");
        if(is_null($arr)) $arr = $_POST;
        if(empty($arr)) halt("数据不能为空！！");
        if(!is_array($arr)) halt("请传数组类型！！！");
        $values = '';
        foreach ($arr as $k => $v) {
         	$values .="`".$this->_safe_str($k)."`="."'".$this->_safe_str($v)."',";
         }
        $values = trim($values,",");
        $sql = "UPDATE " . $this->table." SET " .$values.$this->opt['where'];
        return $this->exe($sql);
    }
	//删除数据
	public function delete(){
		if(empty($this->opt['where'])) halt("执行delete语句必须有where条件！！");
		$sql = "DELETE FROM " . $this->table . $this->opt['where'];
		return $this->exe($sql);
	}

	//添加数据
	public function add($arr=NULL){
         if(is_null($arr)) $arr = $_POST;
         if(empty($arr)) halt("数据不能为空！！");
         if(!is_array($arr)) halt("请传数组类型！！！");
         $fileds = '';
         $values = '';
         foreach ($arr as $k => $v) {
         	$fileds .= "`".$this->_safe_str($k)."`,";
         	$values .= "'".$this->_safe_str($v)."',";
         }
         $fileds = trim($fileds,",");
         $values = trim($values,",");
         $sql ="INSERT INTO ".$this->table." (".$fileds.")"."VALUE (".$values.")";
         return $this->exe($sql);
	}

	//取一条数据
	public function  find($id=NULL){
		if(empty($id)){
			$data = $this->limit(1)->all();
			$data = current($data);
		}else{
			$data = $this->where("id = $id")->limit(1)->all();
			$data = current($data);
		}
		return $data;
	}
	//取别名
	public function one($id=NULL){
		return $this->find($id);
	} 
   //字段限制
	public function field($field){
		$this->opt['field'] = $field;
	    return $this;
	}

	public function where($where){
		$this->opt['where'] =" WHERE " . $where;
	    return $this;
	} 

	public function limit($limit){
		$this->opt['limit'] = " LIMIT ".$limit;
	    return $this;
	}
   public function order($order){
		$this->opt['order'] =" ORDER BY " . $order;
	    return $this;
	} 
	public function group($group){
		$this->opt['group'] =" GROUP BY " . $group;
	    return $this;
	}
	public function having($having){
		$this->opt['having'] =" HAVING " . $having;
	    return $this;
	}  

	//查所有数据
	public function all(){
		$sql = 'SELECT ' . $this->opt['field']. ' FROM ' . $this->table . $this->opt['where'] . $this->opt['group'] . $this->opt['having'] . $this->opt['order'] . $this->opt['limit'] ;
	    return $this->query($sql);

	}

	private function _opt(){
		$this->opt = array(
           'field' =>'*',
           'where' =>'',
           'group' =>'',
           'having'=>'',
           'order' =>'',
           'limit' =>''
			);
	}

	private function connect(){
		if (is_null(self::$link)) {
			$db = C('DB_DATABASE');
			if(empty($db)) halt('请先配置数据库！');
			$link = new Mysqli(C('DB_HOST'),C('DB_USER'),C('DB_PASSWORD'),$db,C('DB_PORT'));
			if($link->connect_error )halt('数据连接错误 请检查配置项！');
			$link->set_charset(C('DB_CHARSET'));
			self::$link = $link;

		}
	}

	private function _safe_str($str){
          if(get_magic_quotes_gpc()){
          	$str = stripcslashes($str);
          }
          return self::$link->real_escape_string($str);
	}
}
?>