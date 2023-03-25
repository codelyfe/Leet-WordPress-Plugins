jQuery( document ).ready( function( $ ) {
	$( '#add-note' ).click( function() {
		var data = {
			action: 'my_notes_plugin_save_note',
			note_date: $( '#note-date' ).val(),
			note_title: $( '#note-title' ).val(),
			note_text: $( '#note-text' ).val(),
			_wpnonce: $( '#_wpnonce' ).val(),
		};
		$.post( myNotesAjax.ajaxUrl, data, function() {
			$( '#note-date' ).val( '' );
			$( '#note-title' ).val( '' );
			$( '#note-text' ).val( '' );
			load_notes();
		});
	});
	
	function load_notes() {
		var data = {
			action: 'my_notes_plugin_load_notes',
			_wpnonce: myNotesAjax.nonce,
		};
		$.post( myNotesAjax.ajaxUrl, data, function( response ) {
			$( '#notes-list ul' ).html( response );
		});
	}
	
	load_notes();
});
