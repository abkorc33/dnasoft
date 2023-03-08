<?
header('Content-Type: text/html; charset=utf-8');
//  [ DB connect ] ===========
$INC_HOME = "../../../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ testDB ] ============
$db = new db_conf("MAINDB_SLV", "fastcampus");
?>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css" />
<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(function(){
		$("#writepass").dialog({
		 	modal:true,
		 	title:'비밀글입니다.',
		 	width:400,
	 	});
	});
</script>
<div id='writepass'>
	<form action="" method="post">
 		<p>비밀번호<input type="password" name="pw_chk" /><input type="submit" value="확인"/></p>
 	</form>
</div>
<?
	$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
	$pw_chk = $_POST['pw_chk'];
	$qry_member2 = "select * from board where idx='{$bno}' and pw=password('{$pw_chk}')"; 
	$res_member = $db -> query_func($qry_member2,1); // $pw_chk값을 암호화 한 sql문
	$board = $db -> query_func($res_member,3);
	$bpw = $board['pw'];

	//만약 pw_chk POST값이 있다면
	if($pw_chk){
		if($bpw){
?>
			<script type="text/javascript">location.replace("read.php?idx=<?php echo $bno; ?>");</script><!-- pwk와 bpw값이 같으면 read.php로 보내고 -->
<?
		}else{ 
?>
			<script type="text/javascript">alert('비밀번호가 틀립니다');</script><!--- 아니면 비밀번호가 틀리다는 메시지를 보여줍니다 -->
<?		}  
	}
?>