<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Array</h1>
    <?php
    $al = array('a', 'b', 'c', 'd');
    array_push($al, 'e', 'f', 'g');
    foreach($al as $value) {
        echo $value."<br>";
    }
    echo $al[2]."<br>";
    var_dump(count($al));
    ?>
</body>
</html>