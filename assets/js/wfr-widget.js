(function () {
  if (typeof wfrSettings === 'undefined') {
    return;
  }

  var launcher    = document.getElementById('wfr-launcher');
  var popup       = document.getElementById('wfr-popup');
  var overlay     = document.getElementById('wfr-overlay');
  var closeBtn    = document.getElementById('wfr-popup-close');
  var form        = document.getElementById('wfr-lead-form');
  var sendBtn     = document.getElementById('wfr-send-btn');
  var telefoneInput = document.getElementById('wfr-telefone');
  var mensagemInput = document.getElementById('wfr-mensagem');
  var statusMsg   = document.getElementById('wfr-status-msg');

  if (!launcher || !popup) {
    return;
  }

  function abrirPopup() {
    popup.classList.add('wfr-open');
    if (overlay) overlay.classList.add('wfr-open');
  }
  function fecharPopup() {
    popup.classList.remove('wfr-open');
    if (overlay) overlay.classList.remove('wfr-open');
  }
  function togglePopup() {
    if (popup.classList.contains('wfr-open')) {
      fecharPopup();
    } else {
      abrirPopup();
    }
  }

  launcher.addEventListener('click', togglePopup);
  if (closeBtn) closeBtn.addEventListener('click', fecharPopup);
  if (overlay) overlay.addEventListener('click', fecharPopup);

  // Qualquer elemento com a classe "wfr-open-form" (inclusive o botão do
  // shortcode [wfr_button]) abre este mesmo popup. Delegação de eventos:
  // funciona mesmo se o elemento for criado/recriado dinamicamente depois.
  document.addEventListener('click', function (e) {
    var el = e.target.closest('.wfr-open-form');
    if (el) {
      e.preventDefault();
      abrirPopup();
    }
  });

  function setStatus(text, isError) {
    if (!statusMsg) return;
    statusMsg.textContent = text || '';
    statusMsg.classList.toggle('wfr-show', !!text);
    statusMsg.classList.toggle('wfr-error-msg', !!isError);
  }

  function aplicarMascaraTelefone(value) {
    if (!value) return '';
    value = value.replace(/\D/g, '');
    if (value.length > 10) {
      value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (value.length > 6) {
      value = value.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
    } else if (value.length > 2) {
      value = value.replace(/^(\d{2})(\d{0,4})$/, '($1) $2');
    } else if (value.length > 0) {
      value = value.replace(/^(\d{0,2})$/, '($1');
    }
    return value;
  }

  if (telefoneInput) {
    telefoneInput.addEventListener('input', function (e) {
      e.target.value = aplicarMascaraTelefone(e.target.value);
    });
  }

  function validarForm() {
    var valido = true;
    var campos = form.querySelectorAll('input[required], select[required]');
    campos.forEach(function (campo) {
      var wrapper = campo.closest('.wfr-field');
      var ok = campo.value && campo.value.trim() !== '';
      if (campo.type === 'email' && ok) {
        ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campo.value);
      }
      if (campo.id === 'wfr-telefone' && ok) {
        var apenasNumeros = campo.value.replace(/\D/g, '');
        ok = apenasNumeros.length >= 10;
      }
      if (wrapper) wrapper.classList.toggle('wfr-invalid', !ok);
      if (!ok) valido = false;
    });
    return valido;
  }

  form.addEventListener('input', function (e) {
    var wrapper = e.target.closest('.wfr-field');
    if (wrapper) wrapper.classList.remove('wfr-invalid');
  });
  form.addEventListener('change', function (e) {
    var wrapper = e.target.closest('.wfr-field');
    if (wrapper) wrapper.classList.remove('wfr-invalid');
  });

  function montarMensagem(dados) {
    var selectLabel = wfrSettings.selectLabel || 'Selecione';
    return wfrSettings.messageTemplate
      .replace(/\{nome\}/g, dados.nome)
      .replace(/\{email\}/g, dados.email)
      .replace(/\{telefone\}/g, dados.telefone)
      .replace(/\{selecione_label\}/g, selectLabel)
      .replace(/\{selecione\}/g, dados.selecione || '')
      .replace(/\{mensagem\}/g, dados.mensagem || '');
  }

  function enviarLead(dados) {
    var body = new URLSearchParams();
    body.append('action', 'wfr_submit_lead');
    body.append('nonce', wfrSettings.nonce);
    Object.keys(dados).forEach(function (key) {
      body.append(key, dados[key]);
    });

    return fetch(wfrSettings.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    }).catch(function (err) {
      console.warn('[WhatsApp Form Redirect] Falha ao enviar lead:', err);
    });
  }

  function redirecionarWhatsApp(dados) {
    var texto = montarMensagem(dados);
    var url = 'https://wa.me/' + wfrSettings.whatsappNumber + '?text=' + encodeURIComponent(texto);
    window.location.href = url;
  }

  function handleSubmit() {
    if (!validarForm()) {
      setStatus('Preencha todos os campos obrigatórios corretamente.', true);
      return;
    }
    setStatus('Enviando...', false);
    sendBtn.disabled = true;

    var dados = {
      nome: document.getElementById('wfr-nome').value.trim(),
      email: document.getElementById('wfr-email').value.trim(),
      telefone: telefoneInput ? telefoneInput.value.trim() : '',
      mensagem: mensagemInput ? mensagemInput.value.trim() : ''
    };
    var selectEl = document.getElementById('wfr-selecione');
    if (selectEl) dados.selecione = selectEl.value;

    enviarLead(dados).finally(function () {
      redirecionarWhatsApp(dados);
    });
  }

  sendBtn.addEventListener('click', function (e) {
    e.preventDefault();
    handleSubmit();
  });

  if (mensagemInput) {
    mensagemInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        handleSubmit();
      }
    });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    handleSubmit();
  });
})();
