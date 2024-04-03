<?php
// بدء الجلسة
session_start();

// إنهاء الجلسة
session_destroy();

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
header("Location: index.php");
exit;
?>
