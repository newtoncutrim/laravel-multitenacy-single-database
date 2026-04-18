# Documentacao Do Banco

A documentacao navegavel do schema MySQL e gerada com [SchemaSpy](https://schemaspy.org/).

Abra:

```txt
docs/database/index.html
```

Conteudo gerado:

- lista de tabelas;
- colunas, tipos e constraints;
- chaves primarias e estrangeiras;
- ordem sugerida de inserts/deletes;
- paginas individuais por tabela;
- diagramas ER gerais e por tabela.

Para regenerar depois de alterar migrations:

```bash
make migrate
make seed
make db-docs
```

O comando usa o MySQL do Docker Compose. As variaveis podem ser sobrescritas se necessario:

```bash
make db-docs DB_DOCS_DATABASE=laravel DB_DOCS_USER=laravel DB_DOCS_PASSWORD=secret
```

Para remover a documentacao HTML gerada:

```bash
make db-docs-clean
```
