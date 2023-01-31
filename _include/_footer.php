		</div>
		<div id="footer">
			<ul>
				<li style="padding:10px 0 0 0; text-align:center;">Copyright 2015  Relocation Administrator </li>
			</ul>
		</div>
		<script type="text/javascript">
			var default_on = '';
			$jq('#menuCategory li').each(function(){if($jq(this).hasClass('on')){default_on = $jq('#menuCategory li').index(this);}});
			$jq(document).ready(function(){
				var reset_Menu ='';
				$jq('#menuCategory li').mouseover(function(){
					$jq('#menuCategory li').removeClass('on');
					$jq(this).addClass('on');
					clearInterval(reset_Menu);
				}).css('cursor','pointer');
				
				$jq('#menuCategory li').mouseout(function(){
					reset_Menu = setInterval(function(){
						$jq('#menuCategory li').removeClass('on');
						$jq('#menuCategory li:eq('+default_on+')').addClass('on');
					},500);
				});
			});

		</script>
	</body>
</html>
