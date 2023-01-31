/***********************정규식 및 validate name setting************************/
/*																																			*/
/*																									2011.03.24 Sunny			*/
/***********************************************************************/

var bgcolor = "#fde9e1";		//미입력한  input 또는 형식이 틀린 input에 배경색을 넣음    색 안넣길 원하면 #fffff로 세팅
//var bgimage1 = "alert_message1.jpg"; 	//미입력한  input 에 배경이미지을 넣음 
//var bgimage2 = "alert_message2.jpg";	//형식이 틀린 input에 배경이미지을 넣음 

function fnc_validate(validate){
var protoType =new Array( 
									new Array('num',/^\d{1,}$/),		//숫자만
									new Array('num3',/^\d{3,}$/),		//숫자만3개 이상
									new Array('email',/^[a-zA-Z]{1,}[0-9a-zA-Z_-]{1,}[.]{0,1}[0-9a-zA-Z_-]{1,}[@][0-9a-zA-Z_-]{1,}[.][\/.0-9a-zA-Z_-]{1,}[0-9a-zA-Z_-]{1,}$/),  //이메일 전체주소
									new Array('email2',/[0-9a-zA-Z_-]{1,}[.][\/.0-9a-zA-Z_-]{1,}[0-9a-zA-Z_-]{1,}$/),	//이메일 뒷주소
									new Array( 'alpha',/^[A-Za-z]{1,}$/ ), //영문만
									new Array('ip' ,/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/) , //ip형식
									new Array('kor',/^[가-힣]*$/ ),	 //한글만 입력
									new Array( 'alphanum',/^[\w|!@#$%^&*\(\)\?\<\>\[\]\{\}\_\-\:\,\.\/.0-9a-zA-Z_-]{0,}(\b.*\b|){0,}$/ ),  //영문과 숫자만 입력
									new Array( 'pwd',/^[a-zA-Z0-9_]{5,20}$/),  //영문과 숫자 5~20자리
									new Array( 'jumin1',/[0-9]{2}(0[1-9]|1[012])(0[1-9]|1[0-9]|2[0-9]|3[01])$/ ),	//주민번호 앞자리
									new Array( 'jumin2',/[1-4][0-9]{6}$/  ),	//주민번호 뒷자리
									new Array('name',/^[가-힣]{2,}$/ )    //2글자 이상 한글

	);//ae

/*****************NULL체크 및 정규식 검사************************/	
	for(i = 0; i < validate.length; i++){
		validBox = validate.filter(':eq(' + i +')');
		
		validOption = validBox.attr('validate');
		require= validBox.attr('require');  //필수 입력 여부
		msg= validBox.attr('msg');  //필수 입력 여부
		
		validOption = validOption.split(',');
		validOption[0] = validOption[0].split('-');
		for(j = 0; j < validOption[0].length; j++){
		// 필수 입력 이라면  //

		if(require=="on"){
			if($.trim(validBox.val()).length == 0){
					alert(msg + '을(를) 입력하세요',validBox);
					$(validBox).css('background-color',bgcolor);
					resetFocus(validBox) 
						//$(validBox).css('background-image','url("'+bgimage1+'")');
					return false;
				}else{
				$(validBox).css('background-color','#ffffff');
				//$(validBox).css('background-image','none');
				}
				for(k=0;k<protoType.length;k++){
					if(validOption[0][j] == protoType[k][0]){
						var regExp=protoType[k][1];
						if( !regExp.test($.trim(validBox.val()))  ){
							alert("올바른 " + msg+"(을)를 입력하세요",validBox);
							$(validBox).css('background-color',bgcolor);
							//$(validBox).css('background-image','url("'+bgimage2+'.jpg")');
							resetFocus(validBox) 
							return false;
						}else{
						$(validBox).css('background-color','#ffffff');
						//$(validBox).css('background-image','none');
						}
					}
				 }
			}
			/// 필수 입력은 아니지만 정규식은 체크할 경우 //
			else{
				if($.trim(validBox.val()) != ""){
				for(k=0;k<protoType.length;k++){
					if(validOption[0][j] == protoType[k][0]){
						var regExp=protoType[k][1];
						if( !regExp.test($.trim(validBox.val()))  ){
							alert("올바른 " + msg +"(을)를 입력하세요",validBox);
							$(validBox).css('background-color',bgcolor);
							//$(validBox).css('background-image','url("'+bgimage2+'")');
							resetFocus(validBox) 
							return false;
						}else{
						$(validBox).css('background-color','#ffffff');
						//$(validBox).css('background-image','none');
						}
					}
				 }
				}

			}
		}
	}
}// fe

/******************* mask 적용 *************************/
function fnc_type(vtype){
	var maskType =new Array( 
			new Array('year',"9999"), 
			new Array('yearmd',"9999-99-99"), 
			new Array('phone',"999-9999-9999"), 
			new Array('cm',"999.9") ,
			new Array('num3',"999"),
			new Array('jumin1',"999999"),
			new Array('jumin2',"9999999")
		);//ae
	
	for(i = 0; i < vtype.length; i++){
			validBox = vtype.filter(':eq(' + i +')');
			vtype2= validBox.attr('vtype');  //필수 입력 여부
			for(k=0;k<maskType.length;k++){
				if (vtype2 != 'phone') {
					if(vtype2 ==maskType[k][0]){
						$(validBox).mask(maskType[k][1]);
					}
				}
				else {
					$(validBox).change(function(e){
						$(this).val($(this).val().replace('-', ''));
						if ($(this).val().length <= 9) {
							//alert($(this).val()+e.keycode);
							$(this).mask('99-999-9999');							
						} 
						else if($(this).val().length == 10) {
							if ($(this).val().substring(0,2) == '02') {
								$(this).mask('99-9999-9999');
							}
							else {
								$(this).mask('999-999-9999');
							}
						}
						else if($(this).val().length == 11) {
							$(this).mask('999-9999-9999');
						}
					})
				}
			}	
	}
}//fe



/************** 이메일주소 출력및 선택입력 ***************/
function func_changeMail(changeMail){

var mailList = new Array(
	'hanmail.net',
	'naver.com',
	'nate.com',
	'hotmail.com',
	'yahoo.co.kr',
	'paran.com',
	'empas.com',
	'dreamwiz.com',
	'lycos.co.kr',
	'korea.com',
	'gamil.com',
	'hanmir.com'
	);

	for(i = 0; i < changeMail.length; i++){
		validBox = changeMail.filter(':eq(' + i +')');
		$('<option value="">직접입력</option>').appendTo(validBox);
		for(k=0;k<mailList.length;k++){
			$('<option value='+mailList[k]+'>'+mailList[k]+'</option>').appendTo(validBox);
		}
	}
		//validBox.css('width','120px');


}//fe




/*********핸드폰번호 **********************/
function func_cell(hpSel){

	var hpList = new Array(
	'010',
	'011',
	'016',
	'018',
	'019'	
	);
		for(i = 0; i < hpSel.length; i++){
		validBox = hpSel.filter(':eq(' + i +')');
		for(k=0;k<hpList.length;k++){
			$('<option value='+hpList[k]+'>'+hpList[k]+'</option>').appendTo(validBox);
		}
		//validBox.css('width','60px');
	}
}//fe

/*********전화번호 **********************/
function func_tel(telSel){

	var telList = new Array(
	'02',
	'031',
	'032',
	'033',
	'041',
	'042',
	'043',
	'051',
	'052',
	'053',
	'054',
	'055',
	'061',
	'062',
	'063',
	'064'
	);
		for(i = 0; i < telSel.length; i++){
		validBox = telSel.filter(':eq(' + i +')');
		for(k=0;k<telList.length;k++){
			$('<option value='+telList[k]+'>'+telList[k]+'</option>').appendTo(validBox);
		}
		//validBox.css('width','60px');
	}
}//fe
/********가입경로 [알게된 경로] *****************/


function func_routeSel(routeSel){
//name,value
var routeList = new Array(
	new Array('잡지/신문 광고',"잡지/신문 광고"), 
	new Array('TV/RADIO광고',"TV/RADIO광고"), 
	new Array('지인소개',"지인소개"), 
	new Array('전단지',"전단지"), 
	new Array('제휴사 링크',"제휴사 링크"), 
	new Array('기타',"기타")
);
	for(i = 0; i < routeSel.length; i++){
		validBox = routeSel.filter(':eq(' + i +')');
		for(k=0;k<routeList.length;k++){
			$('<option value='+routeList[k][1]+'>'+routeList[k][0]+'</option>').appendTo(validBox);
		}
	}
	//validBox.css('width','160px');
}//fe


/********* 지역(국내) ********************/


function func_areaSel(areaSel){
//name,value
var areaList = new Array(
			new Array('서울',"02"), 
			new Array('경기',"031"),
			new Array('인천',"032"), 
			new Array('강원',"033"),
			new Array('부산',"041"), 
			new Array('대구',"042"),
			new Array('울산',"042"), 
			new Array('광주',"051"),
			new Array('대전',"052"), 
			new Array('충북',"053"), 
			new Array('충남',"054"),
			new Array('전남',"055"),
			new Array('전북',"061"),
			new Array('경남',"062"), 
			new Array('경북',"063"), 
			new Array('제주',"064")
);
for(i = 0; i < areaSel.length; i++){
		validBox = areaSel.filter(':eq(' + i +')');
		for(k=0;k<areaList.length;k++){
			$('<option value='+areaList[k][1]+'>'+areaList[k][0]+'</option>').appendTo(validBox);
		}
	}
	//validBox.css('width','60px');



}//fe

/************** 이메일주소 출력및 선택입력 ***************/
function func_changeQuestion(changeQuestion){

var QuestionList = new Array(
	'나의보물1호는?',
	'졸업한초등학교의이름은?',
	'나만아는신체비밀?'
	);

	for(i = 0; i < changeQuestion.length; i++){
		validBox = changeQuestion.filter(':eq(' + i +')');
		$('<option value="">직접입력</option>').appendTo(validBox);
		for(k=0;k<QuestionList.length;k++){
			$('<option value='+QuestionList[k]+'>'+QuestionList[k]+'</option>').appendTo(validBox);
		}
	}
	//validBox.css('width','200px');

}//fe




/************** validate 어트리뷰트 세팅**********************/
function insertAttr(numsetting){
	
	for(i=0;i<numsetting.length;i++){
		valiname = numsetting[i][0];

		 validOption = numsetting[i][1].split(',');
		 for (j=0;j<validOption.length;j++ ){
			     option = validOption[j].split('=');
				 for(k=0;k<option.length;k=k+2){
					  option[k+1]=option[k+1].replace("'","");
					  option[k+1]=option[k+1].replace("'","");
					 $('#'+valiname).attr(option[k],option[k+1]);
				 }
		 }

	}
}//fe





//체크박스 선택 갯수검사
function checkCount(chName,min,max,msg){
	var check = $('input[name='+chName+']');
	var count = 0;
	check.each(function(){
		if( $(this).is(':checked') ==true ){
		count++;
		}	
	 })
   if(count<min || count>max  ){
		 alert( msg +"는(은) "+ min+"개 이상 "+max+"개 이하로 선택해주세요");
	return false;
	}
} //fe


function resetFocus(validBox){
		validBox.val('');
		validBox.focus();
		return false;
}//fe


function func_require(requireCheck){
	for(i = 0; i < requireCheck.length; i++){
	validBox = requireCheck.filter(':eq(' + i +')');
	//$(validBox).css('background-image','url("'+bgimage1+'")');
	}

}//fe


/// <reference path="../../../lib/jquery-1.2.6.js" />
/*
	Masked Input plugin for jQuery
	Copyright (c) 2007-2009 Josh Bush (digitalbush.com)
	Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license) 
	Version: 1.2.2 (03/09/2009 22:39:06)
*/


(function($) {
// jquery-ui.css 의 테마를 변경해서 사용할 수 있습니다.
// base, black-tie, blitzer, cupertino, dark-hive, dot-luv, eggplant, excite-bike, flick, hot-sneaks, humanity, le-frog, mint-choc, overcast, pepper-grinder, redmond, smoothness, south-street, start, sunny, swanky-purse, trontastic, ui-darkness, ui-lightness, vader
// 아래 css 는 date picker 의 화면을 맞추는 코드입니다.

	var pasteEventName = ($.browser.msie ? 'paste' : 'input') + ".mask";
	var iPhone = (window.orientation != undefined);

	$.mask = {
		//Predefined character definitions
		definitions: {
			'9': "[0-9]",
			'a': "[A-Za-z]",
			'*': "[A-Za-z0-9]"
		}
	};


	$.fn.extend({
		//Helper Function for Caret positioning
		caret: function(begin, end) {
			if (this.length == 0) return;
			if (typeof begin == 'number') {
				end = (typeof end == 'number') ? end : begin;
				return this.each(function() {
					if (this.setSelectionRange) {
						this.focus();
						this.setSelectionRange(begin, end);
					} else if (this.createTextRange) {
						var range = this.createTextRange();
						range.collapse(true);
						range.moveEnd('character', end);
						range.moveStart('character', begin);
						range.select();
					}
				});
			} else {
				if (this[0].setSelectionRange) {
					begin = this[0].selectionStart;
					end = this[0].selectionEnd;
				} else if (document.selection && document.selection.createRange) {
					var range = document.selection.createRange();
					begin = 0 - range.duplicate().moveStart('character', -100000);
					end = begin + range.text.length;
				}
				return { begin: begin, end: end };
			}
		},
		unmask: function() { return this.trigger("unmask"); },
		mask: function(mask, settings) {
			if (!mask && this.length > 0) {
				var input = $(this[0]);
				var tests = input.data("tests");
				return $.map(input.data("buffer"), function(c, i) {
					return tests[i] ? c : null;
				}).join('');
			}
			settings = $.extend({
				placeholder: "_",
				completed: null
			}, settings);

			var defs = $.mask.definitions;
			var tests = [];
			var partialPosition = mask.length;
			var firstNonMaskPos = null;
			var len = mask.length;

			$.each(mask.split(""), function(i, c) {
				if (c == '?') {
					len--;
					partialPosition = i;
				} else if (defs[c]) {
					tests.push(new RegExp(defs[c]));
					if(firstNonMaskPos==null)
						firstNonMaskPos =  tests.length - 1;
				} else {
					tests.push(null);
				}
			});

			return this.each(function() {
				var input = $(this);
				var buffer = $.map(mask.split(""), function(c, i) { if (c != '?') return defs[c] ? settings.placeholder : c });
				var ignore = false;  			//Variable for ignoring control keys
				var focusText = input.val();

				input.data("buffer", buffer).data("tests", tests);

				function seekNext(pos) {
					while (++pos <= len && !tests[pos]);
					return pos;
				};

				function shiftL(pos) {
					while (!tests[pos] && --pos >= 0);
					for (var i = pos; i < len; i++) {
						if (tests[i]) {
							buffer[i] = settings.placeholder;
							var j = seekNext(i);
							if (j < len && tests[i].test(buffer[j])) {
								buffer[i] = buffer[j];
							} else
								break;
						}
					}
					writeBuffer();
					input.caret(Math.max(firstNonMaskPos, pos));
				};

				function shiftR(pos) {
					for (var i = pos, c = settings.placeholder; i < len; i++) {
						if (tests[i]) {
							var j = seekNext(i);
							var t = buffer[i];
							buffer[i] = c;
							if (j < len && tests[j].test(t))
								c = t;
							else
								break;
						}
					}
				};

				function keydownEvent(e) {
					var pos = $(this).caret();
					var k = e.keyCode;
					ignore = (k < 16 || (k > 16 && k < 32) || (k > 32 && k < 41));

					//delete selection before proceeding
					if ((pos.begin - pos.end) != 0 && (!ignore || k == 8 || k == 46))
						clearBuffer(pos.begin, pos.end);

					//backspace, delete, and escape get special treatment
					if (k == 8 || k == 46 || (iPhone && k == 127)) {//backspace/delete
						shiftL(pos.begin + (k == 46 ? 0 : -1));
						return false;
					} else if (k == 27) {//escape
						input.val(focusText);
						input.caret(0, checkVal());
						return false;
					}
				};

				function keypressEvent(e) {
					if (ignore) {
						ignore = false;
						//Fixes Mac FF bug on backspace
						return (e.keyCode == 8) ? false : null;
					}
					e = e || window.event;
					var k = e.charCode || e.keyCode || e.which;
					var pos = $(this).caret();

					if (e.ctrlKey || e.altKey || e.metaKey) {//Ignore
						return true;
					} else if ((k >= 32 && k <= 125) || k > 186) {//typeable characters
						var p = seekNext(pos.begin - 1);
						if (p < len) {
							var c = String.fromCharCode(k);
							if (tests[p].test(c)) {
								shiftR(p);
								buffer[p] = c;
								writeBuffer();
								var next = seekNext(p);
								$(this).caret(next);
								if (settings.completed && next == len)
									settings.completed.call(input);
							}
						}
					}
					return false;
				};

				function clearBuffer(start, end) {
					for (var i = start; i < end && i < len; i++) {
						if (tests[i])
							buffer[i] = settings.placeholder;
					}
				};

				function writeBuffer() { return input.val(buffer.join('')).val(); };

				function checkVal(allow) {
					//try to place characters where they belong
					var test = input.val();
					var lastMatch = -1;
					for (var i = 0, pos = 0; i < len; i++) {
						if (tests[i]) {
							buffer[i] = settings.placeholder;
							while (pos++ < test.length) {
								var c = test.charAt(pos - 1);
								if (tests[i].test(c)) {
									buffer[i] = c;
									lastMatch = i;
									break;
								}
							}
							if (pos > test.length)
								break;
						} else if (buffer[i] == test[pos] && i!=partialPosition) {
							pos++;
							lastMatch = i;
						} 
					}
					if (!allow && lastMatch + 1 < partialPosition) {
						input.val("");
						clearBuffer(0, len);
					} else if (allow || lastMatch + 1 >= partialPosition) {
						writeBuffer();
						if (!allow) input.val(input.val().substring(0, lastMatch + 1));
					}
					return (partialPosition ? i : firstNonMaskPos);
				};

				if (!input.attr("readonly"))
					input
					.one("unmask", function() {
						input
							.unbind(".mask")
							.removeData("buffer")
							.removeData("tests");
					})
					.bind("focus.mask", function() {
						focusText = input.val();
						var pos = checkVal();
						writeBuffer();
						setTimeout(function() {
							if (pos == mask.length)
								input.caret(0, pos);
							else
								input.caret(pos);
						}, 0);
					})
					.bind("blur.mask", function() {
						checkVal();
						if (input.val() != focusText)
							input.change();
					})
					.bind("keydown.mask", keydownEvent)
					.bind("keypress.mask", keypressEvent)
					.bind(pasteEventName, function() {
						setTimeout(function() { input.caret(checkVal(true)); }, 0);
					});

				checkVal(); //Perform initial check for existing values
			});
		}
	});
})(jQuery);


$(function(){

		
			
//내용이 있는경우 input의 배경을 없앤다. 
$('input').keydown(function(){
	if(  $(this).val()!=""  ){
	$(this).css('background-image','none');
	$(this).css('background-color','#ffffff');
	}
});
$('input').keypress(function(){
	if(  $(this).val()!=""  ){
	$(this).css('background-image','none');
	$(this).css('background-color','#ffffff');
	}
});



//메일선택 변경시 input 변경
$('#changeMail').change(function(){
var target = $(this).attr('changeMail');
$('#'+target).val( $(this).val()  );
});
//질문 select 변경시 질문 input 변경
$('#changeQuestion').change(function(){
var target = $(this).attr('changeQuestion');
$('#'+target).val( $(this).val()  );
});

var vtype = $('*[vtype]');
fnc_type(vtype);//mask적용 

var changeMail = $('#changeMail');
func_changeMail(changeMail); 
//메일선택적용

var hpSel = $('*[hpSel=on]');
func_cell(hpSel);
//핸드폰 번호

var telSel = $('*[telSel=on]');
func_tel(telSel);
//전화번호 국번

var routeSel = $('*[routeSel=on]');
func_routeSel(routeSel);
//가입경로


var areaSel = $('*[areaSel=on]');
func_areaSel(areaSel);
//지역[국내]


var requireCheck=$('*[require]');
func_require(requireCheck);
//필수 입력 


var changeQuestion=$('#changeQuestion');
func_changeQuestion(changeQuestion); //질문 change

})//he


function confirmJumin(jumin1, jumin2) {				// 주민등록번호 check
	var tmp = 0
	var yy = jumin1.value.substring(0,2)
	var mm = jumin1.value.substring(2,4)
	var dd = jumin1.value.substring(4,6)
	var sex = jumin2.value.substring(0,1)
	var j1 = jumin1
	var j2 = jumin2

	if ((j1.value.length != 6 ) || ( mm < 1 || mm > 12 || dd < 1) ) return false;
	if ((sex != 1 && sex !=2 && sex !=3 && sex !=4)|| (j2.value.length != 7 )) return false;

	for (var i = 0; i <=5 ; i++) {
		tmp = tmp + ((i%8+2) * parseInt(j1.value.substring(i,i+1)))
	}

	for (var i = 6; i <=11 ; i++) {
		tmp = tmp + ((i%8+2) * parseInt(j2.value.substring(i-6,i-5)))
	}

	tmp = 11 - (tmp %11);
	tmp = tmp % 10;

	if (tmp != j2.value.substring(6,7)) return false;
	return true;
}
