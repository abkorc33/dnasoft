<?  // [ 변수처리 및 현재날짜 하루 전 날짜로 표기 ] =========
	$timestamp = strtotime("-1 days");
	$s_date = date("Y-m-d", $timestamp);

	//  [ DB connect ] ===========
    $INC_HOME = "../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

    $db = new db_conf("DSP_MAIN_114", "dsp_log");
	?>
	<!doctype html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>시간대별 상태 로그 카운트</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/shkim/project/css/style.css" />
		<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
		<script>
			// [ datepicker 설정 ] ======
			$(function() {
				$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd', maxDate: '-1d'});
			});

			// [ 검색 클릭 시 실행되는 함수 ] ======
			function fn_search() {
				if(!$("#select").val()){
					alert("지면을 선택하세요.");
					$("#select").focus();
					return false;
				}else if(!$("#type").val()) {
					alert("상태를 선택하세요.");
					$("#type").focus();
					return false;
				}else{
					console.log($("#datepicker").val());
					console.log($("#select").val());
					console.log($("#type").val());
				}

				let select = $("#select").val();
				let s_date = $("#datepicker").val();
				let type = $("#type").val();

				$.ajax({
					type: "POST",
					url: "data2.php",
					data: {
						select: select,
						s_date: s_date,
						type: type,
					},
					success: function (data) {
						//alert("검색 완료 : "+s_date+select+type);
						//alert(data);
						$("#table").html(data);
					},
					error: function (data) {
						alert("검색 실패");
						consol.log(error);
					},
				});
			};
		</script>
	</head>
	<body>
		<!-- <form id='form' name='form' method="POST" action="data.php"> -->
		<div id="form">
			<h1 class="title"><<[시간대별 상태 로그 카운트]>></h1>
            <div style="font-size: 12px;">일자: <input type="text" id="datepicker" name="datepicker" value=<?=$s_date?> required>
			<select id="select" name="select">
				<option value="" >지면을 선택하세요.</option>
				<option value="momento00001" name="momento00001">캐시워크-momento00001</option>
				<option value="runcomm202200001"name="runcomm202200001">주식회사 런커뮤니케이션즈-runcomm202200001</option>
			</select>
			<select id="type" name="type">
				<option value="" >상태를 선택하세요.</option>
				<option value="균등예산제한" name="균등예산제한">균등예산제한</option>
				<option value="광고종료"name="광고종료">광고종료</option>
				<option value="유효시간 무효처리"name="유효시간 무효처리">유효시간 무효처리</option>
				<option value="IP BLOCK 무효"name="IP BLOCK 무효">IP BLOCK 무효</option>
			</select>
			<input type="submit" class="submit" value="검색" onclick="fn_search()"/>
			</div>
		</div>
		<div id="table">
        </div>
		<!-- </form> -->
    </body>
</html>