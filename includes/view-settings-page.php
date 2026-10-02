<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/** @var array $opts */
?>
<div class="wrap wfr-settings-wrap">
    <h1><?php esc_html_e( 'WhatsApp Form Redirect', 'whatsapp-form-redirect' ); ?></h1>
    <p><?php esc_html_e( 'Configure o botão flutuante de WhatsApp que será exibido em todo o site.', 'whatsapp-form-redirect' ); ?></p>

    <form method="post" action="options.php">
        <?php settings_fields( 'wfr_settings_group' ); ?>

        <h2 class="title"><?php esc_html_e( 'WhatsApp', 'whatsapp-form-redirect' ); ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="wfr_whatsapp_number"><?php esc_html_e( 'Número do WhatsApp', 'whatsapp-form-redirect' ); ?></label>
                </th>
                <td>
                    <input type="text"
                           id="wfr_whatsapp_number"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[whatsapp_number]"
                           value="<?php echo esc_attr( $opts['whatsapp_number'] ); ?>"
                           class="regular-text"
                           placeholder="5562999999999"
                           required>
                    <p class="description">
                        <?php esc_html_e( 'Apenas números, com código do país e DDD. Exemplo: 5562999999999 (Brasil = 55, DDD = 62).', 'whatsapp-form-redirect' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wfr_greeting_text"><?php esc_html_e( 'Texto de saudação', 'whatsapp-form-redirect' ); ?></label>
                </th>
                <td>
                    <textarea id="wfr_greeting_text"
                              name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[greeting_text]"
                              rows="3" class="large-text"><?php echo esc_textarea( $opts['greeting_text'] ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Mensagem exibida no topo do formulário.', 'whatsapp-form-redirect' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wfr_message_template"><?php esc_html_e( 'Modelo da mensagem enviada', 'whatsapp-form-redirect' ); ?></label>
                </th>
                <td>
                    <textarea id="wfr_message_template"
                              name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[message_template]"
                              rows="6" class="large-text code"><?php echo esc_textarea( $opts['message_template'] ); ?></textarea>
                    <p class="description">
                        <?php esc_html_e( 'Placeholders disponíveis:', 'whatsapp-form-redirect' ); ?>
                        <code>{nome}</code> <code>{email}</code> <code>{telefone}</code> <code>{selecione}</code> <code>{selecione_label}</code> <code>{mensagem}</code>
                    </p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e( 'Aparência', 'whatsapp-form-redirect' ); ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><label for="wfr_primary_color"><?php esc_html_e( 'Cor do botão', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <input type="text" id="wfr_primary_color" class="wfr-color-field"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[primary_color]"
                           value="<?php echo esc_attr( $opts['primary_color'] ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wfr_header_color"><?php esc_html_e( 'Cor do cabeçalho do formulário', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <input type="text" id="wfr_header_color" class="wfr-color-field"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[header_color]"
                           value="<?php echo esc_attr( $opts['header_color'] ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e( 'Posição na tela', 'whatsapp-form-redirect' ); ?></th>
                <td>
                    <select name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[position]">
                        <option value="right" <?php selected( $opts['position'], 'right' ); ?>><?php esc_html_e( 'Canto inferior direito', 'whatsapp-form-redirect' ); ?></option>
                        <option value="left" <?php selected( $opts['position'], 'left' ); ?>><?php esc_html_e( 'Canto inferior esquerdo', 'whatsapp-form-redirect' ); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e( 'Campo de seleção personalizado', 'whatsapp-form-redirect' ); ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><?php esc_html_e( 'Exibir campo de seleção', 'whatsapp-form-redirect' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[enable_select]" value="1" <?php checked( $opts['enable_select'], '1' ); ?>>
                        <?php esc_html_e( 'Adicionar um campo de seleção (dropdown) ao formulário', 'whatsapp-form-redirect' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wfr_select_label"><?php esc_html_e( 'Rótulo do campo', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <input type="text" id="wfr_select_label" class="regular-text"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[select_label]"
                           value="<?php echo esc_attr( $opts['select_label'] ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wfr_select_options"><?php esc_html_e( 'Opções', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <textarea id="wfr_select_options"
                              name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[select_options]"
                              rows="5" class="large-text"><?php echo esc_textarea( $opts['select_options'] ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Uma opção por linha.', 'whatsapp-form-redirect' ); ?></p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e( 'Integração via Webhook (opcional)', 'whatsapp-form-redirect' ); ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><label for="wfr_webhook_url"><?php esc_html_e( 'URL do Webhook', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <input type="url" id="wfr_webhook_url" class="large-text"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[webhook_url]"
                           value="<?php echo esc_attr( $opts['webhook_url'] ); ?>"
                           placeholder="https://sua-api.com/webhook">
                    <p class="description">
                        <?php esc_html_e( 'Se preenchido, os dados do lead são enviados via POST (JSON) para essa URL antes do redirecionamento para o WhatsApp. Útil para integrar com CRMs como RD Station, HubSpot, Zapier/Make, etc.', 'whatsapp-form-redirect' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wfr_webhook_secret"><?php esc_html_e( 'Chave secreta (opcional)', 'whatsapp-form-redirect' ); ?></label></th>
                <td>
                    <input type="password" id="wfr_webhook_secret" class="regular-text"
                           name="<?php echo esc_attr( WFR_OPTION_KEY ); ?>[webhook_secret]"
                           value="<?php echo esc_attr( $opts['webhook_secret'] ); ?>"
                           autocomplete="new-password">
                    <p class="description">
                        <?php esc_html_e( 'Enviada no header "X-WFR-Secret" da requisição, para que seu endpoint valide a origem.', 'whatsapp-form-redirect' ); ?>
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
