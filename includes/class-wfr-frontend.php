<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Renderiza o widget no front-end e processa o envio do formulário.
 */
class WFR_Frontend {

    private $options;

    public function __construct() {
        $settings      = new WFR_Settings();
        $this->options = $settings->get_options();

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_footer', array( $this, 'render_widget' ) );
        add_shortcode( 'wfr_button', array( $this, 'render_shortcode_button' ) );

        add_action( 'wp_ajax_wfr_submit_lead', array( $this, 'handle_submit' ) );
        add_action( 'wp_ajax_nopriv_wfr_submit_lead', array( $this, 'handle_submit' ) );
    }

    /**
     * O widget só é carregado se um número de WhatsApp estiver configurado.
     */
    private function is_configured() {
        return ! empty( $this->options['whatsapp_number'] );
    }

    public function enqueue_assets() {
        if ( ! $this->is_configured() ) {
            return;
        }

        wp_enqueue_style( 'wfr-widget', WFR_PLUGIN_URL . 'assets/css/wfr-widget.css', array(), WFR_VERSION );
        wp_enqueue_script( 'wfr-widget', WFR_PLUGIN_URL . 'assets/js/wfr-widget.js', array(), WFR_VERSION, true );

        $select_options = array_filter( array_map( 'trim', explode( "\n", $this->options['select_options'] ) ) );

        wp_localize_script( 'wfr-widget', 'wfrSettings', array(
            'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
            'nonce'           => wp_create_nonce( 'wfr_submit_lead' ),
            'whatsappNumber'  => preg_replace( '/\D/', '', $this->options['whatsapp_number'] ),
            'messageTemplate' => $this->options['message_template'],
            'enableSelect'    => '1' === $this->options['enable_select'],
            'selectLabel'     => $this->options['select_label'],
            'hasWebhook'      => ! empty( $this->options['webhook_url'] ),
        ) );
    }

    /**
     * Imprime o HTML do botão flutuante + popup no rodapé do site.
     */
    public function render_widget() {
        if ( ! $this->is_configured() ) {
            return;
        }

        $opts           = $this->options;
        $position_class = ( 'left' === $opts['position'] ) ? 'wfr-pos-left' : 'wfr-pos-right';
        $select_options = array_filter( array_map( 'trim', explode( "\n", $opts['select_options'] ) ) );
        ?>
        <div id="wfr-overlay" class="<?php echo esc_attr( $position_class ); ?>"></div>

        <button id="wfr-launcher" class="<?php echo esc_attr( $position_class ); ?>" aria-label="<?php esc_attr_e( 'Abrir WhatsApp', 'whatsapp-form-redirect' ); ?>"
                style="--wfr-primary: <?php echo esc_attr( $opts['primary_color'] ); ?>;">
            <?php echo $this->get_whatsapp_icon_svg(); ?>
        </button>

        <div id="wfr-popup" class="<?php echo esc_attr( $position_class ); ?>"
             style="--wfr-primary: <?php echo esc_attr( $opts['primary_color'] ); ?>; --wfr-header: <?php echo esc_attr( $opts['header_color'] ); ?>;"
             role="dialog" aria-label="<?php esc_attr_e( 'Formulário WhatsApp', 'whatsapp-form-redirect' ); ?>">

            <div id="wfr-popup-header">
                <?php echo $this->get_whatsapp_icon_svg(); ?>
                <span><?php esc_html_e( 'WhatsApp', 'whatsapp-form-redirect' ); ?></span>
                <button id="wfr-popup-close" aria-label="<?php esc_attr_e( 'Fechar', 'whatsapp-form-redirect' ); ?>">&#10005;</button>
            </div>

            <div id="wfr-popup-body">
                <form id="wfr-lead-form" novalidate>
                    <p class="wfr-intro"><?php echo esc_html( $opts['greeting_text'] ); ?></p>

                    <div class="wfr-field">
                        <label for="wfr-nome"><?php esc_html_e( 'Nome', 'whatsapp-form-redirect' ); ?>*</label>
                        <input type="text" id="wfr-nome" name="nome" placeholder="<?php esc_attr_e( 'Digite aqui...', 'whatsapp-form-redirect' ); ?>" autocomplete="name" required>
                        <span class="wfr-error"><?php esc_html_e( 'Informe seu nome.', 'whatsapp-form-redirect' ); ?></span>
                    </div>

                    <div class="wfr-field">
                        <label for="wfr-email"><?php esc_html_e( 'E-mail', 'whatsapp-form-redirect' ); ?>*</label>
                        <input type="email" id="wfr-email" name="email" placeholder="<?php esc_attr_e( 'Digite aqui...', 'whatsapp-form-redirect' ); ?>" autocomplete="email" required>
                        <span class="wfr-error"><?php esc_html_e( 'Informe um e-mail válido.', 'whatsapp-form-redirect' ); ?></span>
                    </div>

                    <div class="wfr-field">
                        <label for="wfr-telefone"><?php esc_html_e( 'Telefone', 'whatsapp-form-redirect' ); ?>*</label>
                        <input type="text" id="wfr-telefone" name="telefone" placeholder="(00) 00000-0000" autocomplete="tel" maxlength="15" required>
                        <span class="wfr-error"><?php esc_html_e( 'Informe um telefone válido.', 'whatsapp-form-redirect' ); ?></span>
                    </div>

                    <?php if ( '1' === $opts['enable_select'] && ! empty( $select_options ) ) : ?>
                        <div class="wfr-field">
                            <label for="wfr-selecione"><?php echo esc_html( $opts['select_label'] ); ?>*</label>
                            <select id="wfr-selecione" name="selecione" required>
                                <option value=""><?php esc_html_e( 'Selecione...', 'whatsapp-form-redirect' ); ?></option>
                                <?php foreach ( $select_options as $option ) : ?>
                                    <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="wfr-error"><?php esc_html_e( 'Selecione uma opção.', 'whatsapp-form-redirect' ); ?></span>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div id="wfr-status-msg"></div>

            <div id="wfr-popup-footer">
                <input type="text" id="wfr-mensagem" placeholder="<?php esc_attr_e( 'Digite uma mensagem', 'whatsapp-form-redirect' ); ?>">
                <button id="wfr-send-btn" aria-label="<?php esc_attr_e( 'Enviar', 'whatsapp-form-redirect' ); ?>">
                    <svg viewBox="0 0 24 24" fill="#fff"><path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/></svg>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Shortcode [wfr_button text="Fale conosco"] — gera um botão que abre o mesmo popup.
     */
    public function render_shortcode_button( $atts ) {
        $atts = shortcode_atts( array(
            'text'  => __( 'Fale no WhatsApp', 'whatsapp-form-redirect' ),
            'class' => '',
        ), $atts, 'wfr_button' );

        return sprintf(
            '<button type="button" class="wfr-open-form %s">%s</button>',
            esc_attr( $atts['class'] ),
            esc_html( $atts['text'] )
        );
    }

    private function get_whatsapp_icon_svg() {
        return '<svg viewBox="0 0 32 32" fill="#fff"><path d="M16.001 2.667C8.646 2.667 2.667 8.646 2.667 16c0 2.615.766 5.05 2.087 7.098L2.667 29.333l6.42-2.052A13.28 13.28 0 0016.001 29.333c7.354 0 13.332-5.978 13.332-13.333S23.355 2.667 16.001 2.667zm0 24.222a10.83 10.83 0 01-5.897-1.734l-.423-.263-4.29 1.372 1.401-4.16-.276-.44a10.845 10.845 0 01-1.68-5.664c0-6.003 4.885-10.889 10.889-10.889S26.89 9.997 26.89 15.999s-4.885 10.89-10.889 10.89zm5.973-8.152c-.328-.164-1.94-.958-2.24-1.068-.301-.109-.52-.164-.739.164-.219.328-.848 1.068-1.04 1.287-.191.219-.383.246-.71.082-.328-.164-1.385-.51-2.638-1.628-.975-.869-1.633-1.943-1.825-2.271-.191-.328-.02-.505.144-.668.148-.147.328-.383.492-.574.164-.191.219-.328.328-.547.109-.219.055-.41-.027-.574-.082-.164-.739-1.782-1.013-2.44-.267-.64-.539-.553-.739-.563l-.629-.011a1.21 1.21 0 00-.876.41c-.301.328-1.149 1.123-1.149 2.74s1.176 3.18 1.34 3.399c.164.219 2.314 3.534 5.607 4.955.783.338 1.394.54 1.87.691.786.25 1.501.215 2.066.13.63-.094 1.94-.793 2.213-1.559.273-.766.273-1.422.191-1.559-.082-.137-.301-.219-.629-.383z"/></svg>';
    }

    /**
     * Processa o envio via AJAX: valida, repassa ao webhook (se configurado) e responde ao JS.
     * O redirecionamento para o WhatsApp acontece no front-end (widget.js), não aqui.
     */
    public function handle_submit() {
        check_ajax_referer( 'wfr_submit_lead', 'nonce' );

        $nome      = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
        $email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $telefone  = isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '';
        $selecione = isset( $_POST['selecione'] ) ? sanitize_text_field( wp_unslash( $_POST['selecione'] ) ) : '';
        $mensagem  = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';

        if ( empty( $nome ) || empty( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Dados inválidos.', 'whatsapp-form-redirect' ) ), 400 );
        }

        // Repassa para o webhook externo, se configurado (não bloqueia o fluxo em caso de falha)
        if ( ! empty( $this->options['webhook_url'] ) ) {
            $headers = array( 'Content-Type' => 'application/json' );
            if ( ! empty( $this->options['webhook_secret'] ) ) {
                $headers['X-WFR-Secret'] = $this->options['webhook_secret'];
            }

            wp_remote_post( $this->options['webhook_url'], array(
                'headers' => $headers,
                'body'    => wp_json_encode( array(
                    'nome'      => $nome,
                    'email'     => $email,
                    'telefone'  => $telefone,
                    'selecione' => $selecione,
                    'mensagem'  => $mensagem,
                    'origem'    => home_url( '/' ),
                    'data'      => current_time( 'mysql' ),
                ) ),
                'timeout' => 8,
            ) );
        }

        wp_send_json_success( array( 'message' => __( 'Lead recebido.', 'whatsapp-form-redirect' ) ) );
    }
}
