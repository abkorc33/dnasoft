<? include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php"; ?>

<link rel="stylesheet" type="text/css" href="/shkim/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/shkim/css/style.css" />
<script type="text/javascript" src="/shkim/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/shkim/js/jquery-ui.js"></script>
<script type="text/javascript" src="/shkim/js/common.js"></script>

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
		$sql = mq("update board set dragidx = '".$dragidx[$i]."' where idx = '".$idx[$i]."'");
	}

}

//각 변수에 write.php에서 input name값들을 저장한다
if($mode=='write'){
	$username = $_POST['name'];
	$userpw = $_POST['pw'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$regdate = date('Y-m-d h:i:s');
	$masteridx = $_POST['masteridx'];
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

	$sql = mq("select max(`idx`) as drag from board");
	$dragidx = $sql->fetch_array();
	$dragidx = $dragidx['drag'] + 1;


	// 패스워드 입력 생략
	if($username && $title && $content){
		$sql = mq("insert into board(name,title,content,regdate,lock_post,file,masteridx,dragidx) values('".$username."','".$title."','".$content."','".$regdate."','".$lo_post."','".$o_name."','".$masteridx."','".$dragidx."')");
		echo "<script>
		alert('글쓰기 완료되었습니다.');
		location.href='/shkim/index.php';</script>";
	}else{
		echo "<script>
		alert('글쓰기에 실패했습니다.');
		history.back();</script>";
	}
	/*
	// 패스워드 입력부분 
	if($username && $userpw && $title && $content){
		$sql = mq("insert into board(name,pw,title,content,regdate,lock_post,file,masteridx) values('".$username."',password('".$userpw."'),'".$title."','".$content."','".$regdate."','".$lo_post."','".$o_name."','".$masteridx."')");
		echo "<script>
		alert('글쓰기 완료되었습니다.');
		location.href='/shkim/index.php';</script>";
	}else{
		echo "<script>
		alert('글쓰기에 실패했습니다.');
		history.back();</script>";
	}
	*/

}

if($mode=='modify'){

	$bno = $_POST['bno'];
	$sql = mq("select * from board where idx='$bno';");
	$board = $sql->fetch_array();

	$username = $_POST['name'];
	$userpw = $_POST['pw'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$editdate = date('Y-m-d h:i:s');

	$sql1 = mq("update board set name='".$username."',pw=password('".$userpw."'),title='".$title."',content='".$content."',editdate='".$editdate."' where idx='".$bno."'");
	echo "<script>alert('글쓰기 완료되었습니다.'); location.href='/shkim/page/board/read.php?idx=$bno';</script>";
	/*,pw=password('".$userpw."')
	파일삭제 unlink()로 파일삭제*/
}

if($mode=='delete'){

	$bno = $_GET['idx'];
	$sql = mq("delete from board where idx='$bno';");

	echo "<script>alert('삭제되었습니다.'); location.href='/shkim/index.php';</script>";
	// a태그 형식에서 form 태그 형식으로 데이터 전송 방식 수정
}

if($mode=='reply_ok'){

	$bno = $_GET['idx'];
    $userpw = $_POST['dat_pw'];
	$date = date('Y-m-d h:i');
    
    if($bno && $_POST['dat_user'] && $userpw && $_POST['content']){
        $sql = mq("insert into reply(con_num,name,pw,content,date) values('".$bno."','".$_POST['dat_user']."',password('".$userpw."'),'".$_POST['content']."','".$date."')");
        echo "<script>alert('댓글이 작성되었습니다.'); 
        location.href='/shkim/page/board/read.php?idx=$bno';</script>";
    }else{
        echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
    }

}

if($mode=='rep_modify_ok'){
	$input_pw = $_POST['pw'];
	$rno = $_POST['rno'];//댓글번호
	$bno = $_POST['b_no']; //input type hidden으로 넘겨받은 게시글 번호
	$qry = "select * from reply where idx='{$rno}' and pw=password('{$input_pw}')";
    $sql = mq($qry);//reply테이블에서 idx가 rno변수에 저장된 값을 찾음
    $reply = $sql->fetch_array();

	// 수정시 비밀번호 체크
	if ($reply['pw']) {
    $sql3 = mq("UPDATE reply SET content='" . $_POST['content'] . "' WHERE idx = '{$rno}'"); ?>
    <script type="text/javascript">alert('수정되었습니다.');location.replace("/shkim/page/board/read.php?idx=<?php echo $bno; ?>");</script>
<? } else { ?>
    <script type="text/javascript">alert('비밀번호가 틀립니다'); history.back(); </script>
<? }
}

if($mode=='reply_delete'){
    $input_pw   = $_POST['pw']; //사용자의 입력값을 받아온 pw
	$rno = $_POST['rno']; 
	$bno = $_POST['b_no']; // input type hidden으로 넘겨받은 게시글 번호
    $qry = "select * from reply where idx='{$rno}' and pw=password('{$input_pw}')";
    $sql = mq($qry); // $input_pw값을 암호화 한 sql문
    $reply = $sql->fetch_array(); // $reply라는 변수에 배열의 키와 값을 담는다.
    
	// reply 테이블(qry가 reply테이블에 대한 쿼리니까 $reply에는 reply테이블 정보가 들어가는 것)에 무슨 값이 있는지 키와 값으로 보여준다.
    print_r($reply);

    if($reply['pw']) { // reply테이블에 해당 값이 있으면 함수 실행
		// $sql = mq("delete from reply where idx='{$rno}'"); 중괄호{}로 감싸는 방법도 있다.
        $sql = mq("delete from reply where idx='".$rno."'"); ?>
        <script type="text/javascript">alert('댓글이 삭제되었습니다.'); location.replace("/shkim/page/board/read.php?idx=<?php echo $bno; ?>");</script>
<?  }else{ ?>
        <script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
<?
    }

} ?>
