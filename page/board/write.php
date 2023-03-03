<!doctype html>
<head>
<meta charset="UTF-8">
<title>강의자료 게시판</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
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

	function test(){
		let checked = $("input[name='lockpost']:checked").val();
		if(checked==1){
			document.querySelector('#in_pw').innerHTML='<input type="password" name="pw" id="upw" placeholder="비밀번호"/>';
			alert("게시글 잠금시 비밀번호가 필요합니다.");
		}
	}
	</script>
</head>
<body>
    <div id="board_write">
        <h1><a href="https://realmgrdev.realclick.co.kr/fastcampus/fastcampus_board/index.php">강의자료 게시판</a></h1>
        <h4>글을 작성하는 공간입니다.</h4>
            <div id="write_area">
                <form action="process.php" method="post" enctype="multipart/form-data">
                    <div id="in_title">
                        <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                        <textarea name="name" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
					<div id="smarteditor" style="margin-top: 30px;">
                        <textarea name="editortxt" id="editortxt" rows="20" cols="10" placeholder="내용을 입력해주세요." style="width: 895px"></textarea>
                    </div>
                    <div id="in_pw">
                    
                    </div>
					<div id="in_lock">
                        <input type="checkbox" value="1" name="lockpost" onClick="test()"/>해당글을 잠급니다.
                    </div>
					<div id="in_file">
                        <input type="file" id="b_file" value="1" name="b_file" />
                    </div>
					<input type="hidden" value="1" name="masteridx" />
					<input type="hidden" value="write" name="mode"/>
                    <div class="bt_se">
                        <button class="write" type="submit" onclick="submitPost()">글 작성</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
 
 
