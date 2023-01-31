<?php	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/

	$xUidx			= $_GET['xUidx'];				//------------ 일련번호

	$listCnt		= $_GET['listCnt'];				//------------ 출력되는 리스트 수
	$page			= $_GET['page'];				//------------ 현재 페이지

	$selectType		= $_GET['selectType'];			//------------ 검색 조건
	$selectValue	= $_GET['selectValue'];			//------------ 검색값
	$sort			= $_GET['sort'];				//------------ 검색값
    
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
	if (!empty($_GET['ma_codesearch'])) {
		foreach ($_GET['ma_codesearch'] as $maCodeSearchValue) {
			$getStr = getStr($getStr,'ma_codesearch[]',$maCodeSearchValue);
		}
	}

	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['md_codesearch'])) {
		foreach ($_GET['md_codesearch'] as $mdCodeSearchValue) {
			$getStr = getStr($getStr,'md_codesearch[]',$mdCodeSearchValue);
		}
	}

	if ($xUidx) {
		$proc = 'MODIFY';
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('grm_index', $xUidx, 'C');
		$selectWhere = 'Where grm_index = '. $xUidx;

		$selectQuery = "Select * From " . GD_RELOCATION_MEMBER ." ". $selectWhere. ";";

		$dataView = $db->fetch($db->query($selectQuery));
													
		$grmIndex		= $dataView['grm_index'];
		$rdCode			= $dataView['rd_code'];
		$grmId			= $dataView['grm_id'];
		$grmName		= $dataView['grm_name'];
		$grmEnName		= $dataView['grm_enName'];
		$grmEmail		= $dataView['grm_email'];
		$grmPosition	= $dataView['grm_position'];
		$grmExtension	= $dataView['grm_extension'];
		$grmColor		= $dataView['grm_color'];
		$grmIpCheckFl	= $dataView['grm_ip_checkFl'];
		$grmDeleteFl	= $dataView['grm_deleteFl'];
		$grmIp			= $dataView['grm_loginip'];
		$grmWriteDate	= $dataView['grm_writedate'];
		$info			= "수정";
	
	}
	else {
		$proc = 'WRITE';
		$info = "등록";
	}

	### 부서명 추출
	$divisionCode = class_load('code');
	$divisionCode->setCode($dataView['rd_code'], 'division');
	### 권한 추출
	$authCode = class_load('code');
	$authCode->setCode($dataView['ra_code'], 'auth');

?>
<link rel="stylesheet" type="text/css" href="/_js/spectrum/spectrum.css">
<script type="text/javascript" src="/_js/spectrum/spectrum.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
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
	LoadLinkStr("6","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("17","<?=$getStr?>");
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
//-->
$(window).ready(function(){
	$("#grm_color").spectrum({
		color: $(this).val(),
		theme: "sp-light",
		preferredFormat: "hex6",
		showInput: true,
		allowEmpty:true
	});
});


</SCRIPT>				
					<form name="frmMain" method="post" 	action="./_proc.php">
					<?=getStrFromReqValHid($getStr)?>
					<input type="hidden" name="grm_index" value="<?=$grmIndex?>">
					<input type="hidden" name="proc" value="<?=$proc?>">
					<h1 class="frame-title"><?=$menuSubject?> <span>회원을 <?=$info?> 할 수 있습니다.</span></h1>
								<!-- view area -->
								<div class="viewArea" >
									<!-- bbsView -->
									<div class="bbsView">
										<div class="tableView">
											<table class="basic">
											<caption>회원 요약</caption>
											<colgroup>
												<col width="15%" />
												<col width="auto" />
											</colgroup>
											<thead>
											<tr class="bg">
												<th scope="row">아이디</th>
												<td scope="row">
													<?php if (!$xUidx) { ?>
														<input type="text" name="grm_id"  style="width:100px;"   value="<?=$grmId?>" MinL="4" MaxL="30" IsValidStr="STR"  NNull="true" LabelStr="아이디" />
													<?php } 
													else { ?>
														<?=$grmId?>
													<?php } ?>
												</td>
											</tr>
											<tr >
												<th scope="row">이름</th>
												<td scope="row"><input type="text" name="grm_name"  style="width:100px;" value="<?=$grmName?>" IsValidStr="STR" MinL="2" MaxL="20" NNull="true" LabelStr="이름"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">영문이름</th>
												<td scope="row"><input type="text" name="grm_enName"  style="width:100px;" value="<?=$grmEnName?>" IsValidStr="STR" MinL="2" MaxL="30" NNull="false" LabelStr="영문이름"></td>
											</tr>
											<tr >
												<th scope="row">고도 이메일</th>
												<td scope="row"><input type="text" name="grm_email"  style="width:100px;" value="<?=$grmEmail?>" IsValidStr="STR" MinL="2" MaxL="30" NNull="false" LabelStr="이메일"> @godo.co.kr</td>
											</tr>
											<tr class="bgc">
												<th scope="row">내선 번호</th>
												<td scope="row"><input type="text" name="grm_extension"  style="width:50px;" value="<?=$grmExtension?>" IsValidStr="NUM" MinL="4" MaxL="4" NNull="false" LabelStr="내선 번호"></td>
											</tr>
											<tr>
												<th scope="row">부서</th>
												<td scope="row">
													<?php $divisionCode->setCodeSelectBox()?>
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">권한</th>
												<td scope="row">
													<?php $authCode->setCodeSelectBox()?>
												</td>
											</tr>
											<tr >
												<th scope="row">직급</th>
												<td scope="row"><input type="text" name="grm_position"  style="width:50px;" value="<?=$grmPosition?>" IsValidStr="STR" MinL="2" MaxL="10" NNull="false" LabelStr="직급"></td>
											</tr>
											<tr >
												<th scope="row">담당자 색상</th>
												<td scope="row"><input type="text" name="grm_color"  style="width:50px;" id="grm_color" value="<?=$grmColor?>" IsValidStr="STR" NNull="false" LabelStr="담당자 색상"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">비밀번호</th>
												<td scope="row"><input type="password" name="grm_password"  style="width:200px;" value="" IsValidStr="STR" <?=($proc == 'WRITE') ? 'NNull="true"' : 'NNull="false"' ?> LabelStr="비밀번호"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">로그인 IP 체크 여부</th>
												<td scope="row"><input type="checkbox" name="grm_ip_checkFl" value='y' <?=($grmIpCheckFl == 'y') ? 'checked' : '' ?> LabelStr="로그인 IP 체크 여부"></td>
											</tr>
											<tr>
												<th scope="row">로그인 ip</th>
												<td scope="row"><?=$grmIp?></td>
											</tr>
											<?php
												if ($xUidx) {
											?>
											<tr class="bgb">
												<th scope="row">등록일</th>
												<td scope="row"><?=$grmWriteDate?></td>
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