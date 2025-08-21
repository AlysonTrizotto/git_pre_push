# Changelog

Todas as alterações notáveis deste projeto serão documentadas aqui.

O projeto segue Semantic Versioning. Versões são derivadas de tags Git.

## [v1.0.0] - 2025-08-21

Primeiro release estável (v1) do plugin Composer para gerenciamento do hook `pre-push`.

### Destaques
- Plugin Composer com instalação/remoção automática do hook `pre-push`.
- Criação automática do arquivo de configuração `git-pre-push.php` na raiz (não sobrescreve se existir).
- Detecção de ambiente no core (`GitPrePush`), incluindo leitura de `--env=...` do `test_command`.
- Bloqueio do push quando `--env=...` é usado e o arquivo `.env.<env>` correspondente não existe.
- Skip automático em produção: com `APP_ENV=production` os testes não são executados e o push é permitido.
- Documentação atualizada (README) e guia de publicação (RELEASING.md).

### Alterações internas
- `TestService` simplificado: apenas executa o comando de teste; validações movidas para o core.

### Notas de migração
- Composer 2.2+ requer permitir plugins em `composer.json` do projeto:
  ```json
  {
    "config": {"allow-plugins": {"alysontrizotto/git-pre-push": true}}
  }
  ```
- Para validar ambiente de testes, configure no `git-pre-push.php` um comando com `--env=testing` (ou outro) e garanta a existência de `.env.testing` correspondente.

[v1.0.0]: https://github.com/alysontrizotto/git-pre-push/releases/tag/v1.0.0
