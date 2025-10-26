<?php
session_start();
include("conexao.php"); // Conecta ao banco

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Captura os dados do formulário
  $codEscola = isset($_POST['cod_escola']) ? trim($_POST['cod_escola']) : '';
  $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

  // Verifica se os campos foram preenchidos
  if ($codEscola === '' || $senha === '') {
    echo "Por favor, preencha todos os campos.";
  } else {
    // Busca a escola pelo código
    $sql = "SELECT id_escola, nome_escola, cod_escola, senha_hash FROM escola WHERE cod_escola = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $codEscola);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      // Se encontrou a escola, verifica a senha
      if ($row = mysqli_fetch_assoc($result)) {
        $senhaHash = $row['senha_hash'];
        if (password_verify($senha, $senhaHash)) {
          $_SESSION['logado'] = true;
          $_SESSION['escola_nome'] = $row['nome_escola'];
          $_SESSION['escola_codigo'] = $row['cod_escola'];
          header("Location: pg_escola.php"); // Redireciona após login
          exit;
        }
      }
      echo "Usuário ou senha inválidos.";
      mysqli_stmt_close($stmt);
    } else {
      echo "Erro ao preparar consulta.";
    }
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN</title>
  <link rel="stylesheet" href="../css/login_clg.css">
</head>

<body>
  <div class="container">
    <h1>LOGIN</h1>
    <!-- Formulário de login -->
    <form id="escolaForm" action="login_clg.php" method="POST">  
      <input type="text" name="cod_escola" placeholder="Código do Censo Escolar" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>

    <nav class="textinho">Não possui sua escola cadastrada?</nav>
    <button type="button" class="btn-cadastro">
      <a class="cadastro-btn" href="cadastro_clg.php">Cadastrar</a>
    </button>

    <a href="../index.html" class="voltar">
      <button type="button" class="btn-voltar">Volte ao tela inicial</button>
    </a>
  </div>
</body>
</html>
