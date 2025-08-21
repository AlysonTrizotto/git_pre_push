# Release Notes — v1.0.0

Data: 2025-08-21

Primeiro release estável do `alysontrizotto/git-pre-push` — um Composer Plugin para instalar e gerenciar automaticamente o hook `pre-push` do Git, executando testes antes do push.

## Destaques
- Instala/Remove automaticamente o hook `.git/hooks/pre-push` via Composer (plugin).
- Gera automaticamente `git-pre-push.php` na raiz do projeto, sem sobrescrever arquivos existentes.
- Executa o comando de testes configurado antes do push e bloqueia o push em caso de falhas.
- Respeita `--env=...` no `test_command` e exige o arquivo `.env.<env>` correspondente.
- Quando `APP_ENV=production`, os testes são ignorados e o push é permitido.

## Instalação (consumidor)

No `composer.json` (Composer >= 2.2):
```json
{
  "config": {
    "allow-plugins": {
      "alysontrizotto/git-pre-push": true
    }
  }
}
```

```bash
composer require --dev alysontrizotto/git-pre-push
```


## Configuração
Crie/ajuste `git-pre-push.php` na raiz do projeto (o plugin cria um stub automaticamente):
```php
<?php
return [
  'test_command' => 'php artisan test --env=testing',
  // 'test_command' => 'vendor/bin/phpunit',
];
```
Regras de ambiente:
- Se usar `--env=testing`, o arquivo `.env.testing` deve existir — caso contrário, o push é bloqueado.
- Em produção (`APP_ENV=production`), o hook permite o push sem executar testes.

## Quebra de compatibilidade
- Nenhuma.

## Agradecimentos
- Obrigado a todos que testaram e ajudaram a validar os cenários de ambiente e instalação via Composer.

Links úteis:
- README: ./README.md
- Changelog: ./CHANGELOG.md
