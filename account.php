<?php
include 'config.php';
session_start();

// Mock login voor dit voorbeeld
$_SESSION['logged_in'] = true;
$_SESSION['username'] = 'Ash';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Account</title>
</head>
<body>
<?php
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
    echo "Hello, " . $_SESSION['username'] . "!";
} else {
    echo "Please log in!";
}
?>

</body>
</html>
