//	easy WebEditor (DHTML wysiwyg 웹에디터)
//	Beta 20061228
//	http://cafe.daum.net/easyeditor
//변수설정
var easyConfig = {
	//----------------------------------------------------
	//style
	border:"1px solid #cdcdcd",	//기본 border
	bgcolor : "#fff",			//기본 bgcolor
	font : "normal 10pt 굴림",	//기본 폰트 style(font-style, font-variant, font-weight)
	color : "#000",				//기본 폰트 컬러
	margin : "5px",				//내부 margin
	width : "99%",				//기본 width
	height : "600px",			//기본 height
	//----------------------------------------------------
	filepath : ".",	
	imgpath : "/_systool/img/easy",		//버튼 이미지경로
	over_bordercolor : "#fff",	//버튼 오버시 보더컬러
	over_bgcolor : "#ff6600",	//버튼 오버시 bgcolor
	divbtn_bgcolor : "#fff",	//버튼 영역 div bgcolor
	//----------------------------------------------------
	//기본버튼 
	Btn : null,
	BtnList : {
		font	: ["글자체","font.gif"], size : ["글자크기","size.gif"],
		undo	: ["되돌리기","undo.jpg"], redo : ["재실행","redo.jpg"],
		bold	: ["굵게","bold.jpg"], italic : ["기울리기","italic.jpg"],
		strike	: ["취소선","strike.jpg"], left : ["왼쪽 맞춤","left.jpg"],
		center	: ["가운데 맞춤","center.jpg"], right : ["오른쪽 맞춤","right.jpg"],
		justify : ["혼합정렬","justify.jpg"], clean : ["스타일 지움","clean.jpg"],
		del		: ["선택삭제","del.jpg"], color : ["글자색","color.jpg"],
		hilite	: ["글자 배경색","hilite.jpg"], link	: ["링크 삽입","link.jpg"],
		unlink	: ["링크 해제","unlink.jpg"], ul1 : ["번호달기","ul1.jpg"],
		ul2		: ["기호달기","ul2.jpg"], outdent : ["내어쓰기","outdent.jpg"],
		indent	: ["들여쓰기","indent.jpg"], hr : ["수평선 삽입","hr.gif"],
		all		: ["전체선택","selectall.jpg"], save : ["문서 저장","save.jpg"],
		sup		: ["윗첨자","sup.jpg"], sub : ["아래첨자","sub.jpg"],
		underline : ["밑줄","underline.jpg"], about : ["이지 웹에디터","about.gif"],
		//cut	: ["잘라내기","cut.gif"],copy: ["복사","copy.gif"],paste: ["붙여넣기","paste.gif"],
		source  : ["소스보기","source.gif"], bar : ["구분선","bar.gif"]
	},	
	//----------------------------------------------------
	//기본버튼템플릿 (all,simple)
	BtnTemplate  : {
		all : ["save","preview","bar","all","undo","redo","bar",
				"font","size","bar","color","hilite","bar","bold","italic",
				"underline","strike","outdent","indent","bar",
				"ul1","ul2","bar","left","center","right","justify","sup","sub","bar","clean","del","bar","hr","link",
				"unlink","bar","table","image","imageUpload","bar","source"],
		simple : ["source","bar","bold","color","hilite"]
	},
	version : "Beta",
	name : "easyWebEditor"
}
// easyEditor
function easyEditor(id)
{
	if(typeof(document.execCommand)=="undefined") return;
	easyUtil.init();
	//config설정
	this.cfg = easyConfig;
	this.cfg.preid = easyConfig.name+"_"+id;
	this.cfg.Btn = easyConfig.BtnTemplate["all"]; //기본 버튼 템플릿
	this.mode = "wysiwyg";
	this.btn = "";
	this.sel	  = null;
	this.range	  = null;
	this.sel_html = "";
	this._doc		= null;
	this._textarea	= document.getElementById(id);
	this._div		= document.createElement("div");		//전체 div
	this._divbtn	= document.createElement("div");		//버튼영역 div
	this._iframe	= document.createElement("iframe");		//iframe
	this._text		= document.createElement("textarea");	//textarea(source)
	this._div.id	= this.cfg.preid+"_div";
	this._divbtn.id	= this.cfg.preid+"_divbtn";
	this._iframe.id	= this.cfg.preid+"_iframe";
	this._text.id	= this.cfg.preid+"_text";
}
//easyEditor.init
easyEditor.prototype.init = function()
{	
	this._textarea.style.display="none";
	//source
	this._text.style.width = this.cfg.width;
	this._text.style.height= this.cfg.height;
	this._text.style.border= "none";
	this._text.style.display="none";
	this._text.style.font = "9pt 굴림";
	this._text.style.background = "#efefef url("+this.cfg.imgpath+"/source_bg.gif) 0 -2px";
	this._text.style.lineHeight = "165%";
	//iframe
	this._iframe.style.width = '100%';
	this._iframe.style.height= this.cfg.height;	
	//this._iframe.scrolling	 = "yes";
	this._iframe.frameBorder = "no";
	//this._iframe.onmouseover = easyUtil.hideDiv;
	//전체 div	
	this._div.style.border	= this.cfg.border;
	this._div.style.width	= this.cfg.width;
	//버튼 div		
	this._divbtn.style.padding	="2px";
	this._divbtn.style.backgroundColor = this.cfg.divbtn_bgcolor;
	this._divbtn.style.borderBottom = this.cfg.border;
	//if(easyUtil.isIE) {this._divbtn.style.width = this.cfg.width;} // 레이아웃이 깨져 삭제	
	//삽입
	this._textarea.parentNode.insertBefore(this._div, this._textarea);
	this._div.appendChild(this._divbtn);
	this._div.appendChild(this._iframe);
	this._div.appendChild(this._text);	
	//버튼요소 삽입
	this.setBtn();
	//iframe doc
	this._doc = this._iframe.contentWindow.document;
	this._doc.designMode="on";
	//기본 css설정 
	var css  = "body{margin:"+this.cfg.margin+";background-color:"+this.cfg.bgcolor+";}";
	css		+= " body,table,td{font:"+this.cfg.font+";color:"+this.cfg.color+";}";
	this._iframe.css = css;
	this._doc.open();
	this._doc.write('<html><head><style type="text/css">'+css+'</style></head><body>'+this._textarea.value+'</body></html>');
	this._doc.close();
	
	var self=this;
	easyUtil.addEvent(this._doc, "mousedown", easyUtil.hideDiv);
	if(easyUtil.isIE)
	{
		easyUtil.addEvent(this._doc, "keydown", function(e) {
			var range=self._doc.selection.createRange();
			if(e.keyCode==13 && range.parentElement().tagName!="LI")
			{			
				e.cancelBubble=true; e.returnValue=false;
				range.pasteHTML("<br />"); range.select(); return false;
			}
		});
	}
};
//easyEditor.setBtn
easyEditor.prototype.setBtn = function()
{
	this.cfg.Btn.push("bar","ab"+"out");
	var arr=this.cfg.Btn;
	var len=arr.length; var str=order=tmp=""; var btn=tag=null;
	var self=this; var bgcolor=this.cfg.divbtn_bgcolor;
	var over_bordercolor=this.cfg.over_bordercolor;
	var over_bgcolor=this.cfg.over_bgcolor;

	for(var i=0;i<len;i++)
	{
		tmp = "";
		order = easyUtil.trim(arr[i]);		
		btn = this.cfg.BtnList[order];

		if(order!="br" && !btn)
		{	
			if(order!="") alert("없는 버튼명령입니다 ("+order+")");
			continue;
		}
		if(order=="bar")
		{
			tag			= document.createElement("img");
			tag.src		= this.cfg.imgpath+"/"+btn[1];
			tag.width	= 2;
			tag.height	= 18;
			tag.hspace	= 4;
			tag.align	="absmiddle";
			this._divbtn.appendChild(tag);
		}
		else if(order=="br") //br
		{
			this._divbtn.appendChild(document.createElement("br"));
		}
		else
		{
			tag			= document.createElement("img");
			tag.id		= this.cfg.preid+"_btn_"+order;
			tag.src		= this.cfg.imgpath+"/"+btn[1];
			tag.align	="absmiddle";
			tag.alt		= btn[0];
			tag.cmd		= order;
			tag.style.cursor="pointer";
			tag.style.border="1px solid "+this.cfg.divbtn_bgcolor;
					
			tag.onclick = function() { self.cmd(this, this.cmd); };
			tag.onmouseover = function() 
				{ this.style.border="1px solid "+over_bordercolor;this.style.backgroundColor=over_bgcolor; };
			tag.onmouseout = function() 
				{ this.style.border="1px solid "+bgcolor;this.style.backgroundColor=""; };
			this._divbtn.appendChild(tag);
		}
	}
};
//easyEditor.cmd
easyEditor.prototype.cmd = function(btn, order, value) 
{
	var self = (this) ? this:easyUtil._editor;
	var doc = self._doc;
	if(self.mode=="text" && order!="source") {alert("'소스보기' 해제후 사용해 주세요!");	return;}
	self.focus();
	easyUtil.hideDiv();	self.btn = btn;

	try{var create_func = self.cfg.BtnList[order][2];}catch(e) {}
	if(typeof(create_func)=="function")	order = "create_order";

	switch(order)
	{		
		case "create_order":	//사용자 추가명령
			self.setSelection();
			easyUtil._editor = self;
			easyUtil.order = order;
			create_func(self);
		break;
		case "hyperlink": 	
			var link_text = (self.sel_html) ? self.sel_html : easyUtil._linktxt.value;
			var html = "<a href='"+easyUtil._linktxt.value+"' target='_blank'>"+link_text+"</a>";
			self.innerHTML(html);			
		break;
		case "color": case "hilite":
		case "font": case "size":
		case "link": case "about": 
			var div=null;
			if(order=="color")		{ order = "forecolor"; easyUtil.tblSet_color(); div=easyUtil._colortbl;}
			else if(order=="hilite"){ order = "hilitecolor"; easyUtil.tblSet_color(); div=easyUtil._colortbl;}
			else if(order=="font")	{ order = "fontname"; easyUtil.tblSet_font(); div=easyUtil._fonttbl;}
			else if(order=="size")	{ order = "fontsize"; easyUtil.tblSet_size(); div=easyUtil._sizetbl;}
			else if(order=="about")	{ easyUtil.tblSet_about(); div=easyUtil._abouttbl;}
			else if(order=="link") 
			{
				order = "hyperlink"; 
				easyUtil.tblSet_link();
				div=easyUtil._linktbl; 
				easyUtil._linktxt.value = "http://";
				self.setSelection();
			}
		
			easyUtil._editor = self;
			easyUtil.order = order;
			easyUtil.showDiv(div);
		break;
		case "source":
			if(self.mode=="wysiwyg")
			{
				self._text.value = self.getHtml();
				self._iframe.style.display = "none";
				self._text.style.display = "";
				btn.onmouseout = null;
				self.mode="text";				
			}
			else if(self.mode=="text")
			{
				doc.body.innerHTML = self.getHtml();
				self._text.style.display = "none";
				self._iframe.style.display = "";
				bgcolor = self.cfg.divbtn_bgcolor;
				btn.onmouseout = function() 
					{ this.style.border="1px solid "+bgcolor;this.style.backgroundColor=""; };
				self.mode="wysiwyg";
			}
		break;
		case "imageUpload" :
			document.frmfile.submit();
		break;
		default:
			
			if(order=="strike")		order = "strikethrough";
			else if(order=="ul1")	order = "insertorderedlist";
			else if(order=="ul2")	order = "insertunorderedlist";			
			else if(order=="hr")	order = "inserthorizontalrule";
			else if(order=="clean") order = "removeformat";
			else if(order=="save")	order = "saveas";
			else if(order=="all")	order = "selectall";
			else if(order=="sup")	order = "superscript";
			else if(order=="sub")	order = "subscript";
			else if(order=="del")	order = "delete";
			else if(order=="justify") order = "justifyfull";
			else if(order=="center"||order=="left"||order=="right") order = "justify"+order; 
			else if(order=="hilitecolor" && easyUtil.isIE) order = "backcolor";
			doc.execCommand(order, false, value);
		break;
	}
};
//easyEditor.focus
easyEditor.prototype.focus = function() 
{
	if(this.mode=="text") this._text.focus();
	else this._iframe.contentWindow.focus();
}
//easyEditor.getHtml
easyEditor.prototype.getHtml = function() 
{
	var html = "";
	var doc = this._doc;
	if(this.mode=="text") html = this._text.value;
	else
	{
		for(i in doc.links) { if(!doc.links[i].target) doc.links[i].target = "_blank"; }
		html = doc.body.innerHTML;
	}
	this._textarea.value = html;
	return html;
}
//easyEditor.setSelection
easyEditor.prototype.setSelection = function() 
{
	var _iframe=this._iframe;
	var sel=null,range=null,html="";

	if(this._doc.selection)
	{
		sel = this._doc.selection;
		range = sel.createRange();
		html = range.htmlText;
	}
	else if(_iframe.contentWindow.getSelection)
	{
		sel=_iframe.contentWindow.getSelection();
		if (typeof(sel)!="undefined") range=sel.getRangeAt(0);
		else range=this._doc.createRange();
		if(sel.rangeCount > 0 && window.XMLSerializer)
		{	
			html=new XMLSerializer().serializeToString(range.cloneContents());
		}
	}
	this.sel = sel;
	this.range = range;
	this.sel_html = html;
}
//easyEditor.innerHTML
easyEditor.prototype.innerHTML = function(html) 
{
	if(easyUtil.mode=="text") return;
	if(this.range.pasteHTML) this.range.pasteHTML(html);
	else this._doc.execCommand("inserthtml", false, html);
}
//easyUtil
var easyUtil = {	
	_editor : null,	_colortbl : null,
	_fonttbl : null, _sizetbl : null,
	_abouttbl : null, 
	_linktbl : null, _linktxt : null,
	arrtbl : new Array("color","font","size","link","about"),
	order : "",
	is_init : 0,
	isIE : (window.showModalDialog) ? 1:0,
	
	init : function()
	{
		if(easyUtil.is_init) return;

		var s="<style>";
		s += ".easyWebEditorDiv a {text-decoration:none;color:#000}"
		s += "#"+easyConfig.name+"_colortbl a {border:1px solid #f5f5f5;padding:0 6px;height:9px;font:8px verdana;text-decoration:none}";
		s += "</style>";
		document.write(s);
		easyUtil.is_init=1;
	},	
	showDiv : function(div)
	{
		var btn = easyUtil._editor.btn;
		div.style.top= easyUtil.curTop(btn) + btn.offsetHeight + "px";
		div.style.left = easyUtil.curLeft(btn) + "px";
		div.style.display="";
	},
	hideDiv : function()
	{
		if(typeof(easyUtil)!="object") return;
		for(var i=0; i<easyUtil.arrtbl.length; i++)
		{
			try{ eval('easyUtil._'+easyUtil.arrtbl[i]+'tbl.style.display="none"'); }
			catch(e) { }
		}
	},
	curTop : function(el) 
	{
		var top = el.offsetTop;
		var parent = el.offsetParent;
		while(parent) {	top += parent.offsetTop; parent = parent.offsetParent; }
		return top;
	},
	curLeft : function(el) 
	{
		var left = el.offsetLeft;
		var parent = el.offsetParent;
		while(parent) {	left += parent.offsetLeft; parent = parent.offsetParent; }
		return left;
	},
	tblSet_about : function()
	{	
		if(easyUtil._abouttbl) return;
		var s="<span style='color:#ff6600'>이지 웹에디터</span><br/>버전 "+easyConfig.version+" <br/>";
		s +="도움 <a href='http://cafe.daum.net/easyeditor' target='_blank'>http://cafe.daum.net/easyeditor</a>";
		var d = easyUtil.getDiv(easyConfig.name+"_abouttbl",s);
		d.style.font = "9pt 돋움";	d.style.lineHeight ="150%";	
		document.body.appendChild(d); easyUtil._abouttbl = d;
	},
	tblSet_link : function()
	{	
		if(easyUtil._linktbl) return;
		var id = easyConfig.name+"_linktxt";
		var s = "<input type='text' value='http://' style='width:230px;font:8pt 돋움;color:gray' id='"+id+"' /><br>";
		s += "링크주소(URL)를 넣어주세요<br><br>";
		s += "<a href='javascript:;' onclick=\"easyUtil._editor.cmd(null, easyUtil.order, '');\">확인</a>";
		
		var div = easyUtil.getDiv(easyConfig.name+"_linktbl",s);
		div.style.padding = "15px";
		div.style.font = "8pt 돋움";
		document.body.appendChild(div);
		easyUtil._linktbl = div;
		easyUtil._linktxt = document.getElementById(id);
	},
	tblSet_size : function()
	{
		if(easyUtil._sizetbl) return;
		var size = new Array(8,10,12,14,18,24);
		var s="";

		for(var i=0; i<size.length; i++)
		{
			s += "<a href='javascript:;' onclick=\"easyUtil._editor.cmd(null, easyUtil.order,'"+(i+1)+"');\" style='font:"+size[i]+"pt 굴림;'>가나다라 ("+size[i]+")</a><br />";
		}
		var div = easyUtil.getDiv(easyConfig.name+"_sizetbl",s);
		div.style.padding = "5px";
		document.body.appendChild(div);
		easyUtil._sizetbl = div;
	},
	tblSet_font : function()
	{
		if(easyUtil._fonttbl) return;
		var font = new Array("굴림","돋움","궁서","arial","verdana");
		var s=""; var pattern=/^[가-힣]+$/;

		for(var i=0; i<font.length; i++)
		{
			txt = (pattern.test(font[i])) ? "가나다라마바사":"abcdefghijkl";
			s += "<a href='javascript:;' onclick=\"easyUtil._editor.cmd(null, easyUtil.order,'"+font[i]+"');\" style='font:10pt "+font[i]+";line-height:170%'>"+txt+" ("+font[i]+")</a><br />";
		}
		var div = easyUtil.getDiv(easyConfig.name+"_fonttbl",s);
		div.style.padding = "5px";
		document.body.appendChild(div);
		easyUtil._fonttbl = div;
	},
	tblSet_color : function()
	{
		if(easyUtil._colortbl) return;
		var col= new Array();
		col[0] = new Array("#ffffff","#e5e4e4","#d9d8d8","#c0bdbd","#a7a4a4","#8e8a8b","#827e7f","#767173","#5c585a","#000000");
		col[1] = new Array("#fefcdf","#fef4c4","#feed9b","#fee573","#ffed43","#f6cc0b","#e0b800","#c9a601","#ad8e00","#8c7301");
		col[2] = new Array("#ffded3","#ffc4b0","#ff9d7d","#ff7a4e","#ff6600","#e95d00","#d15502","#ba4b01","#a44201","#8d3901");
		col[3] = new Array("#ffd2d0","#ffbab7","#fe9a95","#ff7a73","#ff483f","#fe2419","#f10b00","#d40a00","#940000","#6d201b");
		col[4] = new Array("#ffdaed","#ffb7dc","#ffa1d1","#ff84c3","#ff57ac","#fd1289","#ec0078","#d6006d","#bb005f","#9b014f");
		col[5] = new Array("#fcd6fe","#fbbcff","#f9a1fe","#f784fe","#f564fe","#f546ff","#f328ff","#d801e5","#c001cb","#8f0197");
		col[6] = new Array("#e2f0fe","#c7e2fe","#add5fe","#92c7fe","#6eb5ff","#48a2ff","#2690fe","#0162f4","#013add","#0021b0");
		col[7] = new Array("#d3fdff","#acfafd","#7cfaff","#4af7fe","#1de6fe","#01deff","#00cdec","#01b6de","#00a0c2","#0084a0");
		col[8] = new Array("#edffcf","#dffeaa","#d1fd88","#befa5a","#a8f32a","#8fd80a","#79c101","#3fa701","#307f00","#156200");
		col[9] = new Array("#d4c89f","#daad88","#c49578","#c2877e","#ac8295","#c0a5c4","#969ac2","#92b7d7","#80adaf","#9ca53b");
		var s="";
		for(var i=0; i<10; i++)
		{
			for(var j=0; j<10; j++)
			{
				color = col[i][j];
				s += "<a href='javascript:;' onclick=\"easyUtil._editor.cmd(null, easyUtil.order,'"+color+"');\" style='background-color:"+color+";'>&nbsp;</a>";
			}
			s += "<br />";
		}
		var div = easyUtil.getDiv(easyConfig.name+"_colortbl",s);
		document.body.appendChild(div);
		easyUtil._colortbl = div;
	},
	getDiv : function(id, html)
	{
		var div = document.createElement("div");
		div.id = id;
		div.className = "easyWebEditorDiv";
		div.style.position = "absolute";
		div.style.backgroundColor = "#f5f5f5";
		div.style.display = "none";
		div.style.border = "1px solid #ccc";
		div.style.padding = "5px";
		div.innerHTML = html;
		return div;
	},
	addEvent : function(object, type, listener)
	{	
		if(object.addEventListener) { object.addEventListener(type, listener, false); } 
		else if(object.attachEvent) { object.attachEvent("on"+type, listener); } 
	},
	trim : function(s) {return s.replace(/^\s+|\s+$/g,'');} 
};
//--------------------------------------------------------
//명령(버튼) 추가
easyConfig.BtnList.table = ["테이블 삽입","table.jpg", 
function (self){ 
	window.open(easyConfig.filepath+"/table.html","table","width=400,height=220,status=1");
} ];

easyConfig.BtnList.preview = ["미리보기","preview.jpg",
function (self){
	var w=window.open("","preview","width=800,height=600,status=1,scrollbars=1,resizable=1");
	w.document.open();
	w.document.write("<style>"+self._iframe.css+"</style>"+self.getHtml());
	w.document.close();
}];

easyConfig.BtnList.image = ["이미지 삽입","image.gif",
function (self){
	var order = "image";
	var txt_id = easyConfig.name+"_imagetxt";
	var div_id = easyConfig.name+"_imagetbl";
	var div = null;
	
	if(self.btn) //image 레이어 보여주기
	{	
		if(!document.getElementById(div_id))
		{	
			var s="";
			s +="<input type='text' value='http://' style='width:230px;font:8pt 돋움;color:gray' id='"+txt_id+"' /><br />";
			s += "이미지주소(URL)를 넣어주세요<br /><br />";
			s += "<a href='javascript:;' onclick=\"easyUtil._editor.cmd(null, '"+order+"', '');\">확인</a>";
			div = easyUtil.getDiv(div_id,s);
			div.style.padding = "15px";
			div.style.font = "8pt 돋움";
			document.body.appendChild(div);
			easyUtil._imagetbl = div;
			easyUtil.arrtbl.push(order);
		}
		else
		{
			document.getElementById(txt_id).value = "http://";
			div = document.getElementById(div_id);
		}
		easyUtil.showDiv(div);
	}
	else //명령실행(이미지삽입)
	{
		var html = "<img src='"+document.getElementById(txt_id).value+"' border='0' />";
		self.innerHTML(html);
	}
}];

var img_UploadObj;
easyConfig.BtnList.imageUpload = ["이미지 업로드","images_plus.jpg",
function (self){
	img_UploadObj = self;
	var order = "imageUpload";
	var txt_id = easyConfig.name+"_imageUploadtxt";
	var div_id = easyConfig.name+"_imageUploadtbl";
	var div = null;
	if(self.btn) //image 레이어 보여주기
	{	
		if(!document.getElementById(div_id))
		{	
			var s="";
			s +="<form name='frmfile' method='post' target='ifrup' action='/_systool/editor_upload.php' enctype='multipart/form-data' >";
			s +="<input type='file' name='file_upload' /><br />";
			s +="<input type='hidden' name='obj' value='"+self+"'/><br />";
			s += "업로드할 파일을 등록하여 주세요<br /><br />";
			s += "<input type='submit' value='업로드' />";
			s += "<iframe name='ifrup' frameborder='0' border='0' width='0' height='0'></iframe>";
			s += "</form>";
			div = easyUtil.getDiv(div_id,s);
			div.style.padding = "15px";
			div.style.font = "8pt 돋움";
			document.body.appendChild(div);
			easyUtil._imagetbl = div;
			easyUtil.arrtbl.push(order);
		} else {
			div = document.getElementById(div_id);
		}
		if(document.getElementById(div_id).style.display == 'none'){
			easyUtil.showDiv(div);
		} else {
			document.getElementById(div_id).style.display="none";
		}
	}
	else //명령실행(이미지삽입)
	{
		var html = "<img src='"+document.getElementById(txt_id).value+"' border='0' />";
		self.innerHTML(html);
	}
}];

function AddFile(ad)
{
	document.getElementById(easyConfig.name+"_imageUploadtbl").style.display="none";
	img_UploadObj.innerHTML(ad);
}