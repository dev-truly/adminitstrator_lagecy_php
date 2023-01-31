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

    $mIndex				= trimGetRequest('m_index');	

	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성 데이터 저장시 사용
	*  ------------------------------------------------
	*/
	$getStr ='';
	$getStr = getStr($getStr,'xUidx',$mIndex);
	$getStr = getStr($getStr,'listCnt',$listCnt);
	$getStr = getStr($getStr,'page',$page);
	$getStr = getStr($getStr,'selectType',$selectType);
	$getStr = getStr($getStr,'selectValue',$selectValue);
	$getStr = getStr($getStr,'sort',$sort);

	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성 신규 추가시 사용
	*  ------------------------------------------------
	*/
	$getStr2 ='';
	$getStr2 = getStr($getStr2,'m_index',$mIndex);
	$getStr2 = getStr($getStr2,'listCnt',$listCnt);
	$getStr2 = getStr($getStr2,'page',$page);
	$getStr2 = getStr($getStr2,'selectType',$selectType);
	$getStr2 = getStr($getStr2,'selectValue',$selectValue);
	$getStr2 = getStr($getStr2,'sort',$sort);


	if ($xUidx) {
		$proc = 'MODIFY';
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('mpp_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
	
		$selectQuery = "Select * From " . GD_MANUAL_PROCESS_PATCH . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$mppIndex			= $dataView['mpp_index'];
		//$mdCode			= $dataView['md_code'];
		$mppType			= $dataView['mpp_type'];
		$mppPacthUrl		= $dataView['mpp_patchurl'];
		$mppName			= $dataView['mpp_name'];
		$mppIp				= $dataView['mpp_ip'];
		$mppWriteDate		= $dataView['mpp_writedate'];
		$mppContents		= $dataView['mpp_contents'];
		$mppSubject			= $dataView['mpp_subject'];
		$mppDeleteFl		= $dataView['mpp_deletefl'];


	}
	else {
		$proc	= 'WRITE';
		$info	= "등록";
		
	}

	/* ------------------------------------------------
	*	- 도움말 - 코드 데이터 변환 출력 준비
	*  ------------------------------------------------
	*/
	
	$divisionCode			= class_load('code');
	$divisionCode->setCode($_SESSION['sess']['maDivisionCode'], 'division');
	$categoryCode			= class_load('code');
	$categoryCode->setCode($_SESSION['sess']['maCategoryCode'], 'category');
	$solutionCode			= class_load('code');
	$solutionCode->setCode($mSolutionCode, 'solution');

	/* ------------------------------------------------
	*	- 페이지 설정
	*  ------------------------------------------------
	*/
	if(!$listCnt) $listCnt = 10;
	if(!$page) $page = 1;

	$pg = class_load('page');
	$pg->Page($page,$listCnt);
	
	


?>

<SCRIPT LANGUAGE="JavaScript">
<!--

function _Fsave()
{
	var form = document.frmMain;
	ed1.getHtml();

	doForm(form);
}
function _Fdelete()
{
	var form = document.frmMain;
	form.proc.value = "DELETE";

	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("22","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("26","<?=$getStr2?>");
}

//-->
</SCRIPT>
		<script type="text/javascript" src="/_js/easyEditor.js"></script>
		<form name="frmMain" method="post" 	action="./mpp_proc.php" >
		<?=getStrFromReqValHid($getStr)?>
		<input type="hidden" name="mpp_index" value="<?=$mppIndex?>">
		<input type="hidden" name="m_index" value="<?=$mIndex?>">
		<input type="hidden" name="proc" value="<?=$proc?>">
		<h1 class="frame-title"><?=$menuSubject?> <span>메뉴얼 답글을 <?=$info?> 할 수 있습니다.</span></h1>
						<!-- view area -->
						<div class="viewArea" >
							<!-- bbsView -->
							<div class="bbsView">
								<div class="tableView">
									<table class="basic">
									<caption>메뉴얼 리스트</caption>
									<colgroup>
										<col width="15%" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th scope="row">메뉴얼 제목</th>
										<td scope="row"><input type="text" name="mpp_subject"  style="width:200px;"   value="<?=$mppSubject?>" IsValidStr="STR"  NNull="true" LabelStr="제목" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">메뉴얼 작성자</th>
										<td scope="row"><input type="text" name="mpp_name"  style="width:100px;"   value="<?=($xUidx) ? $mppName : $_SESSION['sess']['mmName']?>" <?=($mppName) ? 'readonly' : ''?> IsValidStr="STR"  NNull="true" LabelStr="작성자" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">패치 구분</th>
										<td scope="row">
											<label for="mpp_type">패치</label><input type="radio" name="mpp_type" value="a" <?=($mppType == 'a' || !$xUidx) ? 'checked' : '' ?> id="mpp_type" />
											<label for="mpp_typer">프로세스</label><input type="radio" name="mpp_type" value="r" <?=($xUidx && $mppType == 'r') ? 'checked' : '' ?> id="mpp_typer" />
										</td>
									</tr>
									<tr class="bgc" id="patchUrl">
										<th scope="row">패치URL</th>
										<td scope="row">
											<table class="basic">
											<tr class="bgc">
												<td width=300><input type="text" name="mpp_patchurl" value="<?=$mppPacthUrl?>"/> 패치 URL </td>
											</tr>
											</table>
										</td>
									</tr>
								
									<tr>
										<th scope="row">답글 내용</th>
										<td scope="row"><textarea name="mpp_contents" id="mpp_contents" rows="10" cols="100" ><?=$mppContents?></textarea></td>
										<script type="text/javascript">
											var ed1 = new easyEditor("mpp_contents");
											ed1.init();
										</script>
									</tr>
									<tr class="bgc">
										<th scope="row">삭제유무</th>
										<td scope="row"><?=( $mpp_deleteFl != 'n' ) ? "보임" : '삭제'?> (삭제시 상단에 삭제버튼을 눌러주세요)</td>
									</tr>
									<tr class="bgc">
										<th scope="row">로그인 ip</th>
										<td scope="row"><?=$mppIp?></td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="bgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$mppWriteDate?></td>
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
	
		<script type="text/javascript">
			function mppTypeCheck(typeValue){
				if (typeValue == 'a') {
					$('#patchUrl').show();
				}
				else {
					$('#patchUrl').hide();
				}
			}
			$('input[name="mpp_type"]').click(function(){
				mppTypeCheck($(this).val());
			});
			
			mppTypeCheck($('input[name="mpp_type"]:checked').val());
		</script>
				

 
<?
	include ('../../_admin/_include/sysbottom.php');
?>