
        // Simulação de banco de dados
        const database = {
            users: [
                {id: 1, username: 'admin', password: 'admin123', role: 'administrator'},
                {id: 2, username: 'user1', password: 'password1', role: 'user'},
                {id: 3, username: 'guest', password: 'guest123', role: 'guest'}
            ],
            secrets: [
                {id: 1, secret_data: 'Dados confidenciais do sistema', access_level: 'admin'},
                {id: 2, secret_data: 'Informações de usuários', access_level: 'user'}
            ]
        };

        function showTab(tabName) {
            // Esconder todas as abas
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Mostrar aba selecionada
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function logSQL(message, isVulnerable = true) {
            const logArea = document.getElementById('sql-logs');
            const timestamp = new Date().toLocaleTimeString();
            const logType = isVulnerable ? '[VULNERÁVEL]' : '[SEGURO]';
            logArea.innerHTML += `[${timestamp}] ${logType} ${message}\n`;
            logArea.scrollTop = logArea.scrollHeight;
        }

        function vulnerableLogin(event) {
            event.preventDefault();
            
            const username = document.getElementById('vuln-user').value;
            const password = document.getElementById('vuln-pass').value;
            
            // Simular SQL vulnerável (SEM sanitização)
            const query = `SELECT * FROM users WHERE username = '${username}' AND password = '${password}'`;
            
            logSQL(`Query executada: ${query}`);
            
            const steps = document.getElementById('vuln-steps');
            steps.innerHTML = '<h4>🔍 Análise do Ataque:</h4>';
            
            // Detectar tentativas de SQL injection
            if (username.includes("'") || password.includes("'")) {
                steps.innerHTML += '<p>✅ SQL Injection detectado!</p>';
                
                if (username.includes("OR") || password.includes("OR")) {
                    steps.innerHTML += '<p>✅ Bypass de autenticação com OR detectado</p>';
                    logSQL('ALERTA: Tentativa de bypass com OR condition');
                    
                    // Simular sucesso do ataque - mostrar como o navegador renderizaria
                    document.getElementById('login-results').innerHTML = `
                        <div class="simulated-page">
                            <h2>🎉 Bem-vindo ao Painel Administrativo!</h2>
                            <p>Você está logado como: <strong>admin</strong></p>
                            <p>Acesso total concedido via SQL Injection.</p>
                            <h3>Lista de Usuários do Sistema:</h3>
                            <ul class="user-list">
                                ${database.users.map(user => `<li><strong>ID:</strong> ${user.id} | <strong>Usuário:</strong> ${user.username} | <strong>Senha:</strong> ${user.password} | <strong>Função:</strong> ${user.role}</li>`).join('')}
                            </ul>
                            <p><em>Esta é uma simulação de como uma página real vulnerável poderia exibir dados após um ataque bem-sucedido.</em></p>
                        </div>
                    `;
                }
                
                if (username.includes("UNION") || password.includes("UNION")) {
                    steps.innerHTML += '<p>✅ UNION SELECT attack detectado</p>';
                    logSQL('ALERTA: Tentativa de UNION SELECT para extrair dados');
                    
                    document.getElementById('login-results').innerHTML = `
                        <div class="simulated-page">
                            <h2>📊 Dados Extraídos via UNION</h2>
                            <p>Tabela 'users' comprometida:</p>
                            <ul class="user-list">
                                ${database.users.map(user => `<li><strong>ID:</strong> ${user.id} | <strong>Usuário:</strong> ${user.username} | <strong>Senha:</strong> ${user.password}</li>`).join('')}
                            </ul>
                            <p><em>Simulação de uma página que exibe dados injetados.</em></p>
                        </div>
                    `;
                }
                
                if (username.includes("--") || password.includes("--")) {
                    steps.innerHTML += '<p>✅ Comment injection (--) detectado</p>';
                    logSQL('ALERTA: Tentativa de comment injection');
                    
                    // Para comment injection, simular login como admin sem senha
                    document.getElementById('login-results').innerHTML = `
                        <div class="simulated-page">
                            <h2>🔓 Acesso Bypassado</h2>
                            <p>Logado como admin (senha ignorada via comment injection).</p>
                            <p>Dados do sistema:</p>
                            <ul class="user-list">
                                <li><strong>Admin Data:</strong> ${database.secrets[0].secret_data}</li>
                            </ul>
                        </div>
                    `;
                }
                
            } else {
                // Login normal
                const user = database.users.find(u => u.username === username && u.password === password);
                if (user) {
                    steps.innerHTML += '<p>✅ Login válido realizado</p>';
                    document.getElementById('login-results').innerHTML = `
                        <div class="simulated-page">
                            <h2>✅ Login Bem-Sucedido</h2>
                            <p>Bem-vindo, ${user.username}!</p>
                            <p>Sua função: ${user.role}</p>
                        </div>
                    `;
                    logSQL(`Login bem-sucedido: ${user.username}`);
                } else {
                    steps.innerHTML += '<p>❌ Credenciais inválidas</p>';
                    document.getElementById('login-results').innerHTML = '<h3>❌ Usuário ou senha incorretos</h3>';
                    logSQL('Login falhado: credenciais inválidas');
                }
            }
        }

        function secureLogin(event) {
            event.preventDefault();
            
            const username = document.getElementById('secure-user').value;
            const password = document.getElementById('secure-pass').value;
            
            // Simular sanitização (escape de caracteres especiais)
            const sanitizedUser = username.replace(/'/g, "\\'").replace(/"/g, '\\"');
            const sanitizedPass = password.replace(/'/g, "\\'").replace(/"/g, '\\"');
            
            logSQL(`Query preparada (sanitizada): SELECT * FROM users WHERE username = ? AND password = ?`, false);
            logSQL(`Parâmetros: ['${sanitizedUser}', '${sanitizedPass}']`, false);
            
            const steps = document.getElementById('secure-steps');
            steps.innerHTML = '<h4>🛡️ Proteções Aplicadas:</h4>';
            steps.innerHTML += '<p>✅ Prepared statements utilizadas</p>';
            steps.innerHTML += '<p>✅ Caracteres especiais escapados</p>';
            steps.innerHTML += '<p>✅ Validação de entrada aplicada</p>';
            
            // Busca segura (apenas match exato)
            const user = database.users.find(u => u.username === username && u.password === password);
            
            if (user) {
                document.getElementById('login-results').innerHTML = `
                    <div class="simulated-page">
                        <h2>✅ Login Seguro Bem-Sucedido</h2>
                        <p>Bem-vindo, ${user.username}!</p>
                        <p>Sua função: ${user.role}</p>
                    </div>
                `;
                logSQL(`Login seguro bem-sucedido: ${user.username}`, false);
            } else {
                document.getElementById('login-results').innerHTML = '<h3>❌ Credenciais inválidas</h3>';
                logSQL('Login seguro falhado: credenciais inválidas', false);
            }
        }

        // Inicializar logs
        logSQL('Sistema de laboratório iniciado');
        logSQL('Banco de dados simulado carregado com ' + database.users.length + ' usuários');
