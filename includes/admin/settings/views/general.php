<?php
/**
 * Template for the general settings
 */
?>

<h3><?php /* translators: woocommerce */ _e( 'General Options', 'woocommerce' ); ?></h3>

<table class="wc_pos-form-table">

  <tr>
    <th scope="row">
      <label for="pos_only_products"><?php _e( 'Product Visibility', 'arcane-woocommerce-pos' ) ?></label>
    </th>
    <td>
      <input type="checkbox" name="pos_only_products" id="pos_only_products" />
      <?php _e( 'Enable POS Only products', 'arcane-woocommerce-pos' ) ?>
    </td>
  </tr>

  <tr>
    <th scope="row">
      <label for="decimal_qty"><?php _e( 'Allow Decimal Quantity', 'arcane-woocommerce-pos' ) ?></label>
      <img title="<?php esc_attr_e( 'Allows items to have decimal values in the quantity field, eg: 0.25', 'arcane-woocommerce-pos' ) ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" data-toggle="wc_pos-tooltip">
    </th>
    <td>
      <input type="checkbox" name="decimal_qty" id="decimal_qty" />
      <?php /* translators: wordpress */ _e('Enable'); ?>
    </td>
  </tr>

  <tr class="default_customer">
    <th scope="row">
      <label><?php _e( 'Default POS Customer', 'arcane-woocommerce-pos' ); ?></label>
      <img title="<?php esc_attr_e( 'The default customer for POS orders, eg: Guest or create a new customer.', 'arcane-woocommerce-pos' ) ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" data-toggle="wc_pos-tooltip">
    </th>
    <td>
      <select name="default_customer" id="default_customer" class="select2" style="width:250px" data-select="customer"></select>&nbsp;
      <input type="checkbox" name="logged_in_user" id="logged_in_user">
      <label for="logged_in_user"><?php _ex( 'Use cashier account', 'Default customer setting', 'arcane-woocommerce-pos' ) ?></label>
    </td>
  </tr>

  <tr>
    <th scope="row">
      <label for="discount_quick_keys"><?php _e( 'Discount Quick Keys', 'arcane-woocommerce-pos' ) ?></label>
      <img title="<?php esc_attr_e( 'Configure discount keys for quick numpad entry', 'arcane-woocommerce-pos' ) ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" data-toggle="wc_pos-tooltip">
    </th>
    <td>
      <select name="discount_quick_keys" id="discount_quick_keys" class="select2" style="width:250px" multiple>
      <?php for($i=1; $i<=100; $i++): ?>
        <option value="<?php echo $i;?>"><?php echo $i;?>%</option>
      <?php endfor; ?>
      </select>
    </td>
  </tr>

</table>