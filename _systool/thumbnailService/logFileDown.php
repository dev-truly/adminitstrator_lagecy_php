<?
	$site = $_GET['site'];
	$fileName = date('Ymdhis') . '_' . str_replace('.', '_', $site) . '_log';
	header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
	header( 'Content-Disposition: attachment; filename=' . $fileName . '.xls' ); 

	$thumbnailUrlDir = './copyLog/';

	$arrayImgName = array(
		'img_mobile'	=> '모바일',
		'img_i'			=> '메인',
		'img_s'			=> '리스트',
		'img_m'			=> '상세',
		'img_l'			=> '확대',
		'thumb'			=> '썸네일',
		'fail'			=> '실패',
	);

	$arrayTumbType	= array('요청', '대체', '썸네일' , '선택');

	$arraySiteLog = array();
	$openDir = opendir($thumbnailUrlDir);
	while ($logDirectRow = readdir($openDir)) {
		if (trim($logDirectRow) != '.' && trim($logDirectRow) != '..' ) {
			$arraySiteLog[] = trim(str_replace('_', '.', $logDirectRow));
		}
	}
	
	if ($site != '') {
		$arraySiteLogFile = array();
		$searchSite = $thumbnailUrlDir . '/' . str_replace('.', '_', $site);
		$openDir = opendir($searchSite);
		while ($logFileRow = readdir($openDir)) {
			if (trim($logFileRow) != '.' && trim($logFileRow) != '..' ) {
				$arraySiteLogFile[] = $logFileRow;
			}
		}

		sort($arraySiteLogFile);
	}
?>
<html>
	<head>
	
	<title>썸네일 작업 사이트 로그 확인</title>
	<style type="text/css">
			body, input, select, table, thead, tbody, tr, td, th , div {
				margin:0px;
				padding:0px;
				font-family:'돋움', 'dotum', 'gulim';
				font-size:12px;
			}
			
			table thead tr th {
				font-size:17px;
				background-color:#ccccff;
			}

			table thead tr th {
				border-top:solid 1px #dbdbdb;
			}

			table thead tr th, table tbody tr th, table tbody tr td {
				padding:5px 0 5px 0;
				border-bottom:solid 1px #dbdbdb;
			}

			.center {
				text-align:center;
			}
			
			.logTime {
				font-size:13px;
				background-color:#ccffff;
				padding:5px 0 5px 0;
			}

			.thumb td {
				color:#339900;
			}
			.change td {
				color:#ff0000;
			}
			.fail td {
				background-color:#ff0000;
				font-weight:bold;
			}
		</style>
	</head>
	<body>
		<?php
			 if(!empty($arraySiteLogFile)) {
		?>
		<table cellspacing="0" cellpadding="0" border="0" width="800px" style="margin:0 auto;">
			<colgroup>
				<col width="100px" />
				<col width="100px" />
				<col width="150px" />
				<col width="250px" />
				<col width="100px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th>타입</th>
					<th>상품번호</th>
					<th>이미지타입</th>
					<th>파일명</th>
					<th>가로</th>
					<th>세로</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($arraySiteLogFile as $logFileName) {
						$arrayHistoryName	= explode('_', $logFileName);
						$historyName		= floor($arrayHistoryName[1]) . ' 번째 상품 부터 300개의';  
						$historyName		.= $arrayImgName[$arrayHistoryName[2] . '_' . $arrayHistoryName[3]] . ' 이미지를';
						$historyName		.= ($arrayHistoryName[4] == 'w') ? ' 가로 ' : ' 세로 ';
						$historyName		.= ($arrayHistoryName[5]) ? $arrayHistoryName[5] . ' ' : ' 동일 ';
						$historyName		.= $arrayImgName[$arrayHistoryName[6] . '_' . $arrayHistoryName[7]];
						$historyName		.= ' 대체 이미지 사용하여 작업';
				?>
					<?php
						$fp			= fopen($searchSite . '/' . $logFileName, 'r' );
						$rowCnt = 0;
						while ($getDataRow	= fgetcsv($fp, 135000, '	' )) {
							if ($rowCnt) {
								$rowClass = '';
								if ($getDataRow[0] === '0') {
									$rowClass = 'change';
								}
								else if ($getDataRow[0] === '2') {
									$rowClass = 'thumb';
								}
								else if ($getDataRow[0] === '4') {
									$rowClass = 'fail';
								}
					?>
					<tr class="<?=$rowClass?>">
						<td class="center"><?=$arrayTumbType[$getDataRow[0]]?></td>
						<td class="center"><?=$getDataRow[1]?></td>
						<td class="center"><?=$arrayImgName[$getDataRow[2]]?></td>
						<td><?=$getDataRow[3]?></td>
						<td class="center"><?=$getDataRow[4]?></td>
						<td class="center"><?=$getDataRow[5]?></td>
					</tr>
					<?php
							}
						
							else {
							$logTime = date('Y년 m월 d일 H시 i분 s초', $getDataRow[0]);
					?>
						<tr>
							<th colspan="6" class="logTime">작업 내용 : <?=$historyName?></th>
						</tr>
						<tr>
							<th colspan="6" class="logTime">작업 시간 : <?=$logTime?></th>
						</tr>
					<?php
							}
						$rowCnt++;
						}
					}
				?>
			</tbody>
		</table>
		<?php
			}
		?>
	</body>
</html>
<?php
/** 
* Date = 개발 작업일(2014.07.23)
* ETC = 썸네일 진행 로그 엑셀 다운로드
* Developer = 한영민
*/
?>