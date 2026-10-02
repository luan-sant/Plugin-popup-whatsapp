=== WhatsApp Form Redirect ===
Contributors: luan-sant
Tags: whatsapp, lead generation, contact form, webhook, floating button
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Botão flutuante de WhatsApp com formulário de qualificação de leads e redirecionamento automático com mensagem pré-preenchida.

== Description ==

O **WhatsApp Form Redirect** adiciona um botão flutuante de WhatsApp em todo o seu site. Ao clicar, o visitante vê um formulário curto (nome, e-mail, telefone e um campo de seleção opcional). Depois de enviado:

1. Os dados podem ser opcionalmente repassados para um webhook (CRM, planilha, Zapier, Make, RD Station, HubSpot, etc.)
2. O visitante é redirecionado automaticamente para o WhatsApp, com uma mensagem já preenchida com os dados informados

**Principais recursos:**

* Botão flutuante responsivo, com posição configurável (esquerda ou direita)
* Cores personalizáveis (botão e cabeçalho do formulário)
* Campo de seleção (dropdown) com opções customizáveis
* Modelo de mensagem totalmente editável, com placeholders
* Integração via Webhook (genérica, compatível com qualquer CRM ou automação)
* Shortcode `[wfr_button]` para adicionar botões extras em qualquer lugar do site que abrem o mesmo formulário
* Sem dependências externas — JavaScript puro, sem jQuery no front-end

== Installation ==

1. Envie a pasta `whatsapp-form-redirect` para `/wp-content/plugins/`
2. Ative o plugin no menu "Plugins" do WordPress
3. Vá em **Ajustes > WhatsApp Form** e configure o número do WhatsApp (obrigatório)
4. Personalize textos, cores e o campo de seleção conforme desejar
5. (Opcional) Configure a URL de Webhook para capturar os leads em um CRM externo

== Frequently Asked Questions ==

= Preciso de algum plugin adicional? =
Não. O plugin funciona de forma independente.

= Como uso o botão do shortcode? =
Insira `[wfr_button text="Fale conosco"]` em qualquer página, post ou widget de texto.

= Como integro com meu CRM? =
Preencha o campo "URL do Webhook" nas configurações. O plugin enviará um POST em JSON com os dados do lead para essa URL antes de redirecionar o usuário para o WhatsApp.

== Changelog ==

= 1.0.0 =
* Versão inicial.
