<?php
include("conecta.php");
$erro = "";
$usuario_email = ""; // Inicializa a variável para armazenar o email do usuário

// Inicia a sessão
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['senhal'])) {
    // Verifica se os campos de usuário e senha foram enviados
    if (!empty($_POST['usuario_email']) && !empty($_POST['senhal'])) {
        $usuario_email = $_POST['usuario_email'];
        $senha = $_POST['senhal'];

        // Usa uma query preparada para evitar SQL Injection
        $query = "SELECT * FROM usuario WHERE usuario = ? OR email = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "ss", $usuario_email, $usuario_email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) == 1) {
            $usuario_id = mysqli_fetch_assoc($resultado);

            // Verifica se a senha corresponde ao hash salvo no banco de dados
            if (password_verify($senha, $usuario_id['senha'])) {
                // Credenciais válidas, define as variáveis de sessão
                $_SESSION['usuario_email'] = $usuario_email;
                $_SESSION['usuario_id'] = $usuario_id['cod']; // Armazena o ID do usuário na sessão

                // Redireciona para a página inicial
                header("Location: ini.php");
                exit();
            } else {
                $erro = "Senha inválida.";
            }
        } else {
            $erro = "Usuário não encontrado.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}

session_destroy(); // Destroi a sessão
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:300,600" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="index.css">

 
</head> 


<body>
 <div class="container fluid">
   <section id="formHolder">
      <div class="row">
         <!-- Brand Box -->
         <div class="col-md-6 col-sm-12 brand">
            <div class="heading">
               <h2>Compete Amador</h2>
            </div>
            <div class="success-msg">
               <p>Great! You are one of our members now</p>
               <a href="#" class="profile btn btn-outline-primary btn-lg">Your Profile</a>
            </div>
         </div>
         <!-- Form Box -->
         <div class="col-md-6 col-sm-12 form">
            <div class="row">
               <!-- Login Form -->
               <div class="col-md-12 login form-peice">
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="p-4 shadow rounded bg-white">
                     <div class="form-group mb-4" >
                        <label for="usuario_email">Usuário ou Email</label>
                        <input   style="border: 1px solid #ccc; border-radius: 4px;"type="text" class="form-control" name="usuario_email" id="usuario_email" placeholder="Digite usuário ou email" required maxlength="50">
                     </div>
                     <div class="form-group mb-4">
   <label for="senhal">Senha</label>
   <div class="hgtup" style="width: 327px; display: flex; align-items: center; border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">
  <input type="password" class="form-control" name="senhal" id="senhal" placeholder="Digite sua senha" required 
         style="flex: 1; border: none; font-size: 14px; outline: none;">
  <button class="" type="button" onclick="togglePasswordVisibilityl()" tabindex="-1" 
          style="background: none; border: none; padding: 10px; cursor: pointer; color: #555; display: flex; align-items: center; justify-content: center;">
    <i id="eye-icon" class="fas fa-eye" style="font-size: 16px; color: inherit;"></i>
  </button>
</div>

</div>
                     <?php if (!empty($erro)) { ?>
                     <div class="alert alert-danger" role="alert">
                        <?php echo $erro; ?>
                     </div>
                     <?php } ?>
                     <div class="CTA text-center">
                        <input type="submit" value="Login" class="btn btn-primary w-100 mb-3">
                        <a href="#" class="switch">Cadastre-se</a>
                     </div>
                  </form>
               </div>

               
               <?php
include("conecta.php");

$mensagem = "";
$nome = $usuario = $telefone = $cpf = $email = $datanasc = "";

// Verificar se todos os campos foram enviados pelo formulário
if (isset($_POST["nomeInput"], $_POST["usuInput"], $_POST["tlInput"], $_POST["cpfInput"], $_POST["femail"], $_POST["dataInput"], $_POST["senha"])) {
    // Obter os valores do formulário
    $nome = $_POST["nomeInput"];
    $usuario = $_POST["usuInput"];
    $telefone = $_POST["tlInput"];
    $cpf = $_POST["cpfInput"];
    $email = $_POST["femail"];
    $datanasc = $_POST["dataInput"];
    $senha = $_POST["senha"];

    // Preparar as consultas SQL para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conexao, $nome);
    $usuario = mysqli_real_escape_string($conexao, $usuario);
    $telefone = mysqli_real_escape_string($conexao, $telefone);
    $cpf = mysqli_real_escape_string($conexao, $cpf);
    $email = mysqli_real_escape_string($conexao, $email);
    $datanasc = mysqli_real_escape_string($conexao, $datanasc);
    $senha = mysqli_real_escape_string($conexao, $senha);

    // Verificar se já existe um usuário com o mesmo nome de usuário
    $sql_usuario = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result_usuario = mysqli_query($conexao, $sql_usuario);
    if (mysqli_num_rows($result_usuario) > 0) {
        echo "<script>alert('Já existe um usuário com este nome.');</script>";
    } else {
        // Verificar se já existe um telefone cadastrado
        $sql_telefone = "SELECT * FROM usuario WHERE telefone = '$telefone'";
        $result_telefone = mysqli_query($conexao, $sql_telefone);
        if (mysqli_num_rows($result_telefone) > 0) {
            echo "<script>alert('Este telefone já está cadastrado.');</script>";
        } else {
            // Verificar se já existe um CPF cadastrado
            $sql_cpf = "SELECT * FROM usuario WHERE cpf = '$cpf'";
            $result_cpf = mysqli_query($conexao, $sql_cpf);
            if (mysqli_num_rows($result_cpf) > 0) {
                echo "<script>alert('Já existe um usuário com este CPF cadastrado.');</script>";
            } else {
                // Verificar se já existe um e-mail cadastrado
                $sql_email = "SELECT * FROM usuario WHERE email = '$email'";
                $result_email = mysqli_query($conexao, $sql_email);
                if (mysqli_num_rows($result_email) > 0) {
                    echo "<script>alert('Este e-mail já está cadastrado.');</script>";
                } else {
                    // Inserir os dados no banco de dados
                    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_insert = "INSERT INTO usuario (nome, usuario, telefone, cpf, email, datanasc, senha) VALUES ('$nome', '$usuario', '$telefone', '$cpf', '$email', '$datanasc', '$senha_criptografada')";
                    if (mysqli_query($conexao, $sql_insert)) {
                        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='index.php';</script>";
                    } else {
                        echo "<script>alert('Erro ao cadastrar: " . mysqli_error($conexao) . "');</script>";
                    }
                }
            }
        }
    }
}
?>

               <!-- Signup Form -->
               <div class="col-md-12 signup form-peice switched">
                  <form method="post" id="meuFormulario" style="width: 475px;">
                     <div class="row mb-4">
                        <div class="col-md-6">
                           <label for="nomeInput" class="form-label">Nome Completo</label>
                           <input type="text" class="form-control" id="nomeInput" name="nomeInput" value="<?php echo $nome; ?>" maxlength="40"required placeholder="Digite seu nome">
                        </div>
                        <div class="col-md-6">
                           <label for="usuInput" class="form-label">Usuário</label>
                           <input type="text" class="form-control" id="usuInput" name="usuInput" value="<?php echo $usuario; ?>" maxlength="20"required placeholder="Digite seu usuário">
                        </div>
                     </div>
                     <div class="row mb-4">
                        <div class="col-md-6">
                           <label for="tlInput" class="form-label">Telefone</label>
                           <input type="tel" class="form-control" id="tlInput" name="tlInput" oninput="formatPhoneNumber(this)" maxlength="15" value="<?php echo $telefone; ?>" required placeholder="Digite seu telefone">
                        </div>
                        <div class="col-md-6">
                        <label for="cpfInput" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpfInput" oninput="formatCPF(this)" maxlength="14" name="cpfInput" value="<?php echo $cpf; ?>" required placeholder="Digite seu cpf">
                        </div>
                     </div>
                     <div class="row mb-4">
                        <div class="col-md-6">
                           <label for="femail" class="form-label">E-mail</label>
                           <input type="email" class="form-control" id="femail" name="femail" value="<?php echo $email; ?>" maxlength="100"required placeholder="Digite seu e-mail">
                        </div>
                        <div class="col-md-6">
                           <label for="dataInput" class="form-label">Data de Nascimento</label>
                           <input type="text" class="form-control" id="dataInput" name="dataInput" oninput="formatDate(this)"maxlength="10"  value="<?php echo $datanasc; ?>" required placeholder="Data de nascimento">
                        </div>
                     </div>
                     <div class="row mb-4">
   <div class="col-md-6">
      <label for="senha" class="form-label">Senha</label>
      <div class="hgtup" style="width: 140x; display: flex; align-items: center; border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">
  <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha"maxlength="20" required 
         style="flex: 1; border: none; padding: 10px; font-size: 14px; outline: none;">
  <button class="" type="button" onclick="togglePasswordVisibility()" tabindex="-1" 
          style="background: none; border: none; padding: 10px; cursor: pointer; color: #555; display: flex; align-items: center; justify-content: center;">
    <i id="eye-icon" class="fas fa-eye" style="font-size: 16px; color: inherit;"></i>
  </button>
</div>
   </div>
   <div class="col-md-6">
      <label for="senhac" class="form-label">Confirmar Senha</label>
      <div class="hgtup" style="width: 140x; display: flex; align-items: center; border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">
  <input type="password" class="form-control" name="senhac" id="senhac" placeholder="Digite sua senha"maxlength="20" required 
         style="flex: 1; border: none; padding: 10px; font-size: 14px; outline: none;">
  <button class="" type="button" onclick="togglePasswordVisibilityc()" tabindex="-1" 
          style="background: none; border: none; padding: 10px; cursor: pointer; color: #555; display: flex; align-items: center; justify-content: center;">
    <i id="eye-icon" class="fas fa-eye" style="font-size: 16px; color: inherit;"></i>
  </button>
</div>
   </div>
</div>
                     <div class="CTA text-center">
                        <input type="submit" value="Cadastre-se" id="submit" class="btn btn-primary w-100 mb-3">
                        <a href="#" class="switch">Já tenho uma conta</a>
                     </div>
                  </form>
               </div>
            </div>
         </div>

      </div>
   </section>
</div>

<script src="jas/script.js"></script>
<script src="jas/index.js"></script>

<script>
// Função para validar CPF
function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, ''); // Remove tudo que não for número

    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        alert('CPF inválido!');
        return false;
    }

    let soma = 0, resto;

    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
    }

    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;

    if (resto !== parseInt(cpf.charAt(9))) {
        alert('CPF inválido!');
        return false;
    }

    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
    }

    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;

    if (resto !== parseInt(cpf.charAt(10))) {
        alert('CPF inválido!');
        return false;
    }

    return true;
}

// Função para validar idade e verificar se a data é válida
function validarIdade() {
    var dataInput = document.getElementById('dataInput').value;

    // Verificar se a data tem o formato correto
    var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    if (!regex.test(dataInput)) {
        alert("Data inválida. Por favor, insira a data no formato dd/mm/yyyy.");
        return false;
    }

    // Converter a data para o formato Date
    var partesData = dataInput.split('/');
    var dia = parseInt(partesData[0], 10);
    var mes = parseInt(partesData[1], 10) - 1; // Meses no JavaScript começam do 0
    var ano = parseInt(partesData[2], 10);

    // Verificar se a data é válida
    var dataNascimento = new Date(ano, mes, dia);
    if (dataNascimento.getDate() !== dia || dataNascimento.getMonth() !== mes || dataNascimento.getFullYear() !== ano) {
        alert("Data inexistente. Por favor, insira uma data válida.");
        return false;
    }

    // Verificar idade
    var hoje = new Date();
    var idade = hoje.getFullYear() - dataNascimento.getFullYear();
    var mesAtual = hoje.getMonth() - dataNascimento.getMonth();

    if (mesAtual < 0 || (mesAtual === 0 && hoje.getDate() < dataNascimento.getDate())) {
        idade--;
    }

    if (idade < 18 || idade > 80) {
        alert("A idade mínima é de 18 anos e a idade máxima é de 80 anos.");
        return false;
    }

    return true;
}

// Função para verificar se as senhas coincidem
function verificarSenhas() {
    const senha = document.getElementById("senha").value;
    const confirmarSenha = document.getElementById("senhac").value;

    if (senha !== confirmarSenha) {
        alert("As senhas não coincidem! Tente novamente.");
        return false;
    }
    return true;
}

// Enviar formulário via AJAX
$("#meuFormulario").on("submit", function (event) {
    event.preventDefault(); // Evitar envio normal do formulário

    const cpf = $("#cpfInput").val();
    if (!verificarSenhas() || !validarCPF(cpf) || !validarIdade()) {
        return; // Impede o envio se alguma validação falhar
    }

    $.ajax({
        url: "processar_cadastro.php",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function (response) {
            if (response.status === "sucesso") {
                alert(response.mensagem);
                window.location.href = "index.php";
            } else {
                alert(response.mensagem);
            }
        },
        error: function () {
            alert("Erro na comunicação com o servidor.");
        }
    });
});

</script>


</body>
</html>
