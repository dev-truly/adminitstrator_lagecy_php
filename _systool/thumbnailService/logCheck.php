<?
	$thumbnailUrlDir = './copyLog/';
	
	$site = $_POST['site'];
	
	$arrayImgName = array(
		'img_mobile'	=> '�����',
		'img_i'			=> '����',
		'img_s'			=> '����Ʈ',
		'img_m'			=> '��',
		'img_l'			=> 'Ȯ��',
		'thumb'			=> '�����',
		'fail'			=> '����',
	);

	$arrayTumbType	= array('��û', '��ü', '�����' , '����');

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
	
	<title>����� �۾� ����Ʈ �α� Ȯ��</title>
	<style type="text/css">
			body, input, select, table, thead, tbody, tr, td, th , div {
				margin:0px;
				padding:0px;
				font-family:'����', 'dotum', 'gulim';
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
		<h1 style="text-align:center;">����� �۾� ����Ʈ �α� Ȯ��</h1>
		<form name="searchArea" method="post" action="<?=$_SERVER['PHP_SELF']?>">
			<div style="text-align:center;">
				<select name="site">
					<option value="">�۾�����Ʈ</option>
					<?php
						foreach ($arraySiteLog as $siteUrl) {
					?>
						<option value="<?=$siteUrl?>" <?=($site == $siteUrl) ? 'selected' : ''?>><?=$siteUrl?></option>
					<?php
						}
					?>
				</select>
				<input type="submit" name="searchSubmit" value="�˻�" />
				<input type="button" name="processPage" onclick="location.href='./'" value="�۾�������" />
				<?php
						if ($site) {
				?>
							<input type="button" name="processPage" onclick="location.href='./logFileDown.php?site=<?=$site?>'" value="�α� �ٿ�" />
				<?php
							}
				?>
			</div>
			
		</form>
		<?php
			 if(!empty($arraySiteLogFile)) {
		?>
		<div style="text-align:center;">
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
						<th>Ÿ��</th>
						<th>��ǰ��ȣ</th>
						<th>�̹���Ÿ��</th>
						<th>���ϸ�</th>
						<th>����</th>
						<th>����</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($arraySiteLogFile as $logFileName) {
							$arrayHistoryName	= explode('_', $logFileName);
							$historyName		= floor($arrayHistoryName[1]) . ' ��° ��ǰ ���� 300�� ��ǰ�� ';  
							$historyName		.= $arrayImgName[$arrayHistoryName[2] . '_' . $arrayHistoryName[3]] . ' �̹�����';
							$historyName		.= ($arrayHistoryName[4] == 'w') ? ' ���� ' : ' ���� ';
							$historyName		.= ($arrayHistoryName[5]) ? $arrayHistoryName[5] . ' ' : '���� ';
							$historyName		.= $arrayImgName[$arrayHistoryName[6] . '_' . $arrayHistoryName[7]];
							$historyName		.= ' ��ü �̹��� ����Ͽ� �۾�';
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
								$logTime = date('Y�� m�� d�� H�� i�� s��', $getDataRow[0]);
						?>
							<tr>
								<th colspan="6" class="logTime">�۾� ���� : <?=$historyName?></th>
							</tr>
							<tr>
								<th colspan="6" class="logTime">�۾� �ð� : <?=$logTime?></th>
							</tr>
						<?php
								}
							$rowCnt++;
							}
						}
					?>
				</tbody>
			</table>
		</div>
		<?php
			}
		?>
	</body>
</html>

<?php
/** 
* Date = ���� �۾���(2014.07.23)
* ETC = ����� ���� �α� Ȯ��
* Developer = �ѿ���
*/
?>