<?php
session_start();

// Подключение к базе данных (предполагаем, что у вас есть база данных с таблицей users)
$conn = new mysqli('localhost', 'root', '', 'PHP_users');

// Проверка подключения к базе данных
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Инициализация переменной для хранения статуса авторизации
$authStatus = "";

// Авторизация пользователя
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Подготовленный запрос для безопасности
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $username;

            // Установка cookie только если значения не null
            if (isset($row['background_color']) && $row['background_color'] !== null) {
                setcookie('background_color', $row['background_color'], time() + (86400 * 30), "/");
            }

            if (isset($row['font_color']) && $row['font_color'] !== null) {
                setcookie('font_color', $row['font_color'], time() + (86400 * 30), "/");
            }

            // Redirect to the main page
            header("Location: main.php");
            $authStatus = "Авторизация успешна!";
        } else {
            $authStatus = "Неверный пароль!";
        }
    } else {
        $authStatus = "Пользователь не найден!";
    }

    $stmt->close();
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <style>
        body {
            background-color: #292b2c;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #ffffff;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #5bc0de;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
    <body>
        <form method="post" action="">
            <h2>Авторизация</h2>
            <label>Имя пользователя:</label>
            <input type="text" name="username" required>
            <label>Пароль:</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Войти</button>
            <p><?php echo $authStatus; ?></p>
            <p>Еще не зарегистрированы? <a href="registration.php">Зарегистрируйтесь!</a></p>
        </form>
    </body>
</html>