<?
//  [ DB connect ] ===========
$INC_HOME = "../../../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ testDB ] ============
$db = new db_conf("MAINDB_SLV", "fastcampus");

if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}
?>
<!doctype html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
<title>게시판</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../../css/main.css" />
</head>
<body class="is-preload">
<div id="header"> 
<!-- 검색 -->
<?
  /* 검색 변수 */
  $catagory = $_GET['catgo'];
  $search_con = $_GET['search'];
  $qry_member1 = "select title, idx, regdate, lock_post, name, hit from board where $catagory like '%{$search_con}%' order by idx desc";
?>
	<h1><?=$catagory?>에서 '<?=$search_con?>'검색결과</h1>
	<h4 style="margin-top:30px;"><a href="../../index.php"><button class="actions">홈으로</button></a></h4>
	<div id="main">
		<section id="content" class="main">
		<div class="table-wrapper">
		<table class="list-table">
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
							where $catagory like '%{$search_con}%' 
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
					$lockimg = "<img src='../../img/lock.png' alt='lock' title='lock' with='20' height='20' />";
					$boardtime = mb_substr($regdate, 0, 10); //$boardtime변수에 board['regdate']값에서 날짜값만 넣음(시간 제외)
					$timenow = date("Y-m-d"); //$timenow변수에 현재 시간 Y-M-D를 넣음

					if($boardtime==$timenow){
						$img = "<img src='../../img/new.png' alt='new' title='new' />";
					}else{
						$img ="";
					}

					if($lock_post=="1"){ 
?>
					<a href='./ck_read.php?idx=<?=$idx?>'><?php echo $title, $lockimg, $img;
					} else{ 
?>
					<a href='./read.php?idx=<?=$idx ?>'><?=$title?><span class="re_ct">[<?=$rep_count ?>]<?=$img ?></span></a></td>
<?
					}
?>
					<td><?=$name?></td>
					<td><?=$regdate?></td>
					<td><?=$hit?></td>
					<!-- <td><? //echo "{$idx} / {$dragidx}" ?></td> -->
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
    <!-- 검색 추가 -->
    <div class="d-flex justify-content-center">
      <form action="./search_result.php" method="get">
		<div class="col input-group" style="margin-top: 20px;">
			<select class="s_search" name="catgo">
				<option value="title">제목</option>
				<option value="name">글쓴이</option>
				<option value="content">내용</option>
			</select>
			<input class="search" type="text" name="search" required="required"/>
			<button class="actions">검색</button>
		</div>
	</form>
  </div>
</div>
</body>
</html>