<?php 
include "../../includes/conexao.php";

$id = $_GET["id"];
$con->query("DELETE FROM pacientes WHERE id=$id");

header("Location: listar.php");
