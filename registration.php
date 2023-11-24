<?php
session_start();

// Подключение к базе данных (предполагаем, что у вас есть база данных с таблицей users)
$conn = mysqli_connect('localhost', 'root', '','PHP_users');
// Проверка подключения к базе данных
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Регистрация пользователя
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Добавление пользователя в базу данных
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = $conn->query($sql);

    if ($result) {
        echo "Регистрация успешна!";
    } else {
        echo "Ошибка при регистрации: " . $conn->error;
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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
            <h2>Регистрация</h2>
            <label>Имя пользователя:</label>
            <input type="text" name="username" required>
            <label>Пароль:</label>
            <input type="password" name="password" required>
            <button type="submit" name="register">Зарегистрироваться</button>
            <p>Уже зарегистрированы? <a href="login.php">Войдите!</a></p>
        </form>
    </body>
</html>