<?php
  return array(
        'CORD_LEN'=>4,

        'DEFAULT_TIME_ZONE'=>'PRC',

        'SESSION_AUTO_START'=>TRUE,

        'VAR_ACTION'        =>'a',

        'VAR_CONTROLLER'    =>'c',
        //是否开启日志；
        'SAVE_LOG'          =>TRUE,

        'ERROR_URL'         =>'',     
            
        'ERROR_MSG'         =>'网站出错了，请稍后再试。。。',
        //自动载入Common/Lib目录下的文件；must array。
        'AUTO_LOAD_FILE'    => array(),

        //
        'DB_CHARSET'       =>'utf-8',
        'DB_HOST'          =>'127.0.0.1',
        'DB_PORT'          =>3306,
        'DB_USER'          =>'root',
        'DB_PASSWORD'      =>'',
        'DB_DATABASE'      =>'',
        'DB_PREFIX'        =>'',
        //smarty 边界
        'LEFT_DELIMITER'   =>'{hl',
        'RIGHT_DELIMITER'  =>'}',
        // 
        'CACHE_ON'         =>false,
        'CACHE_TIME'       =>5,
        'SMARTY_TPL_ON'    =>true,
  	);

  ?>