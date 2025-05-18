<?php 
    session_start();

    session_unset();

    session_destroy();

    //Redirect to login page
    header("Location: login.php");
    exit();
?>
<?php include 'extra/foot.html'; ?>