<small class="info">
  <?= sprintf( __( 'Showing %s of %s', 'arcane-woocommerce-pos' ), '{{showing}}', '{{local}}' ); ?>
  {{#if hasQueue}}(<?= sprintf( __( '%s in queue', 'arcane-woocommerce-pos' ), '{{queue}}' ); ?>){{/if}}
</small>