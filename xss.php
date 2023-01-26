<html>
<head>
    <meta charset="UTF-8">
    <title>XSS</title>
</head>
<body>
    <h1></h1>
    <?php
    // 크로스 사이트 스크립팅 방지 메서드 htmlspecialchars()
    echo htmlspecialchars("<script>alert('babo');</script>");
    ?>
</body>
</html>