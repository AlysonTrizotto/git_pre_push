# Git Pre Push

Biblioteca PHP para automatizar o hook `pre-push` do Git, executando testes antes do push. Compatível com Laravel, Symfony, Yii e projetos sem framework.

## Instalação

```bash
composer require alysontrizotto/git-pre-push
```

Para instalar apenas em desenvolvimento (recomendado):

```bash
composer require --dev alysontrizotto/git-pre-push
```

Em ambientes de produção/CI com `composer install --no-dev`, este pacote não será instalado e o plugin não será executado (nenhum hook será instalado/alterado nesses ambientes).

## Instalação automática do hook

Este pacote agora é um Composer Plugin. Ao instalar/atualizar via Composer, o hook é criado automaticamente em `.git/hooks/pre-push`.

Se necessário, você pode executar manualmente:

```bash
php vendor/bin/git-pre-push install-hook
```

Observação (Composer >= 2.2): é necessário permitir plugins no `composer.json` do projeto raiz:

```json
{
  "config": {
    "allow-plugins": {
      "alysontrizotto/git-pre-push": true
    }
  }
}
```

## Exemplo de composer.json (projeto consumidor)

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

## Desinstalação

O hook é removido automaticamente quando o pacote é desinstalado via Composer (graças ao plugin). Como alternativa, você pode remover manualmente:

```bash
php vendor/bin/git-pre-push uninstall-hook
```

- Segurança: o hook gerado contém verificações para não bloquear o push se o pacote não estiver mais instalado (por exemplo, quando `vendor/autoload.php` não existe ou a classe `GitPrePush\GitPrePush` não está disponível).

## Solução de problemas

- Se `.env.testing` não existir, o push será abortado
- Dê permissão de execução ao hook: `chmod +x .git/hooks/pre-push`
- Use sempre Git CLI ou Git Bash para garantir execução dos hooks

## Licença

MIT
