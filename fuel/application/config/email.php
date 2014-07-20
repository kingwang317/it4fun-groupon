<?php 
//configure for bluehost email server
/*
$config['useragent']	= "CodeIgniter";
$config['protocol'] = 'sendmail';
$config['mailpath']  = "/usr/sbin/sendmail";
$config['smtp_host'] = 'mail.7even.com.tw';
$config['smtp_port'] = 25;
$config['smtp_user'] = 's805@7even.com.tw';
$config['smtp_pass'] = 'S80688142';
$config['smtp_timeout'] = 10;
*/

$config['useragent']	= "CodeIgniter";
$config['protocol'] = 'smtp';
$config['mailpath']  = "/usr/sbin/sendmail";
$config['smtp_host'] = 'taste-it.com.tw';
$config['smtp_port'] = 25;
$config['smtp_user'] = 'service@taste-it.com.tw';
$config['smtp_pass'] = '}Fkk_k3Ho*yq';
$config['smtp_timeout'] = 10;

$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['crlf'] = '\r\n';
$config['newline'] = '\r\n';
$config['validate'] = TRUE;