<?php
	
	ini_set("memory_limit","2048M");
    ini_set('max_execution_time', 2048);
	//�Ϲ������� ���µ� ���� ������ gd ���� ���� �߻��Ͽ� �̹��������� �ȵȴ�.
	//jpeg ������ �Ϲ������� ���µ� ���� ������ �ջ�� �����̰ų� CYMK �� ��� ���� �߻��Ͽ� ���� ���� ó�� ���ش�.
	@ini_set('gd.jpeg_ignore_warning', 1);
    setlocale(LC_CTYPE, "ko_KR.eucKR");
	
	/**
	*	----------------------------------------
	*	- Advice - �Ѿ���� ��
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
	*	- Advice - �Ѿ���� �� ����
	*	----------------------------------------
	*/

	/**
	*	----------------------------------------
	*	- Advice - ��� ���� ����
	*	----------------------------------------
	*/
	$copyPath		= './copyFileStorage/' . str_replace('.', '_', $originUrl) . '/';	// �������� ���� ���
	$thumbnailPath	= $copyPath . 't/';													// ����� ���� ���� ���
	$originUrl		= 'http://' . $originUrl . '/shop/data/goods/';						// ���� ���θ� ��ǰ �̹��� ���
	
	$jsons = '';					// json �� ������ ���� ����
	$result				= true;		// ���μ��� ���
	$resultMsg			= '0';		// ���μ��� MSG Code
	$arrayThumbFailFile	= array();	// ����� �۾� ���� ���� ���� ���� �迭

	/**
	*	----------------------------------------
	*	- Advice - ��� ���� ���� ����
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - ���� ������ ��ȯ �Լ� 
	*	- in - (���λ�����, ���λ�����, ��ȯ������, ���μ��� ����)
	*	- out - (���氡�λ�����, ���漼�λ�����)
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
	*	- Advice - ���� ������ ��ȯ �Լ� ����
	*	----------------------------------------
	*/

	/**
	*	----------------------------------------
	*	- Advice - �����(Thumbnail) ���� �Լ� 
	*	- in - (�����̹������, �������̹������, ������ǰ��λ�����, ������Ǽ��λ�����, Ȯ���ڸ�)
	*	----------------------------------------
	*/
	function createThumbnail($sOrgImgPath, $sNewImgPath, $iSizeW, $iSizeH, $sExt)
	{
			
		// Ȯ���ڿ� ���� �ٸ� �����Լ��� ���� ����� ����
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
	*	- Advice - �����(Thumbnail) ���� �Լ� ����
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - ���� ���� ���� ��� üũ �� ���� ��� ����
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
	*	- Advice - ����� �̹��� ��� üũ �� ���� ��� ����
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
			$oldFilePath = $originUrl . $originFileName[$i];			// ���� ��ǰ �̹��� ���
			$newFilePath = $copyPath . $originFileName[$i];				// ���� ��ǰ �̹��� ���
			$newThumbnailFilePath = $thumbnailPath . $newFileName[$i];	// ����� ��ǰ �̹��� ���
			
			/**
			*	----------------------------------------
			*	- Advice - ���� ���� ���� ����
			*	----------------------------------------
			*/
			if (!file_exists($newFilePath)) {		// ����Ǿ��� ���� ������ ���ٸ�
				if (copy($oldFilePath, $newFilePath)) {	// ��������
					chmod($newFilePath, 0707);	// ���� ����
				}
				else {		// ���� ���н�
					$result = 0;	// ���� ���� ��� 
					$arrayThumbFailFile[] = $oldFilePath . ' => ' . $newFilePath;	// ���� ���� ���� ���

					$resultMsg = '3';	// ������� �޽��� �ڵ� ����
				}
			}
			/**
			*	----------------------------------------
			*	- Advice - ���� ���� ���� ��
			*	----------------------------------------
			*/

			/**
			*	----------------------------------------
			*	- Advice - ���� ���� ����� �۾� ����
			*	----------------------------------------
			*/
			if ($result) {
				$sizeInfo = getImageSize($newFilePath);		// �̹��� ������ üũ
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
			*	- Advice - ���� ���� ����� �۾� ��
			*	----------------------------------------
			*/
		}
		
		if (!empty($selectFileName)) {
			foreach ($selectFileName as $selectFile) {
				$oldFilePath = $originUrl . $selectFile;			// ���� ��ǰ �̹��� ���
				$newFilePath = $copyPath . $selectFile;				// ���� ��ǰ �̹��� ���

				if (!file_exists($newFilePath)) {		// ����Ǿ��� ���� ������ ���ٸ�
					if (@copy($oldFilePath, $newFilePath)) {	// ��������
						chmod($newFilePath, 0707);	// ���� ����
					}
					else {		// ���� ���н�
						$result = 0;	// ���� ���� ��� 
						$arrayThumbFailFile[] = $oldFilePath . ' => ' . $newFilePath;	// ���� ���� ���� ���

						$resultMsg = '5';	// ������� �޽��� �ڵ� ����
					}
				}
			}
		}
	}
	
	/**
	*	----------------------------------------
	*	- Advice - return Json�� ������ ����
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
	*	- Advice - return Json�� ������ ���� ��
	*	----------------------------------------
	*/
	
	/**
	*	----------------------------------------
	*	- Advice - return
	*	----------------------------------------
	*/
	echo $callback . '({'.$jsons.'})';

/** 
* Date = ���� �۾���(2014.07.23)
* ETC = ��ǰ �̹��� ����� �۾� ���� ���μ���
* Developer = �ѿ���
*/
?>