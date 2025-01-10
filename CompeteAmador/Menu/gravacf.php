<?php
include("../conecta.php");

$usuario = $_POST["usuInput"];
$telefone = $_POST["tlInput"];
$email = $_POST["femail"];
$senha = $_POST["senha"];


mysqli_query($conexao, "INSERT INTO usuario (nome, usuario, telefone, cpf, email, datanasc, senha) VALUES ('$nome', '$usuario', '$telefone','$cpf', '$email', '$datanasc','$senha')");
header("location:index.php");
?>
