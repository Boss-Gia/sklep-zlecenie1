<?php
session_start();
require 'connect.php';

$username = $email = $password = $confirm_password = $error_username = $error_email = $error_password = $error_confirm_password = '';

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username)) {
        $error_username = "- nazwa użytkownika jest wymagana.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "- nieprawidłowy format adresu e-mail.";
    }

    if (strlen($password) < 6) {
        $error_password = "- hasło musi mieć co najmniej 6 znaków.";
    }

    if ($password !== $confirm_password) {
        $error_confirm_password = "- hasła nie pasują do siebie.";
    }

    if (empty($error_username) && empty($error_email) && empty($error_password) && empty($error_confirm_password)) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_email = "Adres e-mail jest już używany.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, registration_date) VALUES (:username, :email, :password, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_email'] = $email;
                header("Location: index.php");
                exit;
            } else {
                $error_username =  "Coś poszło nie tak. Spróbuj ponownie później.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L1Cheats - Rejestracja</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="box" method="post">
        <h1>Zarejestruj się</h1>

        <div class="input-group">
            <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($username); ?>" required>
            <label for="username">Nazwa <span class="error"><?php echo $error_username; ?></span></label>
        </div>

        <div class="input-group">
            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="email">E-mail <span class="error"><?php echo $error_email; ?></span></label>
        </div>

        <div class="input-group">
            <input id="password" name="password" type="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <label for="password">Hasło <span class="error"><?php echo $error_password; ?></span></label>
        </div>

        <div class="input-group">
            <input id="confirm_password" name="confirm_password" type="password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
            <label for="confirm_password">Powtórz Hasło <span class="error"><?php echo $error_confirm_password; ?></span></label>
        </div>
        
        <button type="submit">Zarejestruj się</button>

        <a href="login.php">Masz już konto? Zaloguj się</a>
    </form>
    
</body>
</html>
