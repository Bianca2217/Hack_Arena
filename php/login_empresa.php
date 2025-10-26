<?php
session_start();
include ("conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Captura os valores do formulário (CNPJ e senha)
  $cnpj = isset($_POST['cnpj']) ? trim($_POST['cnpj']) : '';
  $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

  // Verifica se os campos foram preenchidos
  if ($cnpj === '' || $senha === '') {
    echo "Por favor, preencha todos os campos.";
  } else {
    // Consulta preparada para buscar a empresa pelo CNPJ
    $sql = "SELECT id_empresa, nome_empresa, cnpj, senha_hash FROM empresa WHERE cnpj = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $cnpj); // Evita SQL Injection
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      // Verifica se encontrou o registro
      if ($row = mysqli_fetch_assoc($result)) {
        // Compara a senha digitada com o hash do banco
        if (password_verify($senha, $row['senha_hash'])) {
          // Cria variáveis de sessão para identificar o usuário logado
          $_SESSION['logado'] = true;
          $_SESSION['nomeEmpresa'] = $row['nome_empresa'];
          $_SESSION['cnpj'] = $row['cnpj'];
          header("Location: ../html/indexchat.html"); // Redireciona para a área da empresa
          exit;
        }
      }
      echo "Usuário ou senha inválidos."; // Caso a senha não bata
      mysqli_stmt_close($stmt);
    } else {
      echo "Erro ao preparar consulta.";
    }
  }
}

mysqli_close($conn); // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN</title>
  <link rel="stylesheet" href="../css/login_empresa.css">
</head>

<body>
  <div class="container">
    <h1><span>LOGIN</span></h1>
    <!-- Formulário de login -->
    <form id="empresaForm" method="post" action="login_empresa.php">
      <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ" required>
      <input type="password" id="senha" name="senha" placeholder="SENHA" required>
      <button type="submit">Entrar</button>

      <!-- Botão para cadastro -->
      <nav class="textinho">Não possui sua empresa cadastrada?</nav>
      <button type="button" class="btn-cadastro">
        <a class="cadastro-btn" href="cadastro_empresa.php">Cadastrar</a>
      </button>
    </form>

    <!-- Botão para voltar à página inicial -->
    <a href="../index.html" class="voltar">
      <button type="button" class="btn-voltar">Volte ao tela inicial</button>
    </a>
  </div>
</body>
</html>
