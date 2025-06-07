<?php
include 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Валидация полей
    if (empty($username)) {
        $errors[] = "Логин обязателен для заполнения";
    } elseif (strlen($username) < 3) {
        $errors[] = "Логин должен содержать минимум 3 символа";
    }

    if (empty($password)) {
        $errors[] = "Пароль обязателен для заполнения";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать минимум 6 символов";
    }

    if (empty($full_name)) {
        $errors[] = "ФИО обязательно для заполнения";
    } elseif (!preg_match('/^[А-Яа-яЁё\s]+$/u', $full_name)) {
        $errors[] = "ФИО должно содержать только кириллицу и пробелы";
    }

    if (empty($phone)) {
        $errors[] = "Телефон обязателен для заполнения";
    }

    if (empty($email)) {
        $errors[] = "Email обязателен для заполнения";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный формат email";
    }

    // Проверка уникальности логина
    if (empty($errors)) {
        $check = $connection->query("SELECT * FROM users WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $errors[] = "Этот логин уже занят!";
        }
    }

    // Если ошибок нет - регистрируем пользователя
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, full_name, phone, email, role) 
                VALUES ('$username', '$hashed_password', '$full_name', '$phone', '$email', 1)";
        
        if ($connection->query($sql)) {
            header('Location: login.php');
            exit();
        } else {
            $errors[] = "Ошибка при регистрации: " . $connection->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        form { display: flex; flex-direction: column; gap: 10px; }
        input { padding: 8px; }
        button { padding: 8px; }
        .submit-btn { background: #4CAF50; color: white; border: none; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Регистрация</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Логин (мин. 3 символа)" 
               value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        
        <input type="password" name="password" placeholder="Пароль (мин. 6 символов)" required>
        
        <input type="text" name="full_name" placeholder="ФИО (кириллица)" 
               value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>" required>
        
        <input type="text" name="phone" placeholder="Телефон" 
               value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
        
        <input type="email" name="email" placeholder="Электронная почта" 
               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        
        <button type="submit" class="submit-btn">Зарегистрироваться</button>
    </form>
    
    <button onclick="window.history.back();">Назад</button>
</body>
</html>