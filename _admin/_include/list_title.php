				<h1 class="frame-title"><?=$menuSubject?> <span><?=$menuExplain?></span></h1>
					<form name="frmMain" action="<?=$_SERVER['PHP_SELF']?>" method="get">
						<div class="dataTablediv board-writeDiv">
							<div class="roundBox" id="type1">
								<p class="bul"> 전체 총 : <?=($pg->recode['total']) ? $pg->recode['total'] : 0 ?>건 검색 되었습니다.</p>