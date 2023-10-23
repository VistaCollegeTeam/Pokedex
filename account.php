<?php
include 'config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['change_email'])) {
        $new_email = $_POST['new_email'];
        $stmt = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
        $stmt->execute([$new_email, $_SESSION['user_id']]);
        $message = 'Email updated successfully!';
    } elseif(isset($_POST['change_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$new_password, $_SESSION['user_id']]);
        $message = 'Password updated successfully!';
    } elseif (isset($_POST['delete_account'])) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        session_destroy();
        header('Location: logout.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Menu -->
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="account.php">Account</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Manage Your Account</h1>
        <div class="message"><?php echo $message; ?></div>

        <form method="post">
            <label for="new_email">Change Email:</label>
            <input type="email" id="new_email" name="new_email" required>
            <button type="submit" name="change_email">Update Email</button>
        </form>

        <form method="post">
            <label for="new_password">Change Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit" name="change_password">Update Password</button>
        </form>

        <form method="post">
            <label for="delete_accnt">Delete Account:</label> <br>
            <button type="submit" name="delete_account">Delete Account</button>
        </form>
    </div>
</body>
</html>
