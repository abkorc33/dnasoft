<?  /*******************************************************************/
	// 프로그램명	: member_manage.php
	// 제목		: 회원 관리 테스트페이지
	// 작성자		: 김수현
	// 작성일		: 2023.02.21
	//
	// 프로그램 설명: 회원 관리 테스트페이지 입니다.
	/*******************************************************************/
?>
<?	//	[ 페이징 변수 ] ============
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}

	//  [ DB connect ] ===========
	$INC_HOME = "../../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

	//	[ 회원DB ] ============
    $db = new db_conf("DSPMAINDB_TEST", "dsp_manage");
?>

<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>회원관리</title>
		<link rel="stylesheet" type="text/css" href="/shkim/css/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/shkim/project/css/member_style.css" />
		<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script type="text/javascript" src="/shkim/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/shkim/js/jquery-ui.js"></script>
		<script>
		// [ 페이지 로드 시 실행되는 함수 ] ======
        fn_search(1);
		// [ 조회 버튼, 페이지 버튼 클릭 시 실행되는 함수 ] ======
		function fn_search(page) {
			let upart = $("#upart").val();
			let utype = $("#utype").val();
			let uconame = $("#uconame").val();
			if(page==null){
				let page = $("#page").val();
			}else{
				
			}
			$.ajax({
				type: "GET",
				url: "member_process.php",
				data: {
					upart: upart,
					utype: utype,
					uconame: uconame,
					page: page,
				},
				success: function (data) {
					$("#tr").html(data);
				},
				error: function (data) {
					alert("검색 실패");
					consol.log(error);
				},
			});
		};
		// [ 담당자 링크 클릭 시 실행되는 함수 ] ======
		function fn_dsp(m_uid, name, uid, page, upart, utype, uconame) {

			$("#dialog-message").dialog({
				modal:true,
				width:800,
				height:400,
				buttons: {
					"취소": function() {$(this).dialog('close');},
					"설정하기": function() {
						$(this).dialog('close');
                        let check_id = $("input[name='input_check']:checked").val();
						let mode = "update";
						if(m_uid == check_id) {
							alert("이미 선택된 담당자입니다.");
						}else{
							//선택한 담당자 value값(id)을 input value에 담아 member_db.php에 보낸다.
							$.ajax({
								type: "GET",
								url: "member_process.php",
								data: {
									upart: upart,
									utype: utype,
									uconame: uconame,
									page: page,
									check_id: check_id,
									uid: uid,
									mode: mode,
								},
								success: function (data) {
									alert("담당자 변경 성공");
									$("#tr").html(data);
								},
								error: function (data) {
									alert("담당자 변경 실패");
									consol.log(error);
								},
							});
						}
					},
				}
			});
		};
		</script>
	</head>
	<body>
		<h1><a href="http://192.168.3.8/shkim/project/member_manage.php">회원관리</a></h1>
		<hr align="left" class="manage">
		<div id="form">
			<select id="upart" name="upart">
				<option value="" >부서전체</option>
				<option value="1"name="1">마케팅1팀</option>
				<option value="2"name="2">마케팅2팀</option>
				<option value="3"name="3">마케팅3팀</option>
			</select>
			<select id="utype" name="utype">
				<option value="" >회원전체</option>
				<option value="1" name="1">대행사</option>
				<option value="11"name="11">랩사</option>
				<option value="2"name="2">관리자</option>
				<option value="3"name="3">광고주</option>
				<option value="31" name="31">개인광고주</option>
				<option value="4" name="4">퍼블리셔</option>
			</select>
			<select>
				<option value="" >업체명</option>
			</select>
			<input id = "uconame" name="uconame" type="textarea" value="<?=$uconame?>"/>
			<input id = "page" name="page" type="hidden" value="<?=$page?>"/>
			<input type="button" class="submit" value="조회" onclick="fn_search()"/>
		</div>
		<hr align="left" class="manage">
		<div id="tr">
		</div>
		<div id="check_val">
		</div>
	</div>
	</body>
</html>