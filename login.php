<?php
session_start();
require 'connect.php';

$email = $password = $error_email = $error_password = '';

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "- nieprawidłowy format.";
    }

    if (strlen($password) < 6) {
        $error_password = "- musi mieć co najmniej 6 znaków.";
    }

    if (empty($error_email) && empty($error_password)) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: index.php");
                exit;
            } else {
                $error_password = "- nieprawidłowe.";
            }
        } else {
            $error_email = "- nieprawidłowy.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L1Cheats - Logowanie</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="box" method="post">
        <h1>Zaloguj się</h1>

        <div class="input-group">
            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="email">E-mail <span class="error"><?php echo $error_email; ?></span></label>
        </div>

        <div class="input-group">
            <input id="password" name="password" type="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <label for="password">Hasło <span class="error"><?php echo $error_password; ?></span></label>
        </div>
        
        <button type="submit">Zaloguj</button>

        <a href="registration.php">Nie masz konta? Zarejestruj się</a>
    </form>
    
</body>
</html>
