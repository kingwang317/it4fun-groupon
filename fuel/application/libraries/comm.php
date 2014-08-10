<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Comm {

	private $_ci;

    public function __construct()
    {
        // Do something with $params
        $this->_ci =& get_instance();
        
    }

	public function plu_redirect($url, $delay, $msg)
	{
	    if( isset($msg) )
	    {
	        $this->notify($msg);
	    }

	    echo "<meta http-equiv='Refresh' content='$delay; url=$url'>";
	}

	public function notify($msg)
	{
	    $msg = addslashes($msg);
	    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	    echo "<script type='text/javascript'>alert('$msg')</script>\n";
	    echo "<noscript>$msg</noscript>\n";
	    return;
	}

}
