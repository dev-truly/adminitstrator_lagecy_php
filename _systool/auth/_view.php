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
	$getStr = getArrayStr($getStr, 'm_codesearch[]', trimGetRequest('m_codesearch'));
	$getStr = getArrayStr($getStr, 'd_codesearch[]', trimGetRequest('d_codesearch'));
	$getStr = getArrayStr($getStr, 'a_codesearch[]', trimGetRequest('a_codesearch'));

	if ($xUidx) {
		$proc = 'MODIFY';
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('a_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_AUTH . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$aIndex				= $dataView['a_index'];
		$aCode				= $dataView['a_code'];
		$dCode				= $dataView['d_code'];
		$aAuthCode			= $dataView['a_auth_code'];
		$aMenuCode			= $dataView['a_menu_code'];
		$aName				= $dataView['a_name'];
		$aDeleteFlag		= $dataView['a_delete_flag'];
		$aIp				= $dataView['a_ip'];
		$aWriteDate			= $dataView['a_write_date'];
		
		/* ------------------------------------------------
		*	- 도움말 - 코드 데이터 변환 출력 준비
		*  ------------------------------------------------
		*/
		$divisionCode		= class_load('code');
		$divisionCode->setCode(explode('|', $dCode), 'division');
		$menuCode			= class_load('code');
		$menuCode->setCode(explode('|', $aMenuCode), 'menu');
		$authCode			= class_load('code');
		$authCode->setCode(explode('|', $aAuthCode), 'auth');
	}
	else {
		$proc	= 'WRITE';
		$info	= "등록";

		/* ------------------------------------------------
		*	- 도움말 - 코드 데이터 변환 출력 준비
		*  ------------------------------------------------
		*/
		$divisionCode		= class_load('code');
		$divisionCode->setCode('', 'division');
		$menuCode			= class_load('code');
		$menuCode->setCode('', 'menu');
		$authCode			= class_load('code');
		$authCode->setCode('', 'auth');
	}

?>

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
	form.proc.value = "DELETE";
	var form = document.frmMain;
	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("9","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("15","<?=$getStr?>");
}

//-->
</SCRIPT>

		<form name="frmMain" method="post" 	action="./_proc.php">
			<?php include ('../../_admin/_include/view_title.php');?>
									<colgroup>
										<col width="15%" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th scope="row">권한 코드</th>
										<td scope="row">
											<?php if (!$xUidx) { ?>
												<input type="text" name="a_code"  style="width:50px;"   value="<?=$aCode?>" IsValidStr="STR"  NNull="true" LabelStr="권한 코드" />
											<?php } 
											else { ?>
												<?=$aCode?>
											<?php } ?>
										</td>
									</tr>
									<tr class="nobgc">
										<th scope="row">권한명</th>
										<td scope="row"><input type="text" name="a_name"  style="width:200px;"   value="<?=$aName?>" IsValidStr="STR"  NNull="true" LabelStr="권한명" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">보유 권한</th>
										<td scope="row" style="padding-bottom:5px;"><p class="codesearchbox"><?php $divisionCode->setCodeCheckBox('d_code');?></p><p class="codesearchbox"><?php $menuCode->setCodeCheckBox('m_code');?></p><p class="codesearchbox"><?php $authCode->setCodeCheckBox('a_auth_code');?></p>
										</td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="bgc">
										<th scope="row">등록, 수정 IP</th>
										<td scope="row"><?=$aIp?></td>
									</tr>
									<tr class="nobgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$aWriteDate?></td>
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