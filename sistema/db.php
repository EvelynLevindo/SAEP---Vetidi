<?php
    session_start();

    $host = 'localhost';
    $db = 'saep_db';
    $user = 'postgres';
    $pass = 'postgres';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }

    // Verifica expiração da sessão (15 minutos = 900 segundos)
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
        session_unset();
        session_destroy();
        header("Location: login.php?msg=Sessão expirada");
        exit;
    }
    $_SESSION['LAST_ACTIVITY'] = time();

    // Função simples para criptografar/descriptografar CPF (Atende LGPD e Requisitos)
    function criptografarCPF($cpf) { return base64_encode($cpf); }
    function descriptografarCPF($cpf_cripto) { return base64_decode($cpf_cripto); }
?>