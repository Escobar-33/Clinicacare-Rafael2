<?php 
include "../../includes/conexao.php";
$result = $con->query("SELECT * FROM pacientes ORDER BY id DESC");
?>

<h2>Lista de Pacientes</h2>

<a href="cadastrar.php">+ Cadastrar novo</a>
<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Ações</th>
    </tr>

    <?php while($p = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nome'] ?></td>
        <td><?= $p['email'] ?></td>
        <td><?= $p['telefone'] ?></td>
        <td>
            <a href="editar.php?id=<?= $p['id'] ?>">Editar</a> |
            <a href="excluir.php?id=<?= $p['id'] ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>
