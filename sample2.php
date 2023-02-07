<? 
//print_R($_POST);

$date = $_POST[datepicker];
if($date){
    $arr_date = explode('-', $date, 3);
    //$arr_date2 = implode('-', array_reverse($arr_date));
    $result = $arr_date[0]."-".$arr_date[1]."-".$arr_date[2];
}
else{
    //오늘날짜 -1
    $result = date("Y-m-d", time());

}
$mcode = $_POST[select];
$arr_mcode[$mcode] = "selected";
if($mcode) {
	echo "선택한 지면 : ".$mcode."의 시간대 별 카운트 입니다.";
}

    $INC_HOME = "../include";

    include "$INC_HOME/dbcon.rc" ; 
    include "$INC_HOME/common_func.rc";
    include "$INC_HOME/static_var.rc";

    $db = new db_conf("DSP_MAIN_114", "dsp_log");
	
	// MID(필드명, 시작index, 원하는 자릿수)
	?>
	<!doctype html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>시간대별 상태 로그 카운트</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
		<script>
        //https://programmer93.tistory.com/29
		$( function() {
			$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd', maxDate: '-1d'});
		 });
		</script>
	</head>
	<body>
		<form id='form' name='form' method="POST" action="<?php echo $_SERVER['PHP_SELF'] ;?>">
            Date: <input type="text" id="datepicker" name="datepicker" value=<?=$result?> required>
			<select id="select" name="select">
				<option value="" >지면을 선택하세요.</option>
				<option value="momento00001" name="momento00001" <?=$arr_mcode[momento00001]?>>캐시워크-momento00001</option>
				<option value="runcomm202200001"name="runcomm202200001" <?=$arr_mcode[runcomm202200001]?>>주식회사 런커뮤니케이션즈-runcomm202200001</option>
			</select>
			<input type="submit">
		</form>
<?	if($mcode) {
    $qry_member = "SELECT A.n_time, COUNT( A.n_time )
		FROM (
		SELECT MID( DATE, 12, 2 ) AS n_time
		FROM dsp_log.refresh_code
		WHERE mcode LIKE '$mcode%'
		AND DATE LIKE '2023-02-01%'
		AND TYPE = 'ip block 무효'
		) AS A
		GROUP BY A.n_time";
    $res_member = $db -> query_func($qry_member,1);
	while(list($date, $count) = $db -> query_func($res_member,3)){
		$arr_tr[$date] = $count;
		//echo $arr_tr[$date];
	}
?>
<h3><?=$mcode?></h3>

<?
    for($i=0;$i<=23;$i++){
        //$num = str_pad($i, 2, 0, STR_PAD_LEFT);
        
        $num = sprintf('%02d',$i);
		//$time = "<td>".$num."</td>";
		//var_dump($arr_tr[$num]);
        //$time = "<tr><td>".$num."</td>";

        //$tr .= "<tr><td>".$num."</td>";
		//echo $time;

		if($arr_tr[$num]==null) {
			$tr .= "<tr>
                        <td>".$num."</td>
                        <td>0</td>
                    </tr>";
			//echo $time_null;
		} else {
			$tr .="<tr>
                        <td>".$num."</td>
                        <td>".$arr_tr[$num]."</td>
                    </tr>";
			//echo $time_isnull;
		}
    }
} else {
    $tr .="<tr><td colspan=2>선택된 지면이 없습니다.</td></tr>";
	//echo "선택된 지면이 없습니다.";
}
?>


	<table border="1">
		<th>시간대</th>
		<th>카운트</th>
        <?=$tr?>
	</table>


</body>
</html>

<?
/*
	    for($i=0;$i<=23;$i++){
        $num = str_pad($i, 2, 0, STR_PAD_LEFT);
        echo "<tr><td>".$num."</td>";

		if($arr_tr[$num]==null) {
			echo "<td>0</td></tr>";
			} else {
			echo "<td>".$arr_tr[$num]."</td></tr>"
			}
    }

	for($i=0;$i<=23;$i++){
        $num = str_pad($i, 2, 0, STR_PAD_LEFT);
        echo "<tr><td>".$num."</td><td>".$arr_tr[$num]."</td></tr>";
		//var_dump($arr_tr[$num]);
		
    }

if($mcode == 'momento00001') {
?>
	<h3>캐시워크-momento00001</h3>
	<table border="1">
		<th>시간대</th>
		<th>카운트</th>
<?
	for($i=1; $i<25; $i++) {
		$num = str_pad($i, 2, 0, STR_PAD_LEFT);
		$tr="<tr><td>$num</td>";
		echo $tr;
	}

	$qry_member = "SELECT A.n_time, COUNT( A.n_time )
		FROM (
		SELECT MID( DATE, 12, 2 ) AS n_time
		FROM dsp_log.refresh_code
		WHERE mcode LIKE 'momento00001%'
		AND DATE LIKE '2023-02-01%'
		AND TYPE = 'ip block 무효'
		) AS A
		GROUP BY A.n_time";
    $res_member = $db -> query_func($qry_member,1);
	while(list($date, $count) = $db -> query_func($res_member,3)){
		$tr="<td>$count</td></tr>";
		echo $tr;
	}
?>
	</table>
<?
	} else if($mcode == 'runcomm202200001') {
?>
	<h3>런커뮤니케이션즈-runcomm202200001</h3>
	<table border="1">
		<th>시간대</th>
		<th>카운트</th>
<?
	$qry_member = "SELECT A.n_time, COUNT( A.n_time )
		FROM (
		SELECT MID( DATE, 12, 2 ) AS n_time
		FROM dsp_log.refresh_code
		WHERE mcode LIKE 'runcomm202200001%'
		AND DATE LIKE '2023-02-01%'
		AND TYPE = 'ip block 무효'
		) AS A
		GROUP BY A.n_time";
    $res_member = $db -> query_func($qry_member,1);
	while(list($date, $count) = $db -> query_func($res_member,3)){
		$tr = "<tr><td>$date</td>
			<td>$count</td></tr>";
		echo $tr;
	}
?>			
	</table>
<?
	} else {
?>
	<p>지면을 선택해주세요.</p>
<?
	}
*/
?>
