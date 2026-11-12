<?php
    require 'db.php';
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

    // Adicionar novo agendamento
    if (isset($_POST['add'])) {
        $tutor_id = $_POST['tutor_id'];
        $pet_id = $_POST['pet_id'];
        $data_agendamento = $_POST['data_agendamento'];
        $pdo->prepare("INSERT INTO agendamentos (tutor_id, pet_id, data_agendamento) VALUES (?, ?, ?)")->execute([$tutor_id, $pet_id, $data_agendamento]);
    }

    // Editar data do agendamento
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nova_data = $_POST['nova_data'];
        $pdo->prepare("UPDATE agendamentos SET data_agendamento = ? WHERE id = ?")->execute([$nova_data, $id]);
    }

    $tutores = $pdo->query("SELECT id, nome FROM tutores")->fetchAll(PDO::FETCH_ASSOC);
    $pets = $pdo->query("SELECT id, nome FROM pets")->fetchAll(PDO::FETCH_ASSOC);

    // Busca agendamentos ordenados por data
    $query = "SELECT a.id, a.data_agendamento, t.nome as tutor, p.nome as pet 
            FROM agendamentos a 
            JOIN tutores t ON a.tutor_id = t.id 
            JOIN pets p ON a.pet_id = p.id 
            ORDER BY a.data_agendamento ASC";
    $agendamentos = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<body>
    <a href="index.php">Voltar</a>
    <h2>Agendamentos</h2>
    
    <!-- Formulário de Criação -->
    <form method="POST" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <h3>Novo Agendamento</h3>
        Tutor: 
        <select name="tutor_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($tutores as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        
        Pet: 
        <select name="pet_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($pets as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        
        Data: <input type="date" name="data_agendamento" required>
        <button type="submit" name="add">Agendar</button>
    </form>

    <!-- Listagem e Atualização -->
    <h3>Lista de Agendamentos</h3>
    <table border="1">
        <tr><th>ID</th><th>Data</th><th>Tutor</th><th>Pet</th><th>Atualizar Data</th></tr>
        <?php foreach ($agendamentos as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['data_agendamento']) ?></td>
            <td><?= htmlspecialchars($a['tutor']) ?></td>
            <td><?= htmlspecialchars($a['pet']) ?></td>
            <td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <input type="date" name="nova_data" value="<?= $a['data_agendamento'] ?>" required>
                    <button type="submit" name="edit">Atualizar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>