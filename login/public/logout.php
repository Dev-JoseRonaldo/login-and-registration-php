<?php 
  session_start();
  session_unset();  // Limpa as variáveis de sessão
  session_destroy();  // Destrui a sessão

  session_start();
  session_regenerate_id(true); // Regenera o ID da sessão
  header("Location: index.php");
  exit;
?>
