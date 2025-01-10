<?php
include("conecta.php");

session_start(); // Inicia a sessão

// Verifica se a sessão está definida e se o usuário está logado corretamente
if (!isset($_SESSION['usuario_email']) || empty($_SESSION['usuario_email'])) {
    // Redireciona para a tela de login
    header("Location: index.php");
    exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
}

// Verifica se o ID do usuário está na sessão
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id']; // Aqui você recupera o ID corretamente
} else {
    // Caso o id não esteja definido na sessão, redireciona ou exibe uma mensagem de erro
    echo "Erro: ID de usuário não encontrado.";
    exit();
}

// Agora você pode usar a variável $usuario_id conforme necessário

$mensagem = "";
$usuario_id = $nomet = $responsavel = $estado = $cidade = $bairro = $rua = $modalidades = $categoria = $genero = $telefonet = $uniformes = $njogadores = $vagas = $valorinsc = $dataInicio = $dataFim = $descricao = $senha = "";

// Função para obter as opções de uma tabela do banco de dados
function obterOpcoes($conexao, $tabela, $campo_id, $campo_nome) {
    $opcoes = array();
    $query = "SELECT $campo_id, $campo_nome FROM $tabela";
    $result = mysqli_query($conexao, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $opcoes[] = $row;
        }
    }

    return $opcoes;
}

// Obter opções de modalidades
$opcoes_modalidade = obterOpcoes($conexao, "modalidades", "id", "modalidades");

// Obter opções de categorias
$opcoes_categoria = obterOpcoes($conexao, "categoria", "id", "categoria");

// Obter opções de gêneros
$opcoes_genero = obterOpcoes($conexao, "genero", "id", "genero");

// Obter opções de uniformes
$opcoes_uniforme = obterOpcoes($conexao, "uniformes", "id", "uniformes");

// Verificar se todos os campos foram enviados pelo formulário
if (isset($_POST["nomet"], $_POST["responsavel"], $_POST["opcao_estado"], $_POST["opcao_cidade"], $_POST["bairro"], $_POST["rua"], $_POST["opcao_modalidade"], $_POST["opcao_categoria"], $_POST["opcao_genero"], $_POST["tltt"], $_POST["opcao_uniforme"], $_POST["numerojj"], $_POST["numeroVagas"], $_POST["valorFormatado"], $_POST["dataInicio"], $_POST["dataFim"], $_POST["descricao"], $_POST["senha"])) 
{
    // Converter os valores de data/hora para o formato desejado
    $dataInicioOriginal = $_POST['dataInicio'];
    $dataFimOriginal = $_POST['dataFim'];
    $dataInicioFormatada = !empty($dataInicioOriginal) ? date('Y-m-d H:i:s', strtotime($dataInicioOriginal)) : null;
    $dataFimFormatada = !empty($dataFimOriginal) ? date('Y-m-d H:i:s', strtotime($dataFimOriginal)) : null;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Competição</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criartor.css">
    <style>
   
  </style>
</head>
    <body>
    <nav class="navbar navbar-sm navbar-dark">
            <div class="container-fluid">
                <!-- Novo botão do menu com Material Icon -->
                <button class="menu-btn w3-button w3-xlarge" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
                    <span class="material-icons">menu</span>
                </button>

                <!-- Logo --><a href="ini.php" class="nav_logo ms-3" style="
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); 
    color: white; 
    text-decoration: none; 
    font-size: 1.5rem; 
    font-weight: 600; 
    transition: text-shadow 0.3s ease, color 0.3s ease;">Compete Amador</a>


                <!-- Dropdowns -->
                <div class="d-flex justify-content-center mx-auto">
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown1">
                        Futebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1">
                            <li><a class="dropdown-item" href="Esportes/Futebol/ftsal.php">Futsal</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Futebol/ft7.php">Futebol 7</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Futebol/ft11.php">Futebol 11</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Futebol/ftv.php">Futevôlei</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown2">
                        Voleibol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown2">
                            <li><a class="dropdown-item" href="Esportes/Volei/vblq.php">Voleibol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Volei/vbla.php">Voleibol de Areia</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown3">
                        Basquete
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown3">
                            <li><a class="dropdown-item" href="Esportes/Basquete/bqtq.php">Basquete de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Basquete/bqt3x3.php">Basquete 3x3</a></li>
                        </ul>
                    </div>
                    <div class="dropdown mx-5">
                        <button class="btn text-white dropdown-toggle" type="button" id="dropdown4">
                        Handebol
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdown4">
                            <li><a class="dropdown-item" href="Esportes/Handebol/handq.php">Handebol de Quadra</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Esportes/Handebol/handp.php">Handebol de Praia</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Botão à direita -->
                <div class="ms-auto">
                    <a href="criartorneio.php" class="btn btn-outline-light">Criar Competições</a>
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
            <li class="list-group-item"><a href="ini.php">Página Inicial</a></li>
            <li class="list-group-item"><a href="Menu\config.php">Configuração</a></li>
            <li class="list-group-item"><a href="Menu\tcri.php">Competições Criados</li>
            <li class="list-group-item"><a href="Menu\tcom.php">Competições Comprados</a></li>
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
 <form action="logout.php" method="post">
    <button id="cancelBtn" class="btn cancel-btn" data-bs-dismiss="modal" style="background-color: #006666; color: white; border: none; padding: 10px 20px; border-radius: 5px;">Confirmar</button>
    </form>
</div>

                
      </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <div class="container register mt-5">
    <div class="row">
        <div class="col-md-12 register-right">
            <!-- Botão de Voltar ajustado -->
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm mb-3" style="position: absolute; top: 1px; left: 25px; background: #006666; border: none; padding: 8px 15px; font-size: 14px;">Voltar</a>

            
            <h3 class="register-heading">Crie sua própria competição</h3>
            <form method="post" action="gravatn.php" id="meuFormulario2">
    <div class="row register-form">
        <div class="col-md-6">
            <div class="form-group">
                <input type="text" name="nomet" id="nomet" class="form-control" placeholder="Nome do Torneio" required maxlength="80">
                <br>
               
                <select name="opcao_estado" id="opcao_estado" class="form-control">
                    <option value="">Selecione um estado</option>
                    <!-- Opção adicionada -->
                    <?php 
                    // Inclua o arquivo de conexão com o banco de dados
                    include("conecta.php"); 

                    // Consulta para obter as opções pré-cadastradas de estados
                    $query_estado = "SELECT id, nome FROM estado"; // Supondo que sua tabela se chame 'estado'
                    $result_estado = mysqli_query($conexao, $query_estado);

                    // Verifique se a consulta de estados teve sucesso
                    if (!$result_estado) {
                        die('Erro na consulta ao banco de dados (estados): ' . mysqli_error($conexao));
                    }

                    // Loop através dos resultados da consulta de estados
                    while ($row_estado = mysqli_fetch_assoc($result_estado)) {
                        // Adiciona cada opção ao array com o ID e o nome do estado
                        echo '<option value="' . $row_estado['id'] . '">' . $row_estado['nome'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <select name="opcao_cidade" id="opcao_cidade" class="form-control" disabled>
                    <option value="">Selecione um estado primeiro</option>
                </select>
                <br>
                <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Bairro"  required maxlength="150">
                <br>
                <input type="text" name="rua" id="rua" class="form-control" placeholder="Rua" required  maxlength="150">
                <br>
                <select name="opcao_modalidade" id="opcao_modalidade" class="form-control">
                    <option value="">Selecione uma modalidade</option>
                    <?php foreach ($opcoes_modalidade as $opcao_modalidade): ?>
                        <option value="<?php echo $opcao_modalidade['id']; ?>">
                            <?php echo $opcao_modalidade['modalidades']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                Data do Torneio
                <input type="datetime-local" name="dataInicio" class="form-control" id="dataInicio" min="" onchange="atualizarMinData()"><br>
               
                <select name="opcao_uniforme" id="opcao_uniforme" class="form-control">
                    <option value="">Selecione um uniforme</option>
                    <?php foreach ($opcoes_uniforme as $opcao_uniforme): ?>
                        <option value="<?php echo $opcao_uniforme['id']; ?>">
                            <?php echo $opcao_uniforme['uniformes']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br> 
                <select name="opcao_genero" id="opcao_genero" class="form-control">
                    <option value="">Selecione um gênero</option>
                    <?php foreach ($opcoes_genero as $opcao_genero): ?>
                        <option value="<?php echo $opcao_genero['id']; ?>">
                            <?php echo $opcao_genero['genero']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <input type="text" name="responsavel" id="responsavel" class="form-control" placeholder="Responsável" required maxlength="40">
                <br>
                <input type="tel" name="tltt" id="telefone" class="form-control" placeholder="Telefone do Responsável" oninput="formatPhoneNumber(this)"  required maxlength="15" >
                <br>
                <select name="opcao_categoria" id="opcao_categoria" class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($opcoes_categoria as $opcao_categoria): ?>
                        <option value="<?php echo $opcao_categoria['id']; ?>">
                            <?php echo $opcao_categoria['categoria']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="text" name="njogadores" class="form-control" id="njogadores" oninput="formatarNumero(this);" maxlength="3" placeholder="Número de Jogadores"required value="">
                <br>
                <input type="text" name="numeroVagas" class="form-control" id="numeroVagas" oninput="formatarNumero(this);" maxlength="3" placeholder="Número de Vagas"required value="">
                <br>
                <input type="text" name="valorinsc" class="form-control" id="valorinsc" oninput="formatarValor(this);" maxlength="6" placeholder="Valor da Inscrição">
                <br> 
                Data fim
                <input type="datetime-local" name="dataFim" class="form-control" id="dataFim" min="" onchange="atualizarMinData()">
                <br>
                <div class="mb-3">
  <div class="form-floating">
    <textarea class="form-control" id="comment" name="descricao" placeholder="Comentário" style="height: 105px; font-size: 16px; padding: 12px; border-radius: 8px; border: 1px solid #ccc;"></textarea>
    <label for="comment">Comentário</label>
  </div>
</div>

             
            </div>
         
        </div>
    </div>   <button type="submit" id="criarTorneioBtn" class="btn btn-primary btn-lg btn-block"onclick="compararSenha()"style="background: #006666; border: none;">Criar Torneio</button>
</form>

        </div>
    </div>
</div>




<script>

    document.getElementById('opcao_estado').addEventListener('change', function() {
        var estadoId = this.value;
        var cidadeSelect = document.getElementById('opcao_cidade');
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;
        if (estadoId !== '') {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cidade.php');
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

    function formatarData(data) {
    var partes = data.split("T");
    var dataPartes = partes[0].split("-");
    var horaPartes = partes[1].split(":");
    return `${dataPartes[2]} ${obterNomeMes(parseInt(dataPartes[1]))} ${dataPartes[0]}, ${horaPartes[0]}:${horaPartes[1]}`;
};

function obterNomeMes(mes) {
    var meses = [
        "janeiro", "fevereiro", "março", "abril", "maio", "junho",
        "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
    ];
    return meses[mes - 1];
};

function atualizarMinData() {
    var amanha = new Date();
    // Adiciona um dia à data atual
    amanha.setDate(amanha.getDate() + 1);
    // Ajusta o horário para 00:00 no fuso horário local
    amanha.setHours(0, 0, 0, 0);
    
    // Verifica se já passou da meia-noite
    var agora = new Date();
    if (agora.getHours() >= 0) {
        // Se já passou da meia-noite, ajusta para o próximo dia
        amanha.setDate(amanha.getDate() + 0);
    }

    var dataAmanhaFormatada = amanha.toISOString().slice(0, 16);
    document.getElementById("dataInicio").min = dataAmanhaFormatada;
    document.getElementById("dataFim").min = dataAmanhaFormatada;
};

atualizarMinData();


function formatarValor(input) {
    // Remove caracteres não numéricos
    let valor = input.value.replace(/[^\d]/g, '');

    // Adiciona zeros à direita nas casas decimais
    while (valor.length < 2) {
        valor = '0' + valor;
    }

    // Separa a parte decimal e a parte inteira
    let parteDecimal = valor.slice(-2); // Pega os últimos dois caracteres
    let parteInteira = valor.slice(0, -2); // Pega todos os caracteres antes dos últimos dois

    // Se a parte inteira estiver vazia, define como '0'
    parteInteira = parteInteira.length === 0 ? '0' : parteInteira;

    // Se a parte decimal estiver vazia, define como '00'
    parteDecimal = parteDecimal.length === 0 ? '00' : parteDecimal;

    // Garante que a parte inteira não ultrapasse 999
    if (parteInteira.length > 3) {
        parteInteira = parteInteira.slice(0, 3);
    }

    // Formata o valor com duas casas decimais
    let valorFormatado = parteInteira + ',' + parteDecimal;

    // Garante que o valor não ultrapasse 999,99
    if (parseFloat(valorFormatado.replace(',', '.')) > 999.99) {
        valorFormatado = '999,99';
    }

    // Remove zeros desnecessários à esquerda
    valorFormatado = valorFormatado.replace(/^0+(?=\d)/, '');

    // Se o valor estiver vazio, define como '0,00'
    if (valorFormatado === '') {
        valorFormatado = '0,00';
    }

    input.value = valorFormatado;
}

        

    // Função para formatar o número de vagas e jj
    function formatarNumero(input) {
        // Remover caracteres não numéricos
        let valor = input.value.replace(/[^\d]/g, '');

        // Se o valor estiver vazio, colocar 00
        if (valor.length === 0) {
            input.value = '';
            return;
        }

        // Limitar o valor a no máximo 3 dígitos
        valor = valor.substring(0, 3);

        // Se o valor começar com zero, remover o zero
        if (valor.charAt(0) === '0') {
            valor = valor.substring(1);
        }

        // Formatar o valor
        input.value = valor;
    };

    // Definir o valor inicial dos campos
    window.onload = function() {
        // Definir valores iniciais dos campos
        document.getElementById('numeroVagas').value = '';
        document.getElementById('njogadores').value = '';
        document.getElementById('valorinsc').value = '';
    }; 
   

   
    document.addEventListener("DOMContentLoaded", function() {
    // Adiciona um ouvinte de eventos ao botão "Criar Torneio"
    document.getElementById("criarTorneioBtn").addEventListener("click", function(event) {
    
        // Verifica se todos os campos obrigatórios estão preenchidos
        var camposObrigatorios = document.querySelectorAll('input:required, select:required');
        for (var i = 0; i < camposObrigatorios.length; i++) {
            if (camposObrigatorios[i].value.trim() === '') {
                alert('Por favor, preencha todos os campos obrigatórios.');
                event.preventDefault(); // Cancela o envio do formulário
                return false; // Não permite o envio do formulário
            }
        }

        // Verifica se os campos "Número de Jogadores" e "Número de Vagas" são válidos
        var numeroJogadores = parseInt(document.getElementById("njogadores").value);
        var numeroVagas = parseInt(document.getElementById("numeroVagas").value);
        if (numeroJogadores <= 0 || isNaN(numeroJogadores) || numeroVagas <= 1 || isNaN(numeroVagas)) {
            alert("Por favor, insira números válidos para Número de Jogadores e Número de Vagas.");
            event.preventDefault(); // Cancela o envio do formulário
            return false; // Não permite o envio do formulário
        }

      // Verifica se as datas de início e fim foram selecionadas
var dataInicio = document.getElementById("dataInicio").value;
var dataFim = document.getElementById("dataFim").value;

// Verifica se os campos de data estão vazios
if (dataInicio === "" || dataFim === "") {
    if (dataInicio === "" && dataFim === "") {
        alert("Por favor, selecione uma data de início e uma data de fim para o torneio.");
    } else if (dataInicio === "") {
        alert("Por favor, selecione uma data de início para o torneio.");
    } else {
        alert("Por favor, selecione uma data de fim para o torneio.");
    }
    event.preventDefault(); // Cancela o envio do formulário
    return false; // Não permite o envio do formulário
}

// Converte as datas de início e fim em objetos Date
var dataInicioObj = new Date(dataInicio);
var dataFimObj = new Date(dataFim);

// Verifica se a data de início é maior que a data de fim
if (dataInicioObj > dataFimObj) {
    alert("A data de início não pode ser posterior à data de fim.");
    event.preventDefault(); // Cancela o envio do formulário
    return false; // Não permite o envio do formulário
}

        // Verifica se todas as opções obrigatórias foram selecionadas
        var selectCampos = ["opcao_uniforme", "opcao_modalidade", "opcao_genero", "opcao_categoria", "opcao_estado", "opcao_cidade"];
        for (var i = 0; i < selectCampos.length; i++) {
            if (document.getElementById(selectCampos[i]).value === "") {
                alert("Por favor, preencha todos os campos obrigatórios.");
                event.preventDefault(); // Cancela o envio do formulário
                return false; // Não permite o envio do formulário
            }
        }

        // Se todas as verificações passarem, permite o envio do formulário
        return true;
    });
});


</script>	<script src="Banco de Dados\yc.js"></script>

	
</body>

</html>