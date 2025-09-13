<?php

session_start(); //iniciar processo
session_destroy(); //finaliza
header("Location: login.php");

exit;

?>