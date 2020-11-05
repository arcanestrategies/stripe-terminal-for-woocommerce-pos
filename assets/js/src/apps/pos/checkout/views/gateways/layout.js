var LayoutView = require('lib/config/layout-view');
var GatewayView = require('./gateway');
var DrawerView = require('./drawer');
var polyglot = require('lib/utilities/polyglot');

module.exports = LayoutView.extend({
	tagName: 'li',

	template: function() {
		return '<div class="gateway"></div><div class="drawer"></div>';
	},

	regions: {
		gatewayRegion: '.gateway',
		drawerRegion: '.drawer'
	},

	initialize: function(){
		if(this.model.attributes.method_id === 'stripe' && window.wc_stripe_params != null){
			this.model.stripe = window.Stripe(window.wc_stripe_params.key);

			const stripe_elements_options = Object.keys(window.wc_stripe_params.elements_options).length ? window.wc_stripe_params.elements_options : {};

			this.model.stripe_elements = this.model.stripe.elements(stripe_elements_options);
			this.model.stripe_card = this.model.stripe_exp = this.model.stripe_cvc = null;

			const elementStyles = wc_stripe_params.elements_styling ? wc_stripe_params.elements_styling : {
				base: {
					iconColor: '#666EE8',
					color: '#31325F',
					fontSize: '15px',
					'::placeholder': {
						  color: '#CFD7E0',
					}
				}
			};
			const elementClasses = wc_stripe_params.elements_classes ? wc_stripe_params.elements_classes : {
				focus: 'focused',
				empty: 'empty',
				invalid: 'invalid',
			};

			if(window.wc_stripe_params.inline_cc_form === 'yes'){
				this.model.stripe_card = this.model.stripe_elements.create('card', { style: elementStyles });
			}else{
				this.model.stripe_card = this.model.stripe_elements.create('cardNumber', { style: elementStyles, classes: elementClasses });
				this.model.stripe_exp = this.model.stripe_elements.create('cardExpiry', { style: elementStyles, classes: elementClasses });
				this.model.stripe_cvc = this.model.stripe_elements.create('cardCvc', { style: elementStyles, classes: elementClasses });
			}
		}
		this.listenTo(this.model, 'change:active', this.toggleDrawer);
	},

	onRender: function(){
		var view = new GatewayView({
			model: this.model
		});
		this.gatewayRegion.show(view);
	},

	onShow: function(){
		if(this.model.get('active')){
			this.openDrawer();
		}
	},

	openDrawer: function(){
		var view = new DrawerView({
			model: this.model
		});
		this.drawerRegion.show(view);
		this.$el.addClass('active');
		if(this.model.attributes.method_id + 'Open' in this){
			this[this.model.attributes.method_id + 'Open']();
		}
	},

	closeDrawer: function(){
		this.drawerRegion.empty();
		this.$el.removeClass('active');
		if(this.model.attributes.method_id + 'Close' in this){
			this[this.model.attributes.method_id + 'Close']();
		}
	},

	toggleDrawer: function(){
		if( this.drawerRegion.hasView() ){
			this.closeDrawer();
		} else {
			this.openDrawer();
		}
	},

	stripe_terminalOpen: function(){
		if(window.tfsw_stripe_terminal_form == null){
			this.$el.find(".drawer").first().text(polyglot.t('messages.bad-gateway'));
			return;
		}

		window.tfsw_stripe_terminal_form.autoCancel();
		const self = this;
		this.model.stSelected = null;
		this.model.stButtonClickListener = function(){
			self.model.stSelected = $(this).data('id');
			self.$el.find(".woocommerce_stripe_terminal_reader_init").removeClass("selected");
			$(this).addClass("selected");
		};
		this.$el.on('click', '.woocommerce_stripe_terminal_reader_init', this.model.stButtonClickListener);

		if(self.$el.find(".woocommerce_stripe_terminal_reader_init").length === 1){
			self.$el.find(".woocommerce_stripe_terminal_reader_init").first().click();
		}
	},

	stripe_terminalClose: function(){
		this.$el.find('.woocommerce_stripe_terminal_reader_init.selected').removeClass('selected');
		this.$el.off('click', '.woocommerce_stripe_terminal_reader_init', this.model.stButtonClickListener);
		this.model.stButtonClickListener = null;
		this.model.stSelected = null;
	},

	stripeOpen: function(){
		if(window.wc_stripe_params == null){
			this.$el.find(".drawer").first().text(polyglot.t('messages.bad-gateway'));
			return;
		}

		if(window.wc_stripe_params.inline_cc_form === 'yes'){
			return this.model.stripe_card.mount('#stripe-card-element');
		}

		this.model.stripe_card.mount('#stripe-card-element');
		this.model.stripe_exp.mount('#stripe-exp-element');
		this.model.stripe_cvc.mount('#stripe-cvc-element');
	},

	stripeClose: function(){
		if(window.wc_stripe_params.inline_cc_form === 'yes'){
			return this.model.stripe_card.unmount('#stripe-card-element');
		}

		this.model.stripe_card.unmount('#stripe-card-element');
		this.model.stripe_exp.unmount('#stripe-exp-element');
		this.model.stripe_cvc.unmount('#stripe-cvc-element');
	}
});