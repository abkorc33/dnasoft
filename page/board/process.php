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
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>

<?
// POST, GET방식으로 mode값 받음
if(isset($_POST['mode'])){
	$mode = $_POST['mode'];
}
else{
	$mode = $_GET['mode'];
}

if($mode=='idxRow'){

	$idx = $_POST[myArray1];
	$dragidx = $_POST[myArray2];
	
	for($i=0; $i<=count($idx); $i++){
		$qry_member1 = "update board set dragidx = '".$dragidx[$i]."' where idx = '".$idx[$i]."'";
		$res_member = $db -> query_func($qry_member1,1);
	}
}

//각 변수에 write.php에서 input name값들을 저장한다
if($mode=='write'){
	$username = $_POST['name'];
	$userpw = $_POST['pw'];
	$title = $_POST['title'];
	$content = $_POST['editortxt'];
	$regdate = date('Y-m-d H:i:s');
	$masteridx = $_POST['masteridx'];
	// Auto increament 초기화
	$qry_member1 = "alter table board auto_increment = 1";
	$res_member = $db -> query_func($qry_member1,1);

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

	$qry_member2 = "select max(`dragidx`) as drag from board";
	$res_member = $db -> query_func($qry_member2,1);
	while(list($dragidx) = $db -> query_func($res_member,3)){	// 새로 글 작성 후 dragidx제대로 insert 되었는지 확인하기
	$dragidx = $dragidx + 1;

		// 패스워드 입력 생략
		if($username && $title && $content){
			$qry_member3 = "insert into board(name,title,content,regdate,lock_post,pw,file,masteridx,dragidx) values('".$username."','".$title."','".$content."','".$regdate."','".$lo_post."',password('".$userpw."'),'".$o_name."','".$masteridx."','".$dragidx."')";
			$res_member = $db -> query_func($qry_member3,1);
			echo "<script>
			alert('글쓰기 완료되었습니다.');
			location.href='https://realmgrdev.realclick.co.kr/fastcampus/fastcampus_board/index.php';</script>";
		}else{
			echo "<script>
			alert('글쓰기에 실패했습니다.');
			history.back();</script>";
		}
	}
}

if($mode=='modify'){

	$bno = $_POST['bno'];
	$username = $_POST['name'];
	$userpw = $_POST['pw'];
	$title = $_POST['title'];
	$content = $_POST['editortxt'];
	$editdate = date('Y-m-d H:i:s');

	$qry_member1 = "update board set name='".$username."',title='".$title."',content='".$content."',editdate='".$editdate."' where idx='".$bno."'";
	$res_member = $db -> query_func($qry_member1,1);
	echo "<script>alert('수정이 완료되었습니다.'); location.href='./read.php?idx={$bno}';</script>";
	/*,pw=password('".$userpw."')
	파일삭제 unlink()로 파일삭제*/
}

if($mode=='delete'){

	$bno = $_GET['idx'];
	$qry_member1 = "delete from board where idx={$bno}";
	$res_member = $db -> query_func($qry_member1,1);
	echo "<script>alert('삭제되었습니다.'); location.href='../../index.php';</script>";
	// a태그 형식에서 form 태그 형식으로 데이터 전송 방식 수정
}

if($mode=='reply_ok'){

	$bno = $_GET['idx'];
    $userpw = $_POST['dat_pw'];
	$date = date('Y-m-d H:i');
	// Auto increament 초기화
	$qry_member1 = "alter table reply auto_increment = 1";
	$res_member = $db -> query_func($qry_member1,1);
    
    if($bno && $_POST['dat_user'] && $userpw && $_POST['content']){
        $qry_member2 = "insert into reply(con_num,name,pw,content,date) values('".$bno."','".$_POST['dat_user']."',password('".$userpw."'),'".$_POST['content']."','".$date."')";
		$res_member = $db -> query_func($qry_member2,1);
        echo "<script>alert('댓글이 작성되었습니다.'); 
        location.href='./read.php?idx=$bno';</script>";
    }else{
        echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
    }

}

if($mode=='rep_modify_ok'){
	$input_pw = $_POST['pw'];
	$rno = $_POST['rno'];//댓글번호
	$bno = $_POST['b_no']; //input type hidden으로 넘겨받은 게시글 번호
	$content = $_POST['content'];
	$qry_member1 = "select * from reply where idx='{$rno}' and pw=password('$input_pw')";
	$res_member = $db -> query_func($qry_member1,1);//reply테이블에서 idx가 rno변수에 저장된 값을 찾음
	$reply = $db -> query_func($res_member,3);

	if($reply['pw']){
		$qry_member2 = "update reply set content='".$content."' where idx='".$rno."'";
		$res_member = $db -> query_func($qry_member2,1);
?>
        <script type="text/javascript">alert('댓글이 수정되었습니다.'); location.replace("./read.php?idx=<?php echo $bno; ?>");</script>
<?  }else{ ?>
        <script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
<?
    }

}

if($mode=='reply_delete'){
    $input_pw   = $_POST['pw']; //사용자의 입력값을 받아온 pw
	$rno = $_POST['rno']; 
	$bno = $_POST['b_no']; // input type hidden으로 넘겨받은 게시글 번호
    $qry_member1 = "select * from reply where idx='{$rno}' and pw=password('{$input_pw}')";
    $res_member = $db -> query_func($qry_member1,1); // $input_pw값을 암호화 한 sql문
    $reply = $db -> query_func($res_member,3); // $reply라는 변수에 배열의 키와 값을 담는다.

    if($reply['pw']) { // reply테이블에 해당 값이 있으면 함수 실행
		// $sql = mq("delete from reply where idx='{$rno}'"); 중괄호{}로 감싸는 방법도 있다.
        $qry_member2 = "delete from reply where idx='".$rno."'"; 
		$res_member = $db -> query_func($qry_member2,1);
?>
        <script type="text/javascript">alert('댓글이 삭제되었습니다.'); location.replace("./read.php?idx=<?php echo $bno; ?>");</script>
<?  }else{ ?>
        <script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
<?
    }

} ?>
