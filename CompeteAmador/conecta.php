<?php
$host = 'localhost'; // ou o endereço correto do seu servidor MySQL
$porta = '3308'; // se o seu MySQL estiver rodando na porta 3308
$usuario = 'root';
$senha = ''; // a senha que você configurou
$banco = 'gtt';

$conexao = mysqli_connect($host, $usuario, $senha, $banco, $porta);

if (!$conexao) {
    die('Erro de conexão: ' . mysqli_connect_error());
}

?>
