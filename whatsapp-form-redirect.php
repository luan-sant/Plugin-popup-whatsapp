<?php
/**
 * Plugin Name:       WhatsApp Form Redirect
 * Plugin URI:         https://github.com/luan-sant/whatsapp-form-redirect
 * Description:        Botão flutuante de WhatsApp com formulário de qualificação de leads. Ao enviar, os dados são opcionalmente encaminhados para um webhook (CRM, RD Station, etc.) e o visitante é redirecionado para o WhatsApp com uma mensagem pré-preenchida.
 * Version:             1.0.0
 * Requires at least:   5.8
 * Requires PHP:        7.4
 * Author:              Luan Santos
 * Author URI:          https://github.com/luan-sant
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         whatsapp-form-redirect
 */

// Impede acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WFR_VERSION', '1.0.0' );
define( 'WFR_PLUGIN_FILE', __FILE__ );
define( 'WFR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WFR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WFR_OPTION_KEY', 'wfr_options' );

require_once WFR_PLUGIN_DIR . 'includes/class-wfr-settings.php';
require_once WFR_PLUGIN_DIR . 'includes/class-wfr-frontend.php';

/**
 * Inicializa o plugin.
 */
function wfr_init_plugin() {
    new WFR_Settings();
    new WFR_Frontend();
}
add_action( 'plugins_loaded', 'wfr_init_plugin' );

/**
 * Define as opções padrão na ativação do plugin.
 */
function wfr_activate_plugin() {
    $defaults = WFR_Settings::get_default_options();
    $current  = get_option( WFR_OPTION_KEY );

    if ( false === $current ) {
        add_option( WFR_OPTION_KEY, $defaults );
    } else {
        // Garante que novas opções adicionadas em updates futuros tenham um valor padrão
        update_option( WFR_OPTION_KEY, wp_parse_args( $current, $defaults ) );
    }
}
register_activation_hook( __FILE__, 'wfr_activate_plugin' );

/**
 * Link de atalho "Configurações" na listagem de plugins.
 */
function wfr_add_settings_link( $links ) {
    $settings_link = '<a href="' . admin_url( 'options-general.php?page=whatsapp-form-redirect' ) . '">' . __( 'Configurações', 'whatsapp-form-redirect' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wfr_add_settings_link' );
