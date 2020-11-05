<h3><?php _e( 'Keyboard Shortcuts', 'arcane-woocommerce-pos' ); ?></h3>

<p>
  <?php _e( 'You can trigger certain events in WooCommerce POS using keyboard shortcuts (HotKeys).', 'arcane-woocommerce-pos' ); ?><br>
  <?php _e( 'HotKeys can also be used as a prefix for your barcode scanner or card reader.', 'arcane-woocommerce-pos' ); ?><br>
</p>

<ul class="wc_pos-hotkeys">
  <?php $keys = $this->get('hotkeys'); if($keys): foreach($keys as $id => $key): ?>
    <li>
      <input type="text" name="hotkeys.<?php esc_attr_e($id); ?>.key">
      <label for="hotkeys.<?php esc_attr_e($id); ?>.key"><?php echo $key['label']; ?></label>
    </li>
  <?php endforeach; endif; ?>
</ul>