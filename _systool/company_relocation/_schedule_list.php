<?php
	include ('../../_admin/_include/systop.php');
	// 년도, 월 데이터 받아오기
	$year	= $_GET['year'];
	$month	= $_GET['month'];
	
	// 년도가 없으면 현재 년도 처리
	if (!$year) {
		$year = date('Y');
	}
	
	// 월이 없으면 현재 월 처리
	if (!$month) {
		$month = date('m');
	}
	
	if (!$returnPage) {
		$returnPage = '_schedule_list';
	}

	$lastDate			= date("t", mktime(0, 0, 0, $month, 1, $year)); //해당 월의 마지막 날짜
	
	$startDate	= date('Y-m-d', strtotime($year . '-' . $month . '-' . '01'));
	$endDate	= date('Y-m-d', strtotime($year . '-' . $month . '-' . $lastDate));

	$nextYear	= $year;
	$prevYear	= $year;
	$nextMonth	= $month + 1;
	$prevMonth	= $month - 1;

	// 내년, 1월 계산
	if ($month == 12) {
		$nextYear	= $year+1;
		$nextMonth	= 1;
	}

	// 작년, 12월 계산
	else if ($month == 1) {
		$prevYear	= $year-1;
		$prevMonth	= 12;
	}

	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성
	*  ------------------------------------------------
	*/
	$getStr ='';
	$getStr = getStr($getStr, 'year', $year);
	$getStr = getStr($getStr, 'month', $month);
	$getStr = getStr($getStr, 'returnPage', $returnPage);	// 리턴 페이지


	/*	----------------------------------------
	*	일정 검색
	*	----------------------------------------
	*/
	$arrayScheduleData = array();
	$selectQuery = "Select RDL.rm_index as dataIndex, rdl_data_range, RDL.grm_index, rdl_complete_date From " . GD_RELOCATION_DATA_LOG . " RDL join " . GD_RELOCATION_MALL . " RM on RDL.rm_index = RM.rm_index Where rdl_complete_date between '" . $startDate . "' and '" . $endDate . "' and RDL.rdl_deleteFl = 'n' and RM.rm_deleteFl = 'n' order by grm_index, rdl_data_range DESC" ;
	
	$scheduleResult = $db->query($selectQuery);
	while ($scheduleRow = $db->fetch($scheduleResult, 1)) { //데이터 쿼리 내 데이터 일련번호 필드 명 AS dataIndex 선언 후 사용
		$arrayScheduleData[strtotime($scheduleRow['rdl_complete_date'])][$scheduleRow['grm_index']][$scheduleRow['dataIndex']][] = $scheduleRow;
	}

	$code = class_load('code');
	$dataRangeCode = $code->setCodeTableType(false, 'dataRange');
?>
	<script language="javascript">
		function LoadPageLink(idx)
		{	
			LoadLinkStr("23","xUidx="+idx+ "&<?=$getStr?>")
		}

		function _Finsert()
		{
			LoadLinkStr("23","<?=$getStr?>");
		}
	</script>
		<div id="viewContent">
			<?php include ('../../_admin/_include/schedule_list_title.php');?>
			<div id="layer-popbg" >
			</div>
			<div id="container">
				<div id="contents">
					<table class="calender" width="100%" cellpadding="0" cellspacing="0">
						<colgroup>
							<col width="142.5px" />
							<col width="143px" />
							<col width="143px" />
							<col width="143px" />
							<col width="143px" />
							<col width="143px" />
							<col width="142.5px" />
						</colgroup>
						<tr>
							<th class="sunday">일요일</th>
							<th>월요일</th>
							<th>화요일</th>
							<th>수요일</th>
							<th>목요일</th>
							<th>금요일</th>
							<th class="saturday">토요일</th>
						</tr>
						<?php 
							//데이터 쿼리 내 고객 ID 필드명 AS godoId 선언 후 사용 
							//데이터 쿼리 내 데이터 일련번호 필드명 AS dataIndex 선언 후 사용
							setSchedule($year, $month, $lastDate, $arrayScheduleData, true);
							
						?>
					</table>
				</div>
			</div>
		</div>
<script type="text/javascript">
	$(document).ready(function(){
		/* 이전 담당자 일정에 마우스 업로드시 활성기능 */
		$('.calender tr td div.grm_area div').mouseover(function(){
			$('.calender tr td div.grm_area div').removeClass('on');
			$(this).addClass('on');
		});

	});
	
</script>
<?
	include ('../../_admin/_include/sysbottom.php');
?>