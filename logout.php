<?php
include_once 'includes/functions.php';
session_destroy();
redirect('login.php');
?>