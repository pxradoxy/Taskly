<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "cadastro");

if (!isset($_SESSION["usuario"])) {
  http_response_code(401);
  exit("Não autorizado");
}

$usuario = $_SESSION["usuario"];
$result = $mysqli->query("SELECT * FROM tarefas WHERE usuario = '$usuario' ORDER BY hora ASC");
$tarefas = $result->fetch_all(MYSQLI_ASSOC);

foreach ($tarefas as $tarefa):
?>
  <li data-id="<?= $tarefa['id'] ?>" class="tarefa bg-gray-100 dark:bg-gray-700 p-4 rounded flex justify-between items-center">
    <div class="flex items-center gap-3">
      <input type="checkbox" class="checkTarefa" data-id="<?= $tarefa['id'] ?>" <?= $tarefa['concluida'] ? 'checked' : '' ?>>
      <div>
        <span class="<?= $tarefa['concluida'] ? 'line-through text-gray-500 dark:text-gray-400' : '' ?>"><?= htmlspecialchars($tarefa["descricao"]) ?></span>
        <div class="text-sm text-gray-600 dark:text-gray-300"><?= date("H:i", strtotime($tarefa["hora"])) ?></div>
      </div>
    </div>
    <button class="deleteTarefa text-red-500 hover:text-red-700" data-id="<?= $tarefa['id'] ?>">🗑</button>
  </li>
<?php endforeach; ?>
