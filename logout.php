<?php
session_start();
session_unset();
session_destroy();
header("Location: CyberLearn.html"); // Update this to the correct path of your main CyberLearn page
exit();
?>

