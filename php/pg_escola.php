<?php
// Inicia a sessão — necessário para acessar variáveis da sessão (como nome e código da escola)
session_start();

// Verifica se o usuário está logado
// Se a variável 'logado' não existir ou for diferente de true, redireciona para a página de login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login_clg.php"); // envia o usuário para a tela de login
    exit(); // encerra o script para impedir que o resto da página carregue sem login
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Faz a página se ajustar a celulares -->
    <title>Cadastro Realizado - Sistema Escolar</title>
    <link rel="stylesheet" href="../css/pg_escola.css"> <!-- Importa o arquivo CSS externo -->
</head>
<body>
    <main class="container"> <!-- Estrutura principal da página -->

        <!-- Cabeçalho com ícone e título -->
        <header>
            <div class="success-icon">🎓</div> <!-- Ícone de sucesso (formatura) -->
            <h1>Cadastro Escolar Realizado!</h1> <!-- Título principal -->
        </header>
        
        <!-- Seção de boas-vindas -->
        <section class="welcome-section">
            <p class="welcome-message">
                Bem-vindo ao Sistema Educacional,<br>
                <!-- Exibe o nome da escola armazenado na sessão (ou “Escola” se não tiver nada salvo) -->
                <span class="school-name"><?php echo $_SESSION['escola_nome'] ?? 'Escola'; ?></span>
            </p>
            
            <div class="academic-year">
                <!-- Mostra o ano atual automaticamente -->
                Ano Letivo: <?php echo date('Y'); ?>
            </div>
            
            <p>Sua instituição de ensino foi cadastrada com sucesso em nossa plataforma educacional.</p>
        </section>
        
        <!-- Seção com informações da escola -->
        <section class="school-info">
            <h3>📋 Informações da Escola</h3>
            <div class="info-grid"> <!-- Grade de informações -->
                
                <!-- Código da escola (pega da sessão ou gera um genérico com a data) -->
                <div class="info-item">
                    <div class="info-label">Código da Escola</div>
                    <div class="info-value">#<?php echo $_SESSION['escola_codigo'] ?? 'ESC'.date('Ymd'); ?></div>
                </div>

                <!-- Mostra a data atual do cadastro -->
                <div class="info-item">
                    <div class="info-label">Data de Cadastro</div>
                    <div class="info-value"><?php echo date('d/m/Y'); ?></div>
                </div>

                <!-- Mostra o status ativo em verde -->
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value" style="color: #27ae60;">● Ativa</div>
                </div>

                <!-- Mostra o tipo de plano do sistema -->
                <div class="info-item">
                    <div class="info-label">Plano</div>
                    <div class="info-value">Básico Educacional</div>
                </div>
            </div>
        </section>
        
        <!-- Botão de navegação para o simulador -->
        <nav class="btn-group">
            <!-- Leva o usuário ao simulador (outra parte do sistema) -->
            <a href="http://localhost/hack_arena/html/simulador.html" class="btn btn-primary">Acessar Simulador</a>
        </nav>
        
    </main>
</body>
</html>
