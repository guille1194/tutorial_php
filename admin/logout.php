<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
unset($_SESSION['SBUsuario']);
header("Location: login.php");
?>