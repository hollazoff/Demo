<?php
session_start();
include 'db.php';

// Проверка прав администратора

if ($_SESSION['username'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Получаем все карточки
$cards_result = $connection->query("SELECT * FROM cards");

// Обработка действий администратора
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_card'])) {
        $card_id = $_POST['approve_card'];
        $connection->query("UPDATE cards SET status='approved' WHERE id = $card_id");
    }

    if (isset($_POST['decline_card'])) {
        $card_id = $_POST['decline_card'];
        $reason = $_POST['reason'];
        $connection->query("UPDATE cards SET status='declined', decline_reason='$reason' WHERE id = $card_id");
    }

    echo "<p>Статус карточки обновлен.</p>";
    // Обновляем список карточек
    $cards_result = $connection->query("SELECT * FROM cards");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button { padding: 5px 10px; margin: 2px; cursor: pointer; }
        input[type="text"] { padding: 5px; width: 200px; }
    </style>
</head>
<body>
    <h2>Панель администратора</h2>
    <table>
        <tr>
            <th>Автор</th>
            <th>Название</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php while ($card = $cards_result->fetch_assoc()): ?>
        <tr>
            <td><?= $card['author'] ?></td>
            <td><?= $card['title'] ?></td>
            <td><?= $card['status'] ?></td>
            <td>
                <form method="POST">
                    <button type="submit" name="approve_card" value="<?= $card['id'] ?>">Опубликовать</button>
                    <input type="text" name="reason" placeholder="Причина отклонения">
                    <button type="submit" name="decline_card" value="<?= $card['id'] ?>">Отклонить</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <button onclick="window.history.back();">Назад</button>
</body>
</html>