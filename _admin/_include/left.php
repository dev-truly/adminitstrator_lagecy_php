<?php
	
	/* ------------------------------------------------
	*	- 도움말 - 메뉴 코드 in절 형태로 변환
	*  ------------------------------------------------
	*/
	
	$menuCode = admintool_class_load('code');
	$menuCode->setCode(explode('|', $_SESSION['sess']['aMenuCode']), 'menu');
	$menuCodeIn = $menuCode->getIn();

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$dataString = admintool_class_load('string');

	$dataString->addItem('m_view_flag', 'y', 'C');
	$dataString->addItem('m_use_flag', 'y', 'C');
	$dataString->addItem('m_delete_flag', 'n', 'C');
	$dataString->addItem('m_code', $menuCodeIn, 'IN');


	// 메뉴 추출
	$memuQuery = "Select * From 
						" . GD_MENU . " 
					" . $dataString->getWhere() . " 
					or (m_code = 'member' and m_parent = 0) or (m_code = 'member' and m_url like '/_systool/member/%' and m_view_flag = 'y')
					Order By m_parent, m_sort asc
				";

	$menuResult = $db->query($memuQuery);

	
?>
<link rel="StyleSheet" type="text/css" href="/_admin/_css/wtree.css"/>
<script language="javascript" src="/_admin/_js/wtree.js"></script>
<style type="text/css">
.Wtreebody {
	width:100%;
	height:100%;
	overflow-X:auto;
	overflow-Y:visible ;
	border:solid 1px #A1A1A1;
	padding: 5px;
}
.WTree img {
	border: 0px;
	vertical-align: middle;
}
.WTree a {
	color: #333;
	text-decoration: none;
}
.WTree a.node, .WTree a.nodeSel {
	white-space: nowrap;
	padding: 1px 2px 1px 2px;
}
.WTree a.node:hover, .WTree a.nodeSel:hover {
	color: #333;
	text-decoration: underline;
}
.WTree a.nodeSel {
	background-color: #c0d2ec;
}
.WTree .clip {
	overflow: hidden;
}
.WTree {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #666;
	white-space: nowrap;
	padding: 10px 0 10px 10px;	
}
.WTreeNode {
	margin-top: 3px;
}
</style>


<SCRIPT LANGUAGE="JavaScript">


function LoadMenu(fthis)
{
	var obj = MenuiWtree.getSelectedObject();
	if(obj == null)
		return;
	var bArray = obj.mButton.split(",");
	var Len = bArray.length;
	if(Len != 7)
		bArray = new Array("N","N","N","N","N","N","N");

	Tabmenu.add(new _TabMenuClass(obj.mSubject,obj.mIndex,obj.mUrl,bArray[0],bArray[1],bArray[2],bArray[3],bArray[4],bArray[5],bArray[6],obj.mIndex));


}

	var MenuiWtree = new WTree('MenuiWtree');
	MenuiWtree.config.folderLinks = false;
	Tabmenu.m_TreeObj = MenuiWtree;

	MenuiWtree.add(0,-1,'Godo Relocation Admin','');
	<? while ($menuRow = $db->fetch($menuResult)) {
			$mIndex			= $menuRow['m_index'];
			$mSubject		= $menuRow['m_subject'];
			$mUrl			= $menuRow['m_url'];
			$mParent		= $menuRow['m_parent'];
			$mSort			= $menuRow['m_sort'];
			$mViewFlag		= $menuRow['m_view_flag'];
			$mUseFlag		= $menuRow['m_use_flag'];
			$mLinkType		= $menuRow['m_link_type'];
			$mWidth			= $menuRow['m_width'];
			$mHeight		= $menuRow['m_height'];
			$mLogin			= $menuRow['m_login'];
			$mButton		= $menuRow['m_button'];

			if ($authRow['ma_member_auth'] == 'n' && ereg('/member/', $mUrl)) continue;
			if ($authRow['ma_authority_auth'] == 'n' && ereg('/auth/', $mUrl)) continue;
	?>
	MenuiWtree.add(<?=$mIndex?>,<?=$mParent?>,'<?=$mSubject?>','javascript:LoadMenu(this)','<?=$mSubject?>','','','',false, {mIndex :"<?=$mIndex?>",mSubject :"<?=$mSubject?>",mUrl :"<?=$mUrl?>",mParent :"<?=$mParent?>",mSort :"<?=$mSort?>",mViewFlag :"<?=mViewFlag?>",mUseFlag :"<?=$mUseFlag?>",mLinkType :"<?=$mLinkType?>",mWidth :"<?=$mWidth?>",mHeight :"<?=$mHeight?>",mLogin :"<?=$mLogin?>",mButton :"<?=$mButton?>"});
	<?php 
		}
	?>
	
</SCRIPT>
			
						<ul>
							<a href="javascript: MenuiWtree.openAll();">open all</a> | <a href="javascript: MenuiWtree.closeAll();">close all</a>
								<script type="text/javascript">
									<!--
									document.write(MenuiWtree);
									//-->
								</script>
						</ul>