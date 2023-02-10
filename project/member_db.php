<?	//  [ 변수처리 ] =========== 
    $upart = $_POST[upart];
    $utype = $_POST[utype];
	$uconame = $_POST[uconame];

	//  [ DB connect ] ===========
	$INC_HOME = "../../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

	//	[ 회원DB ] ============
    $db = new db_conf("DSPMAINDB_TEST", "dsp_manage"); 
	
		//  [ 회원 데이터추출 ] =========== 총입금액 - 총소진액 = 광고 잔액
		//	회원구분, 아이디, 업체명, 총소진액, 최근접속일(최종 소진일), 광고잔액(총입금액-총소진액), 담당자, 팀 파트
		$qry_member = "
		select d.idx, d.utype, d.uid, d.uconame, d.tot_pay_money, d.last_date, d.tot_bank_money, d.uname, r.uteam, r.upart
		from  dsp_manage.dsp_member as d, real_manage.real_master as r
		where d.utype like '{$utype}%' 
			and r.upart like '%{$upart}%' 
			and d.uconame like '%{$uconame}%'
			and d.uname = r.uname
		";
		//and r.uteam = 3
		$res_member = $db -> query_func($qry_member,1);
		while(list($idx, $utype, $uid, $uconame, $tot_pay_money, $last_date, $tot_bank_money, $uname, $uteam, $upart) = $db -> query_func($res_member,3)){

			//	[ 광고잔액 변수 정의 ] ============
			$ad_balance = $tot_bank_money - $tot_pay_money;

			//	[ 데이터 추출 ] ============
			$tr .="<tr><td>".$idx."</td>";
				
			//	[ 회원유형 구분 및 한글 출력 ] ============
			if($utype==1){
				$tr .= "<td>대행사</td>";
			}else if($utype==11){
				$tr .= "<td>랩사</td>";
			}else if($utype==2){
				$tr .= "<td>관리자</td>";
			}else if($utype==3){
				$tr .= "<td>광고주</td>";
			}else if($utype==31){
				$tr .= "<td>개인광고주</td>";
			}else if($utype==4){
				$tr .= "<td>퍼블리셔</td>";
			}

			//	[ 데이터 추출 ] ============
			$tr .="<td>".$uid."</td>
					<td>".$uconame."</td>
					<td>".$tot_pay_money."</td>
					<td>".$last_date."</td>
					<td>".$ad_balance."</td>
					<td>".$uname."</td>
					<td>".$uteam." - ".$upart."</td></tr>";	
		}
		if(!$tr){
			$tr .= "<tr><td colspan=9>조회 할 수 있는 데이터가 없습니다.</td></tr>";
		}
		$tr = "<table border='1'>
					<th>NO</th>
					<th>구분</th>
					<th>아이디</th>
					<th>업체명</th>
					<th>총 소진액</th>
					<th>최종 소진일</th>
					<th>광고 잔액</th>
					<th>담당자</th>
					<th>상세보기(uteam+upart)</th>
					{$tr}
				</table>";
//	[ 데이터 전송 성공시 member_manage.php로 가는 데이터 ] ============
echo $tr;
?>

