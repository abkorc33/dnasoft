<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Function</title>
</head>
<body>
    <h1>Function</h1>
    <h2>Basic</h2>
    <?php
        function basic() {
            print("lorem ipsum dolor1<br>");
            print("lorem ipsum dolor2<br>");
        }

        basic();
        basic();
    ?>
    <h2>parameter &amp; argument</h2>
    <?php
    function sum($left, $right) {
        print($left+$right);
        print("<br>");
    }
    sum(2,4);
    sum(10,2);
    ?>
    <h2>return</h2>
    <?php
    function sum2($left, $right) {
        return $left+$right;
    }
    print(sum2(2,4));
    // file_put_contents('result.txt', sum2(2,4));
    ?>
</body>
</html>