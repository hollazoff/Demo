<?php
include 'db.php';
session_start();

// Упрощенный запрос с MySQLi (без защиты)
$stmt = $connection->prepare("SELECT * FROM cards WHERE status = 'approved'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать в Буквоежка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Портал Буквоежка</h1>
        <nav>
            <ul>
                <?php if (empty($_SESSION['user_id'])): ?>
                    <li><a href="register.php">Регистрация</a></li>
                    <li><a href="login.php">Авторизация</a></li>
                <?php else: ?>
                    <?php if (!empty($_SESSION['username'])): ?>
                        <li><span><?= htmlspecialchars($_SESSION['username']) ?></span></li>
                        <a href="my_cards.php">Просмотреть мои карточки</a>
    <a href="add_card.php">Добавить карточку</a>
                    <?php endif; ?>
           
                    <li><a href="logout.php">Выход</a></li>
                <?php endif; ?>
                <li><a href="admin_panel.php">Панель администратора</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Добро пожаловать!</h2>
        <p>На нашем портале вы можете обмениваться книгами с другими пользователями.</p>

        <h3>Каталог карточек:</h3>
        <table>
            <tr>
                <th>Автор</th>
                <th>Название</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2023 Буквоежка</p>
    </footer>
</body>
</html>