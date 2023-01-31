<?php
/*
	code 클래스
*/

class code {

	protected $codeTypeName;				// 코드 타입명
	protected $arrayCodeTypeField;		// 코드 타입별 alias=>필드명
	public $arrayCodeData;				// '|' 구분자로 넘어오는 데이터 배열 등록 변수
	public $arrayCodeTypeRow;			// 타입별 코드명 변수

	/* ------------------------------------------------
	*	- 도움말 - 생성자
	*  ------------------------------------------------
	*/
	public function __construct() {
		$this->codeTypeName			= '';
		$this->arrayCodeData		= array();
		$this->arrayCodeTypeRow		= array();
	}

	/* ------------------------------------------------
	*	- 도움말 - 코드 타입별 초기 셋팅
	*  ------------------------------------------------
	*/
	public function setCode($codeData, $tableType, $arrayWhere = array()) {
		GLOBAL $db;

		$stringCodeTableName	= '';		// 코드 타입별 테이블명
		$codeTypeSortField		= '';		// 코드 타입별 정렬 필드명
		$codeTypeGroupBy		= '';		// 코드 타입별 Group by 절 생성
		$arraySelectWhere		= array();	// 코드 타입별 조건 문
		$this->arrayCodeTypeRow = array();
		
		if (is_array($arrayWhere)) {
			$arraySelectWhere = $arrayWhere;
		}
		
		if (!is_array($codeData)) {
			$codeData = array($codeData);
		}

		$this->arrayCodeData = $codeData;
		
		if (!$this->arrayCodeData[sizeof($this->arrayCodeData)-1]) unset($this->arrayCodeData[sizeof($this->arrayCodeData)-1]);
		

		if ($tableType == 'codeType') {
			$arraySelectWhere[]					= "ct_delete_flag = 'n'";
			$codeTypeSortField					= 'ct_sort';
			$this->arrayCodeTypeField['code']	= 'ct_code';
			$this->arrayCodeTypeField['name']	= 'ct_name';
			$this->codeTypeName					= '코드 타입';
			$stringCodeTableName				= GD_CODE_TYPE;
		}
		else if ($tableType == 'code') {
			$arraySelectWhere[]					= "c_delete_flag = 'n'";
			$codeTypeSortField					= 'c_sort';
			$this->arrayCodeTypeField['code']	= 'c_code';
			$this->arrayCodeTypeField['name']	= 'c_name';
			$this->codeTypeName					= '코드';
			$stringCodeTableName				= GD_CODE;
		}
		else if ($tableType == 'division') {
			$arraySelectWhere[]					= "d_delete_flag = 'n'";
			$codeTypeSortField					= 'd_index';
			$this->arrayCodeTypeField['code']	= 'd_code';
			$this->arrayCodeTypeField['name']	= 'd_name';
			$this->codeTypeName					= '부서';
			$stringCodeTableName				= GD_DIVISION;
		}
		else if ($tableType == 'auth') {
			$arraySelectWhere[]					= "a_delete_flag = 'n'";
			$codeTypeSortField					= 'a_index';
			$this->arrayCodeTypeField['code']	= 'a_code';
			$this->arrayCodeTypeField['name']	= 'a_name';
			$this->codeTypeName					= '권한';
			$stringCodeTableName				= GD_AUTH;
		}
		else if ($tableType == 'solution') {
			$arraySelectWhere[]					= "s_delete_flag = 'n'";
			$codeTypeSortField					= 's_name';
			$this->arrayCodeTypeField['code']	= 's_code';
			$this->arrayCodeTypeField['name']	= 's_name';
			$this->codeTypeName					= '솔루션';
			$stringCodeTableName				= GD_SOLUTION;
		}
		else if ($tableType == 'menu') {
			$arraySelectWhere[]					= "m_delete_flag = 'n'";
			//$arraySelectWhere[]					= "m_parent = 0";
			$arraySelectWhere[]					= "m_view_flag = 'y'";
			$codeTypeSortField					= 'm_index asc';
			$this->arrayCodeTypeField['code']	= 'm_code';
			$this->arrayCodeTypeField['name']	= 'm_subject';
			$this->codeTypeName					= '메뉴';
			$stringCodeTableName				= GD_MENU;
			$codeTypeGroupBy					= " group by m_code ";
		}
		else if ($tableType == 'position') {
			$arraySelectWhere[]					= "p_delete_flag = 'n'";
			$codeTypeSortField					= 'p_index';
			$this->arrayCodeTypeField['code']	= 'p_code';
			$this->arrayCodeTypeField['name']	= 'p_name';
			$this->codeTypeName					= '직급';
			$stringCodeTableName				= GD_POSITION;
			$codeTypeGroupBy					= " group by p_code ";
		}
		
		$arrayFieldSet = array();
		foreach ($this->arrayCodeTypeField as $aliasKey => $fieldValue) {
			$arrayFieldSet[] = $fieldValue . ' as ' . $aliasKey;
		}
		$codeResult = $db->query("Select " . implode(', ', $arrayFieldSet) . "
										From " . $stringCodeTableName . " 
									Where " . implode(' and ', $arraySelectWhere) . " " . $codeTypeGroupBy . "order by " . $codeTypeSortField);
		
		while ($codeRow = $db->fetch($codeResult)) {
			$this->arrayCodeTypeRow[$codeRow['code']] = $codeRow['name'];
		}
	}

	public function setCodeTableType ($codeData, $codeType) {
		$arrayWhere = array();
		$arrayWhere[] = "ct_code = '" . $codeType . "'";
		
		$this->setCode('', 'codeType', $arrayWhere);
		$codeTypeName = $this->arrayCodeTypeRow[$codeType];

		unset($this->arrayCodeTypeRow);
		$this->setCode($codeData, 'code', $arrayWhere);
		$this->codeTypeName = $codeTypeName;

		return $this;
	}
	
	/* ------------------------------------------------
	*	- 도움말 - 코드별 데이터 전체 체크박스 생성후 
	*	현재 보유 코드 데이터 체크 기능 셋팅
	*  ------------------------------------------------
	*/
	public function setCodeCheckBox($checkBoxName) {
		$searchCheckBoxName = $checkBoxName . '[]'; // 검색 input명

		$labelcnt = 0;
		foreach ($this->arrayCodeTypeRow as $codeKey => $codeName) {
			$checked = '';
			if (is_array($this->arrayCodeData)) {
				if (in_array($codeKey, $this->arrayCodeData)) $checked = 'checked';
			}
			echo '<div style="width:220px;float:left;overflow:visible;"><input type="checkbox" name="' . $searchCheckBoxName . '" id="' . $checkBoxName . $labelcnt . '" value="' . $codeKey . '" ' . $checked . '/> <label for="' . $checkBoxName . $labelcnt . '" >' . $codeName . '  </label></div>';
			$labelcnt++; 
		}
	}
	
	/* ------------------------------------------------
	*	- 도움말 - 코드별 데이터 전체 셀렉트박스 생성후 
	*	현재 보유 코드 데이터 셀렉티드 기능 셋팅
	*  ------------------------------------------------
	*/
	public function setCodeSelectBox($selectBoxName) {
		echo "<select name='" . $selectBoxName . "' LabelStr='" . $this->codeTypeName . "'>";
		echo "<option value='' >- " . $this->codeTypeName . " 선택 -</option>";
		foreach ($this->arrayCodeTypeRow as $codeKey => $codeName) {
			$selected = '';
			if (is_array($this->arrayCodeData)) {
				if (in_array($codeKey, $this->arrayCodeData)) $selected = 'selected';
			}
			echo "<option value='" . $codeKey . "' " . $selected . " >" . $codeName . "</option>";
		}
		echo "</select>";
	}

	/* ------------------------------------------------
	*	- 도움말 - 현재 보유 코드 코드명으로 텍스트 출력 셋팅
	*  ------------------------------------------------
	*/
	public function setText() {
		$separatorCnt = 0;
		foreach ($this->arrayCodeData as $codeKey) {
			if (!$codeKey) continue;
			if ($separatorCnt > 0){
				echo ', ';
			}
			echo $this->arrayCodeTypeRow[$codeKey];
			$separatorCnt++;
		}
	}
	
	/* ------------------------------------------------
	*	- 도움말 - In 절 검색 생성 후 반납
	*  ------------------------------------------------
	*/
	public function getIn() {
		$separatorCnt	= 0;
		$arrayCodeIn	= array();// In절 배열
		for ($i = 0; $i < count($this->arrayCodeData); $i++) {
			if ($this->arrayCodeData[$i]) {
				$arrayCodeIn[] = "'" . $this->arrayCodeData[$i] . "'";
			}
		}
		$outParmeter = implode(', ', $arrayCodeIn);
		
		return $outParmeter;
	}

	/* ------------------------------------------------
	*	- 도움말 - 현재 보유 코드로 like 검색 생성 후 배열 등록 후 반납
	*  ------------------------------------------------
	*/
	public function getLike($fieldName, $searchType = '') {
		if (empty($this->arrayCodeData) === true) return false;
		
		if (!$searchType == 'separator') $separator = '';
		else $separator = '|';
		$arrayCodeLike	= array();// like절 배열
		for ($i = 0; $i < count($this->arrayCodeData); $i++) {
			$arrayCodeLike[] = $fieldName . " Like '%" . $separator . $this->arrayCodeData[$i] . $separator ."%'";
		}

		$outParmeter = implode(' Or ', $arrayCodeLike);
		
		return $outParmeter;
	}

	/* ------------------------------------------------
	*	- 도움말 - 코드별 데이터 권한이 있는 체크박스 생성후 2013-04-18w
	*	현재 보유 코드 데이터 체크 기능 셋팅
	*  ------------------------------------------------
	*/
	public function setAuthCodeCheckBox($checkedCode) {
		$labelcnt = 0;
		$arrayCheck = explode('|', $checkedCode);

		foreach ($this->arrayCodeData as $codeKey) {
		$checked = '';
		
		if (!$codeKey) continue;

		if (in_array($codeKey, $arrayCheck)) $checked = 'checked';
			
			echo '<input type="checkbox" name="' . $this->arrayCodeTypeField['code'] . '[]" id="' . $this->arrayCodeTypeField['code'] . $labelcnt . '" value="' . $codeKey . '" '.$checked.' /><label for="' . $this->codeTypeField . $labelcnt . '" >' . $this->arrayCodeTypeRow[$codeKey] . ' </label>';
			$labelcnt++; 
		}
	}
}


?>