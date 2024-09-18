document.addEventListener("DOMContentLoaded", function () {
  // Selecionar o formulário
  const form = document.getElementById("form1");

  // Adicionar evento de submissão do formulário
  form.addEventListener("submit", function (event) {
    // Prevenir submissão padrão do formulário
    event.preventDefault();

    // Selecionar os campos do formulário
    const escala1 = document.getElementById("escala1").value.trim();
    const escala2 = document.getElementById("escala2").value.trim();
    const escala3 = document.getElementById("escala3").value.trim();
    const escala4 = document.getElementById("escala4").value.trim();
    const escala5 = document.getElementById("escala5").value.trim();
    const motivo = document.getElementById("motivo").value.trim();

    // Validação simples
    if (!escala1 || !escala2 || !escala3 || !escala4 || !escala5) {
      alert("Por favor, preencha todas as escalas obrigatórias.");
      return;
    }

    if (isNaN(escala1) || isNaN(escala2) || isNaN(escala3) || isNaN(escala4) || isNaN(escala5)) {
      alert("Por favor, insira valores numéricos válidos para as escalas.");
      return;
    }

    if (escala1 < 0 || escala1 > 10 || escala2 < 0 || escala2 > 10 || escala3 < 0 || escala3 > 10 || escala4 < 0 || escala4 > 10 || escala5 < 0 || escala5 > 10) {
      alert("Por favor, insira valores de 0 a 10 para as escalas.");
      return;
    }

    // Submeter o formulário se todos os campos são válidos
    form.submit();
  });
});
