<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Inserir
if (isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $cpf = criptografarCPF($_POST['cpf']);
    $pdo->prepare("INSERT INTO tutores (nome, cpf_criptografado) VALUES (?, ?)")->execute([$nome, $cpf]);
    header("Location: tutores.php");
    exit;
}

// Editar
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = criptografarCPF($_POST['cpf']);
    $pdo->prepare("UPDATE tutores SET nome=?, cpf_criptografado=? WHERE id=?")->execute([$nome, $cpf, $id]);
    header("Location: tutores.php");
    exit;
}

// Excluir
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM tutores WHERE id=?")->execute([$_GET['del']]);
    header("Location: tutores.php");
    exit;
}

// Busca e Listagem
$termo = $_GET['busca'] ?? '';
if ($termo) {
    $stmt = $pdo->prepare("SELECT * FROM tutores WHERE nome ILIKE ?");
    $stmt->execute(["%$termo%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM tutores");
}
$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<body>
    <a href="index.php">Voltar ao Início</a>
    <h2>Gerenciar Tutores</h2>
    
    <form method="GET">
        Buscar por nome: <input type="text" name="busca" value="<?= htmlspecialchars($termo) ?>">
        <button type="submit">Pesquisar</button>
        <a href="tutores.php"><button type="button">Limpar</button></a>
    </form>
    <br>
    
    <form method="POST">
        <h3>Novo Tutor</h3>
        Nome: <input type="text" name="nome" required>
        CPF: <input type="text" name="cpf" required>
        <button type="submit" name="add">Salvar Tutor</button>
    </form>
    
    <h3>Lista e Edição de Tutores</h3>
    <table border="1">
        <tr><th>ID</th><th>Dados do Tutor</th><th>Ações</th></tr>
        <?php foreach ($tutores as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td>
                <!-- Formulário de Edição Direta -->
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($t['nome']) ?>" required>
                    CPF: <input type="text" name="cpf" value="<?= htmlspecialchars(descriptografarCPF($t['cpf_criptografado'])) ?>" required>
                    <button type="submit" name="edit">Atualizar</button>
                </form>
            </td>
            <td>
                <a href="?del=<?= $t['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>