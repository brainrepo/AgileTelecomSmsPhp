<?php
set_time_limit(0); 
	class Sms{
		
		var $debug = 1; 
		var $quality = H;        # Possibili valori: H (default) per qualità alta o M per qualità media 
		var $host = "smsserver.agiletelecom.com";  
		var $port = 26; 
		var $userid = "xxxxxx"; //username 
		var $passwd = "xxxxxx"; //password 
		var $conn;
		
		function Sms($host="smsserver.agiletelecom.com", $port=26, $userid="xxxxxx", $passwd= "xxxxxx", $quality=H){
			$this->quality 	= 	$quality;
			$this->host 	= 	$host;
			$this->port		=	$port;
			$this->userid	=	$userid;
			$this->passwd	=	$passwd;
		}
		
		function SendSms($text,$dest,$sender) { 
			$str = $this->wrap($this->msg($text,$dest,$sender,"myfile.sms")); 
			fputs($this->conn,$str); 
			$str = fgets($this->conn,128); 
			return $str." - ".$errno." - ".$errstr; 
		} 
		
		function wrap($data){ 
			$chk = 0; 
			$len = strlen($data); 
			for($i=0;$i<$len;$i++) {  $chk+=ord(substr($data,$i,1)); } 
			$chk = sprintf("%02X",$chk%256); 
			return "\x0B$data$chk\x03";    
		}
		
		function xConnect(){ 
		   $this->conn = fsockopen ($this->host, $this->port, $errno, $errstr, 10); 
		   /*  if (!$this->conn) { 
		        echo "Error happend on ($this->host:$this->port): $errstr ($errno)<br>\n"; 
		        return 0; 
		    } */
		    $str = $this->wrap($this->pwd()); 
		    /*if($this->debug) { 
		        print "$str<br>\n"; 
		    } */
		    fputs($this->conn,$str); 
		    if($debug) { 
		       // print "<br>After pwd: ".fgets($this->conn,128)."<br>\n"; 
		    } 
		    return $this->conn; 
		}
		
		function msg($text,$dest,$sender,$TYPE) { 
		    return "\x05$dest\x04$sender\x04$text\x04N\x04$TYPE\x04".$this->GATEWAY."\x04"; 
		} 
		
		function pwd() { 
		    return "\x06".$this->userid."\x04".$this->passwd."\x04"; 
		} 
		
		function xDisconnect() 
		{ 
		    fclose($this->conn); 
		} 
	}
	
	/*
$sms = new Sms();
$numberSms=array("+393492977246"); 
//sms text 
$txt_sms="Prova"; 
//sender name 
$sender="CDS"; 

if($sms->xConnect()) //if connected 
{ 
//Send an sms to all numbers of array "numberSms" 
while(list ($key, $val) = each($numberSms)) 
{ 
$msg = $sms->SendSMS($txt_sms,$val,$sender); 
if (strstr($msg,'+Ok')) echo "Sms sended<BR>"; 
else echo "Sms not sended. $msg<BR>"; 
} 
$sms->xDisconnect(); 
} 
else 
{ 
echo "Failed: Could not connect - ".$sms->errno." - ".$sms->errstr ; 
} */
?>
