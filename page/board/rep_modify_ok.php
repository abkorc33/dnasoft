<?php
include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php"; /* db load */
require_once('../../password.php');

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
    <?php } ?>