=== WebCourier Plugin ===
Contributors: D'Gledson Rabelo, Franciane Pereira
Tags: WebCourier, user management, mailin list, add users
Requires at least: 3.0.1
Tested up to: 4.6
Stable tag: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin feito para envio de pesquisas de satisfação.

== Description ==
O plugin do webcourier permite que você gerencie pesquisas de forma sincronizada com a API do webcourier e defina em quais eventos suas pesquisas serão lançadas.

Você pode verificar em sua conta da aplicação do webcourier relatórios e gráficos das respostas enviadas.

Exemplo : Você pode definir uma pesquisa para que ela seja enviada na finalização de uma compra.

Como uma função adicional, você pode utilizar o shortcode [form_webcourier_newsletter_shortcode] para criar um formulário que cadastra automaticamente os clientes na sua caixa de email do webcourier.

== Installation ==

1. Registre-se em https://app.webcourier.com.br/ e crie sua chave API na seção de administradores.
2. No seu site wordpress, faça o upload do `webcourier.zip` para a pasta `wp-content/plugins`.
3. Ative o plugin pelo menu 'Plugins' no Wordpress.
4. Sincronize sua chave API com o plugin webcourier.
5. Crie pesquisas e defina seus eventos.

== Changelog ==

= 0.1 =
* Release inicial.

= 1.0 =
* Adicionado eventos de compra.

= 1.1 =
* Adicionado eventos de usuário (comentar e se registrar).

= 1.2 =
* Adicionada página de configurações para o usuário modificar como sua pesquisa aparece.
* Adicionada ação de preview de pesquisa.

= 2.0 =
* Adicionado wizard de adição de pesquisa.
* Adicionado ícone de relatório no plugin que mostrar o resultado de sua pesquisa.

= 2.1 = 
* Adicionada confirmação ao deletar uma pesquisa.
* Modificada a forma que os eventos são escolhidos na criação de uma pesquisa.
* Agora é possível mudar o evento da pesquisa mesmo depois de criada.

= 2.2 =
* Adicionado botão para copiar pesquisa.

= 2.3 =
* Consertada edição de pesquisas.