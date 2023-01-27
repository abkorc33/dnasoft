<?php
	include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php";
	require_once('../../password.php');
?>

<link rel="stylesheet" type="text/css" href="/shkim/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/shkim/css/style.css" />
<script type="text/javascript" src="/shkim/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/shkim/js/jquery-ui.js"></script>
<script type="text/javascript" src="/shkim/js/common.js"></script>

<?php
// POST, GET방식으로 mode값 받음
if(isset($_POST['mode'])){
	$mode = $_POST['mode'];
}
else{
	$mode = $_GET['mode'];
}

//각 변수에 write.php에서 input name값들을 저장한다
if($mode=='write'){
	$username = $_POST['name'];
	$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
	$title = $_POST['title'];
	$content = $_POST['content'];
	$date = date('Y-m-d');

	// Auto increament 초기화
	$mqq = mq("alter table board auto_increment =1");

	if(isset($_POST['lockpost'])){
		$lo_post = '1';
	}else{
		$lo_post = '0';
	}

	$tmpfile =  $_FILES['b_file']['tmp_name'];
	$o_name = $_FILES['b_file']['name'];
	$filename = iconv("UTF-8", "EUC-KR",$_FILES['b_file']['name']);
	$folder = "../../upload/".$filename;
	move_uploaded_file($tmpfile,$folder);

	if($username && $userpw && $title && $content){
		$sql = mq("insert into board(name,pw,title,content,date,lock_post,file) values('".$username."','".$userpw."','".$title."','".$content."','".$date."','".$lo_post."','".$o_name."')");
		echo "<script>
		alert('글쓰기 완료되었습니다.');
		location.href='/shkim/index.php';</script>";
	}else{
		echo "<script>
		alert('글쓰기에 실패했습니다.');
		history.back();</script>";
	}

}

if($mode=='modify'){

	$bno = $_POST['bno'];
	$sql = mq("select * from board where idx='$bno';");
	$board = $sql->fetch_array();

	$username = $_POST['name'];
	$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
	$title = $_POST['title'];
	$content = $_POST['content'];

	$sql1 = mq("update board set name='".$username."',pw='".$userpw."',title='".$title."',content='".$content."' where idx='".$bno."'");
	echo "<script>alert('글쓰기 완료되었습니다.'); location.href='/shkim/page/board/read.php?idx=$bno';</script>";

}

if($mode=='delete'){

	$bno = $_GET['idx'];
	$sql = mq("delete from board where idx='$bno';");

	echo "<script>alert('삭제되었습니다.'); location.href='/shkim/index.php';</script>";
	// a태그 형식에서 form 태그 형식으로 데이터 전송 방식 수정
}

if($mode=='reply_ok'){

	$bno = $_GET['idx'];
    $userpw = password_hash($_POST['dat_pw'], PASSWORD_DEFAULT);
	$date = date('Y-m-d h:i');
    
    if($bno && $_POST['dat_user'] && $userpw && $_POST['content']){
        $sql = mq("insert into reply(con_num,name,pw,content,date) values('".$bno."','".$_POST['dat_user']."','".$userpw."','".$_POST['content']."','".$date."')");
        echo "<script>alert('댓글이 작성되었습니다.'); 
        location.href='/shkim/page/board/read.php?idx=$bno';</script>";
    }else{
        echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
    }

}

if($mode=='rep_modify_ok'){

	$rno = $_POST['rno'];//댓글번호
	$sql = mq("select * from reply where idx='".$rno."'"); //reply테이블에서 idx가 rno변수에 저장된 값을 찾음
	$reply = $sql->fetch_array();

	$bno = $_POST['b_no']; //게시글 번호
	$sql2 = mq("select * from board where idx='".$bno."'");//board테이블에서 idx가 bno변수에 저장된 값을 찾음
	$board = $sql2->fetch_array();

	$input_pw = $_POST['pw'];
	$db_pw = $reply['pw'];

	// reply 테이블의 idx가 rno변수에 저장된 값의 content를 선택해서 값 저장
	// 수정시 비밀번호 체크
	if (password_verify($input_pw, $db_pw)) {
    $sql3 = mq("UPDATE reply SET content='" . $_POST['content'] . "' WHERE idx = '" . $rno . "'"); ?>
    <script type="text/javascript">alert('수정되었습니다.');
    location.replace("/shkim/page/board/read.php?idx=<?php echo $bno; ?>");
    </script>
    <?php
    } else { ?>
    <script type="text/javascript">alert('비밀번호가 틀립니다');
    history.back();
    </script>
    <?php }
}

if($mode=='reply_delete'){

$rno = $_POST['rno']; 
$sql = mq("select * from reply where idx='".$rno."'");//reply테이블에서 idx가 rno변수에 저장된 값을 찾음
$reply = $sql->fetch_array();

$bno = $_POST['b_no'];
$sql2 = mq("select * from board where idx='".$bno."'");//board테이블에서 idx가 bno변수에 저장된 값을 찾음
$board = $sql2->fetch_array();

$input_pw = $_POST['pw'];
$db_pw = $reply['pw'];

if(password_verify($input_pw, $db_pw)) {
	$sql = mq("delete from reply where idx='".$rno."'"); ?>
	<script type="text/javascript">alert('댓글이 삭제되었습니다.'); location.replace("/shkim/page/board/read.php?idx=<?php echo $board["idx"]; ?>");</script>
	<?php 
	}else{ ?>
		<script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
	<?php }
} ?>
