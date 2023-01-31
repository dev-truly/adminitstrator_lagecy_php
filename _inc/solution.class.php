<?php
	class solution extends code {
		public $arrayUseFieldName = array();

		/* ------------------------------------------------
		*	- 도움말 - 생성자
		*  ------------------------------------------------
		*/
		public function __construct() {
			$this->arrayUseFieldName = array(
				'RB'	=> 's_relocation_before',
				'RA'	=> 's_relocation_after',
				'GB'	=> 's_godo_before',
				'GA'	=> 's_godo_after',
				'TS'	=> 's_techsupport',
			);
			$this->arrayCodeTypeField = $this->arrayUseFieldName;
		}

		public function setWhere($codeData, $arrayUseType) {
			unset($this->arrayCodeTypeRow);
			$arrayWhere = array();
			$where = '';
			
			if (!is_array($codeData)) {
				$codeData = array($codeData);
			}

			if (!empty($arrayUseType)) {
				foreach ($this->arrayUseFieldName as $useType => $useFieldName) {
					if (in_array($useType, $arrayUseType)) {
						$arrayWhere[] = $useFieldName . " = 'y'";
					}
				}
				$where = '(' . implode(' or ', $arrayWhere) . ')';
			}

			$this->setCode($codeData, 'solution', array($where));

			return $this;
		}

		/* ------------------------------------------------
		*	- 도움말 - 코드별 데이터 전체 체크박스 생성후 
		*	현재 보유 코드 데이터 체크 기능 셋팅
		*  ------------------------------------------------
		*/
		public function getSolutionListCheckBox($checkboxName) {
			$searchCheckBoxName = ''; // 검색 input명
			
			if ($searchFl) $searchCheckBoxName = 'search';
			
			$labelcnt = 0;

			foreach ($this->arrayCodeTypeRow as $codeKey => $codeName) {
				$checked = '';
				if (in_array($codeKey, $this->arrayCodeData)) $checked = 'checked';
				
				echo '<div style="width:220px;float:left;overflow:visible;"><input type="checkbox" name="' . $checkboxName . '[]" id="' . $checkboxName . $labelcnt . '" value="' . $codeKey . '" ' . $checked . '/> <label for="' . $checkboxName . $labelcnt . '" >' . $codeName . '  </label></div>';
				$labelcnt++; 
			}
		}

		public function setSolutionTypeCheckBox($arrayUseType, $searchFl="") {
			$arrayUseTypeDesc = array(
				'RB'	=>	'타사 이전 전 사용',
				'RA'	=>	'타사 이전 후 사용',
				'GB'	=>	'자사 이전 전 사용',
				'GA'	=>	'자사 이전 후 사용',
				'TS'	=>	'기술지원 사용',
			);
			foreach ($this->arrayUseFieldName as $useType => $useFieldName) {
				$checked = '';
				$selectBoxName = ''; // 검색 input명
				
				$selectBoxName = ($searchFl) ? 'solutionUseType[]' : 'useType[]';
				
				if (!empty($arrayUseType) && in_array($useType, $arrayUseType)) {
					$checked = 'checked';
				}
			?>
				<div style="width: 220px; overflow: visible; float: left;">
					<input type="checkbox" name="<?=$selectBoxName?>" id="<?=$useType?>" value="<?=$useType?>" <?=$checked?>/>
					<label for="<?=$useType?>"><?=$arrayUseTypeDesc[$useType]?></label>
				</div>
			<?php
			}
		}

		/* ------------------------------------------------
		*	- 도움말 - 코드별 데이터 전체 셀렉트박스 생성후 
		*	현재 보유 코드 데이터 셀렉티드 기능 셋팅
		*  ------------------------------------------------
		*/
		public function setCodeSelectBox($codeData, $arrayUseType, $selectBoxName = '') {
			if (!is_array($arrayUseType)) $arrayUseType = array($arrayUseType);

			if (!$selectBoxName) $selectBoxName = $this->arrayCodeTypeField['code'];
			$stringWhere = $this->setWhere($codeData, $arrayUseType);

			echo '<select name="' . $selectBoxName . '" LabelStr="' . $this->codeTypeName . '">';
			echo '<option value="" >- ' . $this->codeTypeName . ' 선택 -</option>';
			foreach ($this->arrayCodeTypeRow as $codeKey => $codeName) {
				$selected = '';
				if (in_array($codeKey, $this->arrayCodeData)) $selected = 'selected';
				echo '<option value="' . $codeKey . '" ' . $selected . ' >' . $codeName . '</option>';
			}
			echo '</select>';
		}
	}
?>