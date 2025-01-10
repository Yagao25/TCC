<?php
// Inclua o arquivo de conexão com o banco de dados
include("conecta.php");

// Verifique se o ID do estado foi enviado via método POST de forma segura
$uf = isset($_POST['uf']) ? $_POST['uf'] : '';

// Verifique se o UF do estado é válido
if(!empty($uf)) {
    // Consulta preparada para evitar injeção de SQL
    $query_cidades = "SELECT id, nome FROM cidade WHERE uf = ?";
    
    // Preparar e executar a consulta
    if ($stmt = mysqli_prepare($conexao, $query_cidades)) {
        mysqli_stmt_bind_param($stmt, "s", $uf);
        mysqli_stmt_execute($stmt);
        
        // Verifique se a consulta de cidades teve sucesso
        $result_cidades = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result_cidades) > 0) {
            // Inicia a string de opções de cidade
            $cidades_options = '';
            $cidades_options .= '<option value="">Selecione uma cidade</option>';

            // Loop através dos resultados da consulta de cidades
            while ($row_cidade = mysqli_fetch_assoc($result_cidades)) {
                // Adiciona cada opção ao string
                $cidades_options .= '<option value="' . $row_cidade['id'] . '">' . $row_cidade['nome'] . '</option>';
            }

            // Retorna as opções de cidade para serem adicionadas ao menu suspenso
            echo $cidades_options;
        } else {
            // Se não houver cidades correspondentes ao estado, retorna uma opção indicando isso
            echo '<option value="">Nenhuma cidade encontrada</option>';
        }
    } else {
        // Se houver um erro na preparação da consulta
        echo '<option value="">Erro na consulta ao banco de dados</option>';
    }
} else {
    // Se o UF do estado não foi enviado via método POST, ou é inválido
    echo '<option value="">Erro: UF do estado não fornecido</option>';
}
?>
