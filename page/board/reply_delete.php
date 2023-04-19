<?php
include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php";
require_once('../../password.php');

$rno = $_POST['rno']; 
$sql = mq("select * from reply where idx='".$rno."'");//reply테이블에서 idx가 rno변수에 저장된 값을 찾음
$reply = $sql->fetch_array();

$bno = $_POST['b_no'];
$sql2 = mq("select * from board where idx='".$bno."'");//board테이블에서 idx가 bno변수에 저장된 값을 찾음
$board = $sql2->fetch_array();

$input_pw = $_POST['pw'];
$db_pw = $reply['pw'];

if(password_verify($input_pw, $db_pw)) 
	{
		$sql = mq("delete from reply where idx='".$rno."'"); ?>
	<script type="text/javascript">alert('댓글이 삭제되었습니다.'); location.replace("/shkim/page/board/read.php?idx=<?php echo $board["idx"]; ?>");</script>
	<?php 
	}else{ ?>
		<script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
	<?php } ?>