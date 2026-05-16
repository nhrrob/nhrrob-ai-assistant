<?php
namespace Nhrada\AIDeveloperAssistant;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Undo {

    public function revert_change( $change_id ) {
        global $wpdb;

        $change = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nhrada_log WHERE id = %d AND record_type = 'change' AND status = 'applied'",
            $change_id
        ) );

        if ( ! $change ) {
            return new WP_Error( 'undo_failed', 'Change not found or already undone.' );
        }

        if ( ! $change->snapshot_type ) {
            return new WP_Error( 'undo_failed', 'No snapshot found for this change.' );
        }

        $success = false;

        if ( 'option' === $change->snapshot_type ) {
            $success = $this->revert_option( $change->target_key, $change->original_value );
        } elseif ( 'file' === $change->snapshot_type ) {
            $success = $this->revert_file( $change->target_key, $change->original_value, $change_id );
        }

        if ( is_wp_error( $success ) ) {
            return $success;
        }

        if ( $success ) {
            $wpdb->update(
                $wpdb->prefix . 'nhrada_log',
                array( 'status' => 'undone' ),
                array( 'id' => $change_id ),
                array( '%s' ),
                array( '%d' )
            );
            return true;
        }

        return new WP_Error( 'undo_failed', 'Failed to revert the change.' );
    }

    private function revert_option( $option_name, $original_value ) {
        if ( 'custom_css_post_id' === $option_name ) {
            wp_update_custom_css_post( $original_value );
            return true;
        }

        update_option( $option_name, $original_value );
        return true;
    }

    private function revert_file( $filename, $original_value, $change_id ) {
        if ( 'nhrada-snippets.php' === $filename ) {
            $filepath = WP_CONTENT_DIR . '/' . $filename;

            if ( ! file_exists( $filepath ) ) {
                return new WP_Error( 'undo_failed', 'Snippets file does not exist.' );
            }

            $current_content = file_get_contents( $filepath );

            $pattern     = '/\n\/\/ \[NHRAA-SNIPPET-' . $change_id . ' \| [^\]]+\]\n.*?\/\/ \[\/NHRAA-SNIPPET-' . $change_id . '\]\n/s';
            $new_content = preg_replace( $pattern, '', $current_content );

            if ( $new_content !== null ) {
                file_put_contents( $filepath, $new_content );
                return true;
            }

            return new WP_Error( 'undo_failed', 'Failed to parse snippets file for undo.' );
        }

        return new WP_Error( 'undo_failed', 'Unknown file target.' );
    }
}
