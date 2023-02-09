<?	//  [ 변수처리 ] =========== 
    $s_date = $_POST[s_date];
    $mcode = $_POST[select];

    //  [ DB connect ] ===========
	$INC_HOME = "../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

    $db = new db_conf("DSP_MAIN_114", "dsp_log");
	
    //  [ 지면유무 ] 유 ===========
	if($mcode) {

        //  [ ip block 무효 데이터추출 ] ===========
        $qry_member = "
        select a.n_time, count( a.n_time )
		from (
            select mid( date, 12, 2 ) as n_time
            from dsp_log.refresh_code
            where mcode like '{$mcode}%' and date like '{$s_date}%' and type='ip block 무효' ) as a
		group by a.n_time";
        $res_member = $db -> query_func($qry_member,1);
        while(list($date, $count) = $db -> query_func($res_member,3)){
            $arr_tr[$date] = $count;
        }

	    echo "<div class='head'>선택한 지면 : ".$mcode."의 시간대 별 카운트 입니다.</div>";
        echo "<h3>{$mcode}</h3>";
        for($i=0;$i<=23;$i++){
            $num = sprintf('%02d',$i);

            if($arr_tr[$num]==null) {
                $tr .= "<tr>
                            <td>".$num."</td>
                            <td>0</td>
                        </tr>";
            } else {
                $tr .="<tr>
                            <td>".$num."</td>
                            <td class='num'>".$arr_tr[$num]."</td>
                        </tr>";
            }
        }

        $tr = "
        	<table border='1'>
                <th>시간대</th>
                <th>카운트</th>
                {$tr}
            </table>
        ";
    }

    //  [ 지면유무 ] 무 ===========
    else {
        $tr .="<tr><td colspan=2>선택된 지면이 없습니다.</td></tr>";
    }
// [ 결과 값 출력 ]	 ===========
echo $tr;
?>
