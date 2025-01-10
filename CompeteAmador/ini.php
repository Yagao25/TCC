<?php
include("conecta.php");

session_start(); // Inicia a sessão

// Verifica se a sessão está definida e se o usuário está logado corretamente
if (!isset($_SESSION['usuario_email']) || empty($_SESSION['usuario_email'])) {
    // Redireciona para a tela de login se não estiver logado
    header("Location: index.php");
    exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>inicio</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ini.css">
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
                <a href="ini.php" class="nav_logo ms-3">Compete Amador</a>

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
</body>
</html>
