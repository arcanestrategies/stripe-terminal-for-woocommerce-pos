<?php
/**
 * Template for the admin tools
 */
?>

<h3><?php /* translators: woocommerce */ _e( 'Tools', 'woocommerce' ); ?></h3>

<table class="widefat striped">
  <tbody>

    <tr>
      <th><?php _e( 'Receipt Template', 'arcane-woocommerce-pos' ); ?></th>
      <td>
        <a href="<?php esc_attr_e( wc_pos_url('#print') ); ?>" target="_blank" class="button">
          <?php _e( 'View Sample Receipt', 'arcane-woocommerce-pos' ); ?>
        </a>
        <?php printf( __( '<strong class="red">Template path:</strong> %s', 'arcane-woocommerce-pos' ), '<code style="font-size: 11px">'. WC_POS_Template::locate_template_file('print/tmpl-receipt.php') .'</code>' ); ?>
      </td>
    </tr>

    <tr>
      <th><?php _e( 'Local Data', 'arcane-woocommerce-pos' ); ?></th>
      <td>
        <a href="#"
           class="button"
           data-action="delete-local-data"
           data-title="<?php esc_attr_e( 'Clear Local Data', 'arcane-woocommerce-pos' ); ?>"
          >
          <?php
            _e( 'Clear All Local Data', 'arcane-woocommerce-pos' );
          ?>
        </a>
      </td>
    </tr>

  </tbody>
</table>