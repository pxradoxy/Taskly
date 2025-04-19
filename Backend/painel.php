<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    echo "<script>alert('Faça login primeiro!'); window.location.href = 'index.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo $_SESSION["usuario"]; ?>!</h2>
    <a href="logout.php">Sair</a>
</body>
</html>