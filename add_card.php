<?php
session_start();
include 'db.php';

// Упрощенная проверка авторизации
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Прямое использование данных из формы
    $author = $_POST['author'];
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id'];
    $sharing_option = $_POST['sharing_option'];

    // Узловые: используем подготовленный запрос для уменьшения уязвимости
    $stmt = $connection->prepare("INSERT INTO cards (user_id, author, title, status) 
    VALUES ($user_id, '$author', '$title', 'pending')");
    $stmt->execute();

    echo "Карточка отправлена на рассмотрение администратору!";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить карточку</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 20px; }
        form { display: flex; flex-direction: column; gap: 10px; }
        input[type="text"] { padding: 8px; width: 100%; }
        button { padding: 8px 12px; margin-top: 10px; }
        .radio-group { margin: 10px 0; }
    </style>
</head>
<body>
    <h2>Добавить карточку</h2>
    <form method="POST">
        <input type="text" name="author" placeholder="Автор книги" required>
        <input type="text" name="title" placeholder="Название книги" required>
        
        <div class="radio-group">
            <label>
                <input type="radio" name="sharing_option" value="share" required> Готов поделиться
            </label>
            <label>
                <input type="radio" name="sharing_option" value="want" required> Хочу в свою библиотеку
            </label>
        </div>
        
        <button type="submit">Отправить</button>
    </form>
    <button onclick="window.history.back();">Назад</button>
</body>
</html>
