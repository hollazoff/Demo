<?php
session_start();
include 'db.php';

// Упрощенная проверка авторизации
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Удаление карточки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_card'])) {
    $card_id = $_POST['delete_card'];
    $connection->query("DELETE FROM cards WHERE id = $card_id");
}

// Получение активных карточек
$cards_result = $connection->query("SELECT * FROM cards WHERE user_id = $user_id");

// Получение архивных карточек
$archived_result = $connection->query("SELECT * FROM cards WHERE user_id = $user_id AND status IN ('declined', 'pending')");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои карточки</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Ваши карточки</h2>
    
    <h3>Активные карточки:</h3>
    <table>
        <tr>
            <th>Автор</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>

        <?php while ($card = $cards_result->fetch_assoc()): ?>
        <tr>
            <td><?= $card['author'] ?></td>
            <td><?= $card['title'] ?></td>
            <td>
                <form method="POST">
                    <button type="submit" name="delete_card" value="<?= $card['id'] ?>">Удалить</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3>Архивные карточки:</h3>
    <table>
        <tr>
            <th>Автор</th>
            <th>Название</th>
            <th>Статус</th>
        </tr>
        <?php while ($card = $archived_result->fetch_assoc()): ?>
        <tr>
            <td><?= $card['author'] ?></td>
            <td><?= $card['title'] ?></td>
            <td><?= $card['status'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <button onclick="window.history.back();">Назад</button>
</body>
</html>