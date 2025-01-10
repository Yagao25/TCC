<?php
// Inicia a sessão (se ainda não estiver iniciada)
session_start();

// Finaliza a sessão (logout)
session_destroy();

// Redireciona para a página inicial
header("Location: index.php");
exit;
?>
