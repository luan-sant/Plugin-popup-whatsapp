# WhatsApp Form Redirect

Plugin WordPress que adiciona um botão flutuante de WhatsApp com formulário de qualificação de leads. Ao enviar, os dados podem ser opcionalmente repassados para um webhook (CRM, planilha, Zapier, Make, RD Station, HubSpot etc.) e o visitante é redirecionado automaticamente para o WhatsApp com uma mensagem pré-preenchida.

## ✨ Funcionalidades

- 🟢 Botão flutuante responsivo (posição configurável: esquerda ou direita)
- 🎨 Cores personalizáveis via painel administrativo (color picker nativo do WP)
- 📝 Formulário com nome, e-mail, telefone e um campo de seleção opcional (opções customizáveis)
- 💬 Modelo de mensagem editável, com placeholders (`{nome}`, `{email}`, `{telefone}`, `{selecione}`, `{mensagem}`)
- 🔗 Integração via Webhook genérica — funciona com qualquer CRM ou automação que aceite POST em JSON
- 🧩 Shortcode `[wfr_button]` para abrir o mesmo formulário a partir de qualquer botão/link do site
- ⚡ Zero dependências de front-end — JavaScript puro (vanilla JS), sem jQuery
- 🔒 Segue os padrões de segurança do WordPress (nonces, sanitização e escaping em todos os campos)

## 📦 Instalação

1. Baixe ou clone este repositório
2. Copie a pasta `whatsapp-form-redirect` para `wp-content/plugins/` do seu WordPress
3. Ative o plugin em **Plugins > Plugins Instalados**
4. Vá em **Ajustes > WhatsApp Form** e configure o número do WhatsApp
5. (Opcional) Configure a URL de Webhook para capturar os leads em um CRM externo

## 🖼️ Como usar

- O botão flutuante aparece automaticamente em todas as páginas do site assim que um número de WhatsApp é configurado
- Para adicionar um botão extra em qualquer lugar (um banner, uma seção específica, etc.), use o shortcode:
  ```
  [wfr_button text="Fale conosco agora"]
  ```
  Ou, via HTML/tema, adicione a classe `wfr-open-form` a qualquer elemento clicável:
  ```html
  <button class="wfr-open-form">Fale conosco</button>
  ```

## 🏗️ Estrutura do projeto

```
whatsapp-form-redirect/
├── whatsapp-form-redirect.php       # Arquivo principal do plugin
├── includes/
│   ├── class-wfr-settings.php       # Página de configurações (admin)
│   ├── class-wfr-frontend.php       # Renderização do widget + handler AJAX
│   └── view-settings-page.php       # Template HTML das configurações
├── assets/
│   ├── css/wfr-widget.css
│   └── js/
│       ├── wfr-widget.js            # Lógica do front-end (popup, validação, envio)
│       └── wfr-admin.js             # Inicializa o color picker no admin
└── readme.txt                       # Padrão WordPress.org
```

## 🔌 Integração via Webhook

Quando configurado, o plugin envia um `POST` com `Content-Type: application/json` para a URL definida, com o seguinte corpo:

```json
{
  "nome": "Nome do Lead",
  "email": "lead@email.com",
  "telefone": "(11) 99999-9999",
  "selecione": "Opção escolhida",
  "mensagem": "Mensagem adicional",
  "origem": "https://seusite.com/",
  "data": "2026-01-01 12:00:00"
}
```

Se uma **chave secreta** for configurada, ela é enviada no header `X-WFR-Secret`, permitindo que seu endpoint valide a origem da requisição.

## 🛠️ Tecnologias

- PHP (WordPress Plugin API, Settings API, AJAX API)
- JavaScript (vanilla, sem dependências)
- CSS (variáveis CSS para theming dinâmico)

## 📄 Licença

GPLv2 ou posterior — compatível com o padrão de licenciamento de plugins WordPress.

---

Desenvolvido como parte do portfólio de [Luan Santos](https://github.com/luan-sant).
