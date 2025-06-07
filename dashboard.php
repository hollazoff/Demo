<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Личный кабинет</h2>
    <p>Вы можете управлять своими карточками.</p>
    
    <a href="my_cards.php">Просмотреть мои карточки</a>
    <a href="add_card.php">Добавить карточку</a>
    
    <button onclick="window.history.back();">Назад</button>
</body>
</html>
