<?php
session_start();
session_destroy();

header("Location: ../login/form_login.php");
?>