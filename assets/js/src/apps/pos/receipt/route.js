var Route = require('lib/config/route');
var Radio = require('backbone.radio');
//var debug = require('debug')('receipt');
var App = require('lib/config/application');
var LayoutView = require('./layout');
var StatusView = require('./views/status');
var ItemsView = require('./views/items');
var TotalsView = require('./views/totals');
var EmailView = require('./views/modals/email');
var polyglot = require('lib/utilities/polyglot');
var Buttons = require('lib/components/buttons/view');
var $ = require('jquery');

var ReceiptRoute = Route.extend({

	initialize: function( options ) {
		options = options || {};
		this.container = options.container;
		this.collection = options.collection;
		this.autoPrint = options.autoPrint;
		this.btnLoading = false;
		this.setTabLabel({
			tab	 : 'right',
			label : polyglot.t('titles.receipt')
		});
		this.tax = Radio.request('entities', 'get', {
			type: 'option',
			name: 'tax'
		}) || {};
	},

	fetch: function() {
		if(this.collection.isNew()){
			return this.collection.fetch();
		}
	},

	onFetch: function(id){
		this.order = this.collection.get(id);
		this.order.clearCart();

		if(this.order.get('payment_details.result') === 'confirm' && this.order.get('payment_details.method_id') === 'stripe'){
			const pi_secret = this.order.get('payment_details.pi_secret');
			if(pi_secret && window.Stripe != null && window.wc_stripe_params != null){
				const self = this;
				const stripe = window.Stripe(window.wc_stripe_params.key);
				this.btnLoading = true;
				stripe.confirmCardPayment(pi_secret).then(function(result){
					if(result.error){
						self.order.set('payment_details.result', 'failure');
						self.order.set('payment_details.pi_secret', null);
					}else if(result.paymentIntent){
						self.order.set('payment_details.paid', true);
						self.order.set('payment_details.result', 'success');
					}
					self.order.remoteSync('update').then(function(){
						// update products
						const products = Radio.request('entities', 'get', {
							type: 'collection',
							name: 'products'
						});
						products.fetchUpdated();
						self.btnLoading = false;
						self.render();
					}).always(function(){
						self.container.$el.find(".btn").trigger('state', 'reset');
					});
				});
				return;
			}
		}else if(this.order.get('status') === 'pending' && this.order.get('payment_details.result') === 'terminal_preprocess'){
			const self = this;
			const order_id = this.order.get('id');
			const terminal_id = this.order.get('payment_details.terminal_id');
			this.btnLoading = true;
			window.tfsw_stripe_terminal_form.customer_id = this.order.get('customer_id') !== 0 ? this.order.get('customer_id') : null;
			window.tfsw_stripe_terminal_form.customer_data = this.terminalFormat(this.order.get('billing'));
			window.tfsw_stripe_terminal_form.order_pay = true;
			window.tfsw_stripe_terminal_form.order_id = order_id;
			window.tfsw_stripe_terminal_form.continueInitTerminal(terminal_id).then(function(){
				self.order.set('payment_details.paid', true);
				self.order.set('payment_details.result', 'success');
				self.order.remoteSync('update').then(function(){
					// update products
					const products = Radio.request('entities', 'get', {
						type: 'collection',
						name: 'products'
					});
					products.fetchUpdated();
					self.btnLoading = false;
					self.render();
				}).always(function(){
					self.container.$el.find(".btn").trigger('state', 'reset');
				});
			}).fail(function(){
				self.render();
				self.container.$el.find(".btn").trigger('state', 'reset');
			});
		}
		// redirect, ie: offsite payment
		var redirect = this.order.get('payment_details.redirect');
		if(redirect && redirect !== ''){
			window.open(redirect, '_blank');
		}

		// update products
		var products = Radio.request('entities', 'get', {
			type: 'collection',
			name: 'products'
		});
		products.fetchUpdated();
	},

	render: function() {
		this.layout = new LayoutView({
			model: this.order
		});

		this.listenTo( this.layout, 'show', function() {
			this.showStatus();
			this.showItems();
			this.showTotals();
			this.showActions();
			if(this.autoPrint){
				this.print();
			}
		});

		this.container.show( this.layout );
		if(this.btnLoading){
			this.container.$el.find(".btn").trigger('state', 'loading');
		}
	},

	showStatus: function(){
		var view = new StatusView({
			model: this.order
		});
		this.layout.getRegion('status').show(view);
	},

	showItems: function(){
		var view = new ItemsView({
			model: this.order
		});

		this.layout.getRegion('list').show(view);
	},

	showTotals: function(){
		var view = new TotalsView({
			model: this.order
		});

		this.layout.getRegion('totals').show(view);
	},

	showActions: function(){
		var view = new Buttons({
			buttons: [{
				action: 'print',
				className: 'btn-primary pull-left'
			}, {
				action: 'email',
				className: 'btn-primary pull-left'
			}, {
				action: 'new-order',
				className: 'btn-success',
				icon: 'prepend'
			}]
		});

		this.listenTo(view, {
			'action:print': this.print,
			'action:email': this.email,
			'action:new-order': function(){
				this.navigate('', { trigger: true });
			}
		});

		this.layout.getRegion('actions').show(view);
	},

	print: function(){
		Radio.request('print', 'print', {
			template: 'receipt',
			model: this.order
		});
	},

	email: function(){
		var self = this;

		var view = new EmailView({
			order_id: this.order.get('id'),
			email: this.order.get('customer.email')
		});

		Radio.request('modal', 'open', view)
			.then(function(args){
				var buttons = args.view.getButtons();
				self.listenTo(buttons, 'action:send', function(btn, view){
					var email = args.view.getRegion('content').currentView.getEmail();
					self.send(btn, view, email);
				});
			});

	},

	// todo: refactor
	send: function(btn, view, email){
		var order_id = this.order.get('id'),
				ajaxurl = Radio.request('entities', 'get', {
					type: 'option',
					name: 'ajaxurl'
				});

		btn.trigger('state', [ 'loading', '' ]);

		function onSuccess(resp){
			if(resp.result === 'success'){
				btn.trigger('state', [ 'success', resp.message ]);
			} else {
				btn.trigger('state', [ 'error', resp.message ]);
			}
		}

		function onError(jqxhr){
			var message = null;
			if(jqxhr.responseJSON && jqxhr.responseJSON.errors){
				message = jqxhr.responseJSON.errors[0].message;
			}
			btn.trigger('state', ['error', message]);
		}

		$.getJSON( ajaxurl, {
			action: 'wc_pos_email_receipt',
			order_id: order_id,
			email : email
		})
		.done(onSuccess)
		.fail(onError);
	},

	terminalFormat: function(billing){
		return {
			billing_first_name: billing.first_name,
			billing_last_name: billing.last_name,
			billing_country: billing.country,
			billing_address_1: billing.address_1,
			billing_address_2: billing.address_2,
			billing_city: billing.city,
			billing_state: billing.state,
			billing_postcode: billing.postcode,
			billing_phone: billing.phone
		};
	}
});

module.exports = ReceiptRoute;
App.prototype.set('POSApp.Receipt.Route', ReceiptRoute);