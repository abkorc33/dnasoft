<?
//  [ DB connect ] ===========
$INC_HOME = "../../../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ 회원DB ] ============
$db = new db_conf("MAINDB_SLV", "fastcampus");

$bno = $_GET['idx'];

$qry_member1 = "select title, name, content from board where idx={$bno}";
$res_member = $db -> query_func($qry_member1,1);
while(list($title, $name, $content) = $db -> query_func($res_member,3)){
?>
	<!doctype html>
	<head>
	<meta charset="UTF-8">
	<title>게시판</title>
	<link rel="stylesheet" href="../../css/style.css" />
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
		<script type="text/javascript" src="../smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
		<script>
		let oEditor = []

		smartEditor = function(){
		  console.log("Naver SmartEditor");
		  nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditor,
			elPlaceHolder: "editortxt",
			sSkinURI: "../smarteditor/SmartEditor2Skin.html",
			fCreator: "createSEditor2",
		  });
		};

		$(document).ready(function(){
		  smartEditor();
		});

		function submitPost(){
			oEditor.getById["editortxt"].exec("UPDATE_CONTENTS_FIELD", []);
			let content = document.getElementById("editortxt").value;
			
			if(content == ""){
				alert("내용을 입력해주세요");
				oEditor.getById["editortxt"].exec("focus");
				return;
			}else{
				console.log(content);
			}
		}

		function pasteHTML(filepath) {
			// var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
			var sHTML = '<span style="color:#FF0000;"><img src="'+filepath+'"></span>';
			oEditors.getById["editortxt"].exec("PASTE_HTML", [sHTML]);
		}
		</script>
	</head>
	<body>
		<div id="board_write">
			<h1><a href="https://realmgrdev.realclick.co.kr/fastcampus/fastcampus_board/index.php">강의자료 게시판</a></h1>
			<h4>글을 수정합니다.</h4>
				<div id="write_area">
					<form action="process.php?idx=<?php echo $bno; ?>" method="post">
						<div id="in_title">
							<textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required><?=$title?></textarea>
						</div>
						<div class="wi_line"></div>
						<div id="in_name">
							<textarea name="name" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required><?=$name?></textarea>
						</div>
						<div class="wi_line"></div>
						<div id="smarteditor" style="margin-top: 30px;">
							<textarea name="editortxt" id="editortxt" style="width: 895px" required><?=$content?></textarea>
						</div>
						<div id="in_pw">
							<input type="password" name="pw" id="upw"  placeholder="비밀번호" />  
						</div>
						<input type="hidden" value=<?php echo $bno; ?> name="bno"/>
						<input type="hidden" value="modify" name="mode"/>
						<div class="bt_se">
							<button class="modify_btn" type="submit" onclick="submitPost()">글 수정</button>
						</div>
					</form>
				</div>
			</div>
		</body>
	</html>
<?
}
?>