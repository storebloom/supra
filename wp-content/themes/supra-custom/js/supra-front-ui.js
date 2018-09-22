/**
 * Supra Front UI.
 *
 * @package Supra_Custom
 */

/* exported SupraFrontUI */
var SupraFrontUI = ( function( $, wp ) {
	'use strict';

	return {
		/**
		 * Holds data.
		 */
		data: {},

		/**
		 * Boot plugin.
		 */
		boot: function ( data ) {
			this.data = data;

			$( document ).ready( function () {
				this.init();
			}.bind( this ) );
		},

		/**
		 * Initialize plugin.
		 */
		init: function () {
			this.$pageContainer = $( 'body.page' );
			this.listen();

			if ( 'Home' === this.data.page ) {
				this.makeSquare( '.three-image-item' );
			}
		},

		/**
		 * Initiate listeners.
		 */
		listen: function () {
			var self = this,
				timer = '';

			this.$pageContainer.on( 'click', 'input[name="radio-286"]', function() {
				var theType = $( this ).val(),
					type = theType.toLowerCase();

				$( 'input:radio[name="radio-286"]' ).filter( '[value=' + theType + ']' ).prop( 'checked', true );
				$( '.rr-form-wrap' ).fadeOut();
				$( '#' + type ).fadeIn();
			} );
		},

		/**
		 * Make div into square.
		 *
		 * @param target
		 */
		makeSquare: function( target ) {
			var width = $( target ).outerWidth();

			$( target ).css( 'height', width + 'px' );
		}

	};
} )( window.jQuery, window.wp );
