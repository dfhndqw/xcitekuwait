<?php
$file = 'products.json';
$products = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// حذف منتج
if (isset($_GET['delete'])) {
    $deleteIndex = (int)$_GET['delete'];
    if (isset($products[$deleteIndex])) {
        // حذف الصورة من السيرفر إذا أردت
        if (file_exists($products[$deleteIndex]['image'])) {
            unlink($products[$deleteIndex]['image']);
        }
        unset($products[$deleteIndex]);
        $products = array_values($products); // إعادة ترتيب الفهارس
        file_put_contents($file, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// إضافة منتج
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . uniqid() . "_" . $imageName;

        if (move_uploaded_file($imageTmpPath, $uploadPath)) {
            $product = [
                "name" => $name,
                "description" => $description,
                "price" => $price,
                "image" => $uploadPath
            ];
            $products[] = $product;
            file_put_contents($file, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات</title>
    <style>
    body {
        font-family: 'Cairo', sans-serif;
        background: #f4f4f4;
        padding: 20px;
    }

    .product {
        background: #fff;
        padding: 10px;
        margin: 10px 0;
        border-radius: 10px;
        box-shadow: 0 0 5px #ccc;
    }

    img {
        max-width: 150px;
        display: block;
        margin-bottom: 10px;
    }

    .actions a {
        margin-right: 10px;
        color: red;
        text-decoration: none;
    }

    form {
        margin-bottom: 30px;
    }
</style>

</head>
<body>

<h2>إضافة منتج</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="add" value="1">
    <label>الاسم: <input type="text" name="name" required></label><br><br>
    <label>الوصف: <input type="text" name="description"></label><br><br>
    <label>السعر: <input type="text" name="price" required></label><br><br>
    <label>اختر صورة: <input type="file" name="image" accept="image/*" required></label><br><br>
    <button type="submit">أضف المنتج</button>
</form>

<h2>المنتجات الحالية</h2>
<?php foreach ($products as $index => $product): ?>
    <div class="product">
        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        <strong><?= htmlspecialchars($product['name']) ?></strong><br>
        <small><?= htmlspecialchars($product['description']) ?></small><br>
        <b><?= htmlspecialchars($product['price']) ?> دينار</b><br>
        <div class="actions">
            <a href="?delete=<?= $index ?>" onclick="return confirm('هل أنت متأكد من حذف المنتج؟')">🗑 حذف</a>
            <!-- التعديل ممكن نعمله لاحقًا بتعبئة الفورم الحالي -->
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
