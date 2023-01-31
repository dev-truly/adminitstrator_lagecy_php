<script language="javascript">
	var OpenWinLayer = new WaitingDialog(WaitSetobject,"OpenWinLayer");
</script><!-- 
<% if SaveType <> "" then %>
<form name="QSaveForm">
<input type="hidden" name="QCODE" value="<%=SaveType%>">
<input type="hidden" name="QSTR" value="<%=SaveWhere%>">
<input type="hidden" name="QNAME" value="<%=SaveTitle%>">
</form>
</body>
</html>

<script language="javascript">
function _Fsavelist()
{
	LoadLink("005");
}
</script>
<% end if%>

<%
'=====================================================
'== 도움말 - DB 객체 소멸
'=====================================================
	Set C_DB = nothing
%> -->