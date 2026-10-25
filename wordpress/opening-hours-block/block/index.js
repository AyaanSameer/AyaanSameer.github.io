/**
 * Opening Hours — the editor side.
 *
 * Plain JavaScript on WordPress's own globals, so the plugin needs no build
 * step: copy the folder into wp-content/plugins and it works. The preview in
 * the editor is the real server render, so what the owner sees is exactly
 * what visitors get, status badge included.
 */
( function ( blocks, element, blockEditor, components, i18n, ServerSideRender ) {
	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var ToggleControl = components.ToggleControl;
	var TextControl = components.TextControl;

	var DAYS = [
		__( 'Sunday', 'opening-hours-block' ),
		__( 'Monday', 'opening-hours-block' ),
		__( 'Tuesday', 'opening-hours-block' ),
		__( 'Wednesday', 'opening-hours-block' ),
		__( 'Thursday', 'opening-hours-block' ),
		__( 'Friday', 'opening-hours-block' ),
		__( 'Saturday', 'opening-hours-block' ),
	];

	var start = ( window.ohbSettings && window.ohbSettings.startOfWeek ) || 0;
	var ORDER = [ 0, 1, 2, 3, 4, 5, 6 ].map( function ( i ) {
		return ( start + i ) % 7;
	} );

	function DayControls( props ) {
		var day = props.day;
		return el(
			'div',
			{ className: 'ohb-editor-day' },
			el( ToggleControl, {
				label: DAYS[ props.index ],
				help: day.closed ? __( 'Closed all day', 'opening-hours-block' ) : null,
				checked: ! day.closed,
				onChange: function ( isOpen ) {
					props.onChange( { closed: ! isOpen } );
				},
				__nextHasNoMarginBottom: true,
			} ),
			! day.closed &&
				el(
					'div',
					{ className: 'ohb-editor-times' },
					el( TextControl, {
						type: 'time',
						label: __( 'Opens', 'opening-hours-block' ),
						value: day.open,
						onChange: function ( value ) {
							props.onChange( { open: value } );
						},
						__nextHasNoMarginBottom: true,
					} ),
					el( TextControl, {
						type: 'time',
						label: __( 'Closes', 'opening-hours-block' ),
						value: day.close,
						onChange: function ( value ) {
							props.onChange( { close: value } );
						},
						__nextHasNoMarginBottom: true,
					} )
				)
		);
	}

	blocks.registerBlockType( 'ayaan/opening-hours', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			function updateDay( index, patch ) {
				var hours = attributes.hours.map( function ( day, i ) {
					return i === index ? Object.assign( {}, day, patch ) : day;
				} );
				setAttributes( { hours: hours } );
			}

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Display', 'opening-hours-block' ) },
						el( ToggleControl, {
							label: __( 'Show “Open now” badge', 'opening-hours-block' ),
							checked: attributes.showStatus,
							onChange: function ( value ) {
								setAttributes( { showStatus: value } );
							},
							__nextHasNoMarginBottom: true,
						} ),
						el( ToggleControl, {
							label: __( 'Highlight today', 'opening-hours-block' ),
							checked: attributes.highlightToday,
							onChange: function ( value ) {
								setAttributes( { highlightToday: value } );
							},
							__nextHasNoMarginBottom: true,
						} ),
						el( TextControl, {
							label: __( 'Note under the hours', 'opening-hours-block' ),
							placeholder: __( 'e.g. Hours may change during Ramadan.', 'opening-hours-block' ),
							value: attributes.note,
							onChange: function ( value ) {
								setAttributes( { note: value } );
							},
							__nextHasNoMarginBottom: true,
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Weekly hours', 'opening-hours-block' ) },
						el(
							'p',
							{ className: 'ohb-editor-hint' },
							__( 'A closing time earlier than the opening time means after midnight, e.g. 1:00 pm – 12:30 am.', 'opening-hours-block' )
						),
						ORDER.map( function ( index ) {
							return el( DayControls, {
								key: index,
								index: index,
								day: attributes.hours[ index ] || { open: '09:00', close: '17:00', closed: true },
								onChange: function ( patch ) {
									updateDay( index, patch );
								},
							} );
						} )
					)
				),
				el(
					'div',
					useBlockProps(),
					el( ServerSideRender, { block: 'ayaan/opening-hours', attributes: attributes } )
				)
			);
		},

		// Rendered on the server every time, so the badge is never stale.
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n, window.wp.serverSideRender );
