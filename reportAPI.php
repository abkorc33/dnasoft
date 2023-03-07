<?
header('Content-Type: text/html; charset=utf-8');
/*  [ DB connect ] ===========
$INC_HOME = "../../static";

include "$INC_HOME/db_info.rc";
include "$INC_HOME/dbcon.rc";
include "$INC_HOME/static_var.rc";

//	[ testDB ] ============
$db = new db_conf("MAINDB_SLV", "fastcampus");
*/
$reg_dt=$_GET['ndate'];
$today = date("Ymd");
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>리포트 API 호출</title>
    <link rel="stylesheet" type="text/css" href="../../css/me_admin.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        // [ datepicker 설정 ] ======
        $(function() {
            $( "#ndate" ).datepicker({dateFormat: 'yymmdd', maxDate: '-1d'});
        });
    </script>
    <style>
        th{
            padding: 5px;
        }
        td{
            padding: 5px;
        }
    </style>
</head>
<body>
    <div id="form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <h2 align="center"><< 런커뮤니케이션 리포트 >></h2>
        <div  align="center" style="font-size: 12px; padding: 10px;">
<?
        if($reg_dt){
?>
        일자: <input type="text" id="ndate" name="ndate" value="<?=$reg_dt?>" class="w_select2" style="height: 18px;">
<?
        }else if(!$reg_dt){
?>
        일자: <input type="text" id="ndate" name="ndate" value="<?=$today?>" class="w_select2" style="height: 18px;">
<?
        }
?>
        <img class="ui-datepicker-trigger" src="http://realmgr.realclick.co.kr/images/ico_calendar.gif" alt="선택" title="선택" style="cursor: pointer; margin-left: 3px; margin-bottom: -8px;">
        <input type="submit" value="조회" class="btn_bg gray_bg" style="height: 26px;"/>
        </div>
        </form>
    </div>
    <div id="table"  align="center">
    <table style="width: 400px;">
        <thead>
            <tr bgcolor="gray" style="height: 20px;">
                <th class="ct_bg1 ct_tit">번호</th>
                <th class="ct_bg1 ct_tit">날짜</th>
                <th class="ct_bg1 ct_tit">매체명</th>
                <th class="ct_bg1 ct_tit">클릭수</th>
                <th class="ct_bg1 ct_tit">노출수</th>
            </tr>
        </thead>
        <tbody>
<?  if($reg_dt){
        $url = "https://ta.runcomm.co.kr/srv/report/api/realclick?ndate=".$reg_dt;
    }else if(!$reg_dt){
        $url = "https://ta.runcomm.co.kr/srv/report/api/realclick";
    }
        $ch = curl_init();                                 //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
        //curl_setopt($ch, CURLOPT_POST, 1);                 //post로 전송
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //post로 변수전달
         
        $response = curl_exec($ch);
        curl_close($ch);
        //return $response;
        //echo $response;        //결과 값 출력
        $result=json_decode($response, true);
        $arr_result=array_reverse($result['data']);
        $i=count($arr_result);
        foreach ($arr_result as $key => $value){
            if($i<=count($arr_result)){
                $tr .= "<tr class='stripe tablehover'><td class='ct_bg3'>".$i."</td>";
                $i--;
            }
            $tr .= "<td class='ct_bg3'>".$value['reg_dt']."</td>
                        <td class='ct_bg3'>".$value['media_name']."</td>
                        <td class='ct_bg3'>".$value['click']."</td>
                        <td class='ct_bg3'>".$value['impression']."</td>
                    </tr>";  
        }
        echo $tr;
?>
        </tbody>
    </table>
    </div>
</body>
</html>