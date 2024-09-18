import sys
import logging
import os

logging.basicConfig(stream=sys.stderr)

# Adicionar o diretório do projeto ao sys.path
sys.path.insert(0, '/home/previsac/domains/previsacontabilidade.com.br/public_html/formulario-nps')

# Definir o ambiente (opcional)
os.environ['FLASK_ENV'] = 'production'

# Ativar o ambiente virtual
activate_this = '/home/previsac/domains/previsacontabilidade.com.br/public_html/formulario-nps/venv/bin/activate_this.py'
with open(activate_this) as file_:
    exec(file_.read(), dict(__file__=activate_this))

# Importar a aplicação Flask
from app import app as application
