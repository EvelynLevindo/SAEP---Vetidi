<?php

    require 'db.php';
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

    if (isset($_POST['add'])) {
        $nome = $_POST['nome'];
        $tutor_id = $_POST['tutor_id'];
        $pdo->prepare("INSERT INTO pets (nome, tutor_id) VALUES (?, ?)")->execute([$nome, $tutor_id]);
    }

    $tutores = $pdo->query("SELECT id, nome FROM tutores")->fetchAll(PDO::FETCH_ASSOC);
    $pets = $pdo->query("SELECT p.id, p.nome, t.nome as tutor FROM pets p JOIN tutores t ON p.tutor_id = t.id")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<body>
    <a href="index.php">Voltar</a>
    <h2>Gerenciar Pets</h2>
    
    <form method="POST">
        Nome do Pet: <input type="text" name="nome" required>
        Tutor: 
        <select name="tutor_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($tutores as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add">Cadastrar</button>
    </form>
    
    <h3>Lista de Pets</h3>
    <table border="1">
        <tr><th>ID</th><th>Pet</th><th>Tutor</th></tr>
        <?php foreach ($pets as $p): ?>
        <tr><td><?= $p['id'] ?></td><td><?= htmlspecialchars($p['nome']) ?></td><td><?= htmlspecialchars($p['tutor']) ?></td></tr>
        <?php endforeach; ?>
    </table>
</body>
</html>