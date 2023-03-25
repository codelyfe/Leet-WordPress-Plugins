<?php
/*
Plugin Name: 1337 DEV NOTES
Plugin URI: https://codelyfe.github.io/
Description: A simple plugin that lets you save notes and display them using AJAX.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function my_notes_plugin_display() {
	?>
	<div class="wrap">
		<h1><u><?php echo esc_html( get_admin_page_title() ); ?></u></h1>
        <h3>Please provide notes for the next developer.</h3>
        <br/>
		<form id="my-notes-form" method="post">
			<label for="note-date"><?php esc_html_e( 'Date:', 'my_notes_plugin' ); ?></label>
			<br/><br/>
            <input type="date" id="note-date" name="note_date" required />
			<br /><br/>
			<label for="note-title"><?php esc_html_e( 'Title:', 'my_notes_plugin' ); ?></label>
			<br/><br/>
            <input type="text" id="note-title" name="note_title" required />
			<br /><br/>
			<label for="note-text"><?php esc_html_e( 'Note:', 'my_notes_plugin' ); ?></label>
			<br/><br/>
            <textarea id="note-text" name="note_text" rows="5" required></textarea>
			<br />
			<input type="button" id="add-note" class="button button-primary" value="<?php esc_html_e( 'Add Note', 'my_notes_plugin' ); ?>" />
			<?php wp_nonce_field( 'my-notes-plugin' ); ?>
		</form>
		<div id="notes-list">
            <br/>
            <hr/>
			<h3><?php esc_html_e( 'Saved Notes', 'my_notes_plugin' ); ?></h3>
            <hr/>
            <br/>
			<ul>
				<?php
				$file_path = plugin_dir_path( __FILE__ ) . 'notes.txt';
				if ( file_exists( $file_path ) ) {
					$file_content = file_get_contents( $file_path );
					$notes        = explode( "\n", $file_content );
					foreach ( $notes as $note ) {
						if ( empty( $note ) ) {
							continue;
						}
						list( $date, $title, $text ) = explode( ':', $note );
						$date  = trim( $date );
						$title = trim( $title );
						$text  = trim( $text );
						echo "<li><strong>$title</strong><p>$text</p><small><em>Added on $date</em></small></li><br/><hr/>";
					}
				}
				?>
			</ul>
		</div>
	</div>
	<?php
}

add_action( 'admin_menu', 'my_notes_plugin_menu' );

function my_notes_plugin_menu() {
	add_menu_page(
		'1337 DEV NOTES',
		'1337 DEV NOTES',
		'manage_options',
		'my-notes-plugin',
		'my_notes_plugin_display'
	);
}

function my_notes_plugin_save_note() {
	if ( ! isset( $_POST['note_date'] ) || ! isset( $_POST['note_title'] ) || ! isset( $_POST['note_text'] ) ) {
		return;
	}

	check_admin_referer( 'my-notes-plugin' );

	$date      = sanitize_text_field( $_POST['note_date'] );
	$title     = sanitize_text_field( $_POST['note_title'] );
	$text      = sanitize_textarea_field( $_POST['note_text'] );
	$file_path = plugin_dir_path( __FILE__ ) . 'notes.txt';

	$note = $date . ': ' . $title . ': ' . $text . "\n";

	if ( file_exists( $file_path ) ) {
		$file_content = file_get_contents( $file_path );
	} else {
		$file_content = '';
	}

	file_put_contents( $file_path, $file_content . $note );

	wp_die();
}
add_action( 'wp_ajax_my_notes_plugin_save_note', 'my_notes_plugin_save_note' );

function my_notes_plugin_enqueue_scripts() {
	wp_enqueue_script( 'my-notes-plugin', plugin_dir_url( __FILE__ ) . 'my-notes.js', array( 'jquery' ), '1.0', true );

	wp_localize_script( 'my-notes-plugin', 'myNotesAjax', array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'my-notes-plugin' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'my_notes_plugin_enqueue_scripts' );

function my_notes_plugin_load_notes() {
	check_admin_referer( 'my-notes-plugin' );
	$file_path = plugin_dir_path( __FILE__ ) . 'notes.txt';
	if ( file_exists( $file_path ) ) {
		$file_content = file_get_contents( $file_path );
		$notes        = explode( "\n", $file_content );
		foreach ( $notes as $note ) {
			if ( empty( $note ) ) {
				continue;
			}
			list( $date, $title, $text ) = explode( ':', $note );
			$date  = trim( $date );
			$title = trim( $title );
			$text  = trim( $text );
			echo "<li><strong>$title</strong><p>$text</p><small><em>Added on $date</em></small></li><br/><hr/>";
		}
	}
	wp_die();
}
add_action( 'wp_ajax_my_notes_plugin_load_notes', 'my_notes_plugin_load_notes' );
// CODƎL¥FƎ