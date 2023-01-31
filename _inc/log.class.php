<?
class LOG 
{
	var $log = array();
	var $logPath;


	function logLogin()
	{
		$logPath = HOME_ROOT."/log/login/".date('Ym').".log";
		$log[] = "[IP] ".$_SERVER[REMOTE_ADDR];
		$log[] = "[DATE] ".date('Y-m-d H:i:s');
		$log[] = "[ID] ".$_SESSION['sess']['mmName'];
		$log[] = "\n";

		//$this->insert($logPath,$log);
		$logMsg = @implode("\n",$log);
		error_log($logMsg, 3, $logPath);
		@chmod( $logPath, 0707 );
	}

	function insert($logPath,$log)
	{
		$logMsg = @implode("\n",$log);
		error_log($logMsg, 3, $logPath);
		@chmod( $logPath, 0707 );
	}
}
?>