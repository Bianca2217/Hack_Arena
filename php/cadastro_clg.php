<?php
include("conexao.php"); // Conecta ao banco

$showForm = true; // Variável para controlar a exibição do formulário
$message = ''; // Variável para armazenar a mensagem

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os campos enviados pelo formulário
    $nomeEscola = trim($_POST['nomeEscola'] ?? '');
    $senha = $_POST['Senha'] ?? '';
    $cod_escola = trim($_POST['Cod_escola'] ?? '');
    $ensino_ofe = trim($_POST['Ensino_ofe'] ?? '');
    $telefone = trim($_POST['Telefone'] ?? '');
    $cidade = trim($_POST['Cidade'] ?? '');
    $estado = trim($_POST['Estado'] ?? '');
    $rua = trim($_POST['Rua'] ?? '');
    $numero = (int)($_POST['Numero'] ?? 0);

    // Validação dos campos
    if ($nomeEscola === '' || $senha === '' || $cod_escola === '' || $ensino_ofe === '' ||
        $telefone === '' || $cidade === '' || $estado === '' || $rua === '' || $numero === 0) {
        echo '<div class="alert alert-danger">Por favor, preencha todos os campos obrigatórios.</div>';
    } else {
        // Criptografa a senha e insere no banco
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO escola (nome_escola, senha_hash, cod_escola, ensino_oferecido, telefone, cidade, estado, rua, numero)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
          $showForm = false; // Esconde o formulário
            mysqli_stmt_bind_param($stmt, 'ssssssssi', $nomeEscola, $senhaHash, $cod_escola, $ensino_ofe, $telefone, $cidade, $estado, $rua, $numero);
            if (mysqli_stmt_execute($stmt)) {
                echo '<div class="alert alert-success">Escola cadastrada com sucesso! <a href="login_clg.php">Ir para o login</a></div>';
            } else {
                echo '<div class="alert alert-danger">Erro ao cadastrar: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo '<div class="alert alert-danger">Erro ao preparar a consulta.</div>';
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Escola</title>
  <link rel="stylesheet" href="../css/cadastro_clg.css">
</head>
<body>
  <div class="container">
  <h2>CADASTRAR</h2>
  <form id="escolaForm" action="cadastro_clg.php" method="post">
    <input type="text" id="nomeEscola" name="nomeEscola" placeholder="Nome da escola" required>
    <input type="password" id="Senha" name="Senha" placeholder="Senha" required>
    <input type="text" id="Cod_escola" name="Cod_escola" placeholder="Código do Censo Escolar" required>
    <input type="text" id="Ensino_ofe" name="Ensino_ofe" placeholder="Ensino Oferecido" required>
    <input type="tel" id="Telefone" name="Telefone" placeholder="Telefone" required>

    <div class="row">
      <input type="text" id="Cidade" name="Cidade" placeholder="Cidade" required>
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
    <input type="text" id="Numero" name="Numero" placeholder="Número" required>
  
    <button type="submit">CADASTRAR</button>
  </form>
    <button type="button" class="btn-voltar"><a href="login_clg.php" class="voltar">Volte ao login</a></button>
  </div>
</body>
</html>
