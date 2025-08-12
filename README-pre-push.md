# README — Preparação e Validações para usar o Git Hook `pre-push`

Este documento descreve como preparar o ambiente, instalar e validar o hook `pre-push` que executa os testes do Laravel antes de cada `git push`, pulando testes em produção.

## Pré‑requisitos

- [ ] **PHP no PATH (CLI)**: `php -v`
- [ ] **Composer deps instaladas**: `composer install`
- [ ] **Laravel na raiz** (arquivo `artisan` presente)
- [ ] **Serviços usados nos testes disponíveis** (ex.: DB/Redis) ou configure SQLite para testes
- [ ] **Git Bash (Windows)** ou shell POSIX para executar hooks `.sh`
- [ ] **Final de linha LF (Unix)** no arquivo `.git/hooks/pre-push`
- [ ] **Permissão de execução (Linux/macOS)**: `chmod +x .git/hooks/pre-push`

## O que o hook faz

- Executa a partir da **raiz do repositório**.
- Detecta o ambiente via `APP_ENV` (variável de ambiente) ou `.env` (`APP_ENV=`).
- **Se `APP_ENV=production`**: não roda testes e permite o push.
- Caso contrário, roda `php artisan test` e bloqueia o push se os testes falharem.

Trecho de detecção de ambiente:
```sh
ENVIRONMENT="${APP_ENV:-}"
if [ -z "$ENVIRONMENT" ] && [ -f .env ]; then
  ENVIRONMENT="$(grep -m1 -E '^APP_ENV=' .env | sed -E 's/^APP_ENV=//; s/["\r]//g')"
fi
ENVIRONMENT_LC="$(printf '%s' "$ENVIRONMENT" | tr '[:upper:]' '[:lower:]')"

if [ "$ENVIRONMENT_LC" = "production" ]; then
  echo "APP_ENV=production detectado. Pulando testes e permitindo push."
  exit 0
fi
```

## Instalação

1. Copie o script para o caminho de hooks (se ainda não estiver):
   - Caminho: `.git/hooks/pre-push`
2. Garanta LF e permissões:
   - Windows (Git Bash): configure o editor para salvar com **LF**
   - Linux/macOS: `chmod +x .git/hooks/pre-push`

## Validações antes do uso

- **Validar shell**: usar Git Bash (Windows) ou terminal (Linux/macOS)
- **Validar PHP**: `php -v` deve funcionar
- **Validar artisan**: na raiz do repo, `test -f artisan && echo ok`
- **Validar serviços**: suba DB/Redis ou configure SQLite para teste
- **Validar APP_ENV**:
  - `.env` contém `APP_ENV=local|staging|production`
  - Para simular produção temporariamente: `APP_ENV=production sh .git/hooks/pre-push` (deve pular testes)

## Como testar o hook

- **Rodar manualmente** na raiz do repo:
  - `sh .git/hooks/pre-push`
- **Simular sucesso**: todos os testes verdes → mensagem “All tests passed. Proceeding with push.”
- **Simular falha**: quebre um teste; deve bloquear com “Tests failed! Aborting push.”
- **Simular produção**:
  - Via variável: `APP_ENV=production sh .git/hooks/pre-push`
  - Via `.env`: defina `APP_ENV=production` e rode o hook

## Solução de problemas

- **^M no shebang/erro no sh (Windows)**: arquivo com CRLF. Converta para **LF**.
- **Permissão negada (Linux/macOS)**: `chmod +x .git/hooks/pre-push`
- **PHP não encontrado**: ajuste o **PATH** ou instale PHP CLI
- **Banco indisponível**: use SQLite em `phpunit.xml`/`.env.testing` ou suba os serviços
- **GUI de Git ignorando hooks**: use Git CLI/Git Bash ou configure a GUI para respeitar hooks

## Notas

- Hooks em `.git/hooks/` não são versionados por padrão. Para compartilhar no time, considere Lefthook/Overcommit/CaptainHook ou um script de bootstrap que copie o hook.
- Em CI, rode testes no pipeline em vez de depender do hook local.
