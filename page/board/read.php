<?
//  [ DB connect ] ===========
$INC_HOME = "../../../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ 회원DB ] ============
$db = new db_conf("MAINDB_SLV", "fastcampus");
?>
<!doctype html>
<head>
	<meta charset="UTF-8">
	<title>강의자료 게시판</title>
	<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="../..//css/style.css" />
	<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui.js"></script>
	<script type="text/javascript" src="../../js/common.js"></script>
</head>
<body>
<?
	$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
	$qry_member1 = "select * from board where idx ='".$bno."'";
	$res_member = $db -> query_func($qry_member1,1);
	$hit = $db -> query_func($res_member,3);
	if($hit['hit']){
		$hit = $hit['hit'] + 1;

		$qry_member2 = "update board set hit = '".$hit."' where idx = '".$bno."'";
		$res_member = $db -> query_func($qry_member2,1);
	}
	$qry_member3 = "select idx, title, name, regdate, hit, file, content from board where idx='".$bno."'"; /* 받아온 idx값을 선택 */
	$res_member = $db -> query_func($qry_member3,1);
	while(list($idx, $title, $name, $regdate, $hit, $file, $content) = $db -> query_func($res_member,3)){

?>
	<!-- 글 불러오기 -->
	<div id="board_read">
		<h2><a href="../../index.php"><?=$title?></a></h2>
			<div id="user_info">
				작성자 : <?=$name?> 작성일 : <?=$regdate?> 조회 : <?=$hit?>
				<!-- 목록, 수정, 삭제 -->
				<div id="bo_ser">
					<a href="./modify.php?idx=<?=$idx?>"><button class="btn_modify">수정</button></a>
					<a href="../../index.php"><button class="btn_index">목록가기</button></a>
					<form action="process.php?idx=<?=$idx?>" method="POST" style="float: left;">
						<button class="btn_delete" type="submit">삭제<button>
						<input type="hidden" value="delete" name="mode"/>
					</form>
				</div>
				<div id="bo_line"></div>
			</div>
			<div>
			파일 : <a href="../../upload/<?=$file?>" download><?=$file?></a>
			</div>
			<div id="bo_content">
<?			
			echo nl2br("{$content}");
		}
?>
			</div>
		</div>
		<!--- 댓글 불러오기 -->
		<div class="reply_view">
			<h3>댓글목록</h3>
<?
			$qry_member4 = "select * from reply where con_num='".$bno."' order by idx desc";
			$res_member = $db -> query_func($qry_member4,1);
			$reply = $db -> query_func($res_member,3);
			if($reply['name']){
?>
				<div class="dap_lo">
					<div><b><?=$reply['name']?></b></div>
					<div class="dap_to comt_edit"><?php echo nl2br("{$reply['content']}"); ?></div>
					<div class="rep_me dap_to"><?=$reply['date']?></div>
					<div class="rep_me rep_menu">
						<a class="dat_edit_bt" href="#">수정</a>
						<a class="dat_delete_bt" href="#">삭제</a>
					</div>
					<!-- 댓글 수정 폼 dialog -->
					<div class="dat_edit">
						<form method="post" action="process.php">
							<input type="hidden" name="rno" value="<?=$reply['idx']?>" />
							<input type="hidden" name="b_no" value="<?=$bno?>">
							<input type="password" name="pw" class="dap_sm" placeholder="비밀번호" />
							<textarea name="content" class="dap_edit_t"><?=$reply['content']?></textarea>
							<input type="hidden" value="rep_modify_ok" name="mode"/>
							<input type="submit" value="수정하기" class="re_mo_bt">
						</form>
					</div>
					<!-- 댓글 삭제 비밀번호 확인 -->
					<div class='dat_delete'>
						<form action="process.php" method="post">
							<input type="hidden" name="rno" value="<?=$reply['idx']?>" />
							<input type="hidden" name="b_no" value="<?=$bno?>">
							<input type="hidden" value="reply_delete" name="mode"/>
							<p>비밀번호<input type="password" name="pw" /> <input type="submit" value="확인"></p>
						 </form>
					</div>
				</div>
<? 
			}
?>
			<!--- 댓글 입력 폼 -->
			<div class="dap_ins">
				<form action="process.php?idx=<?=$bno?>" method="post">

					<input type="text" name="dat_user" id="dat_user" class="dat_user" size="15" placeholder="아이디">
					<input type="password" name="dat_pw" id="dat_pw" class="dat_pw" size="15" placeholder="비밀번호">
					<div style="margin-top:10px; ">
						<textarea name="content" class="reply_content" id="re_content" ></textarea>
						<input type="hidden" value="reply_ok" name="mode"/>
						<button id="rep_bt" class="re_bt">댓글 작성</button>
					</div>
				</form>
			</div>
		</div>
		<!--- 댓글 불러오기 끝 -->
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