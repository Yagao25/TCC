<?php
// Conexão com o banco de dados
include("../conecta.php");

session_start(); // Inicia a sessão

// Verifica se a sessão está definida e se o usuário está logado corretamente
if (!isset($_SESSION['usuario_email']) || empty($_SESSION['usuario_email'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o ID do usuário está presente na sessão
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    echo "Erro: ID de usuário não encontrado.";
    exit();
}

// Função para obter os nomes (cidade, estado)
function obterNome($conexao, $tabela, $campo_id, $valor_id, $campos = '*') {
    $query = "SELECT $campos FROM $tabela WHERE $campo_id = $valor_id";
    $resultado = mysqli_query($conexao, $query);
    return mysqli_fetch_array($resultado);
}

// Consulta para exibir os torneios comprados pelo usuário, incluindo categoria, gênero e suas compras
$query = "
    SELECT 
        t.cod, t.idcri, t.nomet, t.estado, t.cidade, t.datainicio, t.datafim, 
        t.valorinsc, t.modalidades, t.vagasatu, t.descricao, 
        c.categoria AS categoriaNome, g.genero AS generoNome, 
        m.modalidades AS modalidadeNome, 
        t.njogadores, u.uniformes AS uniforme, 
        t.bairro, t.rua, 
        t.responsavel, t.telefonet, 
        GROUP_CONCAT(co.vagascompradas SEPARATOR ', ') AS comprasFeitas
    FROM 
        torneio t
    INNER JOIN 
        compra co ON t.cod = co.id_torneio
    LEFT JOIN 
        categoria c ON t.categoria = c.id
    LEFT JOIN 
        genero g ON t.genero = g.id
    LEFT JOIN 
        modalidades m ON t.modalidades = m.id
    LEFT JOIN 
        uniformes u ON t.uniformes = u.id
    WHERE 
        co.id_usuario = $usuario_id
    GROUP BY 
        t.cod
";
$seleciona = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Competições Compradas</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="tcri.css">
</head>

<body>
    <header>
    <nav class="navbar navbar-sm navbar-dark">
            <div class="container-fluid">
                <!-- Novo botão do menu com Material Icon -->
                <button class="menu-btn w3-button w3-xlarge" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
                    <span class="material-icons">menu</span>
                </button>

                <!-- Logo -->
                <a href="../ini.php" class="nav_logo ms-3">Compete Amador</a>

                <!-- Dropdowns -->
                <div class="d-flex justify-content-center mx-auto">
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown1">
                        Futebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1">
                            <li><a class="dropdown-item" href="../Esportes/Futebol/ftsal.php">Futsal</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Futebol/ft7.php">Futebol 7</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Futebol/ft11.php">Futebol 11</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Futebol/ftv.php">Futevôlei</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown2">
                        Voleibol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown2">
                            <li><a class="dropdown-item" href="../Esportes/Volei/vblq.php">Voleibol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Volei/vbla.php">Voleibol de Areia</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown3">
                        Basquete
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown3">
                            <li><a class="dropdown-item" href="../Esportes/Basquete/bqtq.php">Basquete de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Basquete/bqt3x3.php">Basquete 3x3</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown4">
                        Handebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown4">
                            <li><a class="dropdown-item" href="../Esportes/Handebol/handq.php">Handebol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Esportes/Handebol/handp.php">Handebol de Praia</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Botão à direita -->
                <div class="ms-auto">
                    <a href="../criartorneio.php" class="btn btn-outline-light">Criar Competições</a>
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
            <li class="list-group-item"><a href="../ini.php">Página Inicial</a></li>
            <li class="list-group-item"><a href="config.php">Configuração</a></li>
            <li class="list-group-item"><a href="tcri.php">Competições Criados</li>
            <li class="list-group-item"><a href="tcom.php">Competições Comprados</a></li>
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
 <form action="../logout.php" method="post">
    <button id="cancelBtn" class="btn cancel-btn" data-bs-dismiss="modal" style="background-color: #006666; color: white; border: none; padding: 10px 20px; border-radius: 5px;">Confirmar</button>
    </form>
</div>

                
      </div>
    </div>
  </div>

  <h1 class="text-center mt-2" style="color: white;">Lista de Competições Comprados</h1>
<div class="container mt-4">

<?php if (mysqli_num_rows($seleciona) > 0): ?>
    <?php while ($campo = mysqli_fetch_array($seleciona)): ?>
        <?php
            // Obter o nome da cidade e estado
            $cidadeNome = obterNome($conexao, 'cidade', 'id', $campo['cidade'], 'nome')['nome'];
            $estado = obterNome($conexao, 'estado', 'id', $campo['estado'], 'nome, uf');
            $estadoUF = $estado['uf'];

            // Formatar as datas
            $dataInicio = DateTime::createFromFormat('Y-m-d H:i:s', $campo['datainicio'])->format('d/m/Y, H:i');
            $dataFim = DateTime::createFromFormat('Y-m-d H:i:s', $campo['datafim'])->format('d/m/Y, H:i');
            $valorInscricao = $campo['valorinsc'] == '0,00' ? '<strong>GRÁTIS</strong>' : $campo['valorinsc'];
        ?>
        <div class="card mt-4">
            <?php
            // Mapeamento de modalidades e imagens
            $modalidadesImagens = [
                1 => 'bqt3x3.jpeg',
                2 => 'bqtq.jpeg',
                3 => 'ft11.jpeg',
                4 => 'ft7.jpeg',
                5 => 'ftsal.jpeg',
                6 => 'ftv.jpeg',
                7 => 'handp.jpeg',
                8 => 'handq.jpeg',
                9 => 'vbla.jpeg',
                10 => 'vblq.jpeg'
            ];

            // Verifica se o ID da modalidade existe no mapeamento, se não, usa uma imagem padrão
            $idmoda = $campo['modalidades'];
            $imagemModalidade = isset($modalidadesImagens[$idmoda]) 
                ? $modalidadesImagens[$idmoda] 
                : 'imagem_padrao.jpg';

            echo "<img src='Fotos/{$imagemModalidade}' class='card-img-top' alt='Imagem da modalidade'>";
            ?>
            <div class="card-body">
                <h2 class="card-title"><?= $campo['nomet'] ?></h2>
                <p class="card-text"><strong>Data Início:</strong> <?= $dataInicio ?></p>
                <p class="card-text"><strong>Data Fim:</strong> <?= $dataFim ?></p>
                <p class="card-text"><strong>Valor Inscrição:</strong> <?= $valorInscricao ?></p>
                <p class="card-text"><strong>Vagas:</strong> <span id="vagas<?= $campo['cod'] ?>"><?= $campo['vagasatu'] ?></span></p>
                <p class="card-text"><strong>Cidade:</strong> <?= $cidadeNome ?> - <?= $estadoUF ?></p>
                <p class="card-text"><strong>Categoria:</strong> <?= $campo['categoriaNome'] ?> <?= $campo['generoNome'] ?></p>
            </div>
            <div class="card-footer">
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalDescricao<?= $campo['cod'] ?>">Descrição</button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCompras<?= $campo['cod'] ?>">Ver Compras</button>
            </div>
        </div>
          <!-- Modal para Descrição -->
          <div class="modal fade" id="modalDescricao<?= $campo['cod'] ?>" tabindex="-1" aria-labelledby="modalLabelDescricao<?= $campo['cod'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabelDescricao<?= $campo['cod'] ?>">Descrição da Competição: <?= $campo['nomet'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Responsável:</strong> <?= $campo['responsavel'] ?></p>
                            <p><strong>Telefone:</strong> <?= $campo['telefonet'] ?></p>
                            <p><strong>Data e Hora Início:</strong> <?= $dataInicio ?></p>
                            <p><strong>Data e Hora Fim:</strong> <?= $dataFim ?></p>
                            <p><strong>Modalidade:</strong> <?= $campo['modalidadeNome'] ?></p>
                            
                            <p><strong>Categoria e Gênero:</strong> <?= $campo['categoriaNome'] ?> <?= $campo['generoNome'] ?></p> <!-- Novo campo -->

                            <p><strong>Máximo de Atletas:</strong> <?= $campo['njogadores'] ?></p> <!-- Novo campo -->
                            <p><strong>Uniforme:</strong> <?= $campo['uniforme'] ?></p> <!-- Novo campo -->
                            <p><strong>Estado:</strong> <?= $estado['nome'] ?> - <?= $estadoUF ?></p> <!-- Novo campo -->
                            <p><strong>Cidade:</strong> <?= $cidadeNome ?></p> <!-- Novo campo -->
                            <p><strong>Bairro:</strong> <?= $campo['bairro'] ?></p> <!-- Novo campo -->
                            <p><strong>Rua:</strong> <?= $campo['rua'] ?></p> <!-- Novo campo -->
                            <p><strong>Descrição:</strong> <?= !empty($campo['descricao']) ? $campo['descricao'] : 'Descrição não cadastrada' ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Ver Compras -->
<div class="modal fade" id="modalCompras<?= $campo['cod'] ?>" tabindex="-1" aria-labelledby="modalLabelCompras<?= $campo['cod'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelCompras<?= $campo['cod'] ?>">Compras das Competições: <?= $campo['nomet'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <?php 
                    // Query para obter as compras com a data
                    $query_compras = "SELECT vagascompradas, datacom FROM compra WHERE id_torneio = {$campo['cod']}";
                    $result_compras = mysqli_query($conexao, $query_compras);
                    
                    if ($result_compras) {
                        while ($compra = mysqli_fetch_assoc($result_compras)) {
                            $dataCompraFormatada = date('d/m/Y', strtotime($compra['datacom']));
                            echo "<li>Você comprou {$compra['vagascompradas']} vagas em {$dataCompraFormatada}.</li>";
                        }
                    } else {
                        echo "<li>Não há compras registradas.</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

        <?php endwhile; ?>
<?php else: ?>
    <!-- Exibição da mensagem quando nenhum torneio for encontrado -->
    <p class="text-center" style="color: black; font-size: 2em; font-weight: bold; margin-top: -1em;">Nenhuma competição comprada</p>
<?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
