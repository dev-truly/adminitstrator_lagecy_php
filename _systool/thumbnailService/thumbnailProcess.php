<?php
	
	ini_set("memory_limit","2048M");
    ini_set('max_execution_time', 2048);
	//일반적으로 보는데 문제 없으나 gd 에서 오류 발생하여 이미지생성이 안된다.
	//jpeg 파일이 일반적으로 보는데 문제 없으나 손상된 파일이거나 CYMK 일 경우 오류 발생하여 오류 무시 처리 해준다.
	@ini_set('gd.jpeg_ignore_warning', 1);
    setlocale(LC_CTYPE, "ko_KR.eucKR");
	
	/**
	*	----------------------------------------
	*	- Advice - 넘어오는 값
	*	----------------------------------------
	*/
	$callback		= $_REQUEST["callback"];
	$originUrl		= $_REQUEST['originUrl'];
	$selectFileName	= $_REQUEST['selectFileName'];
	$originFileName = $_REQUEST['originFileName'];
	$newFileName	= $_REQUEST['newFileName'];
	$whFlag			= $_REQUEST['whFlag'];
	$changeSize		= $_REQUEST['changeSize'];

	/**
	*	----------------------------------------
	*	- Advice - 넘어오는 값 종료
	*	----------------------------------------
	*/

	/**
	*	----------------------------------------
	*	- Advice - 사용 변수 선언
	*	----------------------------------------
	*/
	$copyPath		= './copyFileStorage/' . str_replace('.', '_', $originUrl) . '/';	// 원본파일 복사 경로
	$thumbnailPath	= $copyPath . 't/';													// 썸네일 파일 저장 경로
	$originUrl		= 'http://' . $originUrl . '/shop/data/goods/';						// 원본 쇼핑몰 상품 이미지 경로
	
	$jsons = '';					// json 형 데이터 저장 변수
	$result				= true;		// 프로세스 결과
	$resultMsg			= '0';		// 프로세스 MSG Code
	$arrayThumbFailFile	= array();	// 썸네일 작업 파일 실패 내역 저장 배열

	/**
	*	----------------------------------------
	*	- Advice - 사용 변수 선언 종료
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - 파일 사이즈 변환 함수 
	*	- in - (가로사이즈, 세로사이즈, 변환사이즈, 가로세로 구분)
	*	- out - (변경가로사이즈, 변경세로사이즈)
	*	----------------------------------------
	*/
	function fileSizeChange($widthSize, $heightSize, $changeSize, $whFlag) {
		$outWidthSize	= 0;
		$outHeightSize	= 0;
		if ($whFlag == 'h') {
			if ($changeSize) {
				if (!$changeSize) {
					$changeSize = 1;
				}
				$outWidthSize	= $widthSize * $changeSize / $heightSize;
				$outHeightSize	= $changeSize;
			}
			else {
				$outWidthSize = $widthSize;
				$outHeightSize	= $heightSize;
			}
		}
		else {
			if ($changeSize) {
				if (!$widthSize) {
					$widthSize = 1;
				}
				$outWidthSize	= $changeSize;
				$outHeightSize	= $heightSize * $changeSize / $widthSize;
			}
			else {
				$outWidthSize = $widthSize;
				$outHeightSize	= $heightSize;
			}
		}

		return array($outWidthSize, $outHeightSize);
	}
	/**
	*	----------------------------------------
	*	- Advice - 파일 사이즈 변환 함수 종료
	*	----------------------------------------
	*/

	/**
	*	----------------------------------------
	*	- Advice - 썸네일(Thumbnail) 생성 함수 
	*	- in - (기존이미지경로, 생성할이미지경로, 썸네일의가로사이즈, 썸네일의세로사이즈, 확장자명)
	*	----------------------------------------
	*/
	function createThumbnail($sOrgImgPath, $sNewImgPath, $iSizeW, $iSizeH, $sExt)
	{
			
		// 확장자에 따라 다른 내장함수를 통해 썸네일 생성
		if ($sExt == 'jpg' || $sExt == 'jpeg' || $sExt == 'JPG' || $sExt == 'JPEG') {
			$OrgImage = imagecreatefromjpeg($sOrgImgPath);
			$NewImage = imagecreatetruecolor($iSizeW, $iSizeH);
			imagecopyresampled($NewImage, $OrgImage, 0, 0, 0, 0, $iSizeW, $iSizeH, imagesx($OrgImage), imagesy($OrgImage));
			imagejpeg($NewImage, $sNewImgPath);
		} elseif ($sExt == 'gif' || $sExt == 'GIF') {
			$OrgImage = imagecreatefromgif($sOrgImgPath);
			$NewImage = imagecreatetruecolor($iSizeW, $iSizeH);
			imagecopyresampled($NewImage, $OrgImage, 0, 0, 0, 0, $iSizeW, $iSizeH, imagesx($OrgImage), imagesy($OrgImage));
			imagegif($NewImage, $sNewImgPath);
		} elseif ($sExt == 'png' || $sExt == 'PNG') {
			$OrgImage = imagecreatefrompng($sOrgImgPath);
			$NewImage = imagecreatetruecolor($iSizeW, $iSizeH);
			imagecopyresampled($NewImage, $OrgImage, 0, 0, 0, 0, $iSizeW, $iSizeH, imagesx($OrgImage), imagesy($OrgImage));
			imagepng($NewImage, $sNewImgPath);
		} elseif ($sExt == 'bmp' || $sExt == 'BMP') {
			$OrgImage = imagecreatefromwbmp($sOrgImgPath);
			$NewImage = imagecreatetruecolor($iSizeW, $iSizeH);
			imagecopyresampled($NewImage, $OrgImage, 0, 0, 0, 0, $iSizeW, $iSizeH, imagesx($OrgImage), imagesy($OrgImage));
			imagewbmp($NewImage, $sNewImgPath);
		} else {
			copy($sOrgImgPath, $sNewImgPath);
		}
	} // end function
	/**
	*	----------------------------------------
	*	- Advice - 썸네일(Thumbnail) 생성 함수 종료
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - 원본 파일 복사 경로 체크 후 없을 경우 생성
	*	----------------------------------------
	*/
	if (!is_dir($copyPath)) {
		if (!mkdir($copyPath)) {
			$result = 0;
			$resultMsg = '1';
		}
		chmod($copyPath, 0707 );
	}
	
	/**
	*	----------------------------------------
	*	- Advice - 썸네일 이미지 경로 체크 후 없을 경우 생성
	*	----------------------------------------
	*/
	if ($result) {
		if (!is_dir($thumbnailPath)) {
			if (!mkdir($thumbnailPath)) {
				$result = 0;
				$resultMsg = '2';
			}
			chmod($thumbnailPath, 0707);
		}
	}

	
	if ($result) {
		for ($i = 0; $i < count($originFileName); $i++) {
			$oldFilePath = $originUrl . $originFileName[$i];			// 원본 상품 이미지 경로
			$newFilePath = $copyPath . $originFileName[$i];				// 복사 상품 이미지 경로
			$newThumbnailFilePath = $thumbnailPath . $newFileName[$i];	// 썸네일 상품 이미지 경로
			
			/**
			*	----------------------------------------
			*	- Advice - 원본 파일 복사 시작
			*	----------------------------------------
			*/
			if (!file_exists($newFilePath)) {		// 복사되어진 원본 파일이 없다면
				if (copy($oldFilePath, $newFilePath)) {	// 생성시작
					chmod($newFilePath, 0707);	// 권한 변경
				}
				else {		// 복사 실패시
					$result = 0;	// 복사 실패 결과 
					$arrayThumbFailFile[] = $oldFilePath . ' => ' . $newFilePath;	// 복사 실패 내역 등록

					$resultMsg = '3';	// 복사실패 메시지 코드 삽입
				}
			}
			/**
			*	----------------------------------------
			*	- Advice - 원본 파일 복사 끝
			*	----------------------------------------
			*/

			/**
			*	----------------------------------------
			*	- Advice - 원본 파일 썸네일 작업 시작
			*	----------------------------------------
			*/
			if ($result) {
				$sizeInfo = getImageSize($newFilePath);		// 이미지 사이즈 체크
				list($changeWidthSize, $changeHeightSize) = fileSizeChange($sizeInfo[0], $sizeInfo[1], $changeSize, $whFlag);
				
				if ($sizeInfo[0] >= $changeWidthSize) {
					
					if (!file_exists($newThumbnailFilePath)) {
						
						$arrayFileName = explode('.', $originFileName[$i]);
						createThumbnail($newFilePath, $newThumbnailFilePath, $changeWidthSize, $changeHeightSize, $arrayFileName[count($arrayFileName)-1]);
						chmod($newThumbnailFilePath, 0707);

						if (!file_exists($newThumbnailFilePath)) {
							$result = 0;
							$arrayThumbFailFile[] = $newFilePath . ' => ' . $newThumbnailFilePath;
							
							$resultMsg = '4';
						}
					}
				}
			}

			/**
			*	----------------------------------------
			*	- Advice - 원본 파일 썸네일 작업 끝
			*	----------------------------------------
			*/
		}
		
		if (!empty($selectFileName)) {
			foreach ($selectFileName as $selectFile) {
				$oldFilePath = $originUrl . $selectFile;			// 원본 상품 이미지 경로
				$newFilePath = $copyPath . $selectFile;				// 복사 상품 이미지 경로

				if (!file_exists($newFilePath)) {		// 복사되어진 원본 파일이 없다면
					if (@copy($oldFilePath, $newFilePath)) {	// 생성시작
						chmod($newFilePath, 0707);	// 권한 변경
					}
					else {		// 복사 실패시
						$result = 0;	// 복사 실패 결과 
						$arrayThumbFailFile[] = $oldFilePath . ' => ' . $newFilePath;	// 복사 실패 내역 등록

						$resultMsg = '5';	// 복사실패 메시지 코드 삽입
					}
				}
			}
		}
	}
	
	/**
	*	----------------------------------------
	*	- Advice - return Json형 데이터 생성
	*	----------------------------------------
	*/
	$jsons .= '"result":' . $result . ',"resultMsg":"' . $resultMsg . '"';

	if (!empty($arrayThumbFailFile)) {
		$jsons .= ',"failData":{';
		$failCount = 0;
		foreach ($arrayThumbFailFile as $thumbFailFile) {
			if ($failCount) $jsons .= ',';
			$jsons .= '"failNumber' . $failCount . '":"' . $thumbFailFile . '"';
			$failCount++;
		}
		$jsons .= '}';
	}

	/**
	*	----------------------------------------
	*	- Advice - return Json형 데이터 생성 끝
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - return
	*	----------------------------------------
	*/
	echo $callback . '({'.$jsons.'})';

/** 
* Date = 개발 작업일(2014.07.23)
* ETC = 상품 이미지 썸네일 작업 진행 프로세스
* Developer = 한영민
*/
?>