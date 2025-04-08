<?php
// اسم ملف البيانات
$filename = "knet_data.txt";

// التحقق من وجود الملف
if (file_exists($filename)) {
    $content = file_get_contents($filename);
} else {
    $content = "لا توجد بيانات حتى الآن.";
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - بيانات KNET</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .data-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            white-space: pre-wrap;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .refresh-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .refresh-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>لوحة التحكم - بيانات عمليات KNET</h1>
    <div class="data-box">
        <?= nl2br(htmlspecialchars($content)) ?>
    </div>
    <a class="refresh-btn" href="admin_panel.php">تحديث الصفحة</a>
</body>
</html>
