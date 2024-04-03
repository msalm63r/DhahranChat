<?php
session_start();

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

// استعراض جميع الرسائل مع تضمين أسماء المستخدمين
$sql = "SELECT chat_messages.message_text, chat_messages.sent_at, users.fname, users.lname, chat_messages.sender_id
        FROM chat_messages 
        LEFT JOIN users ON chat_messages.sender_id = users.idnum";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='message'>";
        echo "<div class='message-sender'>";
        
        // عرض الاسم الأول والأخير للمشرف، ورقم المعرف لبقية المستخدمين
        if ($_SESSION['idnum'] == '#234987') {
            echo $row['fname'] . ' ' . $row['lname'];
        } else {
            echo $row['sender_id'];
        }
        
        echo "</div>";
        echo "<div class='message-content'>" . $row['message_text'] . "</div>";
        echo "<div class='message-time'>" . $row['sent_at'] . "</div>";
        echo "</div>";
    }
} else {
    echo "لا توجد رسائل.";
}

// إغلاق اتصال قاعدة البيانات
$conn->close();
?>
