<?php
session_start(); 

try {
    $host = "localhost"; 
    $dbname = "cadastro"; 
    $user = "root"; 
    $password = ""; 

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("<script>alert('Usuário não encontrado!'); window.history.back();</script>");
        }

        // Comparação direta da senha (sem bcrypt, como você preferiu)
        if ($senha === $user["senha"]) {
            $_SESSION["usuario"] = $user["nome"]; // Você pode mudar para $user["email"] se quiser
            header("Location: dashboard.php"); // Redireciona para o dashboard
            exit(); // Encerra o script após o redirecionamento
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        }
    }
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>
