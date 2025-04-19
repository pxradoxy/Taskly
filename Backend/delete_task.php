<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $conn = new mysqli("localhost", "root", "", "cadastro");
  if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}

header("Location: dashboard.php");
exit();
