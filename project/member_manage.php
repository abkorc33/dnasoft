<?	//  [ DB connect ] ===========
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
		<title>회원구분</title>
		<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script>
			// [ 조회 클릭 시 실행되는 함수 ] ======
			function fn_search() {
				
				let upart = $("#upart").val();
				let utype = $("#utype").val();
				let uconame = $("#uconame").val();

				$.ajax({
					type: "POST",
					url: "member_db.php",
					data: {
						upart: upart,
						utype: utype,
						uconame: uconame
					},
					success: function (data) {
						//alert("조회 완료");
						//alert(data);
						$("#tr").html(data);
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
		<div id="form">
			<h1>회원관리</h1>
			<select id="upart" name="upart">
				<option value="" >부서전체</option>
				<option value="1"name="마케팅1팀">마케팅1팀</option>
				<option value="2"name="마케팅2팀">마케팅2팀</option>
				<option value="3"name="마케팅3팀">마케팅3팀</option>
			</select>
			<select id="utype" name="utype">
				<option value="" >회원전체</option>
				<option value="1" name="대행사">대행사</option>
				<option value="11"name="랩사">랩사</option>
				<option value="2"name="관리자">관리자</option>
				<option value="3"name="광고주">광고주</option>
				<option value="31" name="개인광고주">개인광고주</option>
				<option value="4" name="퍼블리셔">퍼블리셔</option>
			</select>
			<select>
				<option value="" >업체명</option>
			</select>
			<input id = "uconame" type="textarea"/>
			<input type="submit" class="submit" value="조회" onclick="fn_search()"/>
			</div>
		</div>
		<div id = "tr">
		</div>
		
	<? /* 페이징
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	
	$qry_member = "
		select d.idx, d.utype, d.uid, d.uconame, d.tot_pay_money, d.last_date, d.tot_bank_money, d.uname, r.uteam, r.upart
		from  dsp_manage.dsp_member as d, real_manage.real_master as r
		where d.utype like '{$utype}%' 
			and r.uteam = 3
			and r.upart like '%{$upart}%' 
			and d.uconame like '%{$uconame}%'
			and d.uname = r.uname
		";

		$row_num = mysqli_num_rows($qry_member); //게시판 총 레코드 수
		$list = 5; //한 페이지에 보여줄 개수
		$block_ct = 5; //블록당 보여줄 페이지 개수

		$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
		$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
		$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

		$total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
		if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
		$total_block = ceil($total_page/$block_ct); //블럭 총 개수
		$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.
*/
		?>


<!---페이징 넘버
    <div id="page_num">
      <ul>
        <?php
          if($page <= 1)
          { //만약 page가 1보다 크거나 같다면
            echo "<li style='color: orange'>처음</li>"; //처음이라는 글자에 빨간색 표시 
          }else{
            echo "<li><a href='?page=1'>처음</a></li>"; //아니라면 처음글자에 1번페이지로 갈 수있게 링크
          }
          if($page <= 1)
          { //만약 page가 1보다 크거나 같다면 빈값
            
          }else{
          $pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
            echo "<li><a href='?page=$pre'>이전</a></li>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
          }
          for($i=$block_start; $i<=$block_end; $i++){ 
            //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
            if($page == $i){ //만약 page가 $i와 같다면 
              echo "<li style='color: orange'>[$i]</li>"; //현재 페이지에 해당하는 번호에 빨간색을 적용한다
            }else{
              echo "<li><a href='?page=$i'>[$i]</a></li>"; //아니라면 $i
            }
          }
          if($block_num >= $total_block){ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
          }else{
            $next = $page + 1; //next변수에 page + 1을 해준다.
            echo "<li><a href='?page=$next'>다음</a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
          }
          if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
            echo "<li style='color: orange'>마지막</li>"; //마지막 글자에 긁은 빨간색을 적용한다.
          }else{
            echo "<li><a href='?page=$total_page'>마지막</a></li>"; //아니라면 마지막글자에 total_page를 링크한다.
          }
        ?>
      </ul>
    </div>
--->
	</body>
</html>