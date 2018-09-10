/**
 * Supra Custom Fields.
 *
 * @package Supra_Custom
 */

/* exported SupraCustomFields */
var SupraCustomFields = ( function( $, wp ) {
	'use strict';

	return {
		/**
		 * Holds data.
		 */
		data: {},

		/**
		 * Boot plugin.
		 */
		boot: function( data ) {
			this.data = data;

			$( document ).ready( function() {
				this.init();
			}.bind( this ) );
		},

		/**
		 * Initialize plugin.
		 */
		init: function() {
			this.$container = $( '.wp-admin' );
			this.listen();
		},

		/**
		 * Initiate listeners.
		 */
		listen: function() {
			var self = this,
				timer = '';

			this.$container.on( 'click', '.add-overlay-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-supra', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .supra-overlay-field:last-of-type' ).attr( 'data-num' );

				self.addOverlayField( count, section );
			} );

			// Add new wysiwyg field.
			this.$container.on( 'click', '.add-wysiwyg-repeater-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-supra', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .supra-wysiwyg-repeater-field:last-of-type' ).attr( 'data-num' );

				self.addWysiwygField( count, section );
			} );

			// Add new usecase tab field.
			this.$container.on( 'click', '.add-usecase-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-supra', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .supra-usecase-repeater-field:last-of-type' ).attr( 'data-num' );

				self.addUsecaseTabField( count, section );
			} );

			// Add new usecase field.
			this.$container.on( 'click', '.add-usecase-content-field', function() {
				var side = $( this ).attr( 'data-side' ),
					section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-supra', '' ),
					count = $( this ).closest( '.supra-usecase-repeater-field' ).attr( 'data-num' ),
					sideCount = $( this ).closest( '.' + side + '-repeater-section' ).find( '.supra-tab-content-overlay:last-of-type' ).attr( 'data-num' );

				self.addUsecaseField( count, section, sideCount, side );
			} );

			// Add new link repeater field.
			this.$container.on( 'click', '.add-link-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-supra', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .supra-link-field:last-of-type' ).attr( 'data-num' );

				self.addLinkField( count, section );
			} );

			// Remove overlay field from admin.
			this.$container.on( 'click', '.remove-overlay-field', function() {
				$( this ).parent( '.supra-overlay-field' ).remove();
			} );

			// Remove wysiwyg field from admin.
			this.$container.on( 'click', '.remove-wysiwyg-repeater-field', function() {
				$( this ).parent( '.supra-wysiwyg-repeater-field' ).remove();
			} );

			// Remove link field from admin.
			this.$container.on( 'click', '.remove-link-field', function() {
				$( this ).parent( '.supra-link-field' ).remove();
			} );

			// Remove tab field from admin.
			this.$container.on( 'click', '.remove-usecase-tab-field', function() {
				$( this ).parent( '.supra-usecase-repeater-field' ).remove();
			} );

			// Remove usecase field from admin.
			this.$container.on( 'click', '.remove-usecase-field', function() {
				$( this ).parent( '.supra-tab-content-overlay' ).remove();
			} );

			// Custom image field.
			this.$container.on( 'click', '.add-supra-image', function() {
				const self = this;

				window.send_to_editor = function( html ) {
					const imgurl = $( html ).attr('src');

					$( self ).siblings( 'input' ).val( imgurl );
					tb_remove();
				};

				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true' );

				return false;
			} );
		},

		/**
		 * Add new overlay field to section.
		 *
		 * @param count
		 * @param section
		 */
		addOverlayField: function( count, section ) {
			wp.ajax.post( 'get_overlay_field', {
				count: count,
				section: section,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newCount = parseInt( count ) + 1,
					theId = section + '_overlay-repeater_' + newCount;

				//  Add title and label.
				$( '#' + section + '-supra .inside .supra-overlay-field:last-of-type' ).after(
					'<div data-num="' + newCount + '" class="supra-overlay-field">' +
					'<button type="button" class="remove-overlay-field">-</button>' +
					'<label class="supra-admin-label">Overlay Repeater Title</label>' +
					'<input type="text" name="page-meta[' + section + '][overlay-repeater][' + newCount + '][title]" value="" size="60">' +
					'<label class="supra-admin-label">Overlay Repeater URL (Leave empty if overlay)</label>' +
					'<input type="text" name="page-meta[' + section + '][overlay-repeater][' + newCount + '][url]" value="" size="60">' +
					'<label class="supra-admin-label">Overlay Repeater Content</label>' +
					'</div>'
				);

				// Add new editor to the page.
				$( '#' + section + '-supra .inside .supra-overlay-field:last-of-type' ).append( results );

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new use case tab field to section.
		 *
		 * @param count
		 * @param section
		 */
		addUsecaseTabField: function( count, section ) {
			var self = this;

			wp.ajax.post( 'get_usecase_tab_field', {
				count: count,
				side: 'left',
				section: section,
				nonce: this.data.nonce
			} ).always( function( leftresults ) {
				var newCount = parseInt( count ) + 1,
					theLeftId = section + '_usecase-repeater_' + newCount + '_left_1_graph_content';

				$( '#' + section + '-supra .inside .supra-usecase-repeater-field:last-of-type' ). after(
					'<div data-num="' + newCount + '" class="supra-usecase-repeater-field">' +
					'<label class="supra-admin-label">Tab Name</label>' +
					'<button type="button" class="remove-tab-field">-</button>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][title]" value="" size="60">' +
					'<hr>' +
					'<div data-num="1" class="left-repeater-section">' +
					'<div class="side-title">Left Side</div>' +
					'<div data-num="' + newCount + '" data-side="left" class="supra-tab-content-overlay">' +
					'<button data-side="left" type="button" class="remove-usecase-field">-</button>' +
					'<label class="supra-admin-label">First Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-first]" value="">' +
					'<label class="supra-admin-label">First Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-first-text]" value="" size="60">' +
					'<label class="supra-admin-label">Second Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-second]" value="">' +
					'<label class="supra-admin-label">Second Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-second-text]" value="" size="60">' +
					'<label class="supra-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
					leftresults +
					'</div>' +
					'<button data-side="left" type="button" class="add-usecase-content-field">+</button>' +
					'</div>' +
					'<div data-num="1" class="right-repeater-section">' +
					'<div class="side-title">Right Side</div>' +
					'<div data-num="1" data-side="right" class="supra-tab-content-overlay">' +
					'<button data-side="right" type="button" class="remove-usecase-field">-</button>' +
					'<label class="supra-admin-label">First Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-first]" value="">' +
					'<label class="supra-admin-label">First Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-first-text]" value="" size="60">' +
					'<label class="supra-admin-label">Second Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-second]" value="">' +
					'<label class="supra-admin-label">Second Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-second-text]" value="" size="60">' +
					'<label class="supra-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
					'</div>' +
					'</div>' +
					'<button data-side="right" type="button" class="add-usecase-content-field">+</button>' +
					'</div>'
				);

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theLeftId);
				quicktags({id : theLeftId});

				wp.ajax.post( 'get_usecase_tab_field', {
					count: count,
					side: 'right',
					section: section,
					nonce: self.data.nonce
				} ).always( function( rightresults ) {
					var newCount2 = parseInt( count ) + 1,
						theRightId = section + '_usecase-repeater_' + newCount2 + '_right_1_graph_content';

					//  Add title and label.
					$( '#' + section + '-supra .inside .supra-usecase-repeater-field:last-of-type .right-repeater-section' ).append( rightresults );

					tinymce.execCommand('mceAddEditor', false, theRightId);
					quicktags({id : theRightId});
				} );
			} );
		},

		/**
		 * Add new use case field to section.
		 *
		 * @param count
		 * @param section
		 * @param sideCount
		 * @param side
		 */
		addUsecaseField: function( count, section, sideCount, side ) {
			var self = this;

			wp.ajax.post( 'get_usecase_field', {
				count: count,
				side: side,
				section: section,
				side_count: sideCount,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newSideCount = parseInt( sideCount ) + 1,
					theId = section + '_usecase-repeater_' + count + '_' + side + '_' + newSideCount + '_graph_content';

				$( '#' + section + '-supra .inside .supra-usecase-repeater-field[data-num="' + count + '"] .' + side + '-repeater-section .supra-tab-content-overlay:last-of-type' ). after(
                   '<div data-num="' + newSideCount + '" data-side="' + side + '" class="supra-tab-content-overlay">' +
                   '<button data-side="' + side + '" type="button" class="remove-usecase-field">-</button>' +
                   '<label class="supra-admin-label">First Graph Number</label>' +
                   '<input type="number" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-first]" value="">' +
                   '<label class="supra-admin-label">First Graph Text</label>' +
                   '<input type="text" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-first-text]" value="" size="60">' +
                   '<label class="supra-admin-label">Second Graph Number</label>' +
                   '<input type="number" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-second]" value="">' +
                   '<label class="supra-admin-label">Second Graph Text</label>' +
                   '<input type="text" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-second-text]" value="" size="60">' +
                   '<label class="supra-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
                   results +
                   '</div>'
				);

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new WYSIWYG field to section.
		 *
		 * @param count
		 * @param section
		 */
		addWysiwygField: function( count, section ) {
			wp.ajax.post( 'get_wysiwyg_field', {
				count: count,
				section: section,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newCount = parseInt( count ) + 1,
					theId = section + '_wysiwyg-repeater_' + newCount;

				//  Add title and label.
				$( '#' + section + '-supra .inside .supra-wysiwyg-repeater-field:last-of-type' ).after(
					'<div data-num="' + newCount + '" class="supra-wysiwyg-repeater-field">' +
					'<button type="button" class="remove-wysiwyg-repeater-field">-</button>' +
					'<label class="supra-admin-label">Locations Content</label>' +
					'</div>'
				);

				// Add new editor to the page.
				$( '#' + section + '-supra .inside .supra-wysiwyg-repeater-field:last-of-type' ).append( results );

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new link field to section.
		 *
		 * @param count
		 * @param section
		 */
		addLinkField: function( count, section ) {
			var newCount = parseInt( count ) + 1;

			//  Add title and label.
			$( '#' + section + '-supra .inside .supra-link-field:last-of-type' ).after(
				'<div data-num="' + newCount + '" class="supra-link-field">' +
				'<label class="supra-admin-label">Link repeater Title</label>' +
				'<input type="text" name="page-meta[' + section + '][link-repeater][' + newCount + '][title]" value="" size="60">' +
				'<label class="supra-admin-label">Link repeater URL</label>' +
				'<input type="text" name="page-meta[' + section + '][link-repeater][' + newCount + '][url]" value="" size="60">' +
				'</div>'
			);

			// Add new editor to the page.
			$( '#' + section + '-supra .inside .supra-link-field:last-of-type' ).append( results );
		}
	};
} )( window.jQuery, window.wp );
