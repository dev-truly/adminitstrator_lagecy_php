<?php
	include ('../../_inc/define.php');
	include ('../../_inc/lib.func.php');
	
	$db = class_load('db');
	$isUrl = $_SERVER['PHP_SELF'];
	$menuQuery = "Select m_index, m_subject, m_explain, m_login, m_button, m_code From " . GD_MENU . " Where m_url like '%" . $isUrl . "%' And m_delete_flag = 'n'";
	$menuRow = $db->fetch($db->query($menuQuery));
	
	session_start();
	if ($menuRow['m_login'] == 'y') {
		if(!$_SESSION['sess']['ruIndex']){ // 로그인 체크
			echo "<script type='text/javascript'>parent.alert('로그인이 되어있지 않습니다.');parent.location.href = '../_admin/_login/_login.php';</script>";
		}
		else {
			$session = class_load('session');
			if (!$session->permit_ip_check()) {
				echo "<script type='text/javascript'>parent.alert('로그인 허용 아이피가 아닙니다.');parent.location.reload();</script>";
			}
		}
	}

	$menuSubject = $menuRow['m_subject'];
	$menuExplain = $menuRow['m_explain'];
	$buttonAuth = str_replace(',', '', $menuRow['m_button']);

	if (!ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode']) && (!$menuRow['m_code'] == 'member' && !ereg('/_systool/member/', $_SERVER['PHP_SELF']))) {
		echo "<script type='text/javascript'>parent.alert('메뉴 접근 권한이 없습니다.');parent.location.href = '../_admin/_login/_login.php';</script>";
	}
?>
<html>
	<head>
	<title> 상품 이미지 썸네일 서비스 진행 페이지 </title>
		<link href="../../module/_import/jqueryui/css/south-street/jquery-ui-1.9.2.custom.css" rel="stylesheet">
		<script src="../../module/_import/jqueryui/js/jquery-1.8.3.js"></script>
		<script src="../../module/_import/jqueryui/js/jquery-ui-1.9.2.custom.js"></script>
		<style type="text/css">
			body, div, input, select, p {
				font-size:12px;
				font-family:'돋움', 'dotum', '굴림';
				padding:0;
				margin:0;
			}
			.configRow {
				border-bottom:solid 1px #d4ccb0;
				height:30px;
				padding-top: 3px;
			}
			.configTitle {
				float:left;
				width:180px;
				margin-top:5px;
			}
			.configCol {
				float:left;
			}
			.configColText {
				float:left;
				margin-top:5px;
			}
			.button {
				cursor:pointer;
				padding:2px;
			}
			
			.fileChangePriview {
				border:solid 1px #d4ccb0;
				width:600px;
				float:left;
			}
			.priviewLoaderArea {
				width:600px;
				background-color:#000000;
				height:350px;
				position:absolute;
				opacity:0.5;
				filter:alpha(opacity=50);
			}
			.priviewLoaderArea > p{
				margin:125px 0 0 250px;
			}
			.priviewTitle {
				border-bottom:solid 1px #d4ccb0;
				height:25px;
			}
			.priviewView {
				height:300px;
				overflow-y:scroll;
			}
			.priviewGoodsnoTitle {
				width:70px;
				text-align:center;
			}
			.priviewGoodsnoValue {
				width:70px;
				text-align:center;
				border-bottom:solid 1px #d4ccb0;
			}
			.priviewTypeTitle {
				width:100px;
				text-align:center;
			}
			.priviewTypeName {
				width:100px;
				text-align:center;
				border-bottom:solid 1px #d4ccb0;
			}
			.priviewImgTitle {
				width:250px;
				text-align:center;
			}
			.priviewImgName {
				width:250px;
				overflow:hidden;
				border-bottom:solid 1px #d4ccb0;
			}
			.priviewSizeTitle {
				width:80px;
				text-align:center;
			}
			.priviewSize {
				width:80px;
				text-align:center;
				border-bottom:solid 1px #d4ccb0;
			}
		</style>
	</head>

	<body>
		<div class="accordion">
			<h3>상품 이미지 썸네일 설정</h3>
			<div>
				<div id="thumbnail">
					<div class="configRow">
						<div class="configTitle">썸네일 작업 도메인</div>
						<div class="configSetting"><input type="text" name="thumbnailUrl" value="" /> ex) relocation.godo.co.kr</div>
					</div>
					<div class="configRow">
						<div class="configTitle">등록 상품 갯수</div>
						<div class="configSetting">
							<div id="goodsCnt" style="float:left;width:70px;margin-top:4px;">조회없음</div> 개
							<input type="button" class="button" name="cntCheckButton" value="등록 상품 갯수 조회">
						</div>
					</div>
					<div class="configRow">
						<div class="configTitle">추출 제한</div>
						<div class="configSetting"><input type="text" name="startLimit" onlyNumber style="width:50px;" value="0" /> 번째 상품 부터 300개 상품 조회 goodsno 정렬</div>
					</div>
					<div class="configRow">
						<div class="configTitle">썸네일 변환 갯수</div>
						<div class="configSetting"><input type="text" name="thumbnailCnt" onlyNumber="4" maxlength="1" style="width:20px;" value="1" /></div>
					</div>
					<div id="thumbnailConfigArea">
						<div class="configRow thumbnailConfigRow" id="defaultRow">
							<div class="configTitle">변환 설정</div>
							<div class="configCol" style="width:200px;">
								원본 이미지 설정 
								<select name="originImage[]">
									<option value="img_l">확대</option>
									<option value="img_m">상세</option>
									<option value="img_s">리스트</option>
									<option value="img_i">메인</option>
									<option value="img_mobile">모바일</option>
								</select>
							</div>

							<div class="configColText"> 변환 사이즈 => </div>
							<div class="configCol" >
								<select name="whFlag[]" style="margin-top:1px;">
									<option value="w">가로</option>
									<option value="h">세로</option>
								</select>
							</div>
							<div class="configCol" style="width:100px;"><input type="text" name="newSize[]" onlyNumber maxlength="5" style="width:40px;" /> px</div>
							<div class="configColText" style="width:300px;">원본 이미지가 작을 경우 썸네일 대안 이미지</div>
							<div>
								<select name="changeImage[]">
									<option value="img_l">확대</option>
									<option value="img_m">상세</option>
									<option value="img_s">리스트</option>
									<option value="img_i">메인</option>
								</select>
							</div>
						</div>
					</div>
					<div>
						<div class="configRow">
							<div class="configTitle">버튼 : </div>
							<div >
								<input type="button" name="changeCheck" class="button" value="이미지 변경 확인" />
								<input type="button" name="processPage" class="button" onclick="location.href='./logCheck.php'" value="로그페이지" />
							</div>
						</div>
					</div>
					<div class="configRow" style="height:100px;">
						<div class="configTitle" style="height:90px;font-size:14px;">확인 사항 : </div>
						<div class="configSetting" style="margin-top:4px;font-size:14px;">
							1. 변환 사이즈 미 설정시 원본 이미지 용량 다운 기능이 실행 됩니다. <br/>
							2. 원본 이미지가 변환 사이즈 보다 작거나 파일이 없을 경우 대안 이미지로 사이즈를 변경하게 됩니다.<br />
							3. 원본, 대안 이미지가 변환 사이즈 보다 작을 경우 썸네일 진행이 되어지지 않습니다.<br/>
							4. 원본, 대안 이미지는 서버에 백업 진행 후 썸네일 진행이 되어 집니다.<br/>
							5. 다중 변환 갯수 작업시 원본 이미지 설정을 동일하게 진행 하지 않도록 유의하여 주세요.
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="accordion">
			<h3>상품 이미지 변경 내용 확인</h3>
			<div id="preview">
			</div>
			<div id="previewSample" style="display:none;">
				<div class="fileChangePriview">
				<div class="priviewLoaderArea">
					<p><img src="./images/typeloader.gif" /></p>
				</div>
					<div class="priviewTitle">
						<div class="configTitle priviewGoodsnoTitle">상품번호</div>
						<div class="configTitle priviewTypeTitle">파일타입</div>
						<div class="configTitle priviewImgTitle">파일명</div>
						<div class="configTitle priviewSizeTitle">가로</div>
						<div class="configTitle priviewSizeTitle">세로</div>
					</div>
					<div class="priviewView">
					</div>
					<div>
						<input type="button" class="button processStartButton" value="썸네일 작업 진행" />
						<span class="loadingbar" style="display:none;"><img src="./images/loader.gif" /></span>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				var clearFlag = 0;
				var timeClearInterval = '';
				var configRowHtml = '<div class="configRow thumbnailConfigRow addRow">' + $('#thumbnailConfigArea #defaultRow').html() + '</div>';
				var processPath = '/shop/_godoConn/getGoodsImgFile.php';
				var copyUrl = '';
				var processUrl = '';
				var previewSampleHtml = $('#previewSample').html();
				var arrayHistory = new Array();
				var dataCount = 0;

				var startLimit	= 0;
				var endLimit	= 300;

				$('.accordion').accordion({
					autoHeight:false
				});
				
				function timeClear() {
					clearFlag = 1;
					timeClearInterval = setInterval(function() {
						$('.timeclear').remove();
						clearFlag = 0;
						clearInterval(timeClearInterval);
					}, 1000);
				}

				function parmeterSetting() {
					outParameter = '';
					parameterCount = 0;
					$('select[name="originImage[]"]').each(function() {
						if (parameterCount) {
							outParameter += "&";
						}
						outParameter += 'originImage[]=' + $(this).val();
						outParameter += '&whFlag[]=' + $('select[name="whFlag[]"]:eq(' + parameterCount + ')').val();
						outParameter += '&newSize[]=' + $('input[name="newSize[]"]:eq(' + parameterCount + ')').val();
						outParameter += '&changeImage[]=' + $('select[name="changeImage[]"]:eq(' + parameterCount + ')').val();
						parameterCount++;
					});

					return outParameter;
				}

				$('input[name="changeCheck"]').click(function() {
					copyUrl	= $('input[name="thumbnailUrl"]').val();
					processUrl = 'http://' + copyUrl + processPath;
					startLimit = $('input[name="startLimit"]').val();
					
					outParameter = parmeterSetting();
					
					arrayHistory = Array();

					$('#preview .fileChangePriview').remove();
					
					for (i = 0; i < $('input[name="thumbnailCnt"]').val(); i++) {
						$('#preview').append(previewSampleHtml);
						arrayHistory[i] = Array();
					}
					
					outParameter += '&startLimit=' + startLimit + '&limit=' + endLimit;
					
					$.ajax({
						dataType:'jsonp'
						,jsonp:'callback'
						,type: 'POST'
						, url:processUrl														 // url
						, data:'mode=preview&' + outParameter //넘길 파라메타 값
						, success: function(data){
							$.each(data, function(goodsRowKey, goodsRowValue){
								goodsno = goodsRowValue.goodsno;
								$.each(goodsRowValue, function(goodsRowInfoKey, goodsRowInfoValue){
									if (goodsRowInfoKey.substring(0,7) == 'fileRow') {
										priviewHistoryHtml = '';
										if (goodsRowInfoValue.selectFileName) {
											priviewHistoryHtml += '<div>';
											priviewHistoryHtml += '<div class="configTitle priviewGoodsnoValue" style="color:#ff0000;">' + goodsno + '</div>';
											priviewHistoryHtml += '<div class="configTitle priviewTypeName selectType" style="color:#ff0000;">선택 : ' + goodsRowInfoValue.selectFileType + '</div>';
											priviewHistoryHtml += '<div class="configTitle priviewImgName selectFileName" style="color:#ff0000;">' + goodsRowInfoValue.selectFileName + '</div>';
											priviewHistoryHtml += '<div class="configTitle priviewSize" style="color:#ff0000;">' + goodsRowInfoValue.selectWidthSize + 'px</div>';
											priviewHistoryHtml += '<div class="configTitle priviewSize" style="color:#ff0000;">' + goodsRowInfoValue.selectHeightSize + 'px</div>';
											priviewHistoryHtml += '</div>';
											
											historyTempSave(goodsRowInfoValue.changeNumber, goodsno, '0', goodsRowInfoValue.selectFileType, goodsRowInfoValue.selectFileName, goodsRowInfoValue.selectWidthSize, goodsRowInfoValue.selectHeightSize);
										}
										priviewHistoryHtml += '<div>';
										priviewHistoryHtml += '<div class="configTitle priviewGoodsnoValue historyGoodsno">' + goodsno + '</div>';
										priviewHistoryHtml += '<div class="configTitle priviewTypeName originType">원본 : ' + goodsRowInfoValue.originType + '</div>';
										priviewHistoryHtml += '<div class="configTitle priviewImgName originName">' + goodsRowInfoValue.originFileName + '</div>';
										priviewHistoryHtml += '<div class="configTitle priviewSize">' + goodsRowInfoValue.originFileSizeWidth + 'px</div>';
										priviewHistoryHtml += '<div class="configTitle priviewSize">' + goodsRowInfoValue.originFileSizeHeight + 'px</div>';
										priviewHistoryHtml += '</div>';

										historyTempSave(goodsRowInfoValue.changeNumber, goodsno, '1', goodsRowInfoValue.originType, goodsRowInfoValue.originFileName, goodsRowInfoValue.originFileSizeWidth, goodsRowInfoValue.originFileSizeHeight);

										priviewHistoryHtml += '<div>';
										priviewHistoryHtml += '<div class="configTitle priviewGoodsnoValue" style="color:#459e00;">' + goodsno + '</div>';
										priviewHistoryHtml += '<div class="configTitle priviewTypeName thumbnailType" style="color:#459e00;">썸네일</div>';
										priviewHistoryHtml += '<div class="configTitle priviewImgName newName" style="color:#459e00;">' + goodsRowInfoValue.newFileName + '</div>';
										priviewHistoryHtml += '<div class="configTitle priviewSize" style="color:#459e00;">' + goodsRowInfoValue.newFileSizeWidth + 'px</div>';
										priviewHistoryHtml += '<div class="configTitle priviewSize" style="color:#459e00;">' + goodsRowInfoValue.newFileSizeHeight + 'px</div>';
										priviewHistoryHtml += '</div>';

										historyTempSave(goodsRowInfoValue.changeNumber, goodsno, '2', 'thumb', goodsRowInfoValue.newFileName, goodsRowInfoValue.newFileSizeWidth, goodsRowInfoValue.newFileSizeHeight);
										
										$('#preview .fileChangePriview:eq(' + goodsRowInfoValue.changeNumber + ') > .priviewView').append(priviewHistoryHtml);
									}
								});
							});
							$('#preview .fileChangePriview').each(function() {
								if ($(this).find('.priviewView').html() == '') {
									alert('변환 작업 가능한 썸네일 파일이 없어 삭제 처리 됩니다.\n 설정값을 확인해 주세요');
									$(this).remove();
								}
							});
							$('.priviewLoaderArea').hide();
							$('input[name="startLimit"]').val(Number(startLimit) + endLimit);
						}
						, error: function(data){
							alert('Process File Error');
						}
					});
				});

				function historyTempSave (historyKey, goodsno, type, typeName, file, width, height) {
					arrayHistory[historyKey].push([goodsno, type, typeName, file, width, height]);
				}

				function historySave (historyKey) {
					var originUrl = 'originUrl=' + copyUrl;
					requestParameter = '';

					requestParameter += '&startLimit=' + startLimit;
					requestParameter += '&originImage=' + $('select[name="originImage[]"]:eq(' + historyKey + ')').val();
					requestParameter += '&whFlag=' + $('select[name="whFlag[]"]:eq(' + historyKey + ')').val();
					requestParameter += '&newSize=' + $('input[name="newSize[]"]:eq(' + historyKey + ')').val();
					requestParameter += '&changeImage=' + $('select[name="changeImage[]"]:eq(' + historyKey + ')').val();

					$.each(arrayHistory[historyKey], function(key, value) {
						requestParameter += '&goodsno[]=' + value[0];
						requestParameter += '&type[]=' + value[1];
						requestParameter += '&typeName[]=' + value[2];
						requestParameter += '&file[]=' + value[3];
						requestParameter += '&width[]=' + value[4];
						requestParameter += '&height[]=' + value[5];
					});

					$.ajax({
						dataType:'jsonp'
						,jsonp:'callback'
						,type: 'POST'
						, url:'./logSave.php'		// url
						, data:originUrl + requestParameter	//넘길 파라메타 값
						, success: function(data){
							if (data.result) {
								$('#preview .fileChangePriview:eq(' + historyKey + ')').remove();
								arrayHistory[historyKey] = Array();
								alert('썸네일 작업 및 로그 저장 성공');
								$('.priviewLoaderArea').hide();
							}
							else {
								$('.loadingbar:eq(' + historyKey + ')').hide();
								$('.priviewLoaderArea').hide();
								alert('썸네일 작업 성공 하였으나 로그 저장 디렉토리\n생성 실패로 파일저장이 되어지지 않았습니다.');
							}
						}
						, error: function(data){
							alert('Process File Error');
						}
					});
				}
			
				$('input[onlyNumber]').keydown(function(){
					if (!clearFlag) {
						var obj = $(this);
						thisValue = obj.val();
						
						for (var i = 0; i < thisValue.length ; i++){
							chr = thisValue.substr(i,1);  
							chr = escape(chr);
							key_eg = chr.charAt(1);
							if (key_eg == 'u'){
								key_num = chr.substr(i,(chr.length-1));
								if((key_num < "AC00") || (key_num > "D7A3")) { 
									event.returnValue = false;
								}
							}
						}

						if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode == 8 || event.keyCode ==46) || (event.keyCode >= 96 && event.keyCode <= 105) || (event.keyCode >= 37 && event.keyCode <= 40)) {
							event.returnValue = true;
							if (obj.attr('onlyNumber')) {
								var numberCheck = setInterval(function(){
									if (Number(obj.attr('onlyNumber')) < Number(obj.val())) {
										obj.parent().append('<span class="timeclear" style="color:red;">변경하신 수치는 최대 ' + obj.attr('onlyNumber') + ' 까지 설정이 가능합니다.</span>');
										timeClear();
										obj.val(obj.attr('onlyNumber'));
									}
									else if (obj.val() == 0 && obj.val() != '') {
										obj.parent().append('<span class="timeclear" style="color:red;">변경하신 수치는 0이 될 수 없습니다.</span>');
										timeClear();
										obj.val(1);
									}
									clearInterval(numberCheck);
								}, 1);
							}
						} else {
							event.returnValue = false;
						}
					}
					else {
						event.returnValue = false;
					}
				});

				$('input[name="thumbnailCnt"]').keyup(function() {
					changeCnt = $(this).val();
					if ($(this).val()) {
						addRowCnt = $('#thumbnailConfigArea .addRow').length;
						
						if (addRowCnt < changeCnt - 1) {
							for (i = addRowCnt; i < changeCnt - 1; i++) {
								$('#thumbnailConfigArea').append(configRowHtml);
							}
						}
						else {
							for (i = addRowCnt - 1; i > changeCnt - 2; i--) {
								$('#thumbnailConfigArea .addRow:eq(' + i + ')').remove();
							}
						}
					}
				});

				$('input[name="cntCheckButton"]').click(function(){
					copyUrl	= $('input[name="thumbnailUrl"]').val();
					processUrl = 'http://' + copyUrl + processPath;

					$.ajax({
						dataType:'jsonp'
						,jsonp:'callback'
						,type: 'POST'
						, url:processUrl														 // url
						, data:'mode=goodscount' //넘길 파라메타 값
						, success: function(data){
							dataCount = data.goodscount;
							$('#goodsCnt').text(dataCount);
						}
						, error: function(data){
							alert('Process File Error');
						}
					});
					
				});

				$('.processStartButton').live('click', function() {
					var originUrl = 'originUrl=' + copyUrl;
					var requestParameter = '';
					var historNumber = 0;

					changeArea = $(this).parent('div').parent('div.fileChangePriview').children('div.priviewView');
					historyNumber = $('div.fileChangePriview').index($(this).parent('div').parent('div.fileChangePriview'));
					$('.loadingbar:eq(' + historyNumber + ')').show();
					$('.priviewLoaderArea:eq(' + historyNumber + ')').css('opacity','0.1').find('p').remove();
					$('.priviewLoaderArea').show();

					changeArea.find('.originName').each(function(){
						thisIndex = changeArea.find('.originName').index($(this));

						requestParameter += '&originFileName[]=' + $(this).text();
						requestParameter += '&newFileName[]=' + changeArea.find('.newName:eq(' + thisIndex + ')').text();
					});

					changeArea.find('.selectFileName').each(function(){
						requestParameter += '&selectFileName[]=' + $(this).text();
					});
					
					requestParameter += '&whFlag=' + $('.thumbnailConfigRow:eq(' + historyNumber + ')').find('select[name="whFlag[]"]').val();
					requestParameter += '&changeSize=' + $('.thumbnailConfigRow:eq(' + historyNumber + ')').find('input[name="newSize[]"]').val();

					$.ajax({
						dataType:'jsonp'
						,jsonp:'callback'
						,type: 'POST'
						, url:'./thumbnailProcess.php'		// url
						, data:originUrl + requestParameter	//넘길 파라메타 값
						, success: function(data){
							resultMsg = '';
							if (data.result) {
								//resultMsg = '썸네일 작업 성공';
								historySave(historyNumber);
							}
							else {
								
								if (data.resultMsg === '1'){
									resultMsg = '해당 사이트 경로가 생성되어지지 않았습니다.';
								}
								else if (data.resultMsg === '2'){
									resultMsg = '썸네일 경로가 생성 되어지지 않았습니다.';
								}
								else {
									if (data.resultMsg === '3') {
										resultMsg = '파일 복사 진행이 되어지지 않은 파일이 있습니다.\n 내역 : \n';
									}
									else if (data.resultMsg === '4') {
										resultMsg = '파일 복사 진행이 되어지지 않은 파일이 있습니다.\n 내역 : \n';
									}
									else {
										$.each(data.failData, function(key, failData) {
											historyTempSave(historyNumber, '0', '4', 'fail', failData, '0', '0');
										});
										historySave(historyNumber);
									}
								}
								
								if (data.resultMsg != '5'){
									$('.priviewLoaderArea').hide();
									$('.loadingbar:eq(' + historyNumber + ')').hide();
									alert(resultMsg);
								}
							}
							
							
						}
						, error: function(data){
							$('.priviewLoaderArea:eq(' + historyNumber + ')').hide();
							alert('썸네일 작업 중 에러가 발생 하였습니다.\n썸네일 작업 재 실행 하여 주시기 바랍니다.');
						}
					});
				});
			});
		</script>
	</body>
</html>
