<?
//  [ DB connect ] ===========
$INC_HOME = "../include";

include "$INC_HOME/dbcon.rc" ; 
include "$INC_HOME/common_func.rc";
include "$INC_HOME/static_var.rc";

//	[ 회원DB ] ============
$db = new db_conf("DSPMAINDB_TEST", "fastcampus");

?>
<!doctype html>
<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
	<title>강의자료 게시판</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./html5/assets/css/main.css" />
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css" />
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
	<script type="text/javascript" src="/shkim/js/common.js"></script>
	<script>
	$(function() {
		//	[ 페이지 내 게시글이 1개 이상일 경우 드래그 드롭 순서변경 가능 ] ============
		let dragidx = $("#maxdragidx").val();
		if(dragidx > 1){
			$("#sortable").sortable({
				placeholder: "highlight", // 드래그 중인 아이템이 놓일 자리를 표시할 스타일을 지정
				//rlevert: true,
				//	[ 드래그가 끝난 후 실행되는 코드 ] ============
				update: function(event, ui){
					//	[ 페이지 내 가장 큰 dragidx ] ============
					let dragidx = $("#maxdragidx").val();
					//	[ 드래그가 끝난 후 dragidx desc 정렬 ] ============
					$("#sortable tr").each(function(){
						$(this).children("#num").text(dragidx);
						dragidx = dragidx-1;
					});
					//	[ Ajax로 보낼 데이터 변수 정의 ] ============
					let mode = "idxRow";
					let myArray1 = [];	// idx 고유한 값
					let myArray2 = [];	// 드래그로 변경된 dragidx 값

					//	[ Ajax로 보낼 데이터 추출 ] ============
					$("#sortable tr td").each(function(){
						let this_id = $(this).attr("data_idx");
						if(this_id){
							let this_id2 =$("[data_idx='"+this_id+"']").text();

							myArray1.push(this_id.replace('num_', ''));
							myArray2.push(this_id2);
						}
					});
					let page = "<?=$page?>";
					if(page == ""){
						page =1;
					}
					$.ajax({
						type:"POST",
						url:"./page/board/process.php",
						data:{
							mode: mode,
							myArray1: myArray1,
							myArray2: myArray2,
						},
						success: function(data){
							alert('index가 새롭게 정렬되었습니다.');
							location.href = "http://192.168.3.8/shkim/index.php?page="+page;
						},
						error: function(status){
							alert("순서 변경 실패");
							console.log(status);
						},
					});
				}
			});
		//	[ 페이지 내 게시글이 1일 경우 순서변경 불가능 ] ============
		}else{}
	});
	</script>
</head>
<body class="is-preload">
	<div id="header"> 
		<h1><a href="http://192.168.3.8/shkim/index.php">강의자료 게시판</a></h1>
		<h4>JAVA강의 업로드 게시판입니다.</h4>
		<!-- 검색 -->
		<div class="row justify-content-between">
			<form action="/shkim/page/board/search_result.php" method="get" class="col input-group">
				<div class="col input-group">
					<select name="catgo">
						<option value="title">제목</option>
						<option value="name">글쓴이</option>
						<option value="content">내용</option>
					</select>
					<input type="text" name="search" required="required" />
					<button class="actions">검색</button>
				</div>
			</form>
			<div class="col-sm-3">
				<a href="/shkim/page/board/write.php">
				<button class="actions">글쓰기</button>
				</a>
			</div>
		</div>
		<div id="main">
		<section id="content" class="main">
		<div class="table-wrapper">
		<table>
			<thead>
			<tr id="top">
				<th>번호</th>
				<th>제목</th>
				<th>글쓴이</th>
				<th>작성일</th>
				<th>조회수</th>
				<!-- <th>idx / 순번</th> -->
			</tr>
			</thead>
			<tbody id="sortable">
<?
			if(isset($_GET['page'])){
				$page = $_GET['page'];
			}else{
				$page = 1;
			}

			//  [ test DB연결 ] ===========
			$qry_member1 = "select * from board";
			$res_member = $db -> query_func($qry_member1,1);
			$row_num = $db -> query_func($res_member,2); //게시판 총 레코드 수

			$list = 5; //한 페이지에 보여줄 개수
			$block_ct = 5; //블록당 보여줄 페이지 개수

			$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
			$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
			$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

			$total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
			if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지막 번호가 페이지수보다 많다면 마지박번호는 페이지 수
			$total_block = ceil($total_page/$block_ct); //블럭 총 개수
			$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.
			
			if($page==1){
				$i = $row_num;
			}else if($page==2){
				$i = $row_num - $list;
			}else if($page==3){
				$i = $row_num - ($list*2);
			}else if($page==4){
				$i = $row_num - ($list*3);
			}          

			// board테이블에서 idx를 기준으로 내림차순해서 10개까지 표시
			$qry_member2  = "select title, dragidx, idx, name, regdate, hit, thumbup, lock_post
							from board
							order by dragidx desc limit {$start_num}, {$list}"; 
			$res_member = $db -> query_func($qry_member2,1);
			//	[ while 반복문 시작 ] ============
			while(list($title, $dragidx, $idx, $name, $regdate, $hit, $thumbup, $lock_post) = $db -> query_func($res_member,3)){
				//댓글 수 카운트
				$qry_member3 = "select * from reply where con_num='".$idx."'"; //reply테이블에서 con_num이 board의 idx와 같은 것을 선택
				$res_member2 = $db -> query_func($qry_member3,1);
				$rep_count = $db -> query_func($res_member2,2); //num_rows로 정수형태로 출력

				if($maxdragidx < $dragidx){
					$maxdragidx = $dragidx;
				}
				//title이 30을 넘어서면 ...표시
				if(strlen($title)>30){ 
					$title=str_replace($title, mb_substr($title,0,30,"utf-8")."...", $title);
				}
?>
				<tr>
					<td id="num" data_idx="num_<?=$idx?>">
<?
					if($i<=$row_num){
						echo $i;
						$i--;
					}
?>
					</td>
					<td>
<?
					$lockimg = "<img src='/shkim/img/lock.png' alt='lock' title='lock' with='20' height='20' />";
					$boardtime = mb_substr($regdate, 0, 10); //$boardtime변수에 board['regdate']값에서 날짜값만 넣음(시간 제외)
					$timenow = date("Y-m-d"); //$timenow변수에 현재 시간 Y-M-D를 넣음

					if($boardtime==$timenow){
						$img = "<img src='/shkim/img/new.png' alt='new' title='new' />";
					}else{
						$img ="";
					}

					if($lock_post=="1"){ 
?>
					<a href='/shkim/page/board/ck_read.php?idx=<?=$idx?>'><?php echo $title, $lockimg, $img;
					} else{ 
?>
					<a href='/shkim/page/board/read.php?idx=<?=$idx ?>'><?=$title?><span class="re_ct">[<?=$rep_count?>]<?=$img ?></span></a></td>
<?
					}
?>
					<td><?=$name?></td>
					<td><?=$regdate?></td>
					<td><?=$hit?></td>
					<!-- <td><? echo "{$idx} / {$dragidx}" ?></td> -->
				</tr>
<?			
			}
			//	[ while 반복문 끝 ] ============
?>
		</tbody>
		</table>
		</div>
		</section>
		</div>
		<!---페이징 넘버 --->
		<div id="page_num" class="row justify-content-center">
			<ul class="col d-flex justify-content-center">
<?
			if($page <= 1){ //만약 page가 1보다 크거나 같다면
				echo "<li class='fo_re'>처음</li>"; //처음이라는 글자에 빨간색 표시 
			}else{
				echo "<li><a href='?page=1'>처음</a></li>"; //알니라면 처음글자에 1번페이지로 갈 수있게 링크
			}
			if($page <= 1){ //만약 page가 1보다 크거나 같다면 빈값

			}else{
				$pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
				echo "<li><a href='?page=$pre'>이전</a></li>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
			}
			for($i=$block_start; $i<=$block_end; $i++){ 
				//for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
				if($page == $i){ //만약 page가 $i와 같다면 
					echo "<li class='fo_re'>$i</li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
				}else{
					echo "<li><a href='?page=$i'>$i</a></li>"; //아니라면 $i
				}
			}
			if($block_num >= $total_block){ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
			}else{
				$next = $page + 1; //next변수에 page + 1을 해준다.
				echo "<li><a href='?page=$next'>다음</a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
			}
			if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
				echo "<li class='fo_re'>마지막</li>"; //마지막 글자에 긁은 빨간색을 적용한다.
			}else{
				echo "<li><a href='?page=$total_page'>마지막</a></li>"; //아니라면 마지막글자에 total_page를 링크한다.
			}
?>
			</ul>
			<!-- jQuery 에서 사용하기 위한 값 -->
            <input type="hidden" id='maxdragidx' value="<?=$maxdragidx?>">
		</div>
	</div>
</body>
</html>