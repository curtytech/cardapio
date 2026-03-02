# Bloco de Notas

## Campos


- [ ] Lista 1
- [x] Lista 2

> Citação

Ideias Dashboard 
Lista de Imoveis mais vistos


# Configurar Timezone
config/app.php
'timezone' => 'America/Sao_Paulo',

# Comands

composer require filament/filament:"^3.3" -W

php artisan filament:install --panels

php artisan migrate

php artisan db:seed

### criar link simbólico
php artisan storage:link

### Ngrok
ngrok http 80 --host-header=cardapiovirtual


### Criar um link simbólico (symlink)
cd ~/domains/nome-do-dominio/public_html
ln -s /home/u233139548/domains/cardapiovirtual.space/storage/app/public storage


ln -s //home/u359724568/domains/mcboutique.com.br/storage/app/public storage

### Enter In SHH ignore user erro of windows
ssh -F NUL -p [port] [sshuserip]


