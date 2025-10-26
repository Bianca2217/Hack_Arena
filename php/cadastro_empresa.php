<?php
include("conexao.php"); // Conecta ao banco

$showForm = true; // Variável para controlar a exibição do formulário


// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nomeEmpresa = isset($_POST['nomeEmpresa']) ? trim($_POST['nomeEmpresa']) : '';
    $senha = isset($_POST['Senha']) ? $_POST['Senha'] : '';
    $cnpj = isset($_POST['CNPJ']) ? preg_replace('/\D+/', '', $_POST['CNPJ']) : ''; // Remove tudo que não é número
    $situacao_cadastral = isset($_POST['Situacao_cadastral']) ? trim($_POST['Situacao_cadastral']) : '';
    $telefone = isset($_POST['Telefone']) ? trim($_POST['Telefone']) : '';
    $data_abertura = isset($_POST['Data_abertura']) ? $_POST['Data_abertura'] : null;
    $estado = isset($_POST['Estado']) ? trim($_POST['Estado']) : '';
    $cidade = isset($_POST['Cidade']) ? trim($_POST['Cidade']) : '';
    $rua = isset($_POST['Rua']) ? trim($_POST['Rua']) : '';
    $numero = isset($_POST['Numero']) ? (int)$_POST['Numero'] : 0;

    // Verifica se todos os campos obrigatórios foram preenchidos
    if ($nomeEmpresa === '' || $senha === '' || $cnpj === '' || $situacao_cadastral === '' || $estado === '' || $cidade === '' || $rua === '' || $numero === 0) {
        echo '<div class="alert alert-danger">Por favor, preencha todos os campos obrigatórios.</div>';
    } else {
        // Criptografa a senha antes de salvar
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere os dados na tabela empresa
        $sql = "INSERT INTO empresa (nome_empresa, senha_hash, cnpj, situacao_cadastral, telefone, data_abertura, estado, cidade, rua, numero)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'sssssssssi', $nomeEmpresa, $senhaHash, $cnpj, $situacao_cadastral, $telefone, $data_abertura, $estado, $cidade, $rua, $numero);

            // Executa e verifica sucesso
            if (mysqli_stmt_execute($stmt)) {
                $showForm = false; // Oculta o formulário após o cadastro
                echo '<div class="alert alert-success">Empresa cadastrada com sucesso! <a href="login_empresa.php">Ir para o login</a></div>';
            } else {
                echo '<div class="alert alert-danger">Erro ao cadastrar: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo '<div class="alert alert-danger">Erro ao preparar a consulta.</div>';
        }
    }
}

$conn->close(); // Fecha conexão
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Empresa</title>
  <link rel="stylesheet" href="../css/cadastro_empresa.css">
</head>
<body>
  <div class="container">
    <h1>CADASTRAR</h1>
    <!-- Formulário de cadastro -->
    <form id="empresaForm" action="cadastro_empresa.php" method="post">
      <input type="text" id="nomeEmpresa" name="nomeEmpresa" placeholder="NOME DA EMPRESA" required>
      <input type="password" id="Senha" name="Senha" placeholder="SENHA" required>
      <input type="text" id="CNPJ" name="CNPJ" placeholder="CNPJ" required>
      <input type="text" id="Situacao_cadastral" name="Situacao_cadastral" placeholder=" SITUAÇÃO CADASTRAL" required>
      <input type="tel" id="Telefone" name="Telefone" placeholder="TELEFONE" required>
      <input type="date" id="Data_abertura" name="Data_abertura" placeholder="DATA DE ABERTURA" required>
<!-- Endereço -->
      <div class="linha">
        <input type="text" id="Cidade" name="Cidade" placeholder="CIDADE" required>
        <select id="Estado" name="Estado" required>
          <option value="">Estado</option>
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AP">AP</option>
          <option value="AM">AM</option>
          <option value="BA">BA</option>
          <option value="CE">CE</option>
          <option value="DF">DF</option>
          <option value="ES">ES</option>
          <option value="GO">GO</option>
          <option value="MA">MA</option>
          <option value="MT">MT</option>
          <option value="MS">MS</option>
          <option value="MG">MG</option>
          <option value="PA">PA</option>
          <option value="PB">PB</option>
          <option value="PR">PR</option>
          <option value="PE">PE</option>
          <option value="PI">PI</option>
          <option value="RJ">RJ</option>
          <option value="RN">RN</option>
          <option value="RS">RS</option>
          <option value="RO">RO</option>
          <option value="RR">RR</option>
          <option value="SC">SC</option>
          <option value="SP">SP</option>
          <option value="SE">SE</option>
          <option value="TO">TO</option>
        
        </select>
      </div>

      <input type="text" id="Rua" name="Rua" placeholder="Rua" required>
      <input type="int" id="Numero" name="Numero" placeholder="Número" required>
      
      <button type="submit">CADASTRAR</button>
    </form>
     <!-- Voltar ao login -->
    <button type="button" class="btn-voltar"><a href="login_empresa.php" class="voltar">Volte ao login</a></button>
  </div>
</body>
</html>
