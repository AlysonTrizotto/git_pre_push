# Git Pre Push

Biblioteca PHP (Composer Plugin) para automatizar o hook `pre-push` do Git, executando testes antes do push. Compatível com Laravel, (ainda em testes PHPUnit puro e outros frameworks PHP).

## Principais recursos
- Instala automaticamente o hook `pre-push` ao instalar/atualizar o pacote.
- Cria automaticamente o arquivo de configuração `git-pre-push.php` na raiz do projeto (não sobrescreve se já existir).
- Executa o comando de testes configurado antes do push e bloqueia o push em caso de falhas.
- Respeita `--env=...` no comando de testes e exige o arquivo `.env.<env>` correspondente.
- Em `APP_ENV=production`, pula os testes e permite o push.

## Requisitos
- PHP CLI disponível (`php -v`).
- Composer >= 2.2 (para permitir plugins).
- Repositório Git inicializado (para instalar o hook em `.git/hooks/`).

## Instalação

Permitir o plugin no `composer.json` do projeto (Composer >= 2.2):

```json
{
  "config": {
    "allow-plugins": {
      "alysontrizotto/git-pre-push": true
    }
  }
}
```

Instalar no projeto (recomendado como dev):

```bash
composer require --dev alysontrizotto/git-pre-push
```


Ao instalar/atualizar, o hook `.git/hooks/pre-push` será criado automaticamente e o arquivo `git-pre-push.php` será gerado na raiz se não existir.

## Configuração

O arquivo `git-pre-push.php` contém a configuração do comando de testes:

```php
<?php
return [
  // Exemplo Laravel
  'test_command' => 'php artisan test --env=testing',

  // Alternativa: PHPUnit puro
  // 'test_command' => 'vendor/bin/phpunit',
];
```

Comportamento relacionado a ambientes:
- Se `'test_command'` tiver `--env=testing`, o hook exige a existência de `.env.testing`.
- Se `APP_ENV=production` (variável de ambiente ou `.env`), o hook não roda testes e permite o push.

## Desinstalação

Ao remover o pacote via Composer, o hook é removido automaticamente. Opcionalmente:

```bash
php vendor/bin/git-pre-push uninstall-hook
```

Segurança: o hook gerado possui marcação e só é removido se tiver sido criado por este pacote.

## Solução de problemas
- “Arquivo de ambiente esperado não encontrado: .env.testing”: crie o arquivo ou ajuste o `--env` no `test_command`.
- Permissão do hook (Linux/Mac): `chmod +x .git/hooks/pre-push`.
- Garanta que o Git esteja executando hooks (use Git CLI/Git Bash).

---

## Exemplo de composer.json do consumidor

```json
{
  "require-dev": {
    "alysontrizotto/git-pre-push": "^1.0"
  },
  "config": {
    "allow-plugins": {
      "alysontrizotto/git-pre-push": true
    }
  }
}
```

## Licença

MIT
