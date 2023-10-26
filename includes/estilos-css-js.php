<?php 
// Carrega arquivos CSS e JavaScript no painel de administração
function custom_admin_enqueue_scripts() {
global $pagenow;

if ( $pagenow == 'profile.php' || $pagenow == 'user-edit.php' ) {
wp_enqueue_style( 'custom-admin-style', plugin_dir_url( __FILE__ ) . '../assets/admin-styles.css' );
wp_enqueue_script( 'custom-admin-script', plugin_dir_url( __FILE__ ) . '../assets/script.js', array( 'jquery' ), '', true );
wp_enqueue_script('jquery-mask', plugin_dir_url( __FILE__ ) . '../assets/jquery.maskedinput.js', array('jquery'), '1.4.1', true);
wp_enqueue_script('validar-formulario', plugin_dir_url( __FILE__ ) . '../assets/mascaras.js', array('jquery'), '1.0', true);
wp_enqueue_script('validar-cep', plugin_dir_url( __FILE__ ) . '../assets/valida.cep.js', array('jquery'), '1.0', true);
}
}
add_action( 'admin_enqueue_scripts', 'custom_admin_enqueue_scripts' );