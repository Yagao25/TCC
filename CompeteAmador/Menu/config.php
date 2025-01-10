<?php
// Inclui o arquivo de conexão com o banco de dados
include("../conecta.php");

session_start(); // Inicia a sessão

// Verifica se a sessão está definida e se o usuário está logado corretamente
if (!isset($_SESSION['usuario_email']) || empty($_SESSION['usuario_email'])) {
    // Redireciona para a tela de login se não estiver logado
    header("Location: index.php");
    exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
}

// Obtém o email do usuário logado na sessão
$dados_usuario = $_SESSION['usuario_email'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica a ação a ser executada
    $acao = $_POST['acao'] ?? '';

    if ($acao == 'atualizar_usuario') {
        // Obtém o novo nome de usuário
        $novoUsuario = $_POST['usuario'];

        // Prepara e executa a consulta para verificar se o novo nome de usuário já existe
        $verificar_query = $conexao->prepare("SELECT * FROM usuario WHERE usuario = ?");
        $verificar_query->bind_param("s", $novoUsuario);
        $verificar_query->execute();
        $verificar_result = $verificar_query->get_result();

        if ($verificar_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Usuário já existe!']);
            exit();
        } else {
            // Prepara e executa a consulta para atualizar o nome de usuário
            $update_query = $conexao->prepare("UPDATE usuario SET usuario = ? WHERE usuario = ? OR email = ?");
            $update_query->bind_param("sss", $novoUsuario, $dados_usuario, $dados_usuario);
            $update_result = $update_query->execute();

            if ($update_result) {
                $_SESSION['usuario_email'] = $novoUsuario;
                echo json_encode(['success' => true, 'message' => 'Usuário atualizado com sucesso!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário.']);
                exit();
            }
        }
    } elseif ($acao == 'atualizar_email') {
        // Obtém o novo email
        $novoEmail = $_POST['email'];

        // Prepara e executa a consulta para verificar se o novo email já existe
        $verificar_query = $conexao->prepare("SELECT * FROM usuario WHERE email = ?");
        $verificar_query->bind_param("s", $novoEmail);
        $verificar_query->execute();
        $verificar_result = $verificar_query->get_result();

        if ($verificar_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email já existe!']);
            exit();
        } else {
            // Prepara e executa a consulta para atualizar o email
            $update_query = $conexao->prepare("UPDATE usuario SET email = ? WHERE usuario = ? OR email = ?");
            $update_query->bind_param("sss", $novoEmail, $dados_usuario, $dados_usuario);
            $update_result = $update_query->execute();

            if ($update_result) {
                $_SESSION['usuario_email'] = $novoEmail;
                echo json_encode(['success' => true, 'message' => 'Email atualizado com sucesso!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar email.']);
                exit();
            }
        }
    } elseif ($acao == 'atualizar_senha') {
        // Obtém os valores do POST
        $senhaAtualDigitada = $_POST['senha_atual']; // Senha atual digitada pelo usuário
        $novaSenha = $_POST['senha'];
        $novaSenhac = $_POST['senhac'];
        // Obtemos a senha armazenada do banco de dados
        $usuario_query = $conexao->prepare("SELECT senha FROM usuario WHERE usuario = ? OR email = ?");
        $usuario_query->bind_param("ss", $dados_usuario, $dados_usuario);
        $usuario_query->execute();
        $usuario_query->bind_result($senhaArmazenada);
        $usuario_query->fetch();
        $usuario_query->close();
        
        // Verifica se a senha atual digitada corresponde à senha armazenada
        if (!password_verify($senhaAtualDigitada, $senhaArmazenada)) {
            echo json_encode(['success' => false, 'message' => 'Senha atual incorreta.']);
            exit();
        }// Verifica se a nova senha e a confirmação da nova senha são iguais
        elseif ($novaSenha !== $novaSenhac) {
            echo json_encode(['success' => false, 'message' => 'A nova senha e a confirmação da senha não coincidem.']);
            exit();
        }
        // Verifica se a nova senha é igual à senha atual
        elseif ($senhaAtualDigitada === $novaSenha) {
            echo json_encode(['success' => false, 'message' => 'A nova senha deve ser diferente da senha atual.']);
            exit();
        }

        // Criptografa a nova senha
        $senha_criptografada = password_hash($novaSenha, PASSWORD_DEFAULT);
        
        // Atualiza a senha no banco de dados
        $update_query = $conexao->prepare("UPDATE usuario SET senha = ? WHERE usuario = ? OR email = ?");
        $update_query->bind_param("sss", $senha_criptografada, $dados_usuario, $dados_usuario);
        $update_result = $update_query->execute();
        
        if ($update_result) {
            echo json_encode(['success' => true, 'message' => 'Senha atualizada com sucesso!', 'nova_senha' => $senha_criptografada]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar senha.']);
            exit();
        }
    
    } elseif ($acao == 'atualizar_telefone') {
        // Obtém o novo telefone
        $novoTelefone = $_POST['telefone'];

        // Prepara e executa a consulta para verificar se o novo telefone já existe
        $verificar_query = $conexao->prepare("SELECT * FROM usuario WHERE telefone = ?");
        $verificar_query->bind_param("s", $novoTelefone);
        $verificar_query->execute();
        $verificar_result = $verificar_query->get_result();

        if ($verificar_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Telefone já existe!']);
            exit();
        } else {
            // Prepara e executa a consulta para atualizar o telefone
            $update_query = $conexao->prepare("UPDATE usuario SET telefone = ? WHERE usuario = ? OR email = ?");
            $update_query->bind_param("sss", $novoTelefone, $dados_usuario, $dados_usuario);
            $update_result = $update_query->execute();

            if ($update_result) {
                echo json_encode(['success' => true, 'message' => 'Telefone atualizado com sucesso!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar telefone.']);
                exit();
            }
        }
    }
}

/// Constrói a consulta SQL para selecionar apenas o usuário logado
$query = $conexao->prepare("SELECT * FROM usuario WHERE usuario = ? OR email = ?");
$query->bind_param("ss", $dados_usuario, $dados_usuario);
$query->execute();
$result = $query->get_result();

if (!$result) {
    die("Erro ao executar a consulta: " . mysqli_error($conexao));
}

// Verifica se encontrou algum registro
if ($result->num_rows > 0) {
    // Obtém os dados do primeiro (e único, pois o email deve ser único) resultado
    $campo = $result->fetch_array(MYSQLI_ASSOC);
    
    // Exemplo de como formatar a data de nascimento
    $datanasc_original = $campo['datanasc']; // Supondo que $campo['datanasc'] contenha 'yyyy-mm-dd'
    // Convertendo para formato de data
    $timestamp = strtotime($datanasc_original);
    // Formatando para 'dd/mm/aaaa'
    $datanasc_formatada = date('d/m/Y', $timestamp);
    
    // Pega o CPF
    $cpf = $campo['cpf']; // Supondo que o CPF já esteja formatado como XXX.XXX.XXX-XX
    
    // Mascara o CPF para ***.XXX.XXX-**
    $cpfMascarado = substr_replace($cpf, '***', 0, 3); // Substitui os 3 primeiros dígitos por ***
    $cpfMascarado = substr_replace($cpfMascarado, '**', -2, 2); // Substitui os últimos 2 dígitos por **

} else {
    // Se não encontrar nenhum registro, define $nome como vazio ou outra mensagem padrão
    $nome = "Usuário não encontrado";
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="config.css">
    <title>Perfil</title>
</head>
<style>

/* Alterar cor do texto quando passar o mouse */
.dropdown-item:hover {
    background-color: #006666; /* Altera o fundo ao passar o mouse */
}
/* Sobrescrevendo o estilo do botão btn-primary para deixá-lo verde */
.btn-primary {
    background-color: #006666; /* Altera o fundo ao passar o mouse */
   
}
/* Garantir que não tenha efeito de hover */
.btn-primary:hover {
    background-color: #006666; /* Altera o fundo ao passar o mouse */
   
   
}
</style>


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
            <li class="list-group-item"><a href="../Menu\config.php">Configuração</a></li>
            <li class="list-group-item"><a href="../Menu\tcri.php">Competições Criados</li>
            <li class="list-group-item"><a href="../Menu\tcom.php">Competições Comprados</a></li>
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
  <div class="content-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
    <a href="javascript:history.back();" class="btn btn-primary mb-3" style="background: #006666; border: none;">Voltar</a>
    <h2 style="margin: -15px 0 0; text-align: center; position: absolute; left: 50%; transform: translateX(-50%);">Usuário</h2>
</div>

<?php if ($result->num_rows > 0): ?>
    <!-- Seção para Nome -->
<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <div class="input-group">
        <input type="text" class="form-control" id="nome" value="<?= htmlspecialchars($campo['nome']) ?>" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarNome">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição do Nome -->
<div class="modal fade" id="modalEditarNome" tabindex="-1" aria-labelledby="modalEditarNomeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarNomeLabel">Editar Nome</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Impossível mudar o nome.</p>
                <form method="post" action="editNome.php" id="formEditarNome">
                    <!-- O formulário pode estar vazio, pois a alteração não é permitida -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Seção para Data de Nascimento -->
<div class="mb-3">
    <label for="dataNascimento" class="form-label">Data de Nascimento</label>
    <div class="input-group">
        <input type="text" class="form-control" id="dataNascimento" value="<?= htmlspecialchars($datanasc_formatada) ?>" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarDataNascimento">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição da Data de Nascimento -->
<div class="modal fade" id="modalEditarDataNascimento" tabindex="-1" aria-labelledby="modalEditarDataNascimentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarDataNascimentoLabel">Editar Data de Nascimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Impossível mudar a data de nascimento.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<div class="mb-3">
    <label for="usu" class="form-label">Usuário:</label>
    <div class="input-group">
        <input type="text" class="form-control" id="usu" value="<?= htmlspecialchars($campo['usuario']) ?>" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUsu">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição do Usuário -->
<div class="modal fade" id="modalUsu" tabindex="-1" aria-labelledby="modalUsuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuLabel">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" >
                    <div class="mb-3">
                        <label for="usuModal" class="form-label">Novo Usuário:</label>
                        <input type="text" class="form-control" id="usuModal" value="<?= htmlspecialchars($campo['usuario']) ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarUsu">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('salvarUsu').addEventListener('click', function() {
    atualizarUsuario();
});

document.getElementById('usuModal').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita o comportamento padrão, como o envio de formulários
        atualizarUsuario();
    }
});

function atualizarUsuario() {
    const novoUsuario = document.getElementById('usuModal').value;

    // Atualizar o usuário
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'acao=atualizar_usuario&usuario=' + encodeURIComponent(novoUsuario)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('usu').value = novoUsuario;
            alert(data.message);
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalUsu'));
            modal.hide();
        } else {
            alert(data.message);
        }
    });
}
</script>

<div class="mb-3">
    <label for="cpf" class="form-label">CPF</label>
    <div class="input-group">
        <input type="text" class="form-control" id="cpf" value="<?= htmlspecialchars($cpfMascarado) ?>" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição do Usuário -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Impossível mudar o CPF.</p>
                <form method="post" action="editacd.php" id="formEditarUsuario">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>






<div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <div class="input-group">
        <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($campo['email']) ?>" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEmail">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição do Email -->
<div class="modal fade" id="modalEmail" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmailLabel">Editar Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="editacd.php" id="formEmail">
                    <div class="mb-3">
                        <label for="emailModal" class="form-label">Novo Email:</label>
                        <input type="email" class="form-control" id="emailModal" value="<?= htmlspecialchars($campo['email']) ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarEmail">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailModal = document.getElementById('emailModal');
    function atualizarEmail() {
        const novoEmail = emailModal.value;

        // Atualizar o email
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'acao=atualizar_email&email=' + encodeURIComponent(novoEmail)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('email').value = novoEmail;
                alert(data.message);
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalEmail'));
                modal.hide();
            } else {
                alert(data.message);
            }
        });
    }

    // Adiciona evento de clique no botão
    salvarEmail.addEventListener('click', atualizarEmail);

    // Adiciona evento de tecla pressionada no campo de email
    emailModal.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita que o formulário seja enviado (se houver um formulário)
            atualizarEmail();
        }
    });
});

</script>

<div class="mb-3">
    <label for="telefone" class="form-label">Telefone:</label>
    <div class="input-group">

        <input type="tel" class="form-control" id="telefone" value="<?= htmlspecialchars($campo['telefone']) ?>" maxlength="15" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTelefone">
          <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição do Telefone -->
<div class="modal fade" id="modalTelefone" tabindex="-1" aria-labelledby="modalTelefoneLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTelefoneLabel">Editar Telefone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" >
                    <div class="mb-3">
                        <label for="telefoneModal" class="form-label">Novo Telefone:</label>
                        <input type="tel" class="form-control" id="telefoneModal" value="<?= htmlspecialchars($campo['telefone']) ?>" maxlength="15" oninput="formatPhoneNumber(this)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarTelefone" >Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefoneModal = document.getElementById('telefoneModal');
    function atualizarTelefone() {
        const novoTelefone = telefoneModal.value;

        // Atualizar o telefone
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'acao=atualizar_telefone&telefone=' + encodeURIComponent(novoTelefone)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('telefone').value = novoTelefone;
                alert(data.message);
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalTelefone'));
                modal.hide();
            } else {
                alert(data.message);
            }
        });
    }

    // Adiciona evento de clique no botão
    salvarTelefone.addEventListener('click', atualizarTelefone);

    // Adiciona evento de tecla pressionada no campo de telefone
    telefoneModal.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita que o formulário seja enviado (se houver um formulário)
            atualizarTelefone();
        }
    });
});  
</script>
<div class="mb-3">
    <label for="senha" class="form-label">Senha:</label>
    <div class="input-group">
        <input type="text" class="form-control" id="senha" value="Sua senha está protegida" readonly>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSenha">
        <i class="bi bi-pen-fill text-dark"></i>
        </button>
    </div>
</div>

<!-- Modal de Edição da Senha -->
<div class="modal fade" id="modalSenha" tabindex="-1" aria-labelledby="modalSenhaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSenhaLabel">Editar Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSenha">
                    <div class="mb-3">
                        <label for="senhaModalAtual" class="form-label">Senha Atual:</label>
                        <input type="password" class="form-control" id="senhaModalAtual" value="">
                    </div>
                    <div class="mb-3">
                        <label for="senhaModal" class="form-label">Nova Senha:</label>
                        <input type="password" class="form-control" id="senhaModal" value="">
                    </div>
                    <div class="mb-3">
                        <label for="senhaModalc" class="form-label">Confirme Nova Senha:</label>
                        <input type="password" class="form-control" id="senhaModalc" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarSenha">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
 function atualizarSenha() {
    // Obtém os valores digitados pelo usuário
    const senhaAtualDigitada = document.getElementById('senhaModalAtual').value.trim();
    const novaSenha = document.getElementById('senhaModal').value.trim();
    const senhaConfirmacao = document.getElementById('senhaModalc').value.trim();
  // Verifica se os valores não estão vazios
  if (!senhaAtualDigitada || !novaSenha || !senhaConfirmacao) {
        alert('Todos os campos devem ser preenchidos.');
        return;
    }

    // Cria uma string de parâmetros para enviar
    const params = new URLSearchParams({
        acao: 'atualizar_senha',
        senha: novaSenha,
        senha_atual: senhaAtualDigitada,
        senhac: senhaConfirmacao
    }).toString();
    
    // Atualizar a senha
    fetch('', { // Substitua pelo caminho real do PHP
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na comunicação com o servidor: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Atualiza a senha no cliente com a nova senha recebida
            document.getElementById('senha').value = 'Sua senha foi atualizada';

            // Limpa os campos do modal
            document.getElementById('senhaModalAtual').value = '';
            document.getElementById('senhaModal').value = '';
            document.getElementById('senhaModalc').value = '';

            // Fecha o modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalSenha'));
            modal.hide();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao tentar atualizar a senha.');
    });
}

// Adiciona evento de clique no botão
document.getElementById('salvarSenha').addEventListener('click', atualizarSenha);

// Adiciona evento de tecla pressionada nos campos de entrada
function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita o comportamento padrão de submit do formulário
        atualizarSenha();
    }
}

// Adiciona o evento de tecla pressionada nos campos de entrada
document.getElementById('senhaModalAtual').addEventListener('keydown', handleKeyPress);
document.getElementById('senhaModal').addEventListener('keydown', handleKeyPress);
document.getElementById('senhaModalc').addEventListener('keydown', handleKeyPress);


</script>



<?php else: ?>
    <p>Nenhum usuário encontrado.</p>
<?php endif; ?>
<script>
    function formatPhoneNumber(input) {
    const phoneNumber = input.value.replace(/\D/g, ''); // Remove non-numeric characters

    let formattedPhoneNumber = '';
    const part1 = phoneNumber.slice(0, 2);
    const part2 = phoneNumber.slice(2, 7);
    const part3 = phoneNumber.slice(7);

    if (part1) {
        formattedPhoneNumber += `(${part1}`;
    }
    if (part2) {
        formattedPhoneNumber += `) ${part2}`;
    }
    if (part3) {
        formattedPhoneNumber += `-${part3}`;
    }

    input.value = formattedPhoneNumber;
}


</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
