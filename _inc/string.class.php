<?
class string {
	var $arrayAddItem1; //arrayAddItem1
 	var $arrayAddItem2; // arrayAddItem2
	var $arrayAddItem3; // arrayAddItem3	
	var $arrayAddItemCount;  // arrayAddItemCount
	var $arrayWhereCount;	  // arrayWhereCount
	
	//------------------------------
	//- Advice - 생성자
	//------------------------------
	function string(){
		$this->arrayAddItemCount = 0;
		$this->arrayWhereCount = 0;
	}

	function addItem ($inCol, $inValue, $cType) {
		if ((!$inValue || $inValue == '') && $cType == 'I') {
			return false;
		}
		
		$this->arrayAddItemCount = $this->arrayAddItemCount + 1;
		$this->arrayAddItem1[$this->arrayAddItemCount] = $inCol;
		$this->arrayAddItem2[$this->arrayAddItemCount] = $inValue;
		$this->arrayAddItem3[$this->arrayAddItemCount] = $cType;
	}

	function getWhere() { // Where 절 및 조건 생성
		$outVal			= '';
		$getCount		= '';
		$getQuota		= '';
		for($getCount = 1; $getCount <= $this->arrayAddItemCount; $getCount++) {
			
			if (($this->arrayAddItem1[$getCount] != "" || $this->arrayAddItem3[$getCount] = "ADD") && $this->arrayAddItem2[$getCount] != ""){
				if($getQuota == '') {
					$getQuota = " where ";
				} else {
					$getQuota = " and ";
				}
				
				switch ($this->arrayAddItem3[$getCount]) {
					case "I" : // 숫자형 비교값일 때
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." = ".$this->arrayAddItem2[$getCount];
						break;
					case "FL" : // 문자형 Like 비교값일 때
						$outVal .= $getQuota."  ".$this->arrayAddItem1[$getCount]." Like '%".addslashes($this->arrayAddItem2[$getCount])."%' ";
						break;
					case "C" : // 일반 문자형 비교값
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." = '".addslashes($this->arrayAddItem2[$getCount])."'";
						break;
					case "IN" : //해당 데이터 포함 검색
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." in (".$this->arrayAddItem2[$getCount].")";
						break;
					case "NOTIN" : //아닌 데이터 검색
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." NOT IN (".addslashes($this->arrayAddItem2[$getCount]).")";
						break;
					case "NO" : // 다른 비교값
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." <> '".addslashes($this->arrayAddItem2[$getCount])."'";
						break;
					case "STRD" : // 크거나 같은 비교값
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." >= '".addslashes($this->arrayAddItem2[$getCount])."'";
						break;
					case "LASD" : // 작거나 같은 비교값
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." <= '".addslashes($this->arrayAddItem2[$getCount])."'";
						break;
					case "ADD" : //검색조건 생성
						$outVal .= $getQuota.$this->arrayAddItem2[$getCount];
						break;
					default :
						$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." ".$this->arrayAddItem3[$getCount]." '".addslashes($this->arrayAddItem2[$getCount])."'";
						break;
				}
			}
		}
		return $outVal;
	}

	function getInsert() { // 인서트 문장 생성 데이터 타입은 I, C만 가능
		$getCount = '';
		$getCol = '';
		$getVal = '';
		$getQuota = 1;
		for($getCount=1;$getCount<=$this->arrayAddItemCount;$getCount++){
			if($this->arrayAddItem1[$getCount] != '' && $this->arrayAddItem2[$getCount] != ''){
				if($getCount == 1) $getQuota = "";
				else $getQuota = ",";

				$getCol = $getCol.$getQuota.$this->arrayAddItem1[$getCount];

				if($this->arrayAddItem3[$getCount] == "I"){
					$getVal .= $getQuota.$this->arrayAddItem2[$getCount];
				} else if ($this->arrayAddItem3[$getCount] == "C"){
					$getVal .= $getQuota."'".addslashes($this->arrayAddItem2[$getCount])."'";
				} else if ($this->arrayAddItem3[$getCount] == "P"){
					$getVal .= $getQuota." password('".$this->arrayAddItem2[$getCount]."')";
				}
			}
		}

		return array($getCol,$getVal);
	}

	function getUpdate() {
		$getCount = 1;
		$outVal = '';
		$getQuota = '';

		for($getCount=1;$getCount<=$this->arrayAddItemCount;$getCount++){
			if($this->arrayAddItem1[$getCount] != "" && ($this->arrayAddItem2[$getCount] != "" ||  $this->arrayAddItem3[$getCount] != "I")) {
				if($getCount == 1) $getQuota = "" ;
				else $getQuota = ",";

				if($this->arrayAddItem3[$getCount] == "I"){
					$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." = ".$this->arrayAddItem2[$getCount];
				} else if($this->arrayAddItem3[$getCount] == "C"){
					$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." = '".addslashes($this->arrayAddItem2[$getCount])."'";
				} else if ($this->arrayAddItem3[$getCount] == "P"){
					$outVal .= $getQuota.$this->arrayAddItem1[$getCount]." = password('".$this->arrayAddItem2[$getCount]."')";
				}
			}
		}

		return $outVal;
	}

	function setInit() { // 해당 개체 초기화
		$this->arrayWhereCount = 0;
		$this->arrayAddItemCount = 0;
		$this->arrayAddItem1=array();
		$this->arrayAddItem2=array();
		$this->arrayAddItem3=array();
	}

}
?>