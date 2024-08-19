jQuery(document).ready(function ($) {
	// Field validation
	$(document.body).on(
		'keyup',
		'#_custom_subscription_discount_rate[type=number]',
		function () {

			var error = 'i18n_common_error';
			var value = $( this ).val();

			if( ! isNaN(value) ){
				$( document.body ).triggerHandler( 'cs_add_error_tip', [
					$( this ),
					error,
				] );
			}else {
				$(
					document.body
				).triggerHandler( 'cs_remove_error_tip', [
					$( this ),
					error,
				] );
			}
		}
	)

	// Field validation
	$(document.body).on(
		'keyup',
		'#_custom_subscription_estimated_subscription[type=number]',
		function () {

			var error = 'i18n_common_error';
			var value = $( this ).val();


			if( value !== parseInt(value) ){
				$( document.body ).triggerHandler( 'cs_add_error_tip', [
					$( this ),
					error,
				] );
			}else {
				$(
					document.body
				).triggerHandler( 'cs_remove_error_tip', [
					$( this ),
					error,
				] );
			}
		}
	)


	$(document.body).on(
		'change',
		'#_subscription_duration[type=select]',
		function () {

			var error = 'i18n_common_error';
			var value = $( this ).val();

			if( value !== parseInt(value) ){
				$( document.body ).triggerHandler( 'cs_add_error_tip', [
					$( this ),
					error,
				] );
			}else {
				$(
					document.body
				).triggerHandler( 'cs_remove_error_tip', [
					$( this ),
					error,
				] );
			}
		}
	)

	// Field validation error tips
	$(document.body).on( 'cs_add_error_tip', function ( e, element, error_type ) {
		var offset = element.position();

		if ( element.parent().find( '.wc_error_tip' ).length === 0 ) {
			element.after(
				'<div class="wc_error_tip ' +
					error_type +
					'">' +
					cs_admin[ error_type ] +
					'</div>'
			);
			element
				.parent()
				.find( '.wc_error_tip' )
				.css(
					'left',
					offset.left +
						element.width() -
						element.width() / 2 -
						$( '.wc_error_tip' ).width() / 2
				)
				.css( 'top', offset.top + element.height() )
				.fadeIn( '100' );
		}
	} )

	// Remove error tips.
	$(document.body).on( 'cs_remove_error_tip', function ( e, element, error_type ) {
		element
			.parent()
			.find( '.wc_error_tip.' + error_type )
			.fadeOut( '100', function () {
				$( this ).remove();
			} );
	} )


});

