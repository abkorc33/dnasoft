<?
include $_SERVER['DOCUMENT_ROOT']."/shkim/db.php";
?>
<!doctype html>
<head>
	<meta charset="UTF-8">
	<title>강의자료 게시판</title>
	<link rel="stylesheet" type="text/css" href="/shkim/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/shkim/css/jquery-ui.css" />
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
	<script>
	$(function() {
		$("#sortable").sortable({
			placeholder: "highlight", // 드래그 중인 아이템이 놓일 자리를 표시할 스타일을 지정
			start: function(event, ui){	// 드래그 시작시 호출되는 이벤트 핸들러
				// ui.item.data 아이템에 키, 값 쌍으로 데이터를 저장할 수 있다.
				ui.item.data("start_pos", ui.item.index());	// 드래그 하는 아이템의 위치를 가져온다. 첫 번째 아이템은 0
			},
			stop: function(event, ui){	// 드랍하면 호출되는 이벤트 핸들러
				let spos = ui.item.data("start_pos");	// 원래 게시글의 index값
				let epos = ui.item.index();	// 드래그 드랍으로 변경된 index값
			},
			revert: true,
			update: function(){
				let reverse = $("#sortable tr").get().reverse();
				let page = "<?=$page?>";
				let pageNum2 = 5;
				let pageNum1 = 1;
				$(reverse).each(function(index){
					if(page=="" || page==1){
						$(this).children("#num").text(index+pageNum2);
						$(this).children("input[type='number']").attr("name", index+pageNum2);
					}else if(page==2){
						$(this).children("#num").text(index+pageNum1);
						$(this).children("input[type='number']").attr("name", index+pageNum1);
					}
					//document.querySelector('#name').innerHTML="현재담당자 :"+name+"<input type='hidden' id='uid' value="+uid+">";
				});
			

				/*
				let mode = "idxRow";
				let dragRow = "input[name='input_check']:data-order".val();
				$.ajax({
					type:"POST",
					url:"process.php",
					data:{
						dragRow: dragRow,
						mode: mode,
					},
					dataType:"json",
					success: function (data) {
						if (data.result > 0) {
							alert("순서가 변경되었습니다.");
						}
						else {
							alert("순서 변경 처리중 에러가 발생했습니다.\n잠시후 다시 시도해 주세요.");
						}
					},
					error: function (e) {
						alert("순서 변경 실패");
					}
				});
				*/
			}
		});
	});
	</script>
</head>
<body>
	<div id="board_area"> 
		<h1><a href="http://192.168.3.8/shkim/index.php">강의자료 게시판</a></h1>
		<h4>JAVA강의 업로드 게시판입니다.</h4>
		<!-- 검색 -->
		<div id="search_box">
			<form action="/shkim/page/board/search_result.php" method="get">
				<select class="s_search" name="catgo">
					<option value="title">제목</option>
					<option value="name">글쓴이</option>
					<option value="content">내용</option>
				</select>
				<input class="search" type="text" name="search" size="40" required="required" /> <button class="btn_search">검색</button>
			</form>
		</div>
		<div id="write_btn">
			<a href="/shkim/page/board/write.php"><button class="btn">글쓰기</button></a>
		</div>
		<table class="list-table" id="sortable">
			<thead>
			<tr>
				<th width="70">번호</th>
				<th width="500">제목</th>
				<th width="120">글쓴이</th>
				<th width="100">작성일</th>
				<th width="100">조회수</th>
				<th width="100">추천수</th>
			</tr>
			</thead>
<?
			if(isset($_GET['page'])){
				$page = $_GET['page'];
			}else{
				$page = 1;
			}
			$sql = mq("select * from board");
			$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
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
				$i = 1;
			}else if($page==2){
				$i = ($page * $page) + $page;
			}else{
				$i = (($page * $list) - $list) + 1;
			}

			// board테이블에서 idx를 기준으로 내림차순해서 10개까지 표시
			$sql2 = mq("select * from board order by dragidx desc limit $start_num, $list"); // 드래그로 바뀐 index순서로 적용하려면 order by dragidx

			while($board = $sql2->fetch_array()){
				//title변수에 DB에서 가져온 title을 선택
				$title=$board["title"]; 
				if(strlen($title)>30){ 
					//title이 30을 넘어서면 ...표시
					$title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
				}
				//댓글 수 카운트
				$sql3 = mq("select * from reply where con_num='".$board['idx']."'"); //reply테이블에서 con_num이 board의 idx와 같은 것을 선택
				$rep_count = mysqli_num_rows($sql3); //num_rows로 정수형태로 출력
?>
			<tbody>
			<tr>
				<td width="70" id="num" class="idxRow">
				<?=$board['dragidx']?>				
				</td>
				<td width="500" class="idxRow">
<?
				$lockimg = "<img src='/shkim/img/lock.png' alt='lock' title='lock' with='20' height='20' />";
				$boardtime = mb_substr($board['regdate'], 0, 10); //$boardtime변수에 board['regdate']값에서 날짜값만 넣음(시간 제외)
				$timenow = date("Y-m-d"); //$timenow변수에 현재 시간 Y-M-D를 넣음

				if($boardtime==$timenow){
					$img = "<img src='/shkim/img/new.png' alt='new' title='new' />";
				}else{
					$img ="";
				}

				if($board['lock_post']=="1"){ 
?>
				<a href='/shkim/page/board/ck_read.php?idx=<?php echo $board["idx"];?>'><?php echo $title, $lockimg;
				} else{ 
?>
				<a href='/shkim/page/board/read.php?idx=<?=$board["idx"]; ?>'><?=$title; }?><span class="re_ct">[<?=$rep_count; ?>]<?=$img; ?></span></a></td>
				<td width="120" class="idxRow"><?=$board['name']?></td>
				<td width="100" class="idxRow"><?=$board['regdate']?></td>
				<td width="100" class="idxRow"><?=$board['hit'];?></td>
				<td width="100" class="idxRow"><?=$board['thumbup']?></td>
			</tr>
			</tbody>
<?
			} 
?>
		</table>

		<!---페이징 넘버 --->
		<div id="page_num" align="center">
			<ul>
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
					echo "<li class='fo_re' style='padding: 5px 10px 5px 10px;'>$i</li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
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
		</div>
	</div>
</body>
</html>