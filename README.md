# Descrição inicial do sistema

## Atores

Os atores que interagem com o sistema são:

• Dono: regista e procura um ou mais objetos que perdeu.
• Polícia: regista objetos que alguém achou e entregou.
• Licitante: licita um objeto não reclamado em leilão.
• Administradores do sistema: Têm acesso a todas as estatísticas e informações do sistema;

## Requisitos funcionais implementados:

### Contas e sessões de utilizadores

• RF-01: Registar, editar, e remover uma conta de dono ou licitante.
• RF-02: Registar, editar, e remover um posto de polícia.
• RF-03: Registar, editar, e remover uma conta de utilizador polícia.
• RF-04: Iniciar e terminar uma sessão autenticada.
• RF-05: Desativar e reativar uma conta de utilizador.

### Objetos perdidos e achados

• RF-06: Registar, editar, e remover um objeto perdido.
• RF-07: Ver histórico dos seus objetos perdidos.
• RF-08: Registar, editar, e remover um objeto achado.
• RF-09: Ver histórico dos seus objetos achados.
• RF-10: Pesquisar objetos perdidos pela descrição.
• RF-11: Pesquisar objetos perdidos pelos campos específicos de categorias.
• RF-12: Pesquisar objetos achados correspondentes a objetos perdidos.
• RF-13: Comparar um objeto perdido com um achado, revelando as diferenças. 
• RF-14: Registar, editar, e remover possível dono de objeto achado.
• RF-15: Notificar dono que um seu objeto perdido pode ter sido achado. 
• RF-16: Registar entrega de objeto achado ao dono.
• RF-17: Ver relatório com estatísticas e mapa de objetos perdidos e achados

### Leilões de objetos não reclamados

• RF-18: Registar, editar, e remover um leilão de um objeto.
• RF-19: Ver leilões passados, ativos, e futuros.
• RF-21: Notificar evento ocorrido em leilão. 
• RF-22: Iniciar e terminar um leilão.
• RF-23: Ver histórico de licitações num leilão.
• RF-24: Licitar um objeto num leilão.
• RF-25: Pagar objeto licitado.
• RF-26: Ver histórico de objetos comprados em leilão.

## Requisitos não funcionais realizados

• RFN-1	Especificação API usando o padrão OpenAPI, em formato YAML ou JSON			
• RFN-2	GitHub - uso com branches individuais e releases			
• RFN-3	"Implementação da arquitetura distribuída (implementação de instâncias e
• configuração de redes)"			
• RFN-4	Implementação dos balanceadores de carga			
• RFN-5	Implementação dos servers			
• RFN-6	Implementação das bases de dados			
• RFN-7	Configuração dos balanceadores de carga e mecanismos de escalabilidade			
• RFN-8	Configuração de mecanismos de tolerância a faltas e verificação de saúde			
• RFN-9	Teste de tolerância a faltas nos balanceadores de carga			
• RFN-10	Teste de tolerância a faltas nos webservers			
• RFN-11	Teste de tolerância a faltas nas bases de dados			
• RFN-12	Implementação de mecanismo de Autenticação e autorização (Auth0)			
• RFN-13	Canais seguros, DNS e configuração de firewall			
• RFN-14	Uso de TLS com certificado assinado por uma Autoridade Certificadora (AC)			
• RFN-15	Nome de domínio registado e associado ao IP estático			
• RFN-16	Configurações de firewall			
• RFN-17	Configurar uso de APIs externas			
• RFN-18	Gestão de credenciais e IPs na instalação			
• RFN-19	Automação e scripts para construção e lançamento da aplicação			
• RFN-20	Inclusão de testes unitários integrados ao processo de construção da aplicação(Github)			
• RFN-21	Testes de aceitação com a API			
• RFN-22	Testes de carga			
• RFN-23	Testes de vulnerabilidades			

# Informações extra

Este projeto foi desenvolvido com:

### PHP 8.2
### Laravel 10
### Bootstrap
### MongoDB
### Apache2
### TomTom API
### Paypal API

