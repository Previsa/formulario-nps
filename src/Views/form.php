<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body style="background: url('https://raw.githubusercontent.com/Previsa/images-novero/main/background-formulario.jpg') no-repeat center 0vh; background-size: cover; color: #fff;">
  <div class="mx-auto" style="width: 500px; padding: 20px; border-radius: 10px; margin-top: 100px;">
    
    <!-- Cabeçalho com logo e título -->
    <div class="text-center mb-4" style="margin-top: -90px;">
      <div class="d-flex justify-content-center align-items-center">
        <h1 style="color: #fffefa; text-transform: uppercase; font-size: 1.5rem; margin-right: 40px;">PESQUISA DE SATISFAÇÃO</h1>
        <img src="https://raw.githubusercontent.com/Previsa/images-novero/main/Logotipo_Previsa_Branca.png" alt="Logo Previsa" style="width: 270px; height: auto;">
      </div>
      <p style="color: #fffefa; margin-top: 10px;">
        Sua opinião sincera é fundamental para que possamos oferecer a melhor experiência para nossos clientes. Agradecemos sua participação!
      </p>
    </div>

    <form action="/formulario-nps/submit" method="post" id="form1" name="form1" class="mt-4">
      <!-- Campo oculto para enviar o UUID -->
      <input type="hidden" name="uuid" value="<?php echo htmlspecialchars($_GET['uuid'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <div class="form-group">
        <label for="escala1">Em uma escala de 0 a 10, o quanto você indicaria nossa empresa para um amigo?*</label>
        <select class="form-control" name="escala1" id="escala1" required>
          <option value="">Selecione</option>
          <?php for ($i = 0; $i <= 10; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="escala2">Como você avalia o atendimento da Área Contábil?*</label>
        <select class="form-control" name="escala2" id="escala2" required>
          <option value="">Selecione</option>
          <?php for ($i = 0; $i <= 10; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="escala3">Como você avalia o atendimento da Área Fiscal?*</label>
        <select class="form-control" name="escala3" id="escala3" required>
          <option value="">Selecione</option>
          <?php for ($i = 0; $i <= 10; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="escala4">Como você avalia o atendimento da Área Pessoal?*</label>
        <select class="form-control" name="escala4" id="escala4" required>
          <option value="">Selecione</option>
          <?php for ($i = 0; $i <= 10; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="escala5">Como você avalia o atendimento de seu Gerente de Sucesso?*</label>
        <select class="form-control" name="escala5" id="escala5" required>
          <option value="">Selecione</option>
          <?php for ($i = 0; $i <= 10; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="motivo">Poderia descrever o motivo para suas notas?</label>
        <textarea class="form-control" name="motivo" id="motivo" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzm7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUIbX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jUot6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</body>

</html>
