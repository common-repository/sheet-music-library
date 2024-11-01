/**
 * Sheet music library admin script. Primarily handles file upload UI.
 */

var sml = {};
( function( $ ) {
	sml = {
		container: '',
		frames: {
			score: '',
			parts: '',
			audio: ''
		},
		settings: sheetMusicOptions || {},

		init: function() {
			sml.container = $( '#sml_piece_files' );
			sml.initFrames();

			// Bind events, with delegation to facilitate re-rendering.
			sml.container.on( 'click', '#score-upload', sml.openScoreFrame );
			sml.container.on( 'click', '#score-image', sml.openScoreFrame );
			sml.container.on( 'click', '#parts-upload', sml.openPartsFrame );
			sml.container.on( 'click', '#audio-upload', sml.openAudioFrame );
			sml.container.on( 'click', '#score-attachment .deletion', sml.removeScore );
			sml.container.on( 'click', '#parts-attachment .deletion', sml.removeParts );
			sml.container.on( 'click', '#audio-attachment .deletion', sml.removeAudio );
			sml.container.on( 'input focus', '#piece-video-url', sml.youtubeEmbed );

			sml.initAudioPreview();
			sml.youtubeEmbed();
		},

		// Actions common to all frames.
		preOpenFrame: function ( event ) {
			event.preventDefault();

			if ( ! sml.frames.score ) {
				sml.initFrames();
			}
		},

		/**
		 * Open the score media modal.
		 */
		openScoreFrame: function( event ) {
			sml.preOpenFrame( event );
			sml.frames.score.open();
		},

		/**
		 * Open the parts media modal.
		 */
		openPartsFrame: function( event ) {
			sml.preOpenFrame( event );
			sml.frames.parts.open();
		},

		/**
		 * Open the score media modal.
		 */
		openAudioFrame: function( event ) {
			sml.preOpenFrame( event );
			sml.frames.audio.open();
		},

		/**
		 * Create a media modal select frame, and store it so the instance can be reused when needed.
		 */
		initFrames: function() {
			sml.frames.score = wp.media({
				button: {
					text: sml.settings.l10n.select
				},
				states: [
					new wp.media.controller.Library({
						title:     sml.settings.l10n.scorePdf,
						library:   wp.media.query({ type: 'application/pdf' }),
						multiple:  false,
						date:      false
					})
				]
			});

			sml.frames.parts = wp.media({
				button: {
					text: sml.settings.l10n.select
				},
				states: [
					new wp.media.controller.Library({
						title:     sml.settings.l10n.partsPdf,
						library:   wp.media.query({ type: 'application/pdf' }),
						multiple:  false,
						date:      false
					})
				]
			});

			sml.frames.audio = wp.media({
				button: {
					text: sml.settings.l10n.select
				},
				states: [
					new wp.media.controller.Library({
						title:     sml.settings.l10n.recordingAudio,
						library:   wp.media.query({ type: 'audio' }),
						multiple:  false,
						date:      false
					})
				]
			});

			// When a file is selected, run a callback.
			sml.frames.score.on( 'select', sml.selectScore );
			sml.frames.parts.on( 'select', sml.selectParts );
			sml.frames.audio.on( 'select', sml.selectAudio );
		},

		/**
		 * Callback handler for when an attachment is selected in the media modal.
		 * Gets the selected attachment information, and sets it within the control.
		 */
		selectScore: function() {
			// Get the attachment from the modal frame.
			var attachment = sml.frames.score.state().get( 'selection' ).first().toJSON();
			$( '#score-attachment-id' ).val( attachment.id );
			$( '#score-attachment-title' ).text( attachment.title );
			$( '#score-attachment-link' ).attr( 'href', attachment.url );
			$( '#score-attachment' ).removeClass( 'empty' );

			// Show a preview of the score PDF in the admin panel.
			$.post( ajaxurl, { 
					attachment_ID: attachment.id,
					action: 'sml-get-score-image-url' 
				},
				function ( response ) {
					if ( response ) {
						$( '#score-image' ).attr( 'src', response );
					}
				}
			);
		},

		/**
		 * Callback handler for when an attachment is selected in the media modal.
		 * Gets the selected attachment information, and sets it within the control.
		 */
		selectParts: function() {
			// Get the attachment from the modal frame.
			var attachment = sml.frames.parts.state().get( 'selection' ).first().toJSON();
			$( '#parts-attachment-id' ).val( attachment.id );
			$( '#parts-attachment-title' ).text( attachment.title );
			$( '#parts-attachment-link' ).attr( 'href', attachment.url );
			$( '#parts-attachment' ).removeClass( 'empty' );
		},

		/**
		 * Callback handler for when an attachment is selected in the media modal.
		 * Gets the selected attachment information, and sets it within the control.
		 */
		selectAudio: function() {
			// Get the attachment from the modal frame.
			var attachment = sml.frames.audio.state().get( 'selection' ).first().toJSON();
			$( '#audio-attachment-id' ).val( attachment.id );
			$( '#audio-attachment-title' ).text( attachment.title );
			$( '#audio-attachment' ).removeClass( 'empty' );
			sml.embedAudioPreview( attachment );
		},

		/**
		 * Callback handler for when an attachment is removed.
		 */
		removeScore: function() {
			$( '#score-attachment-id' ).val( '' );
			$( '#score-attachment-title' ).text( '' );
			$( '#score-attachment' ).addClass( 'empty' );
			$( '#score-image' ).attr( 'src', '' );
		},
		/**
		 * Callback handler for when an attachment is removed.
		 */
		removeParts: function() {
			$( '#parts-attachment-id' ).val( '' );
			$( '#parts-attachment-title' ).text( '' );
			$( '#parts-attachment' ).addClass( 'empty' );
		},

		/**
		 * Callback handler for when an attachment is removed.
		 */
		removeAudio: function() {
			$( '#audio-attachment-id' ).val( '' );
			$( '#audio-attachment-title' ).text( '' );
			$( '#audio-attachment' ).addClass( 'empty' );
			$( '#audio-preview-container' ).contents().html( '' );
		},

		// Load the mediaelement audio preview.
		// Implementation is similar to wpviews, so this happens in an iframe.
		embedAudioPreview: function( attachment ) {
			var shortcodeOptions = {
				tag: 'audio',
				attrs: {
					src: attachment.url
				},
				type: 'self-closing',
				content: ''
			}
			wp.ajax.send( 'parse-media-shortcode', {
				data: {
					post_ID: attachment.id,
					type: 'audio',
					shortcode: wp.shortcode.string( shortcodeOptions )
				}
			} )
			.done( function( response ) {
				if ( response ) {
					// Hack in some CSS, then load it from an iframe.
					var width = $( '#audio-preview-container' ).width() - 100;
					$( '#audio-preview-container' ).contents().find('body').css({ 'margin': 0, 'width': width, 'overflow': 'hidden' });

					$( '#audio-preview-container' ).contents().find('head').html( response.head );
					$( '#audio-preview-container' ).contents().find('body').html( response.body );
					$( '#audio-preview-container' ).contents().find('video,audio').css( 'width', width - 20 )
																				  .mediaelementplayer( _wpmejsSettings );
				} else {
					$( '#audio-preview-container' ).contents().html( 'Error loading preview.' );
				}
			} )
			.fail( function( response ) {
				$( '#audio-preview-container' ).contents().html( 'Error loading preview.' );
			} );
		},

		initAudioPreview: function() {
			var attachment = initialAudioAttachment;
			if ( attachment ) {
				sml.embedAudioPreview( attachment );
			}
		},

		// @todo currently unused due to interference between this and the youtube preview.
		audioEmbed: function( attachment ) {
			wp.ajax.send( 'parse-embed', {
				data : {
					post_ID: wp.media.view.settings.post.id,
					shortcode: '[audio src="' + attachment.url + '"][/audio]'
				}
			} ).done( function( response ) {
				var html = ( response && response.body ) || '';
				$('#audio-embed-preview').html( html );
			} );
		},

		youtubeEmbed: function() {
			wp.ajax.send( 'parse-embed', {
				data : {
					post_ID: wp.media.view.settings.post.id,
					shortcode: '[video src="' + $('#piece-video-url').val() + '"][/video]'
				}
			} ).done( function( response ) {
				var html = ( response && response.body ) || '';
				$('#video-embed-preview').html( html );
			} );
		}
	}

	$(document).ready( function() { sml.init(); } );

} )( jQuery );