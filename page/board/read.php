<?
//  [ DB connect ] ===========
$INC_HOME = "../../../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ testDB ] ============
$db = new db_conf("TESTSERVER", "fastcampus");
?>
<!doctype html>
<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
	<title>강의자료 게시판</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../../css/main.css" />
	<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css" />
	<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui.js"></script>
	<script type="text/javascript" src="../../js/common.js"></script>
</head>
<body class="is-preload">
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
	<div id="header">
		<h1><a href="https://realmgrdev.realclick.co.kr/fastcampus/fastcampus_board/index.php">강의자료 게시판</a></h1>
		<h4>JAVA강의 업로드 게시판입니다.</h4>
		<h2 align="left"><a href="../../index.php">제목 : <?=$title?></a></h2>
		<div id="main">
			<div align="right" style="padding: 10px; margin-right:10px;">작성자 : <?=$name?> 작성일 : <?=$regdate?> 조회 : <?=$hit?></div>
			<div align="left" style="margin-left: 10px;">
			<?
			if($file){		
?>
			파일 : <a href="../../upload/<?=$file?>" download><?=$file?></a>
<?
			}else{
?>	
<?
			}	
?>
			</div>
<?			
				echo nl2br("{$content}");
?>
		</div>
		<div>
			<!-- 목록, 수정, 삭제 -->
			<div class="input-group justify-content-end" style="height: 50px; margin-top:10px;">
				<a href="../../index.php"><button class="actions">목록가기</button></a>
				<a href="./modify.php?idx=<?=$idx?>"><button class="actions">수정</button></a>
				<form action="process.php?idx=<?=$idx?>" method="POST" style="float: left;">
					<button class="actions" type="submit">삭제</button>
					<input type="hidden" value="delete" name="mode"/>
				</form>
			</div>
<?
	}
?>
		</div>
	</div>
	<!--- 댓글 불러오기 -->
	<div id="pc_mo">
		<h3>댓글목록</h3>
<?
		$qry_member4 = "select * from reply where con_num='".$bno."' order by idx desc";
		$res_member = $db -> query_func($qry_member4,1);
		$reply = $db -> query_func($res_member,3);
		if($reply['name']){
?>
			<div style="margin-bottom: 30px;" class="dap_lo">
				<div><b><?=$reply['name']?></b></div>
				<div class="dap_to comt_edit"><?php echo nl2br("{$reply['content']}"); ?></div>
				<div class="rep_me dap_to"><?=$reply['date']?></div>
				<div class="rep_me rep_menu">
					<a class="dat_edit_bt" href="#"><button class="button small">수정</button></a>
					<a class="dat_delete_bt" href="#"><button class="button small">삭제</button></a>
				</div>
				<!-- 댓글 수정 폼 dialog -->
				<div style="display: none;" class="dat_edit">
					<form method="post" action="process.php">
						<input type="hidden" name="rno" value="<?=$reply['idx']?>" />
						<input type="hidden" name="b_no" value="<?=$bno?>">
						비밀번호 입력
						<input type="password" name="pw" class="dap_sm col-sm-5" style="border: solid 1px gray; margin-bottom: 10px;"/>
						<textarea name="content" class="dap_edit_t col-sm-10" style="border: solid 1px gray; resize:none;"><?=$reply['content']?></textarea>
						<input type="hidden" value="rep_modify_ok" name="mode"/>
						<input class="button small" type="submit" class="re_mo_bt" value="확인" style="border:solid 1px gray; margin-top: 10px;">click
					</form>
				</div>
				<!-- 댓글 삭제 비밀번호 확인 -->
				<div style="display: none;" class='dat_delete'>
					<form action="process.php" method="post">
						<input type="hidden" name="rno" value="<?=$reply['idx']?>" />
						<input type="hidden" name="b_no" value="<?=$bno?>">
						<input type="hidden" value="reply_delete" name="mode"/>
						<p>비밀번호
						<input type="password" name="pw" />
						<input class="button small" type="submit" value="확인" style="border:solid 1px gray; margin-top: 10px;">click
						</p>
						</form>
				</div>
			</div>
<? 
		}
?>
		<!--- 댓글 입력 폼 -->
		<div>
			<form action="process.php?idx=<?=$bno?>" method="post" class="row justify-content-center">
				<div class="col-sm-3 input-group" style="margin-bottom: 10px;">
					<input type="text" name="dat_user" id="dat_user" class="dat_user" size="15" placeholder="아이디">
					<input type="password" name="dat_pw" id="dat_pw" class="dat_pw" size="15" placeholder="비밀번호">
				</div>
				<div class="input-group">
					<textarea id="dat_width" name="content" style="height: 100px;"></textarea>
					<input type="hidden" value="reply_ok" name="mode"/>
					<button id="rep_bt" style="height: 100px;">댓글 작성</button>
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
					show:'slide',
					width:400,
					height:350,
					title:"댓글 수정"});
			});

		$(".dat_delete_bt").click(function(){
			/* dat_delete_bt클래스 클릭시 동작(댓글 삭제) */
			var obj = $(this).closest(".dap_lo").find(".dat_delete");
			obj.dialog({
				modal:true,
				show:'slide',
				width:400,
				title:"댓글 삭제확인"});
			});

		});
	</script>
	<div id="foot_box"></div>
	</div>
</body>
</html>