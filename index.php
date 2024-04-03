<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<title>تسجيل الدخول</title>
<link rel="stylesheet" href="./assets/css/style.css" />
</head>
<body>

<div class="imageside">
    <img src="./assets/images/Messages.png">
</div>

<div class="center">
    <h1>تسجيل الدخول</h1>

    <form method="post">
        <div class="txt_field">
            <input type="text" name="idnum" required>
            <span></span>
            <label>رقم المعرف</label>
        </div>

        <div class="txt_field">
            <input type="password" name="password" required>
            <span></span>
            <label>كلمة المرور</label>
        </div>

        <input type="submit" value="تسجيل الدخول">
    </form>
</div>

<?php
// بدء الجلسة لتخزين بيانات تسجيل الدخول إذا تم التحقق بنجاح
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // معلومات الاتصال بقاعدة البيانات
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'dhahranchat';

    // إنشاء الاتصال
    $conn = new mysqli($host, $user, $password, $database);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("فشل الاتصال: " . $conn->connect_error);
    }

    // استعلام SQL
    $idnum = $_POST['idnum'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE idnum='$idnum' AND password='$password'";
    $result = $conn->query($sql);

    // توجيه المستخدم إلى صفحة تسجيل الدخول الناجحة أو أي صفحة أخرى
if ($result->num_rows > 0) {
    // تم العثور على مستخدم متطابق
    // احصل على معلومات المستخدم من قاعدة البيانات
    $row = $result->fetch_assoc();
    // تعيين المتغيرات $_SESSION
    $_SESSION['loggedin'] = true;
    $_SESSION['idnum'] = $idnum;
    // توجيه المستخدم إلى صفحة تسجيل الدخول الناجحة أو أي صفحة أخرى
    header("Location: chat.php");
}
 else {
        // خطأ في معلومات تسجيل الدخول
        echo "خطأ: اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
    $conn->close();
}
?>

</body>
</html>
