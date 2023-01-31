<?
	//-------------------------------------------
	//- Advice - 파일 초기화 / 업로드
	//-------------------------------------------
	$Editor_obj = $_POST['obj'];
	if($_FILES['file_upload']['name']){
		$arrFile_nm = explode(".",$_FILES['file_upload']['name']);
		$file_upload = time().".".$arrFile_nm[count($arrFile_nm)-1];

		$upload_dir = '../data/editor_img/';
		$link_dir = '/data/editor_img/';
		if(!is_dir('../data/')){
			mkdir('../data/');
			chmod('../data/',0707);
		}
		if(!is_dir($upload_dir)){
			mkdir($upload_dir);
			chmod($upload_dir,0707);
		}

		$upload_file	= $upload_dir . basename($file_upload);
		$link_file		= $link_dir . basename($file_upload);
		
		$upload_result = move_uploaded_file($_FILES['file_upload']['tmp_name'], $upload_file);
		
		if(!$upload_result){
			?>
				<script type="text/javascript">
					alert('파일 업로드가 되지 않았습니다.재 업로드 해주시기 바랍니다.');
				</script>
			<?
		}else{
			?>
				<script type="text/javascript">
					parent.AddFile("<img src='<?=$link_file?>' />");
				</script>
			<?
		}
	}

?>