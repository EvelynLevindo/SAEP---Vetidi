<?php
    require 'db.php';
    $erro = $_GET['msg'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($senha, $user['senha_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            header("Location: index.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos!";
        }
    }
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Login Vetidi</h2>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <form method="POST">
        E-mail: <input type="email" name="email" required><br>
        Senha: <input type="password" name="senha" required><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>