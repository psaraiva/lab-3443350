# Lab 34483350

## Objetivo
Testar a arquitetura fornecida no curso [Arquitetura Hexagonal/Ports and Adapters NA PRÁTICA](https://www.udemy.com/course/arquitetura-hexagonal-ou-ports-and-adapters-na-pratica)

Para isso foi desenvolvido o conceito em PHP.

## Arquitetura
A camada mais interna contém a definição de Entidades de Domínio e Casos de Usos que por sua vez usa de interfaces para gerenciar a abstração desses dados e executar a regra de negócio. Dessa forma é possível criar diversas implementações para essas interfaces sem quebrar o contrato existente.

Embora esse lab seja baseado em um cenário muito simples, já provou como é flexivel essa arquitetura permitindo que a camada externa possa ser alterada sem comprometer a interna.

## Aplicação núcleo
Seguindo o que foi explicado no curso, a pasta Application é o núcleo da aplicação e não tem vinculo com as camadas exteriores, segue um contrato rígido em seus UserCases.

## Usuários
Nesse modelo simples, existe somente uma entidade: Usuários. Representando simbolicamente um modelo de algum sistema legado tem apenas alguns campos:
- id
- name
- cpf
- isValidCpf

Não importa qual é o input/output de usuários, dentro de Aplicação (Application) essa entidade é reconhecida por esses atributos, isso fica claro com uso de repositores.

## Destaque para os repositores
Nesse Lab foram usados três _repositories_ diferentes, dois deles altamente NÃO recomentado para o uso de gerenciar dados.

Porém, com o usos destes, uma ótima oportunidade de entender o desafio de sua implementação e impacto na applicação.

## SQLite
O [SQLite](https://www.sqlite.org/) é um banco de dados. Nesse cenário o ID do registro é gerenciado pelo banco de dados e há possibilidade de gerenciar o registro único de CPF pelo próprio banco de dados usando a instrução `UNIQUE`.

## CSV
O [CSV](https://pt.wikipedia.org/wiki/Comma-separated_values) é um tipo de arquivo que segue a RFC 4180. Não é usado como um gerenciador de dados. Normalmente é usado para transferir certa quantidade de registros ou visualizador de dados para excel por exemplo.

Exatamento por isso que está sendo usado com um gerenciador de dados, para demonstrar o custo de usar o CSV para isso e o impacto na arquitetura escolhida, porta/adaptadores.

## JSON
O [JSON](https://www.json.org/json-en.html) é um formato leve para transferencia de dados, muito usado em resposta de API. Novamente nesse caso não se aproxima em nada com um gerenciador de dados.

Nesse exemplo cada registros de usuários representa um arquivo JSOn em uma pasta. Assim como CSV, foi escolhido esse formato para poder transmitir toda a dificuldade de se usar essa tecnologia para esse fim e analisar o impacto na arquitetura escolhida, porta/adaptadores.

## Tudo começa aqui...
Usando o docker compose, execute o comando abaixo:
- `docker-compose up -d`

Crie a tabela de usuários no SQLite.
- `docker exec -it lab-34483350 sqlite3 ./data/sqlite/data.db`
- `.read migration/sqlite/create_table_users_20241125102000.sql`
- `.schema`
- `.exit`
- `docker exec -it lab-34483350 composer dump-autoload`

## Modo de usar
O parâmetro `--db-engine` aceita somente um único valor: `sqlite`, `csv` ou `json`.

```bash
docker exec -it lab-34483350 php main.php --db-engine=??? --entity=user --action=insert --data='{"name":"Abacate", "cpf":"123.456.789-00"}'
docker exec -it lab-34483350 php main.php --db-engine=??? --entity=user --action=find-by-id --data='{"id":"1"}'
docker exec -it lab-34483350 php main.php --db-engine=??? --entity=user --action=update --data='{"id":"1", "name":"Avocado", "cpf":"123.456.000-00"}'
docker exec -it lab-34483350 php main.php --db-engine=??? --entity=user --action=delete-by-id --data='{"id":"1"}'
```
