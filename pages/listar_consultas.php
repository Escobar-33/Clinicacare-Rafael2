<?php
include "../includes/conexao.php";
include "../includes/header.php";

// join para mostrar nomes
$sql = "SELECT c.id, c.data_consulta, c.horario, p.nome AS paciente, m.nome AS medico
        FROM consultas c
        JOIN pacientes p ON c.paciente_id = p.id
        JOIN medicos m ON c.medico_id = m.id
        ORDER BY c.data_consulta, c.horario";
$res = $conn->query($sql);
?>

<div class="card p-4">
  <h3>Lista de Consultas</h3>

  <table class="table table-striped mt-3">
    <thead>
      <tr><th>Data</th><th>Hora</th><th>Paciente</th><th>Médico</th><th>Ações</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($row['data_consulta'])?></td>
        <td><?=htmlspecialchars($row['horario'])?></td>
        <td><?=htmlspecialchars($row['paciente'])?></td>
        <td><?=htmlspecialchars($row['medico'])?></td>
        <td>
          <a href="#" class="btn btn-sm btn-outline-secondary disabled">Editar</a>
          <a href="#" class="btn btn-sm btn-outline-danger disabled">Cancelar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="/index.php" class="btn btn-secondary">Voltar</a>
</div>

<?php include "../includes/footer.php"; ?>
