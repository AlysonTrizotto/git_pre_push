# Git Pre Push

Biblioteca PHP para automatizar o hook `pre-push` do Git, executando testes antes do push. Compatível com Laravel, Symfony, Yii e projetos sem framework.

## Instalação

```bash
composer require alysontrizotto/git-pre-push
```

## Instalação automática do hook

Após instalar, o hook será criado automaticamente em `.git/hooks/pre-push`.
Se necessário, execute manualmente:

```bash
php vendor/bin/git-pre-push install-hook
```

## Pré-requisitos

- PHP CLI disponível (`php -v`)
- Dependências instaladas (`composer install`)
- Arquivo `.env.testing` na raiz do projeto

## Funcionamento

- O hook só executa em ambiente de desenvolvimento (não roda em produção)
- Antes do push, executa os testes definidos na configuração
- Se os testes falharem, o push é bloqueado

## Configuração

Crie um arquivo `git-pre-push.php` na raiz do projeto para customizar o comando de teste:

```php
return [
  'test_command' => 'php artisan test', // Laravel
  // 'test_command' => 'vendor/bin/phpunit', // PHPUnit
];
```

## Uso avançado

Você pode estender a biblioteca usando listeners, eventos e services para customizar o fluxo do hook.

## Solução de problemas

- Se `.env.testing` não existir, o push será abortado
- Dê permissão de execução ao hook: `chmod +x .git/hooks/pre-push`
- Use sempre Git CLI ou Git Bash para garantir execução dos hooks

## Licença

MIT
