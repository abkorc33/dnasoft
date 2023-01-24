<?php
// 파일명 수정/변경
rename('data/'.$_POST['old_title'], 'data/'.$_POST['title']);
// 수정된 파일의 description파일을 변경
file_put_contents('data/'.$_POST['title'], $_POST['description']);
// 리다이렉션
header('Location: /index.php?id='.$_POST['title']);
?>