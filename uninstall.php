<?php
// Impede acesso direto; só executa quando chamado pelo próprio WordPress na desinstalação.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'wfr_options' );
