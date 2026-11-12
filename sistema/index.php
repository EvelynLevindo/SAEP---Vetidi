<?php
    require 'db.php';
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['user_nome']) ?>!</h2>
    <nav>
        <a href="tutores.php">Gerenciar Tutores</a> | 
        <a href="pets.php">Gerenciar Pets</a> | 
        <a href="agendamentos.php">Agendamentos</a> | 
        <a href="?logout=true" style="color:red;">Sair do Sistema</a>
    </nav>
</body>
</html>