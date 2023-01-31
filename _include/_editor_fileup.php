<? 
	include("../../shop/lib/library.php");
	include("../../relocation_admin/_inc/lib.func.php");
//-----------------------------------------------------
//- Advice - 넘어오는 값
//-----------------------------------------------------
$ef_parent_idx = $_GET["ef_parent_idx"];
$ef_parent_type = $_GET["ef_parent_type"];

//-----------------------------------------------------
//- Advice - 초기화
//-----------------------------------------------------
if(!$ef_parent_idx || $ef_parent_idx == '') $ef_parent_idx = 0;
$SaveFolder = $ef_parent_type;

$DefaultFilePath = '/relocation_admin/editor_img/'.$SaveFolder;

//-----------------------------------------------------
//- Advice - 검색 문자 초기화
//-----------------------------------------------------
$C_Str='';
$C_Str = class_load('string');
$C_Str->AddItem('ef_parent_idx',$ef_parent_idx,'I');
$C_Str->AddItem('ef_parent_type',$ef_parent_type,'C');

$SelWhere = '';
$SelWhere = $C_Str->GetWhere();

//-----------------------------------------------------
//- Advice - 저장된 이미지 검색
//-----------------------------------------------------
$SelSql = " select ef_idx,ef_file_type,ef_file_size,ef_new_name,ef_ori_name from n_e_file ".$SelWhere;
$SelSql = $SelSql." order by ef_IDX desc ";

$res = $db->query($SelSql);
?>