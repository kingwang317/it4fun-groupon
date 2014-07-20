<?php $this->load->view('_blocks/header')?>
	
	<!--<div id="main_inner">-->
		<?php 
			if(isset($views)){
				$this->load->view($views);
			}
			else
			{
				$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
				if ($_SERVER["SERVER_PORT"] != "80")
				{
				    $pageURL .= substr($_SERVER['SERVER_NAME'], 4).":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				} 
				else 
				{
				    $pageURL .= substr($_SERVER['SERVER_NAME'], 4).$_SERVER["REQUEST_URI"];

				    if(substr($_SERVER['SERVER_NAME'], 0, 4) == "www.")
				    {
				    	redirect($pageURL, 'refresh');
				    }
				}
				echo fuel_var('body', '');
			}
		?>
	<!--</div>-->
	
<?php 
	$this->load->view('_blocks/footer');
?>
