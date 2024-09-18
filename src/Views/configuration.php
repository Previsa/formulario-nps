<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Configuração de Disparo de Emails</title>
</head>
<body style="background-color: rgb(99, 100, 103);">
    <div class="mx-auto" style="width: 500px;">
        <div class="header-top d-flex justify-content-between alinhando-items-center">
            <div align="center" class="title mt-4 d-flex" style="color: #FFF; font-size: 22px;">Configuração de Disparo de Emails</div>
        </div>
        <form action="/formulario-nps/schedule_emails" method="post" id="form1" name="form1" class="mt-4 ml-4 mr-4" style="color: #fff;">
            <div class="form-group">
                <label for="start_date">Data e Hora de Início:</label>
                <input type="datetime-local" class="form-control" name="start_date" id="start_date" required onchange="updateStartDate()">
                <small id="startDateText" class="form-text text-muted">Data e Hora de Início selecionada: Nenhuma</small>
            </div>
            <!-- Removido o campo de Data e Hora de Término -->
            <button type="submit" class="btn btn-primary" name="action" value="Agendar Disparos">Agendar Disparos</button>
            <button type="submit" class="btn btn-danger" name="action" value="Interromper Disparos">Interromper Disparos</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUIbX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jUot6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <script>
        function updateStartDate() {
            const startDate = document.getElementById('start_date').value;
            document.getElementById('startDateText').innerText = 'Data e Hora de Início selecionada: ' + startDate;
        }
    </script>
</body>
</html>
