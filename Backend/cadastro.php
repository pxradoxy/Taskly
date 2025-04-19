<?php
$host = "localhost";
$dbname = "seu_banco";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cadastro de usuário
    $nome = "Usuário Teste";
    $email = "email@teste.com";
    $senha = "senha123";

    // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senha_hash]);

    echo "Usuário cadastrado com sucesso!";
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>