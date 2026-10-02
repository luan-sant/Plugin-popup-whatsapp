<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Página de configurações do plugin (Ajustes > WhatsApp Form Redirect)
 */
class WFR_Settings {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Valores padrão de todas as opções do plugin.
     */
    public static function get_default_options() {
        return array(
            'whatsapp_number'   => '',
            'greeting_text'     => 'Olá, tudo bem? Preencha os dados abaixo para iniciar a conversa:',
            'message_template'  => "Olá! Meu nome é {nome}.\nE-mail: {email}\nTelefone: {telefone}\n{selecione_label}: {selecione}\nMensagem: {mensagem}",
            'primary_color'     => '#25D366',
            'header_color'      => '#2f8f7f',
            'position'          => 'right',
            'enable_select'     => '1',
            'select_label'      => 'Como podemos ajudar?',
            'select_options'    => "Quero um orçamento\nTenho uma dúvida\nSuporte técnico",
            'webhook_url'       => '',
            'webhook_secret'    => '',
        );
    }

    public function get_options() {
        $saved = get_option( WFR_OPTION_KEY, array() );
        return wp_parse_args( $saved, self::get_default_options() );
    }

    public function add_settings_page() {
        add_options_page(
            __( 'WhatsApp Form Redirect', 'whatsapp-form-redirect' ),
            __( 'WhatsApp Form', 'whatsapp-form-redirect' ),
            'manage_options',
            'whatsapp-form-redirect',
            array( $this, 'render_settings_page' )
        );
    }

    public function enqueue_admin_assets( $hook ) {
        if ( 'settings_page_whatsapp-form-redirect' !== $hook ) {
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script(
            'wfr-admin',
            WFR_PLUGIN_URL . 'assets/js/wfr-admin.js',
            array( 'wp-color-picker', 'jquery' ),
            WFR_VERSION,
            true
        );
    }

    public function register_settings() {
        register_setting(
            'wfr_settings_group',
            WFR_OPTION_KEY,
            array( $this, 'sanitize_options' )
        );
    }

    /**
     * Sanitiza e valida todos os campos antes de salvar no banco.
     */
    public function sanitize_options( $input ) {
        $defaults = self::get_default_options();
        $output   = array();

        $output['whatsapp_number'] = isset( $input['whatsapp_number'] )
            ? preg_replace( '/\D/', '', $input['whatsapp_number'] )
            : $defaults['whatsapp_number'];

        $output['greeting_text'] = isset( $input['greeting_text'] )
            ? sanitize_textarea_field( $input['greeting_text'] )
            : $defaults['greeting_text'];

        $output['message_template'] = isset( $input['message_template'] )
            ? sanitize_textarea_field( $input['message_template'] )
            : $defaults['message_template'];

        $output['primary_color'] = isset( $input['primary_color'] ) && preg_match( '/^#[a-fA-F0-9]{6}$/', $input['primary_color'] )
            ? sanitize_text_field( $input['primary_color'] )
            : $defaults['primary_color'];

        $output['header_color'] = isset( $input['header_color'] ) && preg_match( '/^#[a-fA-F0-9]{6}$/', $input['header_color'] )
            ? sanitize_text_field( $input['header_color'] )
            : $defaults['header_color'];

        $output['position'] = ( isset( $input['position'] ) && 'left' === $input['position'] ) ? 'left' : 'right';

        $output['enable_select'] = ! empty( $input['enable_select'] ) ? '1' : '0';

        $output['select_label'] = isset( $input['select_label'] )
            ? sanitize_text_field( $input['select_label'] )
            : $defaults['select_label'];

        $output['select_options'] = isset( $input['select_options'] )
            ? sanitize_textarea_field( $input['select_options'] )
            : $defaults['select_options'];

        $output['webhook_url'] = isset( $input['webhook_url'] )
            ? esc_url_raw( trim( $input['webhook_url'] ) )
            : '';

        $output['webhook_secret'] = isset( $input['webhook_secret'] )
            ? sanitize_text_field( $input['webhook_secret'] )
            : '';

        return $output;
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $opts = $this->get_options();
        include WFR_PLUGIN_DIR . 'includes/view-settings-page.php';
    }
}
