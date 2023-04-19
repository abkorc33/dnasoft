<? //  [ 변수처리 ] =========== 
$date = $_POST[datepicker];
$mcode = $_POST[select];
$arr_mcode[$mcode] = "selected";

if($date){ // [ 날짜 dateFormat 커스텀 ] ==========
    $arr_date = explode('-', $date, 3);
    $s_date = implode('-', $arr_date);
} else{ // [ 날짜선택 안할 시 현재날짜 하루 전 날짜로 표기 ] =========
	$timestamp = strtotime("-1 days");
	$s_date = date("Y-m-d", $timestamp);
}
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
					return false;
				}else{
					alert($("#select").val());
				}

				let select = $("#select").val();
				let s_date = $("#datepicker").val();

				$.ajax({
					type: "POST",
					url: "data.php",
					data: {
						select: select,
						s_date: s_date,
					},
					success: function (data) {
						alert("검색 완료 : "+s_date+select);
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
				<option value="momento00001" name="momento00001" <?=$arr_mcode[momento00001]?>>캐시워크-momento00001</option>
				<option value="runcomm202200001"name="runcomm202200001" <?=$arr_mcode[runcomm202200001]?>>주식회사 런커뮤니케이션즈-runcomm202200001</option>
			</select>
			<input type="submit" class="submit" value="검색" onclick="fn_search()"/>
			</div>
		</div>
		<div id="table">
        </div>
		<!-- </form> -->