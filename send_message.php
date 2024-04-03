<?php
session_start();

// التحقق من جلسة المستخدم
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit;
}

// تحديد متغير اتصال قاعدة البيانات
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'dhahranchat';

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $password, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// التحقق من الطلب عبر POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استخراج الرسالة من النموذج
    $message = $_POST['message'];
    $sender_id = $_SESSION['idnum']; // تحديد رقم المعرف للمرسل
    $sent_at = date('Y-m-d H:i:s'); // التاريخ والوقت الحالي

    // إعداد استعلام SQL لإدراج الرسالة في قاعدة البيانات
    $sql = "INSERT INTO chat_messages (sender_id, message_text, sent_at) VALUES ('$sender_id', '$message', '$sent_at')";

    // تنفيذ الاستعلام
    if ($conn->query($sql) === TRUE) {
        // الرسالة تم إرسالها بنجاح
        echo "تم إرسال الرسالة بنجاح!";
    } else {
        echo "خطأ في إرسال الرسالة: " . $conn->error;
    }
}

// إغلاق اتصال قاعدة البيانات
$conn->close();
?>
