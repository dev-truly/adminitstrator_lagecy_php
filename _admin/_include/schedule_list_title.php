				<link rel="stylesheet" type="text/css" href="../../_admin/_css/schedule.css">
				<script type="text/javascript" src="../../_calendar/jquery-1.3.2.js"></script>
				<script type="text/javascript" src="../../_calendar/ui/ui.core.js"></script>
				<script type="text/javascript" src="../../_calendar/ui/ui.datepicker.js"></script>
				<script type="text/javascript" src="../../_calendar/ui/ui.datepicker-ko.js"></script>
				<h1 class="frame-title"><?=$menuSubject?> <span><?=$menuExplain?></span></h1>
					<div class="dataTablediv board-writeDiv">
						<div class="roundBox" id="type1">
							<div class="selectArea">
									<ul id="calenderbtn">
										<li><a href="<?=$_SERVER['PHP_SELF']?>?year=<?=$prevYear?>&month=<?=$prevMonth?>"><img src="../../images/btn/calendar_prev.png" alt="이전 달" /></a></li>
										<li><h1><?=$year . '년 ' . $month . '월'?></h1></li>
										<li><a href="<?=$_SERVER['PHP_SELF']?>?year=<?=$nextYear?>&month=<?=$nextMonth?>"><img src="../../images/btn/calendar_next.png" alt="다음 달" /></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>