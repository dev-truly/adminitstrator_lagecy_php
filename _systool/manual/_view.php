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

	// 솔루션 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['ms_codesearch'])) {
		foreach ($_GET['ms_codesearch'] as $mamCodeSearchValue) {
			$getStr = getStr($getStr,'ms_codesearch[]',$mamCodeSearchValue);
		}
	}
	
	// 카테고리 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['mc_codesearch'])) {
		foreach ($_GET['mc_codesearch'] as $mcCodeSearchValue) {
			$getStr = getStr($getStr,'mc_codesearch[]',$mcCodeSearchValue);
		}
	}
	
	// 부서 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['md_codesearch'])) {
		foreach ($_GET['md_codesearch'] as $mdCodeSearchValue) {
			$getStr = getStr($getStr,'md_codesearch[]',$mdCodeSearchValue);
		}
	}


	if ($xUidx) {
		$proc = 'MODIFY';
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('m_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_MANUAL . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
	
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$mIndex			= $dataView['m_index'];
		//$mdCode			= $dataView['md_code'];
		$mDivisionCode	= $dataView['m_division_code'];
		$mCategoryCode	= $dataView['m_category_code'];
		$mSolutionCode	= $dataView['m_solution_code'];
		$mPatchFl		= $dataView['m_patchFl'];
		$mFileUpload	= $dataView['m_fileupload'];
		$mName			= $dataView['m_name'];
		$mIp			= $dataView['m_ip'];
		$mWriteDate		= $dataView['m_writedate'];
		$mContents		= $dataView['m_contents'];
		$mSubject		= $dataView['m_subject'];
		$mDeleteFl		= $dataView['m_deletefl'];


	}
	else {
		$proc	= 'WRITE';
		$info	= "등록";
		$mppType				= 'a';
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
	var form = document.frmMain;
	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("21","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("22","<?=$getStr?>");
}

//-->
</SCRIPT>
		<style type="text/css">
			#check tr td {
				padding-top: 4px;
			}
			#check tr td input{
				margin-left:4px;
			}
		</style>
		<script type="text/javascript" src="/_js/easyEditor.js"></script>
		<form name="frmMain" method="post" 	action="./_proc.php" enctype='multipart/form-data'>
		<?=getStrFromReqValHid($getStr)?>
		<input type="hidden" name="m_index" value="<?=$mIndex?>">
		<input type="hidden" name="proc" value="<?=$proc?>">
		<input type="hidden" name="m_deleteFl" value="<?=$m_DeleteFl?>" >
		<input type="hidden" name="mr_index" value="<?=$mrIndex?>">
		<input type="hidden" name="mpp_index" value="<?=$mppIndex?>">
		<h1 class="frame-title"><?=$menuSubject?> <span>메뉴얼을 <?=$info?> 할 수 있습니다.</span></h1>
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
										<td scope="row"><input type="text" name="m_subject"  style="width:200px;"   value="<?=$mSubject?>" IsValidStr="STR"  NNull="true" LabelStr="제목" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">메뉴얼 작성자</th>
										<td scope="row"><input type="text" name="m_name"  style="width:100px;"   value="<?=($xUidx) ? $mName : $_SESSION['sess']['mmName']?>" IsValidStr="STR"  NNull="true" LabelStr="작성자" /></td>
									</tr>
								
									<tr>
										<th scope="row">솔루션 정보</th>
										<td scope="row"><p><?php $solutionCode->setCodeCheckBox();?></p></td>
									</tr>
									<tr>
										<th scope="row">메뉴얼 체크 항목</th>
										<td scope="row">
											<table id="check">
											<tr id="first">
												<td width=150 >카테고리 </td>
												<td><?php $categoryCode->setAuthCodeCheckBox($mCategoryCode);?></td>
											</tr>
											<tr>
												<td>부서 </td>
												<td align=left><?php $divisionCode->setAuthCodeCheckBox($mDivisionCode);?></td>
											</tr>
											</table>
										</td>
									</tr>
									<!-- <tr class="bgc">
										<th scope="row">패치유무</th>
										<td scope="row">
											<table class="basic">
											<tr class="bgc">
												<td width=300><input type="checkbox" name="m_patchFl" value="y" <?=($mPatchFl == 'y') ? 'checked' : '' ?>> 패치가 있을시 체크 </td>
											</tr>
											</table>
										</td>
									</tr> -->
									<tr class="bgc">
										<th scope="row">첨부 파일</th>
										<td scope="row"><input type="file" name="m_fileupload" /><?php if ($mFileUpload) {?><a href="/_inc/down.php?xUidx=<?=$xUidx?>" ><?=urldecode($mFileUpload)?></a><?php } ?></td>
									</tr>
									<tr>
										<th scope="row">메뉴얼 내용</th>
										<td scope="row"><textarea name="m_contents" id="m_contents" rows="10" cols="100" ><?=$mContents?></textarea></td>
										<script type="text/javascript">
											var ed1 = new easyEditor("m_contents");
											ed1.init();
										</script>
									</tr>
									<tr class="bgc">
										<th scope="row">삭제유무</th>
										<td scope="row"><?=( $m_deleteFl != 'n' ) ? "보임" : '삭제'?> (삭제시 상단에 삭제버튼을 눌러주세요)</td>
									</tr>
									<tr class="bgc">
										<th scope="row">로그인 ip</th>
										<td scope="row"><?=$mIp?></td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="bgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$mWriteDate?></td>
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
	
<SCRIPT LANGUAGE="JavaScript">
<!--
function _mppinsert(m_index)
{
	LoadLinkStr("26","m_index="+m_index+"&<?=$getStr?>");
}
function _mppdelete()
{
	var form = document.frmmpp;
	form.proc.value = "DELETE";
	var form = document.frmmpp;
	doForm(form);

}
function LoadPageLink(idx, m_index)
{	
	LoadLinkStr("26","xUidx="+idx+ "&m_index="+m_index+"&<?=$getStr?>")
}

//-->
</SCRIPT>
<style type="text/css">
				table.r-list tr td {
					padding: 5px;
				}
				table.r-list tr.write_info th {
					border-top:1px solid #bebebe;
					border-bottom:1px solid #cacaca;
					background-color:#f5f5f5;
					vertical-align:middle;
				}
				table.r-list tr.write_info td {
					border-top:1px solid #bebebe;
					border-bottom:1px solid #cacaca;
					background-color:#f5f5f5;
					vertical-align:middle;
				}

</style>
				<?php
				if ($xUidx) {
				?>
				<!-- manual reply 답글 시작-->
				<?php
				
				//$dataString->addItem('mpp_deleteFl', 'n', 'C');
				$selectWhere = $dataString->getWhere();
				$mppSelectQuery = "Select * From " . GD_MANUAL_PROCESS_PATCH . $selectWhere ." and mpp_deleteFl = 'n' order by mpp_index DESC ";
				$mppResult = $db->query($mppSelectQuery);
				?>

				<form name="frmmpp" action="./mpp_write.php" method="GET">
				<h1 class="frame-title" style="margin-top:10px;">답글 <span>메뉴얼 답글을 관리합니다. </span></h1>
				<div style="width:100%;height:100%;border:solid 1px;margin-top:20px;">
						<input type="hidden" name="m_index" value="<?=$mIndex?>">

						<table class="r-list" summary="메뉴얼 답글 관리 table">	
						<caption>메뉴얼 관리</caption>
						<thead>
						<tr class="write_info">
							<th scope="row"><span>답변 번호</span></th>
							<th scope="row"><span>답변 제목</span></th>
							<th scope="row"><span>답변 작성자</span></th>
							<th scope="row"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
							<?php
								while ($mppRow = $db->fetch($mppResult)) {
							?>

						<tr height="25">
							<td align="center"><?=$pg->idx++;?></td>
							<td>
								<DIV class="ell"  ><NOBR><a href="javascript:LoadPageLink('<?=$mppRow['mpp_index']?>', '<?=$mIndex?>')" hidefocus><?=$mppRow['mpp_subject']?></a></NOBR></DIV>
							</td>
							<td>
								<DIV class="ell"  ><NOBR><a href="javascript:LoadPageLink('<?=$mppRow['mpp_index']?>', '<?=$mIndex?>')" hidefocus><?=$mppRow['mpp_name']?></a></NOBR></DIV>
							</td>
							<td ><?=$mppRow['mpp_writedate']?></td>
						</tr>
						<? } 
					
						?>
						
						</tbody>
						</table>
						<div>
							<input type="button" onclick="javascript:_mppinsert('<?=$mIndex?>');" name="reply" value="답글달기">
						</div>
						</div>
						<!-- paging -->
						<div class="pageArea">
						<div class="hiddenObj">페이지 리스트</div>
							<div class="pageList">
								<?=$pg->page['navi']?>
							</div>
					</div>
						<iframe name="mpp_proc" id="mpp_proc" frameborder="0" style="width:0;height:0;display:none;" src=""></iframe>
				</form>
				
				<!-- 답글 끝-->
				<?}
				?>

	<!-- reply start -->
	<SCRIPT LANGUAGE="JavaScript">
<!--
function _replydelete(reIdx) {
	form = document.frmReply;
	form.mr_index.value = reIdx;
	form.submit();
}
//-->
</SCRIPT>
			<style type="text/css">
				table.reply tr td {
					padding: 5px;
				}
				table.reply tr.write_info td {
					border-top:1px solid #bebebe;
					border-bottom:1px solid #cacaca;
					background-color:#f5f5f5;
				}
				table.reply tr.write_info td {
					border-top:1px solid #bebebe;
					border-bottom:1px solid #cacaca;
					background-color:#f5f5f5;
				}

			</style>
			<?php
				// 댓글 데이터 추출
				if($xUidx){
				$dataString->addItem('mr_deleteFl', 'n', 'C');
				
				$selectWhere = $dataString->getWhere();
				$replySelectQuery = "Select * From " . GD_MANUAL_REPLY . $selectWhere;
				
				$replyResult = $db->query($replySelectQuery);
			?>

			<form name="frmReply" action="./reply_proc.php" method="POST" target="reply_ifr_proc">
			<input type="hidden" name="mr_index" />
			<input type="hidden" name="m_index" value="<?=$xUidx?>"/>
			<div class="viewArea" >
				<h1 class="frame-title">댓글 <span>메뉴얼 댓글을 관리합니다. </span></h1>
				<div style="margin-top:10px;">
					<table cellspacing="0" cellpadding="0" class="reply" summary="메뉴얼 댓글 관리 table">
						<colgroup>
							<col width="100px" />
							<col width="auto" />
							<col width="50px" />
						</colgroup>
						<tbody>
							<?php
								while ($dataReplyRow = $db->fetch($replyResult)) {
							?>
							

							<tr class="write_info">
								<td style="text-align:left;" >
									<?=$dataReplyRow['mr_name']?>
								</td>
								<td style="text-align:right;">
									<?=date('Y-m-d', strtotime($dataReplyRow['mr_writedate']))?>
								</td>
								<td style="text-align:center;">
									<span onclick="javascript:_replydelete('<?=$dataReplyRow['mr_index']?>')" style="cursor:pointer;">X</span></span>
								</td>
							</tr>
							<tr class="reContent">
								<td colspan="3"><?=$dataReplyRow['mr_contents']?></td>
							</tr>
							<?php
								}
							?>
							<tr>
								<th>이름</th>
								<th colspan="2">메모</th>
							</tr>
							<tr>
								<td><?=$_SESSION['sess']['mmName']?><input type="hidden" name="mr_name" value="<?=$_SESSION['sess']['mmName']?>" /></td>
								<td><textarea name="mr_contents" style="width:100%;" rows="5"></textarea></td>
								<td><input type="button" value="댓글 작성" style="height:50px;" onclick="this.form.mr_index.value='';this.form.submit();" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<iframe name="reply_ifr_proc" id="reply_ifr_proc" frameborder="0" style="width:0;height:0;display:none;" src=""></iframe>
			</form>
			<?php
				}
			?>
			<!-- reply end -->

			
 
<?
	include ('../../_admin/_include/sysbottom.php');
?>