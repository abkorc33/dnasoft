<?php
file_put_contents('data/'.$_POST['title'], $_POST['description']);
// 리다이렉션
header('Location: /index.php?id='.$_POST['title']);
?>