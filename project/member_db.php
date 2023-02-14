<?  /*******************************************************************/
	// 프로그램명	: member_db.php
	// 제목		: 회원 구분 테스트페이지
	// 작성자		: 김수현
	// 작성일		: 2023.02.14
	//
	// 프로그램 설명: 회원 구분 테스트페이지 입니다.
	/*******************************************************************/
?>
<?	//  [ 변수처리 ] =========== 
    $upart = $_GET[upart];
    $utype = $_GET[utype];
	$uconame = $_GET[uconame];
	$page = $_GET[page];

	//	[ 페이징 변수 ] ============
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}

	$list = 20; //한 페이지에 보여줄 개수
	$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.
	$block_ct = 20; //블록당 보여줄 페이지 개수

	$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
	$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
	$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

	//  [ DB connect ] ===========
	$INC_HOME = "../../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

	//	[ 회원DB ] ============
    $db = new db_conf("DSPMAINDB_TEST", "dsp_manage");

	//  [ 카운트 용도 ] ===========
	$qry_member2 = "
		select *
		from  dsp_manage.dsp_member as d, real_manage.real_master as r
		where d.utype like '{$utype}%' 
			and r.upart like '%{$upart}%' 
			and d.uconame like '%{$uconame}%'
			and d.umaster = r.uid
			and uteam = 3
		";
	$res_member = $db -> query_func($qry_member2,1);
	$row_num = $db -> query_func($res_member,2); // 쿼리 결과 카운트

	//	[ $row_num이 정의된 후 사용해야하는 페이징 변수 ] ============
    $total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
    if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
    $total_block = ceil($total_page/$block_ct); //블럭 총 개수
	
	//  [ 페이지별 NO 출력 조건 ] ===========
	if($page==1){
		$i = 1;
	}else if($page==2){
		$i = ($page * 10) + 1;
	}else{
		$i = (($page * $list) - $list) + 1;
	}

	//  [ 회원 데이터추출 ] =========== 총입금액 - 총소진액 = 광고 잔액
    //	회원구분, 아이디, 업체명, 총소진액, 최근접속일(최종 소진일), 광고잔액(총입금액-총소진액), 담당자, 팀 파트
    $qry_member = "
    select d.utype, d.uid, d.uconame, d.tot_pay_money, d.last_date, d.tot_bank_money, r.uname, r.uteam, r.upart
    from  dsp_manage.dsp_member as d, real_manage.real_master as r
    where d.utype like '{$utype}%' 
        and r.upart like '%{$upart}%' 
        and d.uconame like '%{$uconame}%'
        and d.umaster = r.uid
		and uteam = 3
    limit {$start_num}, {$list}
    ";

    $res_member = $db -> query_func($qry_member,1);
    while(list($m_utype, $uid, $m_uconame, $tot_pay_money, $last_date, $tot_bank_money, $uname, $uteam, $m_upart) = $db -> query_func($res_member,3)){

        $ad_balance = $tot_bank_money - $tot_pay_money;
		
		if($i<=$row_num){
			$tr .= "<tr><td>".$i."</td>";
			$i++;
		}
		
        //	[ 회원유형 구분 및 한글 출력 ] ============
        if($m_utype==1){
            $tr .= "<td>대행사</td>";
        }else if($m_utype==11){
            $tr .= "<td>랩사</td>";
        }else if($m_utype==2){
            $tr .= "<td>관리자</td>";
        }else if($m_utype==3){
            $tr .= "<td>광고주</td>";
        }else if($m_utype==31){
            $tr .= "<td>개인광고주</td>";
        }else if($m_utype==4){
            $tr .= "<td>퍼블리셔</td>";
        }

        //	[ 데이터 추출 ] ============
        $tr .="<td>".$uid."</td>
                <td>".$m_uconame."</td>
                <td>".$tot_pay_money."</td>
                <td>".$last_date."</td>
                <td>".$ad_balance."</td>
                <td>".$uname."</td>
                <td>".$uteam.'-'.$m_upart."</td></tr>";


    }

    //	[ 조건에 해당하는 데이터가 없을 경우 ] ============
    if(!$tr){
        $tr .= "<td colspan=9>조회 할 수 있는 데이터가 없습니다.</td>";
    }
    $tr = "<table border='1'>
				<tr>
                <th>NO</th>
                <th>구분</th>
                <th>아이디</th>
                <th>업체명</th>
                <th>총 소진액</th>
                <th>최종 소진일</th>
                <th>광고 잔액</th>
                <th>담당자</th>
                <th>상세보기</th>
				</tr>
                {$tr}
            </table>";
    
    //	[ table 결과 출력 ] ============
    echo $tr;
?>
	<!---페이징 넘버 --->
	<div id="page_num">
		<p>전체 <?=$row_num?> / 현재 <?=$page?>페이지(총 <?=$total_page?>페이지)</p>
		<ul>
<?
			if($page <= 1){ //만약 page가 1보다 작거나 같다면
				echo "<li class='color'>처음</li>"; //처음이라는 글자에 색 표시 
			}else{
				echo "<li><a onclick='fn_page(1)'>처음</a></li>"; //아니라면 처음글자에 1번페이지로 갈 수있게 링크
			}
			if($page <= 1){ //만약 page가 1보다 작거나 같다면 빈값
				
			}else{
				if($block_start<$block_ct){ // $block_start번호가 20보다 작을 경우 $pre는 1
				$pre = 1;
				}else{
				$pre = $block_start-1; //pre변수를 누르면 현재 페이지보다 이전 block으로 이동
				}
				echo "<li><a onclick='fn_page($pre)'><</a></li>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
			}

			for($i=$block_start; $i<=$block_end; $i++){ 
			//for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
				if($page == $i){ //만약 page가 $i와 같다면 
					echo "<li class='color' style='padding: 5px 10px 5px 10px;'>$i</li>"; //현재 페이지에 해당하는 번호에 색을 적용한다
				}else{
					//echo "<li><a href='?upart=$upart&utype=$utype&uconame=$uconame&page=$i'onclick='fn_page($i)'>[$i]</a></li>"; //아니라면 $i
                    echo "<li><a onclick='fn_page($i)'>$i</a></li>"; //아니라면 $i
				}
			}

			if($block_num >= $total_block){ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
			
			}else{
				$next = $block_end+1; //next를 누르면 현재 페이지 다음 block으로 이동 
				echo "<li><a onclick='fn_page($next)'>></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
			}
			if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
				echo "<li class='color'>마지막</li>"; //마지막 글자에 색을 적용한다.
			}else{
				echo "<li><a onclick='fn_page($total_page)'>마지막</a></li>"; //아니라면 마지막글자에 total_page를 링크한다.
			}
?>
		</ul>
	</div>

