<?
class DB
{
	
	var $db_conn, $err_report;
	var $count;

	var $pageNumber = 10;

	function DB ()
	{	
		$this->db_host = 'localhost';
		$this->db_user = 'admin';
		$this->db_pass = 'Admin1004';
		$this->connect('administrator');
	}

	function connect ($db_name="")
	{
		$this->db_conn = mysql_connect($this->db_host, $this->db_user, $this->db_pass,true);
		if (!$this->db_conn) {
			$err['msg'] = 'DB connection error..';
			$this->error($err);
		}
		if ($db_name) $this->select($db_name);
		mysql_query('set names utf8');
	}

	function select ($db_name)
	{
		$ret = mysql_select_db($db_name);
		if (!$ret) {
			$err['msg'] = 'DB selection error..';
			$this->error($err);
		}
	}
	
	function _query_print ($query) {
		$argList = func_get_args();
		array_shift($argList);
		$this->replaceNum=0;
		$this->replaceArgs=$argList;
		$query = preg_replace_callback('/\[(i|d|s|c|cv|vs|v)\]/',array(&$this,'_queryReplace'), $query);
		return $query;
	}

	function _queryReplace ($matches) {
		if ($matches[1]=='i') {
			$result = (int)$this->replaceArgs[$this->replaceNum];
		}
		elseif ($matches[1]=='d') {
			$result = (float)$this->replaceArgs[$this->replaceNum];
		}
		elseif ($matches[1]=='s') {
			if (!is_scalar($this->replaceArgs[$this->replaceNum])) {
				die('query_error');
			}
			$result = '"'.mysql_real_escape_string($this->replaceArgs[$this->replaceNum],$this->db_conn).'"';
		}
		elseif ($matches[1]=='c') {
			$cols = &$this->replaceArgs[$this->replaceNum];
			if (!(is_array($cols) && count($cols))) {
				die('query_error');
			}
			foreach($cols as $eachCol) {
				if (!preg_match("/[_a-zA-Z0-9-]+/",$eachCol)) {
					die('query_error');
				}
			}
			$result = '('.implode(",",$cols).')';
		}
		elseif ($matches[1]=='v') {
			$values = &$this->replaceArgs[$this->replaceNum];
			if (!(is_array($values) && count($values))) {
				die('fff');
			}
			foreach($values as $k=>$eachValue) {
				if (is_null($eachValue)) {
					$values[$k]='null';
				}
				else {
					$values[$k]='"'.mysql_real_escape_string($eachValue,$this->db_conn).'"';
				}

			}
			$result = '('.implode(",",$values).')';
		}
		elseif($matches[1]=='vs') {
			$values = &$this->replaceArgs[$this->replaceNum];
			if (!(is_array($values) && count($values))) {
				die('query_error');
			}
			$arrayRecord=array();
			foreach($values as $eachValue) {
				foreach($eachValue as $k=>$eachElement) {
					if (is_null($eachElement)) {
						$eachValue[$k] ='null';
					}
					else {
						$eachValue[$k] ='"'.mysql_real_escape_string($eachElement,$this->db_conn).'"';
					}

				}
				$arrayRecord[] ='('.implode(",",$eachValue).')';
			}
			$result = implode(',',$arrayRecord);
		}
		elseif ($matches[1]=='cv') {
			$colValues = &$this->replaceArgs[$this->replaceNum];
			if (!(is_array($colValues) && count($colValues))) {
				die('query_error');
			}
			$arrayImplode=array();
			foreach($colValues as $eachCol=>$eachValue) {
				if (is_null($eachValue)) {
					$arrayImplode[]= $eachCol.'=null';
				}
				else {
					$arrayImplode[]= $eachCol.'="'.mysql_real_escape_string($eachValue,$this->db_conn).'"';
				}

			}
			$result = implode(",",$arrayImplode);
		}
		$this->replaceNum++;
		return $result;
	}

	function _select($query) {
		$result = mysql_query($query,$this->db_conn);
		if (!$result) {
			return false;
		}
		$arResult=array();
		while ($row = mysql_fetch_assoc($result)) {
			$arResult[] = $row;
		}
		return $arResult;
	}

	function query ($query)
	{
		$time[] = microtime();

		$res = mysql_query($query, $this->db_conn);
		if (preg_match("/^select/",trim(strtolower($query)))) $this->count = $this->count_($res);
		
		if (!$res){
			$debug = @debug_backtrace();
			if($debug){
				krsort($debug);
				foreach ($debug as $v) $debugInfo[] = $v['file']." (line:$v[line])";
				$debugInfo = implode("<br>",$debugInfo);
			}

			$err['query']	= $query;
			$err['file']	= $debugInfo;
			$this->error($err);
		}

		$time[] = microtime();
		$this->time[] = get_microtime($time[0],$time[1]);
		$this->log[] = $query;

		if ($res) return $res;
	}

	function count_($result)
	{
		if (is_resource($result))$rows = mysql_num_rows($result);
		if ($rows !== null) return $rows;
	}

	function fetch($res,$mode=0)
	{
		if (!is_resource($res)) $res = $this->query($res);
		return (!$mode) ? @mysql_fetch_array($res) : @mysql_fetch_assoc($res);
	}

	function error($err)
	{
		if ($this->err_report){
			//msg("정상적인 요청이 아니거나 DB에 문제가 있습니다",-1);
			echo "
			<div style='background-color:#f7f7f7;padding:2'>
			<table width=100% border=1 bordercolor='#cccccc' style='border-collapse:collapse;font:9pt tahoma'>
			<col width=100 style='padding-right:10;text-align:right;font-weight:bold'><col style='padding:3 0 3 10'>
			<tr><td>error</td><td>".mysql_error()."</td></tr>
			";
			foreach ($err as $k=>$v) echo "<tr><td>$k</td><td>$v</td></tr>";
			echo "</table></div>";
			//exit();
		}
	}
}
?>