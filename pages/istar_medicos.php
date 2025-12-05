<?php
include "../includes/conexao.php";
include "../includes/header.php";

$res = $conn->query("SELECT id, nome, especialidade FROM medicos ORDER BY nome");
?>

<div class="card p-4">
  <h3>Lista de Médicos</h3>

  <table class="table table-striped mt-3">
    <thead>
      <tr><th>Nome</th><th>Especialidade</th><th>Ações</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($row['nome'])?></td>
        <td><?=htmlspecialchars($row['especialidade'])?></td>
        <td>
          <a href="#" class="btn btn-sm btn-outline-secondary disabled">Editar</a>
          <a href="#" class="btn btn-sm btn-outline-danger disabled">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="/index.php" class="btn btn-secondary">Voltar</a>
</div>

<?php include "../includes/footer.php"; ?>
