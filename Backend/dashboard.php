<?php
session_start();
if (!isset($_SESSION["usuario"])) {
  header("Location: index.html");
  exit;
}

$mysqli = new mysqli("localhost", "root", "", "cadastro");
if ($mysqli->connect_error) {
  die("Erro na conexão: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $acao = $_POST["action"] ?? '';
  if ($acao === "add") {
    $usuario = $_SESSION["usuario"];
    $tarefa = $mysqli->real_escape_string($_POST["tarefa"]);
    $hora = $mysqli->real_escape_string($_POST["hora"]);
    $mysqli->query("INSERT INTO tarefas (usuario, descricao, hora, concluida) VALUES ('$usuario', '$tarefa', '$hora', 0)");
    echo json_encode(["status" => "ok"]);
    exit;
  } elseif ($acao === "toggle") {
    $id = (int) $_POST["id"];
    $status = (int) $_POST["status"];
    $mysqli->query("UPDATE tarefas SET concluida = $status WHERE id = $id");
    echo json_encode(["status" => "ok"]);
    exit;
  } elseif ($acao === "delete") {
    $id = (int) $_POST["id"];
    $mysqli->query("DELETE FROM tarefas WHERE id = $id");
    echo json_encode(["status" => "ok"]);
    exit;
  
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Taskly - Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 dark:bg-gray-900 dark:text-white">
  <nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Taskly</h1>
    <div class="flex gap-4 items-center">
      <button id="toggleDark" onclick="toggleDarkMode()" class="bg-white text-blue-600 px-4 py-2 rounded">Modo Escuro</button>
      <a href="logout.php" class="bg-white text-blue-600 px-4 py-2 rounded">Sair</a>
    </div>
  </nav>

  <div class="max-w-3xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow-md rounded">
    <h2 class="text-2xl font-semibold mb-4">Adicionar nova tarefa</h2>
    <form id="formTarefa" class="flex flex-col sm:flex-row gap-4 mb-8">
      <input type="text" name="tarefa" id="tarefaInput" placeholder="Digite sua tarefa" required class="flex-1 p-3 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"/>
      <input type="time" name="hora" id="horaInput" required class="p-3 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"/>
      <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">Adicionar</button>
    </form>

    <h2 class="text-xl font-medium mb-3">Suas tarefas</h2>
    <ul id="listaTarefas" class="space-y-3"></ul>
  </div>

  <script>
    function toggleDarkMode() {
      const html = document.documentElement;
      html.classList.toggle('dark');
      localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
      document.getElementById('toggleDark').textContent = html.classList.contains('dark') ? 'Modo Claro' : 'Modo Escuro';
    }

    window.onload = () => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') document.documentElement.classList.add('dark');
      document.getElementById('toggleDark').textContent = savedTheme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
      carregarTarefas();
    };

    async function carregarTarefas() {
  const res = await fetch("tarefas.php");
  const html = await res.text();
  const lista = document.getElementById("listaTarefas");
  lista.innerHTML = html;

  // Marcar/desmarcar tarefa
  lista.querySelectorAll(".checkTarefa").forEach(check => {
    check.addEventListener("change", async (e) => {
      const id = e.target.dataset.id;
      const status = e.target.checked ? 1 : 0;

      const formData = new FormData();
      formData.append("action", "toggle");
      formData.append("id", id);
      formData.append("status", status);

      await fetch("dashboard.php", { method: "POST", body: formData });
      carregarTarefas();
    });
  });

  // Excluir tarefa
  lista.querySelectorAll(".deleteTarefa").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();
      const id = e.target.dataset.id;

      const formData = new FormData();
      formData.append("action", "delete");
      formData.append("id", id);

      await fetch("dashboard.php", { method: "POST", body: formData });
      carregarTarefas();
    });
  });
    }

    document.getElementById("formTarefa").addEventListener("submit", async (e) => {
      e.preventDefault();
      const tarefa = document.getElementById("tarefaInput").value;
      const hora = document.getElementById("horaInput").value;

      const formData = new FormData();
      formData.append("action", "add");
      formData.append("tarefa", tarefa);
      formData.append("hora", hora);

      const res = await fetch("dashboard.php", { method: "POST", body: formData });
      const json = await res.json();
      if (json.status === "ok") {
        document.getElementById("formTarefa").reset();
        carregarTarefas();
      }
    });
  </script>
</body>
</html>
