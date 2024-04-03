<?php
session_start();

// التحقق من جلسة المستخدم
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit;
}

// تحديد معلومات الاتصال بقاعدة البيانات
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
        // لا يوجد حاجة لإعادة توجيه المستخدم بعد إرسال الرسالة
        // سيتم عرض الرسالة الجديدة بشكل تلقائي
    } else {
        echo "خطأ في إرسال الرسالة: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
     <!-- مكتبة Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق الدردشة</title>
    <link rel="stylesheet" href="./assets/css/stylechat.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .chat-container {
            height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="chat-header">
        <h1>بلدية مدينة الظهران</h1>
        <a href="logout.php" class="logout-button">تسجيل الخروج</a>
        <img src="./assets/images/Dharanlogo.png" class="logo">
    </header>

    <div class="chat-container" id="chat-container">
        <?php
        $sql = "SELECT chat_messages.message_text, chat_messages.sent_at, users.fname, users.lname, chat_messages.sender_id
                FROM chat_messages 
                LEFT JOIN users ON chat_messages.sender_id = users.idnum";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='message";
                if ($row['sender_id'] == $_SESSION['idnum']) {
                    echo " sender-message"; // تطبيق فئة خاصة لرسائل المرسل
                }
                echo "'>";
                echo "<div class='message-sender'>";
                if ($_SESSION['idnum'] == '#234987') {
                    echo $row['fname'] . ' ' . $row['lname']; // اسم المستخدم إذا كان المشرف
                } else {
                    echo $row['sender_id']; // استخدام رقم المعرف إذا كان المستخدم عادي
                }
                echo "</div>";
                echo "<div class='message-content'>" . $row['message_text'] . "</div>";
                echo "<div class='message-time'>" . $row['sent_at'] . "</div>";
                echo "</div>";
            }
        } else {
            echo "لا توجد رسائل.";
        }
        ?>
    </div>

    <div class="chat-input">
        <form id="message-form" enctype="multipart/form-data">
            <input type="text" name="message" id="chat-input" placeholder="اكتب رسالتك...">
            <input type="file" name="userfile" id="file-input" style="display: none;"> <!-- زر لتحميل الصور والملفات -->
            <label for="file-input" style="cursor: pointer;"><i class="fas fa-ellipsis-v"></i></label>
            <button type="submit" class="btn btn-send"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>

    <script>
       $(document).ready(function(){
        function getNewMessages() {
            $.ajax({
                url: 'fetch_messages.php',
                type: 'POST',
                success: function(data) {
                    $('#chat-container').html(data);
                    applyStyles(); // تطبيق الأنماط عند تحميل المحتوى الجديد
                }
            });
        }
        
        function applyStyles() {
            $('.message').each(function() {
                if ($(this).find('.message-sender').text() == '<?php echo $_SESSION['idnum']; ?>') {
                    $(this).addClass('sender-message'); // تطبيق الأنماط على رسائل المرسل
                }
            });
        }
        
        setInterval(getNewMessages, 5000);
        
        $('#message-form').submit(function(e) {
            e.preventDefault();
            var message = $('#chat-input').val().trim();
            if (message !== '') {
                var formData = $(this).serialize();
                $.ajax({
                    url: 'send_message.php',
                    type: 'POST',
                    data: formData,
                    success: function() {
                        $('#chat-input').val('');
                        getNewMessages();
                    }
                });
            } else {
                alert('يرجى كتابة رسالة قبل الإرسال.');
            }
        });
    });
    </script>
</body>
</html>
