<?php
// Conexão com o banco de dados
include("../../conecta.php");
date_default_timezone_set('America/Sao_Paulo');
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_email']) || empty($_SESSION['usuario_email'])) {
    header("Location: index.php");
    exit();
}

// Obtém o ID do usuário da sessão
if (!isset($_SESSION['usuario_id'])) {
    echo "Erro: ID de usuário não encontrado.";
    exit();
}
$usuario_id = $_SESSION['usuario_id'];

// ID fixo da modalidade
$codmodalidade = 9;

// Data e hora do acesso
$datavisu = date("Y-m-d H:i:s");

// Registra o acesso na tabela ACESSO_TORNEIO
$sql_acesso = "INSERT INTO ACESSO_TORNEIO (codusuario, codmodalidade, datavisu) VALUES (?, ?, ?)";
$stmt_acesso = mysqli_prepare($conexao, $sql_acesso);
if ($stmt_acesso) {
    mysqli_stmt_bind_param($stmt_acesso, "iis", $usuario_id, $codmodalidade, $datavisu);
    mysqli_stmt_execute($stmt_acesso);
    mysqli_stmt_close($stmt_acesso);
} else {
    echo "Erro ao registrar o acesso: " . mysqli_error($conexao);
}

// Função para obter nomes de cidade e estado
function obterNome($conexao, $tabela, $campo_id, $valor_id, $campos = '*') {
    $query = "SELECT $campos FROM $tabela WHERE $campo_id = $valor_id";
    $resultado = mysqli_query($conexao, $query);
    return mysqli_fetch_array($resultado);
}

// Processamento de compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'], $_POST['id_torneio'], $_POST['vagascompradas'])) {
    $id_usuario = (int)$_POST['id_usuario'];
    $id_torneio = (int)$_POST['id_torneio'];
    $vagascompradas = (int)$_POST['vagascompradas'];
    $datacom = date('Y-m-d');

    // Validação do número de vagas
    if ($vagascompradas <= 0) {
        echo "Erro: O número de vagas deve ser maior que zero!";
        exit();
    }

    // Obter número de vagas disponíveis
    $query_vagas = "SELECT vagasatu FROM torneio WHERE cod = ?";
    $stmt_vagas = mysqli_prepare($conexao, $query_vagas);
    mysqli_stmt_bind_param($stmt_vagas, "i", $id_torneio);
    mysqli_stmt_execute($stmt_vagas);
    $result_vagas = mysqli_stmt_get_result($stmt_vagas);
    $row_vagas = mysqli_fetch_assoc($result_vagas);
    mysqli_stmt_close($stmt_vagas);

    if (!$row_vagas) {
        echo "Erro: Torneio não encontrado.";
        exit();
    }

    $vagasatu = (int)$row_vagas['vagasatu'];
    if ($vagascompradas > $vagasatu) {
        echo "Erro: Vagas insuficientes!";
        exit();
    }

    // Atualiza o número de vagas disponíveis
    $novas_vagasatu = $vagasatu - $vagascompradas;
    $update_vagas = "UPDATE torneio SET vagasatu = ? WHERE cod = ?";
    $stmt_update = mysqli_prepare($conexao, $update_vagas);
    mysqli_stmt_bind_param($stmt_update, "ii", $novas_vagasatu, $id_torneio);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Registra a compra na tabela COMPRA
    $inserir_compra = "INSERT INTO compra (id_usuario, id_torneio, vagascompradas, datacom) VALUES (?, ?, ?, ?)";
    $stmt_compra = mysqli_prepare($conexao, $inserir_compra);
    mysqli_stmt_bind_param($stmt_compra, "iiis", $id_usuario, $id_torneio, $vagascompradas, $datacom);
    mysqli_stmt_execute($stmt_compra);
    mysqli_stmt_close($stmt_compra);

    echo "Compra realizada com sucesso!";
    exit();
}

// Consulta para exibir torneios
$query_torneios = "SELECT cod, idcri, nomet, cidade, estado, vagasatu FROM torneio";
$result_torneios = mysqli_query($conexao, $query_torneios);

if (!$result_torneios) {
    echo "Erro ao carregar os torneios: " . mysqli_error($conexao);
    exit();
}

// Fecha a conexão
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <title>Volei de Areia</title>
  <link rel="stylesheet" href="Vls.css">

     

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                  <!-- Novo botão do menu com Material Icon -->
                  <button class="menu-btn w3-button w3-xlarge" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
                    <span class="material-icons">menu</span>
                </button>

                <!-- Logo -->
                <a href="../../ini.php" class="nav_logo ms-3">Compete Amador</a>

               <!-- Dropdowns -->
               <div class="d-flex justify-content-center mx-auto">
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown1">
                        Futebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1">
                            <li><a class="dropdown-item" href="../Futebol/ftsal.php">Futsal</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Futebol/ft7.php">Futebol 7</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Futebol/ft11.php">Futebol 11</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Futebol/ftv.php">Futevôlei</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown2">
                        Voleibol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown2">
                            <li><a class="dropdown-item" href="vblq.php">Voleibol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="vbla.php">Voleibol de Areia</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown3">
                        Basquete
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown3">
                            <li><a class="dropdown-item" href="../Basquete/bqtq.php">Basquete de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Basquete/bqt3x3.php">Basquete 3x3</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown4">
                        Handebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown4">
                            <li><a class="dropdown-item" href="../Handebol/handq.php">Handebol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Handebol/handp.php">Handebol de Praia</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Botão à direita -->
                <div class="ms-auto">
                    <a href="../../criartorneio.php" class="btn btn-outline-light">Criar Competições</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-start" id="demo" tabindex="-1" aria-labelledby="demoLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="demoLabel">MENU</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group">
                <li class="list-group-item"><a href="../../ini.php">Página Inicial</a></li>
                <li class="list-group-item"><a href="../../Menu/config.php">Configuração</a></li>
                <li class="list-group-item"><a href="../../Menu/tcri.php">Competições Criados</a></li>
                <li class="list-group-item"><a href="../../Menu/tcom.php">Competições Comprados</a></li>
            </ul>
            <div class="mt-3 d-flex justify-content-between align-items-end">
    <button 
        class="btn" 
        data-bs-toggle="modal" 
        data-bs-target="#confirmModal" 
        style="
            background: #006666; 
            border: 2px solid #006666; 
            color: white; 
            margin-left: auto; 
            box-sizing: border-box; 
        "
        onmouseover="this.style.backgroundColor='white'; this.style.color='#006666';"
        onmouseout="this.style.backgroundColor='#006666'; this.style.color='white';"
    >
        Sair
    </button>
</div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: #006666">
          Tem certeza que deseja sair?
        </div>
                <div class="modal-footer">
                <form action="../../logout.php" method="post">
    <button id="cancelBtn" class="btn cancel-btn" data-bs-dismiss="modal" style="background-color: #006666; color: white; border: none; padding: 10px 20px; border-radius: 5px;">Confirmar</button>
    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro -->
    <div class="container mt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="filtro">
                        <div class="form-group">
                            <p class="form-paragraph">Disponibilizamos algumas opções de filtragem para atender às suas preferências.</p>
                        </div>

                        <div class="row">
                            <!-- Coluna 1: Estado e Cidade -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="opcao_estado">Selecione um Estado:</label>
                                    <select name="opcao_estado" id="opcao_estado" class="form-control">
                                        <option value="">Selecione um estado</option>
                                        <?php 
                                        include("../../conecta.php"); 
                                        $query_estado = "SELECT id, nome FROM estado";
                                        $result_estado = mysqli_query($conexao, $query_estado);
                                        if (!$result_estado) {
                                            die('Erro na consulta ao banco de dados (estados): ' . mysqli_error($conexao));
                                        }
                                        while ($row_estado = mysqli_fetch_assoc($result_estado)) {
                                            echo '<option value="' . $row_estado['id'] . '">' . $row_estado['nome'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="opcao_cidade">Selecione uma Cidade:</label>
                                    <select name="opcao_cidade" id="opcao_cidade" class="form-control" disabled>
                                        <option value="">Selecione um estado primeiro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Coluna 2: Gênero e Categoria -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="genero">Selecione um Gênero:</label>
                                    <select id="genero" class="form-control">
                                        <option value="">Todos os gêneros</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Misto">Misto</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="categoria">Selecione uma Categoria:</label>
                                    <select id="categoria" class="form-control">
                                        <option value="">Todas as categorias</option>
                                        <option>SUB-7</option>
                                        <option>SUB-9</option>
                                        <option>SUB-11</option>
                                        <option>SUB-13</option>
                                        <option>SUB-15</option>
                                        <option>SUB-17</option>
                                        <option>Livre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna para Data -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="data">Selecione uma Data:</label>
                                    <input type="date" id="datainicio" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
  <div class="container-fluid mt-2">
    <div class="row">
      <?php
    include("../../conecta.php");

    // Data atual no formato Y-m-d
    $dataAtual = date('Y-m-d');
  
    // Consulta SQL para buscar os torneios com a data de início maior ou igual à data atual
    $query = "SELECT cod, idcri, nomet, estado, cidade, bairro, rua, datalanc, datafim, datainicio, responsavel, valorinsc, descricao, telefonet, modalidades, vagas, vagasatu, njogadores, categoria, uniformes, genero FROM torneio WHERE modalidades = 9 AND datainicio >= '$dataAtual'";
  
    // Execute a consulta e armazene os resultados
    $result = mysqli_query($conexao, $query); // Supondo que $conexao seja sua conexão com o banco
  
  
    // Executa a query para recuperar os dados
    $seleciona = mysqli_query($conexao, $query);
    if (!$seleciona) {
      die("Erro ao executar a consulta: " . mysqli_error($conexao));
    }
    
    // Exibe os resultados da consulta
    while ($campo = mysqli_fetch_array($seleciona)) {
      // Formatando as datas e horas de início e fim
      $dataInicio = date('d/m/Y, H:i', strtotime($campo['datainicio']));
      $dataDIA = date('d/m/Y', strtotime($campo['datainicio']));
      $dataFim = date('d/m/Y, H:i', strtotime($campo['datafim']));
  
      // Obtém o id do gênero, categoria, cidade e estado
      $idGenero = $campo['genero'];
      $idCategoria = $campo['categoria'];
      $idCidade = $campo['cidade'];
      $idEstado = $campo['estado'];
      $idModalidade = $campo['modalidades'];
      $idUniforme = $campo['uniformes'];
  
      // Inicialize o contador (se não estiver inicializado previamente)
      if (!isset($contador)) {
        $contador = 1;
      } else {
        $contador++;
      }
  
      $queryUniforme = "SELECT uniformes FROM uniformes WHERE id = $idUniforme";
      $resultadoUniforme = mysqli_query($conexao, $queryUniforme);
      if (!$resultadoUniforme) {
        die("Erro ao executar a consulta de uniforme: " . mysqli_error($conexao));
      }
  
      // Obtém o valor da coluna 'uniformes'
      $dadosUniforme = mysqli_fetch_array($resultadoUniforme);
      $uniformeNome = $dadosUniforme['uniformes'];
  
      //Consulta para obter o nome da modalidade usando o id
      $queryModalidade = "SELECT modalidades FROM modalidades WHERE id = $idModalidade";
      $resultadoModalidade = mysqli_query($conexao, $queryModalidade);
      if (!$resultadoModalidade) {
        die("Erro ao executar a consulta de modalidade: " . mysqli_error($conexao));
      }
      // Obtém o nome da modalidade
      $dadosModalidade = mysqli_fetch_array($resultadoModalidade);
      $modalidadeNome = $dadosModalidade['modalidades'];
  
  
      // Consulta para obter o nome do gênero usando o id
      $queryGenero = "SELECT genero FROM genero WHERE id = $idGenero";
      $resultadoGenero = mysqli_query($conexao, $queryGenero);
      if (!$resultadoGenero) {
        die("Erro ao executar a consulta de gênero: " . mysqli_error($conexao));
      }
  
      // Obtém o nome do gênero
      $dadosGenero = mysqli_fetch_array($resultadoGenero);
      $generoNome = $dadosGenero['genero'];
  
      // Consulta para obter o nome da categoria usando o id
      $queryCategoria = "SELECT categoria FROM categoria WHERE id = $idCategoria";
      $resultadoCategoria = mysqli_query($conexao, $queryCategoria);
      if (!$resultadoCategoria) {
        die("Erro ao executar a consulta de categoria: " . mysqli_error($conexao));
      }
  
      // Obtém o nome da categoria
      $dadosCategoria = mysqli_fetch_array($resultadoCategoria);
      $categoriaNome = $dadosCategoria['categoria'];
  
      // Consulta para obter o nome da cidade usando o id
      $queryCidade = "SELECT nome FROM cidade WHERE id = $idCidade";
      $resultadoCidade = mysqli_query($conexao, $queryCidade);
      if (!$resultadoCidade) {
        die("Erro ao executar a consulta de cidade: " . mysqli_error($conexao));
      }
  
      // Obtém o nome da cidade
      $dadosCidade = mysqli_fetch_array($resultadoCidade);
      $cidadeNome = $dadosCidade['nome'];
  
      // Consulta para obter o nome do estado usando o id
      $queryEstado = "SELECT nome, uf FROM estado WHERE id = $idEstado";
      $resultadoEstado = mysqli_query($conexao, $queryEstado);
      if (!$resultadoEstado) {
        die("Erro ao executar a consulta de estado: " . mysqli_error($conexao));
      }
  
      // Obtém o id do estado
      $dadosEstado = mysqli_fetch_array($resultadoEstado);
      $estadoUF = $dadosEstado['uf'];
      $estadoNM = $dadosEstado['nome'];
        ?>

        <!-- Card -->
        <div class="col-md-3 col-sm-6 mb-4">
        <div class="card mt-4"  data-genero="<?= strtolower($generoNome) ?>" data-categoria="<?= strtolower($categoriaNome) ?>" data-estado="<?= strtolower($idEstado) ?>" data-cidade="<?= strtolower($idCidade) ?>"data-inicio="<?= strtolower($dataDIA) ?>" >
            <img src="../Fotos/vbla.jpeg" class="card-img-top" alt="Imagem do torneio">
            <div class="card-body">
              <h2 class="card-title"><?= $campo['nomet'] ?></h2>
              <p class="card-text"><strong>Data Início:</strong> <?= $dataInicio ?></p>
              <p class="card-text"><strong>Data Fim:</strong> <?=  $dataFim ?></p>
              <p class="card-text">
                <strong>Valor Inscrição:</strong> 
                <?= $campo['valorinsc'] == '0,00' ? '<strong>GRÁTIS</strong>' : $campo['valorinsc'] ?>
              </p>
              <p class="card-text"><strong>Vagas:</strong> <span id="vagas<?= $campo['cod'] ?>"><?= $campo['vagasatu'] ?></span></p>
              <p class="card-text"><strong>Cidade:</strong> <?= $cidadeNome ?> - <?= $estadoUF ?></p>
              <p class="card-text"><strong>Categoria:</strong> <?= $categoriaNome ?> <?= $generoNome ?></p>
            </div>
            <div class="card-footer" style="display: flex; gap: 10px; padding: 10px;">
              <button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#modalDescricao<?= $contador ?>">Descrição</button>
              <button onclick="abrirModal(<?= $campo['cod'] ?>, <?= $campo['vagasatu'] ?>, <?= $campo['idcri'] ?>)" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCompra">Comprar Vagas</button>
            </div>
          </div>

          <!-- Modal de Descrição -->
          <div class="modal fade" id="modalDescricao<?= $contador ?>" tabindex="-1" aria-labelledby="modalDescricaoLabel<?= $contador ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalDescricaoLabel<?= $contador ?>">Descrição da Competição: <?= $campo['nomet'] ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <table id="cardInfoTable" class="info-table">
                    <tr><td><strong>Responsável</strong></td><td><?= $campo['responsavel'] ?></td></tr>
                    <tr><td><strong>Telefone</strong></td><td><?= $campo['telefonet'] ?></td></tr>
                    <tr><td><strong>Data Início</strong></td><td><?= $dataInicio ?></td></tr>
                    <tr><td><strong>Data Fim</strong></td><td><?= $dataFim ?></td></tr>
                    <tr><td><strong>Modalidade</strong></td><td><?= $modalidadeNome ?></td></tr>
                    <tr><td><strong>Categoria</strong></td><td><?= $categoriaNome ?></td></tr>
                    <tr><td><strong>Gênero</strong></td><td><?= $generoNome ?></td></tr>
                    <tr><td><strong>Máximo de Atletas</strong></td><td><?= $campo['njogadores'] ?></td></tr>
                    <tr><td><strong>Uniforme</strong></td><td><?= $uniformeNome ?></td></tr>
                    <tr><td><strong>Estado</strong></td><td><?= $estadoNM ?></td></tr>
                    <tr><td><strong>Cidade</strong></td><td><?= $cidadeNome ?></td></tr>
                    <tr><td><strong>Bairro</strong></td><td><?= $campo['bairro'] ?></td></tr>
                    <tr><td><strong>Rua</strong></td><td><?= $campo['rua'] ?></td></tr>
                    <tr><td><strong>Descrição</strong></td><td><?= !empty($campo['descricao']) ? $campo['descricao'] : 'Descrição não cadastrada' ?></td></tr>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCompra" tabindex="-1" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCompraLabel">Comprar Vagas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idTorneioModal">
                <input type="hidden" id="vagasatuModal">
                <input type="hidden" id="idCriadorModal">
                <input type="number" id="vagasInputModal" placeholder="Quantas vagas deseja comprar?" class="form-control mb-3" min="1">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" onclick="realizarCompraModal(<?= $usuario_id ?>)">Confirmar Compra</button>
            </div>
        </div>
    </div>
</div>
<script src="../Esportes.js"></script>  
<script>
      // Função para carregar cidades com base no estado selecionado
document.getElementById('opcao_estado').addEventListener('change', function() {
    var estadoId = this.value;
    var cidadeSelect = document.getElementById('opcao_cidade');
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
    cidadeSelect.disabled = true;
    if (estadoId !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../cidade.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                cidadeSelect.innerHTML = xhr.responseText;
                cidadeSelect.disabled = false;
            } else {
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            }
        };
        xhr.send('uf=' + estadoId);
    } else {
        cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
    }
});
function abrirModal(id_torneio, vagasatu, idCriador) {
    document.getElementById('idTorneioModal').value = id_torneio;
    document.getElementById('vagasatuModal').value = vagasatu;
    document.getElementById('idCriadorModal').value = idCriador;
}

// Realizar compra e validação
function realizarCompraModal(id_usuario) {
    const id_torneio = document.getElementById('idTorneioModal').value;
    const vagasatu = parseInt(document.getElementById('vagasatuModal').value, 10);
    const vagasInput = parseInt(document.getElementById('vagasInputModal').value, 10);
    const idCriador = document.getElementById('idCriadorModal').value;

    console.log("Vagas disponíveis: ", vagasatu); 
    console.log("Vagas solicitadas: ", vagasInput);

    // Validação de entrada
    if (isNaN(vagasInput) || vagasInput <= 0) {
        alert('Por favor, insira um número válido de vagas.');
        return;
    }
       // Verificar se o usuário é o criador do torneio
       if (idCriador == id_usuario) {
        alert("Erro: Você não pode comprar vagas do torneio que criou.");
        return;
    }


    // Validação de vagas disponíveis
    if (vagasInput > vagasatu) {
        alert('Erro: O número de vagas solicitadas excede as vagas disponíveis.');
        return;
    }

 
    // Prosseguir com a compra
    const dados = new FormData();
    dados.append('id_usuario', id_usuario);
    dados.append('id_torneio', id_torneio);
    dados.append('vagascompradas', vagasInput);

    fetch('', { // Enviar ao próprio arquivo PHP
        method: 'POST',
        body: dados
    })
    .then(response => response.text())
    .then(data => {
        alert(data);

        if (data.includes('sucesso')) {
            // Atualizar vagas no frontend
            const vagasAtualizadas = vagasatu - vagasInput;
            document.getElementById('vagas' + id_torneio).innerText = vagasAtualizadas;

            // Limpar campo e fechar modal
            document.getElementById('vagasInputModal').value = '';
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalCompra'));
            modal.hide();

        // Redirecionar para a página de início
        window.location.href = '../../Menu/tcom.php';
        }
    })
    .catch(error => console.error('Erro:', error));
}

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

</body>
</html>

