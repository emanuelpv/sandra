# SANDRA: SISTEMA DE GESTÃO OPENSOURCE PARA HOSPITAIS, CLÍNICAS E CONSULTÓRIOS
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/emanuelpv/sandra/pulls)
https://img.shields.io/github/license/emanuelpv/sandra


## O que é o sistema SANDRA?

O sistema de Gestão Hospitalar e Clínicas SANDRA tem o objetivo de fornecer **GRATUITAMENTE** funcionalidade para o gerenciamento de pequenos hospitais e postos de saúde.


### Funcionalidades

- Agendamento Eletrônico (ambulatorial e exames)
- Painel de Chamadas
- Prontuário Eletrônico (PEP)
- Prescrições de medicamentos
- Controle Antimicrobiano
- Internação de pacientes
- Gestão de leitos
- Gestão de Estoque de Medicamentos
- Tabela CBHPM
- Dispensação de medicamentos
- Gestão de compras
- Faturamento de contas médicas
- Gestão de contas (Pacientes e colaboradores)
- Integração com LDAP
- Integração com gateway de SMS e E-MAIL
- Dashboards


### Tecnologias Opensource utilizadas
- [PHP >=7.4](https://www.php.net/)
- [Codeigniter 4](https://github.com/codeigniter4/CodeIgniter4/tree/develop)
- [MySql](https://github.com/mysql)
- [Bootstrap](https://getbootstrap.com/)
- [AdminLTE](https://adminlte.io/)
- [Google API](https://github.com/googleapis)
- [DataTable](https://github.com/DataTables/DataTables)
- Dentre muitas outras API's Opensource.

### Documentação

Esta [Documentação](https://github.com/emanuelpv/sandra) é a principal referencia sobre o produto.


### Licenciamento
Esta [GNU GPL](https://github.com/emanuelpv/sandra/blob/Sandra/LICENSE).



## Contribuições

Nós **estamos** aceitando a contribuição  de comunidade opensource!

Neste momento, não procuramos contribuições fora do escopo do projeto, apenas aquelas que seriam consideradas parte da evolução do sistema!


## Requisitos do servidor

PHP 7.4 ou superior é requerido, estando as seguintes extenções ativadas:


- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) caso você planeje utilizar a biblioteca HTTP\CURLRequest
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Também, garanta que as extensões a seguir estejam ativadas no seu PHP:

- json (Ativada por padrão - Não desative-a!!)
- xml (Ativada por padrão - Não desative-a!!)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)


## Instalação do servidor

- 1) Copiar o fonte do sistema no diretório da aplicação
- 2) Restaurar o banco de dados constantes no arquivo [sandraGithub] (https://github.com/emanuelpv/sandra/blob/Sandra/sandraGithub.sql)
- 3) Configurar os arquivos App/Config/App.php e App/Config/Database.php, para respectivamente os parametros de URL e Banco de dados.
- 4) Agora a mágica acontece. 

- **Acesso ao painel de administração (Login: admin |Senha: admin)**
- **Acesso ao painel paciente (Login: paciente |Senha: paciente)**