<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}

if (isset($_POST['tarefa']) && isset($_POST['hora'])) {
  $descricao = $_POST['tarefa'];
  $hora = $_POST['hora'];

  $conn = new mysqli("localhost", "root", "", "cadastro");
  if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO tarefas (descricao, hora) VALUES (?, ?)");
  $stmt->bind_param("ss", $descricao, $hora);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}

header("Location: dashboard.php");
exit();
