<?php
session_start();          // Mulai atau lanjutkan session  
session_unset();          // Hapus semua variabel session  
session_destroy();        // Hancurkan session di server  
header('Location: login.php');  // Redirect ke halaman login  
exit();                   // Pastikan eksekusi berhenti di sini  
?>
