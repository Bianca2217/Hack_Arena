const chat = document.getElementById('chat');
const input = document.getElementById('msg');
const HIST_KEY = 'chat_hackarena';
let contadorMensagensCliente = 0; // Contador para mensagens do cliente

// Carrega histórico ao abrir (mas será limpo ao sair, então pode estar vazio)
window.onload = () => {
  const historico = JSON.parse(localStorage.getItem(HIST_KEY) || '[]');
  historico.forEach(msg => adicionarMensagem(msg.texto, msg.classe, false));
  chat.scrollTop = chat.scrollHeight;
};

// Limpa o histórico quando a janela for fechada
window.addEventListener('beforeunload', () => {
  localStorage.removeItem(HIST_KEY);
  contadorMensagensCliente = 0; // Reseta contador
});

// Adiciona mensagem
function adicionarMensagem(texto, classe, salvar = true) {
  const msg = document.createElement('div');
  msg.className = 'mensagem ' + classe;
  msg.innerText = texto;
  chat.appendChild(msg);
  chat.scrollTop = chat.scrollHeight;

  if (salvar) salvarMensagem(texto, classe);
}

// Salva no histórico
function salvarMensagem(texto, classe) {
  const historico = JSON.parse(localStorage.getItem(HIST_KEY) || '[]');
  historico.push({ texto, classe });
  localStorage.setItem(HIST_KEY, JSON.stringify(historico));
}

// Envia mensagem
function enviarMsg() {
  const texto = input.value.trim();
  if (!texto) return;
  adicionarMensagem(texto, 'cliente');
  input.value = '';
  contadorMensagensCliente++; // Incrementa contador

  if (contadorMensagensCliente === 1) {
    // Primeira mensagem: resposta automática
    setTimeout(() => respostaAutomatica(texto), 700);
  } else if (contadorMensagensCliente === 2) {
    // Segunda mensagem: redireciona para WhatsApp
    setTimeout(() => redirecionarWhatsApp(texto), 700);
  }
  // Para mensagens subsequentes, pode adicionar lógica extra se quiser
}

// Permite enviar com Enter
input.addEventListener('keypress', e => {
  if (e.key === 'Enter') enviarMsg();
});

// Respostas automáticas simples (para a primeira mensagem)
function respostaAutomatica(texto) {
  const respostas = {
    'oi': 'Olá! Bem-vindo(a) à Hack Arena ⚡ Como posso ajudar?',
    'olá': 'Olá! Aqui é o suporte da Hack Arena. Em que posso te ajudar hoje?',
    'preço': 'Temos planos gratuitos e pagos. Quer ver os detalhes?',
    'ajuda': 'Claro! Me diga o que está acontecendo.',
    'default': 'Nosso atendente vai te ajudar em instantes. Pode detalhar melhor sua dúvida?'
  };

  const chave = Object.keys(respostas).find(k => texto.toLowerCase().includes(k));
  adicionarMensagem(respostas[chave] || respostas['default'], 'atendente');
}

// Redireciona para WhatsApp na segunda mensagem
function redirecionarWhatsApp(texto) {
  // Substitua '5511999999999' pelo número real do WhatsApp (formato internacional sem +)
  const numeroWhatsApp = '5545991332863'; // Exemplo: Brasil
  const urlWhatsApp = `https://chat.whatsapp.com/H5t1oR0sKwODaih2tF2mWL${numeroWhatsApp}?text=${encodeURIComponent('Mensagem do chat Hack Arena: ' + texto)}`;
  
  // Primeiro, adiciona a mensagem de redirecionamento
  adicionarMensagem('Redirecionando para o WhatsApp para atendimento personalizado...', 'atendente');
  
  // Depois, com um pequeno delay, abre o WhatsApp
  setTimeout(() => {
    window.open(urlWhatsApp, '_blank'); // Abre em nova aba
  }, 1000); // Delay de 1 segundo para dar tempo de ver a mensagem
}
