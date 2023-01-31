<?php	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/

	$xUidx			= trimGetRequest('xUidx');				//------------ 일련번호

	$listCnt		= trimGetRequest('listCnt');				//------------ 출력되는 리스트 수
	$page			= trimGetRequest('page');				//------------ 현재 페이지

	$selectType		= trimGetRequest('selectType');			//------------ 검색 조건
	$selectValue	= trimGetRequest('selectValue');			//------------ 검색값
	$sort			= trimGetRequest('sort');				//------------ 검색값
    
	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성
	*  ------------------------------------------------
	*/
	$getStr ='';
	$getStr = getStr($getStr,'listCnt',$listCnt);
	$getStr = getStr($getStr,'page',$page);
	$getStr = getStr($getStr,'selectType',$selectType);
	$getStr = getStr($getStr,'selectValue',$selectValue);
	$getStr = getStr($getStr,'sort',$sort);

	// 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'a_codesearch[]', trimGetRequest('a_codesearch'));
	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'd_codesearch[]', trimGetRequest('d_codesearch'));
	// 직급 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'p_codesearch[]', trimGetRequest('p_codesearch'));

	if ($xUidx) {
		$proc = 'MODIFY';
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('ru_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		$selectQuery = "Select * From " . GD_RELOCATION_USER ." ". $selectWhere. ";";

		$dataView = $db->fetch($db->query($selectQuery));
		
		$ruIndex				= $dataView['ru_index'];
		$aCode					= $dataView['a_code'];
		$dCode					= $dataView['d_code'];
		$pCode					= $dataView['p_code'];
		
		$ruId					= $dataView['ru_id'];

		$ruName					= $dataView['ru_name'];
		$ruEnName				= $dataView['ru_en_name'];
		$ruEmail				= $dataView['ru_email'];
		$ruPassword				= $dataView['ru_password'];
		$ruExtension			= $dataView['ru_extension'];
		$ruColor				= $dataView['ru_color'];
		$ruPermitIp				= $dataView['ru_permit_ip'];
		$ruPermitIpCheckFlag	= $dataView['ru_permit_ip_check_flag'];
		$ruLoginIp				= $dataView['ru_login_ip'];
		$ruLoginDate			= $dataView['ru_login_date'];
		$ruWriteDate			= $dataView['ru_write_date'];

		$info			= "수정";
	
	}
	else {
		$proc = 'WRITE';
		$info = "등록";
	}

	### 부서명 추출
	$divisionCode = class_load('code');
	$authDCode = explode('|', $_SESSION['sess']['dCode']);
	$divisionCode->setCode($authDCode, 'division');
	$searchDivisionCode = $divisionCode->getIn();
	$divisionCode->setCode($dCode, 'division', array('d_code in (' . $searchDivisionCode . ')'));

	### 권한 추출
	$authCode = class_load('code');
	$authACode = explode('|', $_SESSION['sess']['aAuthCode']);

	$authCode->setCode($authACode, 'auth');
	$searchAAuthCode = $authCode->getIn();
	$authCode->setCode($aCode, 'auth', array('a_code in (' . $searchAAuthCode . ')'));

	### 직급 추출
	$positionCode = class_load('code');
	$positionCode->setCode($pCode, 'position');
	
	$areaHidden = '';
?>
<link rel="stylesheet" type="text/css" href="/_js/spectrum/spectrum.css">

<script type="text/javascript" src="/_js/spectrum/spectrum.js"></script>

<style type="text/css">
	#ruPermitIpCheckBox {
		display:none;
	}
</style>
<SCRIPT LANGUAGE="JavaScript">
function _Fsave()
{
	var form = document.frmMain;
	doForm(form);
}
function _Fdelete()
{
	var form = document.frmMain;
	form.proc.value="DELETE";
	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("15","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("16","<?=$getStr?>");
}

function IsDelete(df)
{
	S_ETCEditor.getHtml();
	var  obj = document.all["B_CONTENT"];
	var Str = obj.innerHTML;
	if(Str.indexOf(df) > -1)
		return false;
	else
		return true;
}
$(document).ready(function() {
	function ruPermitIpFormToggle(checkBoxObj) {
		if (checkBoxObj.is(':checked')) {
			$('#ruPermitIpCheckBox').show();
		}
		else {
			$('#ruPermitIpCheckBox').hide();
		}
	}

	$('#ru_permit_ip_check_flag').click(function(){
		ruPermitIpFormToggle($(this));
	}).css('cursor', 'pointer');
	
	ruPermitIpFormToggle($('#ru_permit_ip_check_flag'));

	$("#ru_color").spectrum({
		color: $(this).val(),
		theme: "sp-light",
		preferredFormat: "hex",
		showInput: true,
		allowEmpty:true
	});
});


</SCRIPT>				

					<form name="frmMain" method="post" 	action="./_proc.php">
						<?php include ('../../_admin/_include/view_title.php');?>
											<caption>회원 요약</caption>
											<colgroup>
												<col width="15%" />
												<col width="auto" />
											</colgroup>
											<thead>
											<?php 
												if (ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode'])) {
											?>
											<tr class="bg">
												<th scope="row">권한</th>
												<td scope="row">
													<?php $authCode->setCodeSelectBox('a_code')?>
												</td>
											</tr>
											<tr class="nobgc">
												<th scope="row">부서</th>
												<td scope="row">
													<?php $divisionCode->setCodeSelectBox('d_code')?>
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">직급</th>
												<td scope="row">
													<?php $positionCode->setCodeSelectBox('p_code')?>
												</td>
											</tr>
											<?php
												}
												else {
													$areaHidden = 'style="display:none;"';
											?>
											<tr class="bg">
												<th scope="row">권한</th>
												<td scope="row">
													<?php $authCode->setText()?>
													<input type="hidden" name="a_code" value="<?=$aCode?>" />
												</td>
											</tr>
											<tr class="nobgc">
												<th scope="row">부서</th>
												<td scope="row">
													<?php $divisionCode->setText()?>
													<input type="hidden" name="d_code" value="<?=$dCode?>" />
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">직급</th>
												<td scope="row">
													<?php $positionCode->setText()?>
													<input type="hidden" name="p_code" value="<?=$pCode?>" />
												</td>
											</tr>
											<?php
												}
											?>

											<tr class="nobgc">
												<th scope="row">아이디</th>
												<td scope="row">
													<?php if (!$xUidx) { ?>
														<input type="text" name="ru_id"  style="width:100px;"   value="<?=$ruId?>" MinL="4" MaxL="30" IsValidStr="STR"  NNull="true" LabelStr="아이디" />
													<?php } 
													else { ?>
														<?=$ruId?>
													<?php } ?>
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">비밀번호</th>
												<td scope="row"><input type="password" name="ru_password"  style="width:200px;" value="" IsValidStr="STR" <?=($proc == 'WRITE') ? 'NNull="true"' : 'NNull="false"' ?> LabelStr="비밀번호"></td>
											</tr>
											<tr >
												<th scope="row">이름</th>
												<td scope="row"><input type="text" name="ru_name"  style="width:100px;" value="<?=$ruName?>" IsValidStr="STR" MinL="2" MaxL="20" NNull="true" LabelStr="이름"></td>
											</tr>
											
											<tr class="bgc">
												<th scope="row">영문이름</th>
												<td scope="row"><input type="text" name="ru_en_name"  style="width:100px;" value="<?=$ruEnName?>" IsValidStr="STR" MinL="2" MaxL="30" NNull="false" LabelStr="영문이름"></td>
											</tr>

											<tr >
												<th scope="row">고도 이메일</th>

												<td scope="row"><input type="text" name="ru_email"  style="width:100px;" value="<?=$ruEmail?>" IsValidStr="STR" MinL="2" MaxL="50" NNull="false" LabelStr="이메일"> @godo.co.kr</td>
											</tr>
											<tr class="bgc">
												<th scope="row">내선 번호</th>
												<td scope="row"><input type="text" name="ru_extension"  style="width:50px;" value="<?=$ruExtension?>" IsValidStr="NUM" MinL="4" MaxL="4" NNull="false" LabelStr="내선 번호"></td>
											</tr>
											<tr >
												<th scope="row">담당자 색상</th>

												<td scope="row"><input type="text" name="ru_color"  style="width:50px;" id="ru_color" value="<?=$ruColor?>" IsValidStr="STR" NNull="false" LabelStr="담당자 색상"></td>
											</tr>
											<tr class="bgc" <?=$areaHidden?>>
												<th scope="row">로그인 IP 체크 여부</th>

												<td scope="row"><input type="checkbox" name="ru_permit_ip_check_flag" id="ru_permit_ip_check_flag" value='y' <?=($ruPermitIpCheckFlag == 'y') ? 'checked' : '' ?> LabelStr="로그인 IP 체크 여부">
													<span id="ruPermitIpCheckBox">
														<input type="text" name="ru_permit_ip" value="<?=$ruPermitIp?>" MinL="4" MaxL="15" IsValidStr="STR" NNull="<?=($ruPermitIpCheckFlag == 'y') ? 'true' : 'false' ?>" LabelStr="로그인 체크 IP"/>
													</span>
												</td>

											</tr>
											<?php
												if ($xUidx) {
											?>
											<tr>
												<th scope="row">최근 로그인 ip</th>
												<td scope="row"><?=$ruLoginIp?></td>
											</tr>
											<tr>
												<th scope="row">최근 로그인 일자</th>
												<td scope="row"><?=$ruLoginDate?></td>
											</tr>
											<tr class="bgb">
												<th scope="row">등록일</th>
												<td scope="row"><?=$ruWriteDate?></td>
											</tr>
											<?php
												}
											?>
											</thead>
											</table>
										</div>
									</div>
									<!-- //bbsView -->
								</div>
						</form>

<?
	include ('../../_admin/_include/sysbottom.php');
?>