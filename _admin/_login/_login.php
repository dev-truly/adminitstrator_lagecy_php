<?
	ini_set('session.gc_maxlifetime', 36000);
	session_start();

	if($_SESSION['sess']['ruIndex']){
		header("Location: ../frame.php");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>:: Relocation Administrator Login ::</title>
	<link rel="stylesheet" type="text/css" href="../_css/default.css" />
	<script type="text/javascript" src="/_js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript">
		$jq = jQuery.noConflict();
	</script>


<style type="text/css">
	#logintitle{
		font-size:20px;
		height:30px;
		color:#000000;
		padding-bottom:10px;
	}
	.id_blur { border:#ddd 1px solid; background: transparent url(txt_id.gif) no-repeat; font-size:11px; font-family:돋움; width: 125px; height: 15px; line-height:14px; } 
	.id_focus { border:#ddd 1px solid;  color:#9e9e9e; font-size:11px; font-family:돋움; width: 125px; height: 15px; line-height:15px; } 
	.pw_blur {border:#ddd 1px solid; background: transparent url(txt_pw.gif) no-repeat; font-size:11px; font-family:돋움; width: 125px; height: 15px; line-height:15px;} 
	.pw_focus { border:#ddd 1px solid;color:#9e9e9e; width: 125px; height: 15px; font-size:10px; line-height:15px;}
</style>
</head>

<body id="login" onload="document.getElementById('ru_id').focus();"> 

<!-- login area -->
<div style="width:100%; position:absolute; top:50%; text-align:center; margin-top:-320px;">
	<div style="margin-top:20px;width:100%;text-align:center;height:30px;vertical-align:middle;">
	<h1 id="logintitle">Godo Soft Relocation Admin Login</h1><!-- <img src="/_frame/_images/com_login_logo.jpg" > -->
	</div>

	<form name="loginform" id="loginform" method="post">
		<div style="padding-top:13px; position:relative; text-align:center;">
			<div style="margin:0 auto; position:relative; background:url('/images/bg/loginForm_bg.jpg') no-repeat 0 0; width:375px; height:155px;">
					<?=$_SESSION['sess']['ruIndex']?>
					<ul style="padding:50px 0 0 0px;">
						<li style="margin-left:-30px;"><input type="text" id="ru_id"  name="ru_id"  style="width:163px; height:21px; ime-mode:disabled" tabindex="1" onFocus="this.className='id_focus'" onBlur="if ( this.value == '' ) { this.className='id_blur' }"  class='id_blur'  value=""  /></li>
						<li style="padding-top:5px; margin-left:-30px;"><input type="password" id="ru_password"  name="ru_password"  tabindex="2" maxlength="20" onFocus="this.className='pw_focus'" onBlur="if ( this.value == '' ) { this.className='pw_blur' }" class='pw_blur'  style="width:163px;height:21px; "/></li>
						<li style="padding-top:7px; margin-left:-5px; ">
							<!-- <input type="checkbox" style="border:none;"/> <img src="/_frame/_images/txt/idSave.gif" style="vertical-align:middle;"> -->
						</li>
					</ul>
					<ul style="position:absolute; top:47px; right:39px;">
						<button  class="loginSubmit" id="loginSubmit" title="로그인" onfocus="this.blur();" style="background:url(/images/btn/login_btn.gif) no-repeat 0 0;width:71px;height:56px;"></button>
					</ul>
					<ul>
						<li style="padding:68px 0 0 0; text-align:center;">Copyright ⓒ 2015 Godo Soft Relocation Admin Login Form.</li>
					</ul>
			</div>

		</div>
	</form>
</div>
<script type="text/javascript">
	function login_start(){
		var ru_id = $jq('#ru_id').val();
		var ru_password  = $jq('#ru_password').val();
		AuthUrl = "./login_ok.php";
		$jq.ajax({
			dataType:'jsonp'
			,jsonp : 'callback'
			,type: "POST"
			, url:AuthUrl														 // url
			, data: "ru_id=" + ru_id + "&ru_password=" + ru_password //넘길 파라메타 값
			, success: function(data){					
					if (!data.result) {
						alert(data.msg);
					}
					location.href=data.url;
				}
			, error: function(data){
					alert('Error');
				}
		});
	}

	$jq(document).ready(function(){
		$jq('#loginSubmit').click(function(){
			login_start();
			return false;
		});//로그인 서브밋 핸들러

		$jq('#loginform').keydown(function(evt) {
			if (evt.keyCode==13){
				login_start();
				return false;
			}
		});
	});//핸들러
	
		/* 벨리데이션 체크 추후 작업 예정
			var validate = $jq('#loginform : *[validate]');
			var validOption;

			if(fnc_validate(validate) == false){
				return false;
			}*/
</script>
</body>
</html>
