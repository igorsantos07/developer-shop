Especificações do projeto
=========================

> A documentação da API, criada em SwaggerUI (baseada no Restler, conforme especificado abaixo), está disponível em [https://devshop.igorsantos.com.br/api/docs].

Tecnologias
-----------

### Infra
- **Heroku**: prático de instalar e eu já possuía todo o setup, visto que o utilizo no [Konato], meu _pet project_ permanente; 
- **CloudFlare**: possibilita o uso de HTTPS - afinal, e-commerce tem que ser seguro :)


### Backend
A API foi utilizada para persistir o carrinho do usuário e encapsular a interação com o GitHub.

- **PHP 5.3**: linguagem de backend com a qual eu me sinto mais confortável pela experiência que já possuo. As libraries utilizadas junto com ela me permitirão dar maior foco à parte de frontend do projeto;
- **[Restler]**: biblioteca eficiente para criar servidores REST, que toma vantagem do conceito de _Reflection_ para espelhar os métodos públicos de classes cruas numa API RESTful. Também inclui documentação poderosa baseada no SwaggerUI;
- **PostgreSQL**: persistência. Assim como o PHP, foi escolhido pela facilidade de implementação;
- **[Codeception]**: TDD automatizado para a API; também é possível incluir nele testes via Selenium (utilizando browsers de verdade ou o PhantomJS).

### Frontend
- **React**: além da sugestão do README, eu já tinha interesse em estudar React.js ou Vue.js para implementação em algumas telas mais complexas do [Konato];
- **Gulp simplificado**: para compilação de assets foi utilizado o [Elixir do Laravel][elixir], um layer simplificado, acima do Gulp.

Tarefas e Priorização
---------------------
Segue a descrição e motivação das tarefas desenvolvidas neste projeto, na ordem de execução. O título indica o número correspondente da tarefa no README original. 


### 0. Funcionamento básico (tag: basic)
1. aplicação do layout proposto em React;
2. implementação de adição e remoção de itens no carrinho, com preço opcional;
3. responsividade no layout, visto que é uma tela bem simples e com potencial para tal.

### 1. API para operações do carrinho (#8)
Esta tarefa foi tomada como a primeira da lista por dois motivos: ela integra a UX, demonstrando o próximo passo no flow do projeto, e também necessita da interação com uma API para persistência e finalização das operações.

### 2. Integração com o GitHub para captura de dados e preço (#1, abrindo possibilidades para #2 e #7)
Considerei esta tarefa importante por representar a integração com uma API de terceiros, que pode ser encapsulada na API do sistema, tornando a integração transparente para o _client-side_. Também é importante para a coesão do sistema, de modo a torná-lo mais realista - afinal, ninguém define o preço que vai pagar por um produto, ou mesmo um desenvolvedor, certo? ;)

### 3. Cupom de desconto (#6)
Incluída aqui também por representar outro passo de um fluxo de carrinho comum: antes da compra, o usuário deve poder incluir um cupom de desconto - quem não gosta de desconto? Sob o ponto de vista técnico, é outro ponto de contato do frontend com o backend e, por ser uma parte intermediária do fluxo, seria interessante de demonstrar também.

### 4. Escolha da quantidade de horas contratadas (#5)
Foi colocado aqui por ser uma tarefa simples e que traz mais sentido ainda para o sistema.

### 5. Lista de desenvolvedores de uma organização (#4, #2)
Por fim, para aumentar a integração com o GitHub, incluí estes dois pontos como um só, visto que são bem próximos e a integração criada anteriormente possibilita facilmente a implementação destes. É outro ponto de contato do frontend com a API e traz um funcionamento mais prático e lógico para o sistema.


[Restler]:http://www.luracast.com/products/restler
[Codeception]:http://codeception.com
[Konato]:https://konato.igorsantos.com.br
[elixir]:http://laravel.com/docs/5.1/elixir