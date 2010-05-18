<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Log extends CI_Log
{
	var $mongo;
	
	
	function __construct()
    {
    	$this->mongo = new Mongo();
        parent::CI_Log();
    }
	
	function write_log($level = 'error', $msg, $php_error = FALSE)
	{		
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	
		$level = strtoupper($level);
		
		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}
		
		$db = $this->mongo->logs->system_codeigniter;
		$output = $db->insert(array(
		
		// Server Info
		'server_name'	=> $_SERVER['SERVER_NAME'],
		'server_ip'		=> (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0',
		'domain'		=> (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '',
		
		//User Info
		'user_agent'	=> (!empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '',
		'ip_address'	=> (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '',
		'uri'			=> (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '',
		'query_string'	=> (!empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '',
		
		'timestamp'		=> date($this->_date_fmt),
		'message'		=> $msg,
		'level'			=> $level,
		));
		
		 		
		return TRUE;
	}

}
/* End of file MY_Log.php */
/* Location: ./application/libraries/MY_Log.php */