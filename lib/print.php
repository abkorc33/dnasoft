<?php
function print_title() {
    if(isset($_GET['id'])) {
        echo htmlspecialchars($_GET['id']);
    } else {
        echo "Welcome";
    }
}
function print_description() {
    if(isset($_GET['id'])) {
        // 본문 내용 부분을 htmlspecialchars()로 제한하면 이미지, 줄바꿈 태그 작동x
        // strip_tags() : 태그를 다 날린다, 특정 태그 허용 / 옵션지정 가능
        // basename() : 파일의 경로에서 파일명만 추출해주는 함수
        $basename = basename($_GET['id']);
        echo htmlspecialchars(file_get_contents("data/".$basename));
    } else {
        echo "Hello, PHP";
    }
}
function print_list() {
    $list = scandir('./data');
    $i = 0;
    while($i < count($list)) {
        $title = htmlspecialchars($list[$i]);
        if($list[$i] != ".") {
            if($list[$i] != "..") {
                echo "<li><a href='index.php?id=$title'>$title</a></li>\n";
            }
        }
        $i++;
    }
}
?>