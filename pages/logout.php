<?php
session_start();
session_unset();
session_destroy();
header('Location: /Pet_Hotel/pages/login.php');
exit;
