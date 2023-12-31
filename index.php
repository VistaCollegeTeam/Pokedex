<?php
include 'config.php';

// Check if user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Handle login
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare('SELECT id, email, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password';
        }
    } elseif (isset($_POST['signup'])) {
        // Handle sign-up
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email and password inputs
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        if (empty($errors)) {
            // Check if the email already exists
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $errors[] = 'Email address is already in use. Please choose another one or log in.';
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
                $stmt->execute([$email, $hashedPassword]);

                $_SESSION['user_id'] = $pdo->lastInsertId();

                // Add the starter Pokémon to the user's collection
                $starter_pokemon_id = $_POST['starter_pokemon'];
                $stmt = $pdo->prepare('INSERT INTO pokemon_collection (user_id, pokemon_id) VALUES (?, ?)');
                $stmt->execute([$_SESSION['user_id'], $starter_pokemon_id]);

                header('Location: dashboard.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>UrbanEats & Spirits - Pokedex</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Menu -->
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="account.php">Account</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Login or Sign Up</h1>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>

        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <!-- Starter Pokémon Selection -->
            <label for="starter_pokemon">Choose a Starter Pokémon:</label>
            <select name="starter_pokemon" id="starter_pokemon" required>
                <option value="1">Bulbasaur</option>
                <option value="4">Charmander</option>
                <option value="7">Squirtle</option>
                <!-- Add other Pokémon as needed -->
            </select>

            <button type="submit" name="login">Login</button>
            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>
</body>
</html>
