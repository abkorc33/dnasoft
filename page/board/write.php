<!doctype html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
<title>강의자료 게시판</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../../css/main.css" />
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
<body class="is-preload">
	<div  id="header" style="position: relative;">
	<h1><a href="https://realmgrdev.realclick.co.kr/fastcampus/fastcampus_board/index.php">강의자료 게시판</a></h1>
        <h4>글을 작성하는 공간입니다.</h4>
			<div id="write_area" align="center">
                <form action="process.php" method="post" enctype="multipart/form-data">
                    <div id="in_title">
                        <textarea name="title" id="utitle" rows="1" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div id="in_name">
                        <textarea name="name" id="uname" rows="1" placeholder="글쓴이" maxlength="100" required></textarea>
                    </div>
                    <div id="main">
						<div id="smarteditor" style="margin-top: 30px;" class="col-md-8">
							<textarea name="editortxt" id="editortxt" rows="20" cols="10" placeholder="내용을 입력해주세요." style="width: 880px"></textarea>
						</div>
					</div>
					<div id="in_file" style="margin-top: 10px;" align="left">
						<input type="file" id="b_file" value="1" name="b_file" />
					</div>
					<div class="row justify-content-start">
						<div id="in_lock" style="margin-top: 10px;" class="form-check col-sm-2">
							<input class="form-check-input" type="checkbox" value="1" name="lockpost" id="flexCheckDefault" onClick="test()"/>
							<label class="form-check-label" for="flexCheckDefault" style="margin-top:10px;">글을 잠급니다.</label>
						</div>
						<div id="in_pw" style="margin-top: 10px;" class="col-sm-3">
						</div>
					</div>
					<input type="hidden" value="1" name="masteridx" />
					<input type="hidden" value="write" name="mode"/>
					<div class="bt_se" style="margin-top: 10px;">
						<button class="write" type="submit" onclick="submitPost()">글 작성</button>
					</div>
                </form>
            </div>
        </div>
    </body>
</html>
 
 
