<?php
include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nomeInput"];
    $usuario = $_POST["usuInput"];
    $telefone = $_POST["tlInput"];
    $cpf = $_POST["cpfInput"];
    $email = $_POST["femail"];
    $datanasc = $_POST["dataInput"];
    $senha = $_POST["senha"];

    // Sanitizar entrada e evitar injeção SQL
    $nome = mysqli_real_escape_string($conexao, $nome);
    $usuario = mysqli_real_escape_string($conexao, $usuario);
    $telefone = mysqli_real_escape_string($conexao, $telefone);
    $cpf = mysqli_real_escape_string($conexao, $cpf);
    $email = mysqli_real_escape_string($conexao, $email);
    $datanasc = mysqli_real_escape_string($conexao, $datanasc);
    $senha = mysqli_real_escape_string($conexao, $senha);

    // Validar usuário único
    $sql_usuario = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    if (mysqli_num_rows(mysqli_query($conexao, $sql_usuario)) > 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Já existe um usuário com este nome."]);
        exit;
    }

    // Validar telefone único
    $sql_telefone = "SELECT * FROM usuario WHERE telefone = '$telefone'";
    if (mysqli_num_rows(mysqli_query($conexao, $sql_telefone)) > 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Este telefone já está cadastrado."]);
        exit;
    }

    // Validar CPF único
    $sql_cpf = "SELECT * FROM usuario WHERE cpf = '$cpf'";
    if (mysqli_num_rows(mysqli_query($conexao, $sql_cpf)) > 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Já existe um usuário com este CPF cadastrado."]);
        exit;
    }

    // Validar e-mail único
    $sql_email = "SELECT * FROM usuario WHERE email = '$email'";
    if (mysqli_num_rows(mysqli_query($conexao, $sql_email)) > 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Este e-mail já está cadastrado."]);
        exit;
    }

    // Inserir os dados
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO usuario (nome, usuario, telefone, cpf, email, datanasc, senha) VALUES ('$nome', '$usuario', '$telefone', '$cpf', '$email', '$datanasc', '$senha_criptografada')";

    if (mysqli_query($conexao, $sql_insert)) {
        echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro realizado com sucesso!"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar: " . mysqli_error($conexao)]);
    }
}
?>
