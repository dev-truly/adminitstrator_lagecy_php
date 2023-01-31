<?php	
	include ('../../_admin/_include/systop.php');
	
	//-------------------------------------------
	//- Advice - 전달 받는 파라미터
	//-------------------------------------------
	$xUidx			= trimGetRequest('xUidx');				//------------ 쇼핑몰 번호
	$returnPage		= trimGetRequest('returnPage');			//------------ 리턴 페이지
	$rrlIndex		= trimGetRequest('rrlIndex');

	//-------------------------------------------
	//- Advice - get데이터로 넘길값 생성
	//-------------------------------------------
	$getStr ='';
	$getStr2 ='';
	$getStr3 ='';
	if ($returnPage == '_schedule_list') {
		$year	= trimGetRequest('year');					//------------ 스케쥴 페이지 연도
		$month	= trimGetRequest('month');					//------------ 스케쥴 페이지 월

		$getStr2 = getStr($getStr2,'year',$year);
		$getStr2 = getStr($getStr2,'month',$month);
	}
	else {
		$listCnt		= trimGetRequest('listCnt');			//------------ 출력되는 리스트 수
		$page			= trimGetRequest('page');				//------------ 현재 페이지
		$searchState	= trimGetRequest('searchState');		//------------ 이전 상태 검색
		$selectType		= trimGetRequest('selectType');			//------------ 검색 조건
		$selectValue	= trimGetRequest('selectValue');		//------------ 검색값
		$sort			= trimGetRequest('sort');				//------------ 현재 페이지
		
		$arraySearchOld		= trimGetRequest('searchOld');		//------------ 이전 전 솔루션
		$arraySearchGodo	= trimGetRequest('searchGodo');	//------------ 이전 후 고도 솔루션
		
		$getStr2 = getStr($getStr2,'listCnt',$listCnt);
		$getStr2 = getStr($getStr2,'page',$page);
		$getStr2 = getStr($getStr2,'searchState',$searchState);
		$getStr2 = getStr($getStr2,'selectType',$selectType);
		$getStr2 = getStr($getStr2,'selectValue',$selectValue);
		$getStr2 = getArrayStr($getStr2, 'searchOld[]', $arraySearchOld);
		$getStr2 = getArrayStr($getStr2,'searchGodo[]',$arraySearchGodo);
		$getStr2 = getStr($getStr2,'sort',$sort);
	}

	$getStr = getStr($getStr2,'returnPage',$returnPage);
	

	if ($xUidx) {
		$proc = 'MODIFY';
		$info = "수정";
		//-------------------------------------------
		//- Advice - 등록 쇼핑몰 데이터 검색
		//-------------------------------------------
			$query = "select rm.*, pc.pc_index from " . GD_RELOCATION_MALL . " rm left join (Select rm_index, pc_index From " . GD_PROMOTION_COUPON . " where pc_delete_flag = 'n') pc on pc.rm_index = rm.rm_index Where rm.rm_index = " . $xUidx;

			$dataView = $db->fetch($query, 1);
			$mallStr = '';	// 메일 발송페이지 전송 데이터

			$mallStr = getStr($getStr2, 'xUidx',$xUidx);
			$mallStr = getStr($mallStr, 'reception',$dataView['rm_admin_email']);
			$mallStr = getStr($mallStr, 'shopurl', $dataView['rm_before_site_url']);
			$mallStr = getStr($mallStr,'returnPage',$returnPage);

			/*	----------------------------------------
			*	담당자 검색
			*	----------------------------------------
			*/
			$arrayUserData = array();
			$selectQuery = "Select ru_index, ru_name From " . GD_RELOCATION_USER . " RU join " . GD_AUTH . " A on RU.a_code = A.a_code Where A.a_menu_code like '%|work|%' order by ru_index" ;
			$userResult = $db->query($selectQuery);
			while ($userRow = $db->fetch($userResult, 1)) {
				$arrayUserData[$userRow['ru_index']] =  $userRow['ru_name'];
			}
	}
	else {
		$proc = 'WRITE';
		$info = "등록";
	}

	$code = class_load('code');
	$oldSolution = class_load('solution');
	$godoSolution = class_load('solution');	

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

function _reponseLogSave()
{
	var form = document.frmResponse;
	doForm(form);
}
function _dataLogAllsave() {
	var form = document.frmAllDataType;
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
	if ('<?=$returnPage?>' == '_schedule_list') {
		LoadLinkStr("21","<?=$getStr2?>");
	}
	else {
		LoadLinkStr("22","<?=$getStr?>");
	}
}

function _Finsert()
{
	LoadLinkStr("23","<?=$getStr?>");
}



</SCRIPT>			
		<style type="text/css">
			.infoLine th, .infoLine td{ border-top:solid 1px #b6b6b6; }
		</style>
		<link type="text/css" href="../../_calendar/themes/base/ui.all.css" rel="stylesheet" />
		<script type="text/javascript" src="../../_calendar/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="../../_calendar/ui/ui.core.js"></script>
		<script type="text/javascript" src="../../_calendar/ui/ui.datepicker.js"></script>
		<script type="text/javascript" src="../../_calendar/ui/ui.datepicker-ko.js"></script>
					<form name="frmMain" method="post" action="./_proc.php">
						<?php include ('../../_admin/_include/view_title.php');?>
											<caption>고객 정보 관리 요약</caption>
											<colgroup>
												<col width="15%" />
												<col width="35%" />
												<col width="15%" />
												<col width="35%" />
											</colgroup>
											<thead>
											<tr class="bg">
												<th scope="row" colspan="4" style="text-align:center;font-weight:bold;"> 고객 정보 관리</th>
											</tr>
											<?php 
												if ($xUidx) {
											?>
											<tr class="bgc">
												<th>프로모션</th>
												<td colspan="3">
												<?php
													if ($dataView['pc_index']) {
												?>
													<a href="/promotion/_coupon_view.php?xUidx=<?=$dataView['pc_index']?>"><div style="color:blue;">프로모션페이지 이동</div></a>
												<?php
													}
													else {
														$arrayPromotion = getPromotinInfo();
												?>
													<div>
														<select name="p_index_select" id="p_index_select">
															<option value="" selected>프로모션 선택</option>
															<?php
																foreach ($arrayPromotion as $promotionIndex => $promotionName) {
															?>
																<option value="<?=$promotionIndex?>" ><?=$promotionName?></option>
															<?php
																}
															?>
														</select>
														<span>
															<input type="button" name="promotionInsert" id="promotionInsert" onclick="javascript:promotion('<?=$getStr2?>');" value="프로모션등록" />
														</span>
													</div>
												<?php
													}
												?>
												</td>
											</tr>
											<?php
												}
											?>
											<tr class="nobgc">
											<th>쇼핑몰명</th>
												<td><input type="text" size="26" name="rm_name" id="rm_name" value="<?=$dataView['rm_name']?>" IsValidStr="STR" MinL="1" MaxL="50" NNull="true" LabelStr="쇼핑몰명"></td>
												<th>진행 상태</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_state'], 'state'); 
														$code->setCodeSelectBox('rm_state');
													?>
													<div id="rm_state_cause_area" style="<?=($dataView['rm_state'] != 'ing') ? 'display:none;' : ''?>position:absolute;">진행중 이유 : <input type="text" name="rm_state_cause" value="<?=$dataView['rm_state_cause']?>" /></div>
												</td>
											</tr>
											<tr class="bgc">
												<th class="first">아이디</th>
												<td><input type="text" size="26" name="rm_godo_id" id="rm_godo_id" value="<?=$dataView['rm_godo_id']?>" IsValidStr="ALPHANUM" MinL="3" MaxL="50"  NNull="false" LabelStr="아이디"></td>
												<th>쇼핑몰 관리자</th>
												<td><input size="26" type="text" name="rm_admin_name" id="rm_admin_name" value="<?=$dataView['rm_admin_name']?>" IsValidStr="STR" MinL="2" MaxL="10"  NNull="true" LabelStr="쇼핑몰관리자"></td>
											</tr>
											<tr class="nobgc">
												<th>주사용 연락처</th><td><input type="text" size="26" name="rm_default_tel" id="rm_default_tel" value="<?=$dataView['rm_default_tel']?>" IsValidStr="TEL" NNull="true" MaxL="13" LabelStr="주사용 연락처"></td>
												<th>보조 연락처</th><td><input type="text" size="26" name="rm_sub_tel" id="rm_sub_tel" value="<?=$dataView['rm_sub_tel']?>" IsValidStr="TEL" NNull="false" MaxL="13" LabelStr="보조 연락처"></td>
											</tr>
											<tr class="bgc">
												<th>이메일</th><td><input type="text" size="26" name="rm_admin_email" id="rm_admin_email" value="<?=$dataView['rm_admin_email']?>" IsValidStr="EMAIL" MinL="7" MaxL="100"  NNull="true" LabelStr="이메일"><?php if ($dataView['rm_admin_email']) {?><a href="./answer_mail_write.php?<?=$mallStr?>">[답변메일 발송페이지]</a><?php }?></td>
												<th>고도 솔루션 구매일</th><td><input type="text" name="rm_mall_buy_date" value="<?=$dataView['rm_mall_buy_date']?>" readonly IsValidStr="STR" NNull="false" MinL="10" MaxL="10" LabelStr="고도 솔루션 구매일"/></td>
											</tr>
											<tr class="nobgc">
												<th>주요 취급 품목</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_main_item'], 'mainItem');
														$code->setCodeSelectBox('rm_main_item');
													?>
												</td>
												<th>기존 이용 PG</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_before_PG'], 'PG');
														$code->setCodeSelectBox('rm_before_PG');
													?>
												</td>
											</tr>
											<tr class="bgc">
												<th>PG 신규 신청 여부</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_pg_move_type'], 'PGMOVETYPE');
														$code->setCodeSelectBox('rm_pg_move_type');
													?>
												</td>
												<th>변경 이용 PG</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_after_PG'], 'PG');
														$code->setCodeSelectBox('rm_after_PG');
													?>
												</td>
											</tr>
											<tr class="nobgc">
												<th>이전 사유</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_move_reason'], 'moveReason');
														$code->setCodeSelectBox('rm_move_reason');
													?>
												</td>
												<th>신청사유</th>
												<td>
													<?php 
														$code->setCodeTableType($dataView['rm_apply_reason'], 'applyReaso');
														$code->setCodeSelectBox('rm_apply_reason');
													?>
												</td>
											</tr>
											<tr class="bgc">
												<th colspan="2" style="text-align:center;">이전 및 신청 사유 상세 내용</th>
												<th colspan="2" style="text-align:center;">요청 및 전달사항</th>
											</tr>
											<tr class="nobgc">
												<td colspan="2"><textarea name="rm_relocation_reason" rows="10" style="width:100%;border:solid 1px #b6b6b6;"><?=$dataView['rm_relocation_reason']?></textarea></td>
												<td colspan="2"><textarea name="rm_request_info"  style="width:100%;border:solid 1px #b6b6b6;" rows="10"><?=$dataView['rm_request_info']?></textarea></td>
											</tr>
											</thead>
										
										</table>
										<table class="basic">
											<caption>쇼핑몰 정보 관리 요약</caption>
												<colgroup>
													<col width="15%" />
													<col width="10%" />
													<col width="35%" />
													<col width="10%" />
													<col width="30%" />
												</colgroup>
												<thead>
													<tr class="bg">
														<th scope="row" colspan="5" style="text-align:center;font-weight:bold;"> 쇼핑몰 정보 관리</th>
													</tr>
													<tr class="nobgc">
														<th>&nbsp;</th>
														<th colspan="2" style="text-align:center;font-weight:bold;">이전 솔루션</th>
														<th colspan="2" style="text-align:center;font-weight:bold;">고도 솔루션</th>
													</tr>
													<tr class="bgc infoLine">
														<th rowspan="2" style="text-align:center;">솔루션 정보</th>
														<th>
															솔루션 선택
														</th>
														<td><?php 
																$oldSolution->setCodeSelectBox($dataView['rm_before_solution_code'], 'RB', 'rm_before_solution_code'); 
															?>
															<span id="etc" style="display:<?=($dataView['rm_before_solution_code'] == 'ETC')? 'inline' : 'none'?>;"><input type="text" name="rm_before_etc_solution_name" value="<?=$dataView['rm_before_etc_solution_name']?>" /></span>
														</td>
														<th>
															솔루션 선택
														</th>
														<td>
															<?php 
																$oldSolution->setCodeSelectBox($dataView['rm_godo_solution_code'], 'RA', 'rm_godo_solution_code'); 
															?>
															</span>
														</td>
													</tr>
													<tr class="nobgc">
														<th>
															도메인
														</th>
														<td>
															<input type="text" size="26" name="rm_before_site_url" id="rm_before_site_url" value="<?=$dataView['rm_before_site_url']?>" IsValidStr="STR" MinL="5" MaxL="100"  NNull="true" LabelStr="대표도메인">
															<?php
																$httpUrl = '';
																if (!ereg('http://', $dataView['rm_before_site_url'])) $httpUrl = 'http://';
															?>
															<?=($dataView['rm_before_site_url']) ? '<a href="' . $httpUrl . $dataView['rm_before_site_url'] . '" target="_blank"><b style="color:#aaaaff;"> ▶ Site </b></a>' : ''?>
														</td>
														<th>
															도메인
														</th>
														<td>
															<input type="text" size="26" name="rm_godo_domain" id="rm_godo_domain" value="<?=$dataView['rm_godo_domain']?>"><?=($dataView['rm_godo_domain']) ? '<a href="https://gadmin.godo.co.kr/echost_proc/total_solution_search.php?searchYn=y&key%5B0%5D=domain&keyword=' . $dataView['rm_godo_domain'] . '" target="_blank"><b style="color:#aaaaff;"> ▶ Info </b></a>' : ''?>
														</td>
													</tr>
													<tr class="bgc infoLine">
														<th rowspan="3" style="text-align:center;">FTP 정보</th>
														<th>HOST / PORT</th>
														<td><input type="text" name="rm_before_ftp_ip" id="rm_before_ftp_ip" value="<?=$dataView['rm_before_ftp_ip']?>" /> / <input type="text" name="rm_before_ftp_port" id="rm_before_ftp_port" style="width:30px;" value="<?=($dataView['rm_before_ftp_port']) ? $dataView['rm_before_ftp_port'] : '21'?>" />
														</td>
														<th>HOST</th>
														<td>
															<input type="text" name="rm_godo_ip" id="rm_godo_ip" value="<?=$dataView['rm_godo_ip']?>" />
														</td>
													</tr>
													<tr class="nobgc">
														<th>ID</th>
														<td>
															<input type="text" name="rm_before_db_id" id="rm_before_db_id" value="<?=$dataView['rm_before_ftp_id']?>" />
														</td>
														<th>ID</th>
														<td>
															<input type="text" name="rm_godo_ftp_id" id="rm_godo_ftp_id" value="<?=$dataView['rm_godo_ftp_id']?>" />
														</td>
													</tr>
													<tr class="bgc">
														<th>PWD</th>
														<td>
															<input type="text" name="rm_before_ftp_pwd" id="rm_before_ftp_pwd" value="<?=$dataView['rm_before_ftp_pwd']?>" />
														</td>
														<th>PWD</th>
														<td>
															<input type="text" name="rm_godo_ftp_pwd" id="rm_godo_ftp_pwd" value="<?=$dataView['rm_godo_ftp_pwd']?>" />
														</td>
													</tr>
													<tr class="nobgc infoLine">
														<th rowspan="4" style="text-align:center;">DB 정보</th>
														<th>HOST / PORT</th>
														<td>
															<input type="text" name="rm_before_db_host" id="rm_before_db_host" value="<?=($dataView['rm_before_db_host']) ? $dataView['rm_before_db_host'] : 'localhost'?>" /> / <input type="text" name="rm_before_db_port" id="rm_before_db_port" style="width:30px;" value="<?=($dataView['rm_before_db_port']) ? $dataView['rm_before_db_port'] : '3306' ?>" />
														</td>
														<th>HOST</th>
														<td>
															<input type="text" name="rm_godo_db_host" id="rm_godo_db_host" value="<?=($dataView['rm_godo_db_host']) ? $dataView['rm_godo_db_host'] : 'localhost' ?>" />
														</td>
													</tr>
													<tr class="bgc">
														<th>NAME</th>
														<td>
															<input type="text" name="rm_before_db_name" id="rm_before_db_name" value="<?=$dataView['rm_before_db_name']?>" />
														</td>
														<th>NAME</th>
														<td>
															<input type="text" name="rm_godo_db_name" id="rm_godo_db_name" value="<?=$dataView['rm_godo_db_name']?>" />
														</td>
													</tr>
													<tr class="nobgc">
														<th>ID</th>
														<td>
															<input type="text" name="rm_before_db_id" id="rm_before_db_id" value="<?=$dataView['rm_before_db_id']?>" />
														</td>
														<th>ID</th>
														<td>
															<input type="text" name="rm_godo_db_id" id="rm_godo_db_id" value="<?=$dataView['rm_godo_db_id']?>" />
														</td>
													</tr>
													<tr class="bgc">
														<th>PWD</th>
														<td><input type="text" name="rm_before_db_pwd" id="rm_before_db_pwd" value="<?=$dataView['rm_before_db_pwd']?>" /></td>
														<th>PWD</th>
														<td><input type="text" name="rm_godo_db_pwd" id="rm_godo_db_pwd" value="<?=$dataView['rm_godo_db_pwd']?>" /></td>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<!-- //bbsView -->
								</div>
								</form>
								<!-- view area -->
								<div class="viewArea" >
									<!-- bbsView -->
									<div class="bbsView">
										<div class="tableView">
											<?php
												if ($xUidx) {
													//-------------------------------------------
													//- Advice - 등록 쇼핑몰 응대 내역 검색
													//-------------------------------------------
													$query = "select * from " . GD_RELOCATION_RESPONSE_LOG . " Where rm_index = " . $xUidx . " and rrl_deleteFl = 'n' order by rrl_index DESC";
													$responseLogResult = $db->query($query);

													$clTypeCode = class_load('code');
													$clTypeCode->setCodeTableType('', 'clType');
													$cpTypeCode = class_load('code');
													$cpTypeCode->setCodeTableType('', 'cpType');
													$dataTypeCode = class_load('code');
													$dataRangeCode  = class_load('code');
													$rdTypeCode  = class_load('code');


													//-------------------------------------------
													//- Advice - 등록 쇼핑몰 이전 내역 검색
													//-------------------------------------------
													$query = "select * from " . GD_RELOCATION_DATA_LOG . " Where rm_index = " . $xUidx . "  and rdl_deleteFl = 'n' ";
													$dataLogResult = $db->query($query);
											?>
											<form method="POST" name="frmResponseDel" action="./_proc.php" />
												<input type="hidden" name="mode" value="responseDel" />
												<input type="hidden" name="rrl_index" />
											</form>
											<table class="basic">
											<caption>응대 내역 요약</caption>
												<colgroup>
													<col width="25%" />
													<col width="25%" />
													<col width="25%" />
													<col width="25%" />
												</colgroup>
												<thead>
												<tr class="bg">
													<th colspan="4" style="text-align:center;font-weight:bold;">응대내역</th>
												</tr>
												<tr class="nobgc infoLine">
													<th style="text-align:center;font-weight:bold;">처리내용</th>
													<th style="text-align:center;font-weight:bold;">답변 방법</th>
													<th style="text-align:center;font-weight:bold;">안내 담당자</th>
													<th style="text-align:center;font-weight:bold;">안내 일자</th>
												</tr>
												<?php
													$trClassType = 'nobgc';
													while ($responseLogRow = $db->fetch($responseLogResult, 1)) {
														$trClassType = ($trClassType == 'nobgc') ? 'bgc' : 'nobgc';
												?>
												<tr class="responseLogList <?=$trClassType?>" rrlIndex="<?=$responseLogRow['rrl_index']?>">
													<td class="rrlType" style="text-align:center;" rrlType="<?=$responseLogRow['rrl_type']?>">
														<?=$clTypeCode->arrayCodeTypeRow[$responseLogRow['rrl_type']]?>
													</td>
													<td class="rrlResponseType" rrlResponseType="<?=$responseLogRow['rrl_response_type']?>" style="text-align:center;" ><span class="responseLogSel">
														<?=$cpTypeCode->arrayCodeTypeRow[$responseLogRow['rrl_response_type']]?>
													</span></td>
													<td class="grmIndex" style="text-align:center;"  grmIndex="<?=$responseLogRow['grm_index']?>"><?=($arrayUserData[$responseLogRow['grm_index']]) ? $arrayUserData[$responseLogRow['grm_index']] : '정보 없음'?></td>
													<td class="rrlResponseDate" style="text-align:center;" ><?=$responseLogRow['rrl_response_date'];?></td>
												</tr>
												<tr class="responseLog <?=($trClassType == 'nobgc') ? 'bgc' : 'nobgc'?>" <?=($rrlIndex == $responseLogRow['rrl_index']) ? '' : 'style="display:none;"'?>>
													<td colspan="4">
														<div>
															<h3>문의 내용</h3>
															<div class="rrlInquiryContents" style="height:300px;overflow-y:scroll; border:solid 1px #b6b6b6;padding:5px 0 0 5px;"><?=($responseLogRow['rrl_inquiry_contents']) ? str_replace("\r\n","<br />",$responseLogRow['rrl_inquiry_contents']) : '정보없음'?></div>
															<h3>답변 내용</h3>
															<div class="rrlContents" style="height:300px;overflow-y:scroll; border:solid 1px #b6b6b6;padding:5px 0 0 5px;"><?=($responseLogRow['rrl_contents']) ? str_replace("\r\n","<br />",$responseLogRow['rrl_contents']) : '정보없음'?></div>
														</div>
														<div>
															<input type="button" class="responseLogModify" name="responseLogModify" value="수정" />
															<input type="button" class="responseLogDel" name="responseLogDel" value="삭제" />
														</div>
													</td>
												</tr>
												<?php
													}
													if (!$db->count_($responseLogResult)){
												?>
												<tr class="responseLog bgc">
													<td colspan="4" style="text-align:center;font-weight:bold;">
														응대 내역이 존재 하지 않습니다.
													</td>
												</tr>
												<?php
													}
												?>
												</thead>
											</table>
											<form method="POST" name="frmResponse" action="./_proc.php">
											<input type="hidden" name="mode" value="responseSave" />
											<input type="hidden" name="xUidx" value="<?=$xUidx?>" />
											<input type="hidden" name="rrl_index" value="" />
											<?=getStrFromReqValHid($getStr)?>
											<table class="basic">
												<caption>응대 내역 작성 요약</caption>
												<colgroup>
													<col width="25%" />
													<col width="25%" />
													<col width="25%" />
													<col width="25%" />
												</colgroup>
												<thead>
												<tr class="bg">
													<th colspan="4" style="text-align:center;font-weight:bold;">응대 내역 작성</th>
												</tr>
												<tr class="nobgc infoLine">
													<td style="text-align:center;"><?php $clTypeCode->setCodeSelectBox('rrl_type');?></td>
													<td style="text-align:center;"><?php $cpTypeCode->setCodeSelectBox('rrl_response_type');?></td>
													<td style="text-align:center;"><select name="grm_index">
															<?php
																foreach ($arrayUserData as $memberKey => $memberName) {
															?>
																<option value="<?=$memberKey?>" <?=($_SESSION['sess']['grm_idx'] == $memberKey) ? 'selected' : '' ?>><?=$memberName?></option>
															<?php
																}
															?>
														</select>
													</td>
													<td style="text-align:center;"><input type="text" name="rrl_response_date" class="date_type" id="rrl_response_date" value="<?=date('Y-m-d')?>" /></td>
												</tr>
												<tr class="bgc">
													<td style="text-align:center;font-weight:bold;">문의 내용</td>
													<td colspan="3"><textarea rows="10" name="rrl_inquiry_contents" id="rrl_inquiry_contents" style="width:100%;"></textarea></td>
												</tr>
												<tr class="nobgc">
													<td style="text-align:center;font-weight:bold;">답변 내용</td>
													<td colspan="3"><textarea rows="10" name="rrl_contents" id="rrl_contents" style="width:100%;"></textarea></td>
												</tr>
												<tr class="bgc">
													<td colspan="4" style="text-align:center;"><input type="button" id="responseSave" name="responseSave" value="저장" onclick = "_reponseLogSave()" /></td>
												</tr>
												</thead>
											</table>
											</form>
											<form name="frmDataType" method="POST" action="./_proc.php">
												<input type="hidden" name="mode" value="" />
												<input type="hidden" name="xUidx" value="<?=$xUidx?>" />
												<input type="hidden" name="rdl_index" value="" />
												<input type="hidden" name="rdl_data_type" value="" />
												<input type="hidden" name="rdl_data_range" value="" />
												<input type="hidden" name="rdl_data_cnt" value="" />
												<input type="hidden" name="rdl_complete_date" value="" />
												<input type="hidden" name="rdl_proc_type" value="" />
												<input type="hidden" name="grm_index" value="" />
											</form>
											<form name="frmAllDataType" method="POST" action="./_proc.php">
											<input type="hidden" name="xUidx" value="<?=$xUidx?>" />
											<input type="hidden" name="mode" value="allDataTypeSave" />
											<div>
											<table class="basic" id="dataLog">
												<caption>이전 내역 요약</caption>
												<colgroup>
													<col width="15%" />
													<col width="15%" />
													<col width="15%" />
													<col width="15%" />
													<col width="15%" />
													<col width="15%" />
													<col width="auto" />
												</colgroup>
												<thead>
													<tr class="bg">
														<th colspan="7" style="text-align:center;font-weight:bold;">이전 내역</th>
													</tr>
													<tr class="nobgc">
														<td style="text-align:center;font-weight:bold;">이전 이력 처리</td>
														<td colspan="6">
															<input type="button" name="addDataType" id="addDataType" value="신규내역 추가" /> 
															<input type="button" name="dataLogAllsave" id="dataLogAllsave" onclick="_dataLogAllsave();" value="내역 일괄 저장" />
														</td>
													</tr>
													<tr class="bgc infoLine">
														<th style="text-align:center;font-weight:bold;">데이터 종류</th>
														<th style="text-align:center;font-weight:bold;">데이터 범위</th>
														<th style="text-align:center;font-weight:bold;">이전 데이터 수</th>
														<th style="text-align:center;font-weight:bold;">처리 일자</th>
														<th style="text-align:center;font-weight:bold;">처리 구분</th>
														<th style="text-align:center;font-weight:bold;">작업 담당자</th>
														<th style="text-align:center;font-weight:bold;">저장 / 삭제</th>
													</tr>
													<?php
														$dataLogRowIdNum = 1;
														while ($dataLogRow = $db->fetch($dataLogResult, 1)) {
													?>
													<tr class="<?=($dataLogRowIdNum % 2 == '0') ? 'bgc' : 'nobgc'?>">
														<td style="text-align:center;"><input type="hidden" name="rdl_index[]" value="<?=$dataLogRow['rdl_index']?>" />
															<?php
																$dataTypeCode->setCodeTableType($dataLogRow['rdl_data_type'], 'dataType');
																$dataTypeCode->setCodeSelectBox('rdl_data_type[]');
																unset($dataTypeCode->arrayCodeData);
															?>
														</td>
														<td style="text-align:center;">
															<?php 
																$dataRangeCode->setCodeTableType($dataLogRow['rdl_data_range'], 'dataRange');
																$dataRangeCode->setCodeSelectBox('rdl_data_range[]');
																unset($dataRangeCode->arrayCodeData);
															?>
														</td>
														<td style="text-align:center;"><input type="text" name="rdl_data_cnt[]" id="rdl_data_cnt" value="<?=$dataLogRow['rdl_data_cnt']?>" style="width:50px;" /></td>
														<td style="text-align:center;"><input type="text" name="rdl_complete_date[]" id="rdl_complete_date<?=$dataLogRowIdNum?>" class="date_type" value="<?=$dataLogRow['rdl_complete_date']?>" /></td>
														<td style="text-align:center;">
															<?php 
																$rdTypeCode->setCodeTableType($dataLogRow['rdl_proc_type'], 'rdType');		
																$rdTypeCode->setCodeSelectBox('rdl_proc_type[]');
																unset($rdTypeCode->arrayCodeData);
															?>
														</td>
														<td style="text-align:center;">
															<select name="grm_index[]">
																<?php
																	foreach ($arrayUserData as $memberKey => $memberName) {
																?>
																	<option value="<?=$memberKey?>" <?=($dataLogRow['grm_index'] == $memberKey) ? 'selected' : '' ?>><?=$memberName?></option>
																<?php
																	}
																	if (!$dataLogRow['grm_index']) {
																?>
																	<option value="0" selected>정보없음</option>
																<?php 
																	}
																?>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="button" name="dataTypeSave" class="dataTypeSave" value="저장" /> 
															<input type="button" name="dataTypeDel" class="dataTypeDel" value="삭제" />
														</td>
													</tr>
													<?php
														$dataLogRowIdNum++;
														}
														if ($dataLogRowIdNum === 1) {
															$dataTypeCode->setCodeTableType('', 'dataType');
															$dataRangeCode->setCodeTableType('', 'dataRange');
															$rdTypeCode->setCodeTableType('', 'rdType');
														}
													?>
												</thead>
											</table>
											</div>
											</form>
											<?php
												}
											?>
										</div>
									</div>
									<!-- //bbsView -->
								</div>
								
	<script type="text/javascript">
		$(document).ready(function() {
			$('input[name="rm_mall_buy_date"]').datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: '2010:<?=date("Y")?>'
			});

			$('.date_type').each(function(){
				dateTypeId = $(this).attr('id');
				$('#' + dateTypeId).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: '<?=date("Y")?>:2020'
				});
			});

			$('.dataTypeSave').live('click', function(){
				var form = document.frmDataType;

				thisForm = $(this).parent().parent('tr');
				form.mode.value = 'dataTypeSave';
				form.rdl_index.value = (thisForm.find('input[name="rdl_index[]"]').val()) ? thisForm.find('input[name="rdl_index[]"]').val() : 0;
				form.rdl_data_type.value = thisForm.find('select[name="rdl_data_type[]"]').val();
				form.rdl_data_range.value = thisForm.find('select[name="rdl_data_range[]"]').val();
				form.rdl_data_cnt.value = thisForm.find('input[name="rdl_data_cnt[]"]').val();
				form.rdl_complete_date.value = thisForm.find('input[name="rdl_complete_date[]"]').val();
				form.rdl_proc_type.value = thisForm.find('select[name="rdl_proc_type[]"]').val();
				form.grm_index.value = thisForm.find('select[name="grm_index[]"]').val();

				doForm(form);
			});

			$('.dataTypeDel').live('click', function() {
				if (confirm('정말 해당 데이터 타입을 삭제 하시겠습니까?'))
				{
					var form = document.frmDataType;
					form.mode.value		= 'dataTypeDel';
					form.rdl_index.value	= $(this).parent().parent('tr').find('input[name="rdl_index[]"]').val();
					doForm(form);
				}
			});
			
			var dataLogRowIdNum = '<?=$dataLogRowIdNum?>';

			var defultDataType = '';
				defultDataType += '<td style="text-align:center;">' + "<?php $dataTypeCode->setCodeSelectBox('rdl_data_type[]');?>" + '</td>';
				defultDataType += '<td style="text-align:center;">' + "<?php $dataRangeCode->setCodeSelectBox('rdl_data_range[]');?>" + '</td>';
				defultDataType += '<td style="text-align:center;"><input type="text" name="rdl_data_cnt[]" id="rdl_data_cnt" value="0" style="width:50px;" /></td>';
				defultDataType += '<td style="text-align:center;">' + "<input type='text' name='rdl_complete_date[]' value='<?=date('Y-m-d')?>' />" + '</td>';
				defultDataType += '<td style="text-align:center;">' + "<?php $rdTypeCode->setCodeSelectBox('rdl_proc_type[]');?>" + '</td>';
				defultDataType += '<td style="text-align:center;">';
				defultDataType += '<select name="grm_index[]">';
				<?php
					foreach ($arrayUserData as $memberKey => $memberName) {
				?>				
				defultDataType += '<option value="<?=$memberKey?>"' + " <?=($_SESSION['sess']['grm_idx'] == $memberKey) ? 'selected' : '' ?>' + '>" + '<?=$memberName?>' + '</option>';
				<?php
					}
				?>
				defultDataType += '</select>';
				defultDataType += '</td>';
				defultDataType += '<td style="text-align:center;">';
				defultDataType += '<input type="button" name="dataTypeSave" class="dataTypeSave" value="저장" /> ';
				defultDataType += '<input type="button" name="dataTypeDel" class="dataTypeDel" value="삭제" />';
				defultDataType += '</td>';
				defultDataType += '</tr>';

			$('#addDataType').click(function() {
				var insertDataType = (dataLogRowIdNum % 2 == 0) ? "<tr class='bgc'>" + defultDataType : "<tr class='nobgc'>" + defultDataType;
				var addObjectIdx = $('#dataLog thead tr').length;
				$('#dataLog thead').append(insertDataType);
				$('#dataLog thead tr:eq(' + addObjectIdx + ') td input[name="rdl_complete_date[]"]').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: '<?=date("Y")?>:2020'
				});
				iframeResize();
				dataLogRowIdNum++;
			});

			$('.responseLogSel').click(function() {
				responseIndex = $('tr.responseLogList').index($(this).parent().parent('tr.responseLogList'));
				if ($('.responseLog:eq('+responseIndex+')').css('display') == 'none') {
					$('.responseLog').hide();
					$('.responseLog:eq('+responseIndex+')').show();
				}
				else {
					$('.responseLog').hide();
				}
				iframeResize();
			});

			$('.responseLogModify').click(function() {
				responseIndex		= $('tr.responseLog').index($(this).parent().parent().parent('tr.responseLog'));
				rrlIndex			= $('tr.responseLogList:eq(' + responseIndex + ')').attr('rrlIndex');
				rrlType				= $('tr.responseLogList:eq(' + responseIndex + ')').find('td.rrlType').attr('rrlType');
				rrlResponseType		= $('tr.responseLogList:eq(' + responseIndex + ')').find('td.rrlResponseType').attr('rrlResponseType');
				grmIndex			= $('tr.responseLogList:eq(' + responseIndex + ')').find('td.grmIndex').attr('grmIndex');
				rrlResponseDate		= $('tr.responseLogList:eq(' + responseIndex + ')').find('td.rrlResponseDate').html();

				rrlInquiryContents	= $('.responseLog:eq('+responseIndex+')').find('div.rrlInquiryContents').html();
				rrlContents			= $('.responseLog:eq('+responseIndex+')').find('div.rrlContents').html();
				
				rrlInquiryContents	= rrlInquiryContents.replace(/<BR>/gi, '\r\n');
				rrlContents			= rrlContents.replace(/<BR>/gi, '\r\n');

				f = document.frmResponse;
				
				f.rrl_index.value				= rrlIndex;
				f.rrl_type.value				= rrlType;
				f.rrl_response_type.value		= rrlResponseType;
				f.grm_index.value				= grmIndex;
				f.rrl_response_date.value		= rrlResponseDate;
				f.rrl_inquiry_contents.value	= rrlInquiryContents;
				f.rrl_contents.value			= rrlContents;
				$('.responseLog').hide();
				f.rrl_contents.focus();

			});

			$('.responseLogDel').click(function(){
				if (confirm('해당 응대 내역을 삭제 하시겠습니까?')) {
					responseIndex		= $('tr.responseLog').index($(this).parent().parent().parent('tr.responseLog'));
					rrlIndex			= $('tr.responseLogList:eq(' + responseIndex + ')').attr('rrlIndex');

					form = document.frmResponseDel;
					form.rrl_index.value	= rrlIndex;
					doForm(form);
				}
				
			});

			$('select[name="rm_before_solution_code"]').change(function(){
				if ($(this).val() == 'ETC') {
					$('#etc').show();
				}
				else {
					$('#etc').hide();
				}
			});

			function iframeResize() {
				$(parent.document).find('#iframe-box').find('iframe').height($(document).height());
			}

		});
	</script>
<?
	include ('../../_admin/_include/sysbottom.php');
?>