<?php
include("conecta.php"); // Inclui o arquivo de conexão com o banco de dados

session_start(); // Assegura que a sessão está iniciada

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (!isset($_SESSION['usuario_id'])) {
    echo "ID do usuário não está definido.";
    exit();
}

// Coletando e escapando os dados do formulário
$nomet = mysqli_real_escape_string($conexao, $_POST["nomet"]);
$responsavel = mysqli_real_escape_string($conexao, $_POST["responsavel"]);
$estado = mysqli_real_escape_string($conexao, $_POST["opcao_estado"]);
$cidade = mysqli_real_escape_string($conexao, $_POST["opcao_cidade"]);
$bairro = mysqli_real_escape_string($conexao, $_POST["bairro"]);
$rua = mysqli_real_escape_string($conexao, $_POST["rua"]);
$modalidades = mysqli_real_escape_string($conexao, $_POST["opcao_modalidade"]);
$categoria = mysqli_real_escape_string($conexao, $_POST["opcao_categoria"]);
$genero = mysqli_real_escape_string($conexao, $_POST["opcao_genero"]);
$telefonet = mysqli_real_escape_string($conexao, $_POST["tltt"]);
$uniformes = mysqli_real_escape_string($conexao, $_POST["opcao_uniforme"]);
$njogadores = mysqli_real_escape_string($conexao, $_POST["njogadores"]);
$vagas = mysqli_real_escape_string($conexao, $_POST["numeroVagas"]);
$vagasatu = mysqli_real_escape_string($conexao, $_POST["numeroVagas"]);
$valorinsc = mysqli_real_escape_string($conexao, $_POST["valorinsc"]);
$dataInicio = mysqli_real_escape_string($conexao, $_POST["dataInicio"]);
$dataFim = mysqli_real_escape_string($conexao, $_POST["dataFim"]);
$descricao = mysqli_real_escape_string($conexao, $_POST["descricao"]);
$usuario_id = $_SESSION['usuario_id']; // ID do usuário logado

// Data atual
$date = new DateTime();
$data_atual = $date->format('Y-m-d H:i:s'); // Formata a data e hora no formato desejado

// Construindo a consulta SQL
$sql = "INSERT INTO torneio (idcri, nomet, estado, cidade, bairro, rua, datalanc, datafim, datainicio, responsavel, valorinsc, descricao, telefonet, modalidades, vagas, vagasatu, njogadores, categoria, uniformes, genero) 
        VALUES ('$usuario_id','$nomet', '$estado', '$cidade', '$bairro', '$rua', '$data_atual', '$dataFim', '$dataInicio', '$responsavel', '$valorinsc', '$descricao', '$telefonet', '$modalidades', '$vagas','$vagasatu', '$njogadores', '$categoria', '$uniformes', '$genero')";

// Executando a consulta
if (mysqli_query($conexao, $sql)) {
    // Verifica a modalidade pelo ID e redireciona para a página correspondente
    switch ($modalidades) {
        case '1':  // ID 1: Basquete 3x3
            header("Location: Esportes/Basquete/bqt3x3.php");
            break;
        case '2':  // ID 2: Basquete
            header("Location: Esportes/Basquete/bqtq.php");
            break;
        case '3':  // ID 3: Futebol 11
            header("Location: Esportes/Futebol/ft11.php");
            break;
        case '4':  // ID 4: Futebol 7
            header("Location: Esportes/Futebol/ft7.php");
            break;
        case '5':  // ID 5: Futebol de Salão
            header("Location: Esportes/Futebol/ftsal.php");
            break;
        case '6':  // ID 6: Futebol de Volei
            header("Location: Esportes/Futebol/ftv.php");
            break;
        case '7':  // ID 7: Handebol Praia
            header("Location: Esportes/Handebol/handp.php");
            break;
        case '8':  // ID 8: Handebol Quadra
            header("Location: Esportes/Handebol/handq.php");
            break;
        case '9':  // ID 9: Vôlei de areia
            header("Location: Esportes/Volei/vbla.php");
            break;
        case '10': // ID 10: Vôlei de Quadra
            header("Location: Esportes/Volei/vblq.php");
            break;
        default:  // Qualquer outra modalidade não especificada
            header("Location: Esportes/modalidades/outras_modalidades.php");
            break;
    }
    exit(); // Certifique-se de que o script pare de executar após o redirecionamento
} else {
    echo "Erro ao inserir dados: " . mysqli_error($conexao);
}


// Fecha a conexão
mysqli_close($conexao);
?>
