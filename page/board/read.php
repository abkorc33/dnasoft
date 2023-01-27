<?php
	include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php"; /* db load */
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/shkim/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/shkim/css/style.css" />
<script type="text/javascript" src="/shkim/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/shkim/js/jquery-ui.js"></script>
<script type="text/javascript" src="/shkim/js/common.js"></script>
</head>
<body>
	<?php
		$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
		$hit = mysqli_fetch_array(mq("select * from board where idx ='".$bno."'"));
		$hit = $hit['hit'] + 1;
		$fet = mq("update board set hit = '".$hit."' where idx = '".$bno."'");
		$sql = mq("select * from board where idx='".$bno."'"); /* 받아온 idx값을 선택 */
		$board = $sql->fetch_array();
	?>
<!-- 글 불러오기 -->
<div id="board_read">
	<h2><?php echo $board['title']; ?></h2>
		<div id="user_info">
			<?php echo $board['name']; ?> <?php echo $board['date']; ?> 조회:<?php echo $board['hit']; ?>
			<div id="bo_line"></div>
		</div>
		<div>
		파일 : <a href="../../upload/<?php echo $board['file'];?>" download><?php echo $board['file']; ?></a>
		</div>
		<div id="bo_content">
			<?php echo nl2br("$board[content]"); ?>
		</div>
	<!-- 목록, 수정, 삭제 -->
	<div id="bo_ser">
		<ul>
			<li><a href="/shkim/index.php">[목록으로]</a></li>
			<li><a href="/shkim/page/board/modify.php?idx=<?php echo $board['idx']; ?>">[수정]</a></li>
		</ul>
		<form action="process.php?idx=<?php echo $board['idx']; ?>" method="POST">
			<button type="submit">삭제<button>
			<input type="hidden" value="delete" name="mode"/>
		</form>
	</div>
</div>
<!--- 댓글 불러오기 -->
<div class="reply_view">
	<h3>댓글목록</h3>
		<?php
			$sql3 = mq("select * from reply where con_num='".$bno."' order by idx desc");
			while($reply = $sql3->fetch_array()){ 
		?>
		<div class="dap_lo">
			<div><b><?php echo $reply['name'];?></b></div>
			<div class="dap_to comt_edit"><?php echo nl2br("$reply[content]"); ?></div>
			<div class="rep_me dap_to"><?php echo $reply['date']; ?></div>
			<div class="rep_me rep_menu">
				<a class="dat_edit_bt" href="#">수정</a>
				<a class="dat_delete_bt" href="#">삭제</a>
			</div>
			<!-- 댓글 수정 폼 dialog -->
			<div class="dat_edit">
				<form method="post" action="process.php">
					<input type="hidden" name="rno" value="<?php echo $reply['idx']; ?>" /><input type="hidden" name="b_no" value="<?php echo $bno; ?>">
					<input type="password" name="pw" class="dap_sm" placeholder="비밀번호" />
					<textarea name="content" class="dap_edit_t"><?php echo $reply['content']; ?></textarea>
					<input type="hidden" value="rep_modify_ok" name="mode"/>
					<input type="submit" value="수정하기" class="re_mo_bt">
				</form>
			</div>
			<!-- 댓글 삭제 비밀번호 확인 -->
			<div class='dat_delete'>
				<form action="process.php" method="post">
					<input type="hidden" name="rno" value="<?php echo $reply['idx']; ?>" />
					<input type="hidden" name="b_no" value="<?php echo $bno; ?>">
					<input type="hidden" value="reply_delete" name="mode"/>
			 		<p>비밀번호<input type="password" name="pw" /> <input type="submit" value="확인"></p>
				 </form>
			</div>
		</div>
	<?php } ?>

	<!--- 댓글 입력 폼 -->
	<div class="dap_ins">
		<form action="process.php?idx=<?php echo $bno; ?>" method="post">

			<input type="text" name="dat_user" id="dat_user" class="dat_user" size="15" placeholder="아이디">
			<input type="password" name="dat_pw" id="dat_pw" class="dat_pw" size="15" placeholder="비밀번호">
			<div style="margin-top:10px; ">
				<textarea name="content" class="reply_content" id="re_content" ></textarea>
				<input type="hidden" value="reply_ok" name="mode"/>
				<button id="rep_bt" class="re_bt">댓글</button>
			</div>
		</form>
	</div>
</div><!--- 댓글 불러오기 끝 -->
	<script>
	/*댓글 수정*/
	$(document).ready(function(){
		$(".dat_edit_bt").click(function(){
			/* dat_edit_bt클래스 클릭시 동작(댓글 수정) */
				var obj = $(this).closest(".dap_lo").find(".dat_edit");
				obj.dialog({
					modal:true,
					width:650,
					height:200,
					title:"댓글 수정"});
			});

		$(".dat_delete_bt").click(function(){
			/* dat_delete_bt클래스 클릭시 동작(댓글 삭제) */
			var obj = $(this).closest(".dap_lo").find(".dat_delete");
			obj.dialog({
				modal:true,
				width:400,
				title:"댓글 삭제확인"});
			});

		});
	</script>
<div id="foot_box"></div>
</div>
</body>
</html>