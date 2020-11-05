<?php
/**
 * Template for the POS access settings
 */
?>

<h3><?php _e( 'Grant POS Access', 'arcane-woocommerce-pos' ); ?></h3>

<p class="update-nag" style=font-size:13px;margin:0;">
  <?php _e( 'By default, access to the POS is limited to Administrator and Shop Manager roles.', 'arcane-woocommerce-pos' ); ?>
  <?php _e( 'It is recommended that you <strong>do not change</strong> the default settings unless you are fully aware of the consequences.', 'arcane-woocommerce-pos' ); ?>
</p>

<div class="wc_pos-access">
  <ul class="wc_pos-access-tabs">
    <?php $data = $this->get(); if($data): foreach($data['roles'] as $slug => $role): ?>
      <li data-id="<?php echo $slug; ?>"><?php echo translate_user_role($role['name']); ?></li>
    <?php endforeach; endif; ?>
  </ul>
  <ul class="wc_pos-access-panel">
    <?php if($data): foreach($data['roles'] as $slug => $role): ?>
      <li id="<?php echo $slug; ?>">
        <ul>
          <?php if(isset($this->caps)): foreach($this->caps as $key => $caps): ?>
            <?php if($caps): foreach($caps as $cap): ?>
              <li>
                <input type="checkbox" name="roles.<?php echo esc_attr($slug); ?>.capabilities.<?php echo esc_attr($key); ?>.<?php echo esc_attr($cap); ?>" />
                <?php echo $cap; ?>
              </li>
            <?php endforeach; endif; ?>
          <?php endforeach; endif; ?>
        </ul>
      </li>
    <?php endforeach; endif; ?>
  </ul>
</div>