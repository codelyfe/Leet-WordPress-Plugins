<?php
/*
Plugin Name: 1337 Upload Multiple Plugins
Plugin URI: https://codelyfe.github.io/
Description: A WordPress plugin that allows users to upload multiple plugins at once.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Add the plugin menu item
function wpmp_add_menu_item() {
  add_menu_page(
    'Upload Multiple Plugins',
    'Upload Plugins',
    'manage_options',
    'wpmp-menu',
    'wpmp_render_form'
  );
}
add_action('admin_menu', 'wpmp_add_menu_item');

// Render the plugin upload form
function wpmp_render_form() {
  // Check if plugin is activated
  if (!function_exists('wp_handle_upload')) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
  }

  // Check if upload directory is writable
  $upload_dir = wp_upload_dir();
  if (!is_writable($upload_dir['path'])) {
    echo '<div class="notice notice-error"><p>The upload directory is not writable. Please contact your system administrator.</p></div>';
    return;
  }

  // Handle form submission
  if (isset($_POST['submit']) && $_POST['submit'] == 'Upload') {
    $errors = array();

    // Check if any files were uploaded
    if (!isset($_FILES['zip_files'])) {
      $errors[] = 'Please select at least one file to upload.';
    } else {
      $zip_files = $_FILES['zip_files'];

      // Loop through each uploaded file
      for ($i = 0; $i < count($zip_files['name']); $i++) {
        $zip_file = array(
          'name'     => $zip_files['name'][$i],
          'type'     => $zip_files['type'][$i],
          'tmp_name' => $zip_files['tmp_name'][$i],
          'error'    => $zip_files['error'][$i],
          'size'     => $zip_files['size'][$i]
        );

        // Check if file is a zip file
        $file_type = wp_check_filetype($zip_file['name'], array('zip' => 'application/zip'));
        if ($file_type['ext'] != 'zip') {
          $errors[] = 'File "' . $zip_file['name'] . '" is not a valid zip file.';
          continue;
        }

        // Move the file to the upload directory
        $moved = move_uploaded_file($zip_file['tmp_name'], $upload_dir['path'] . '/' . $zip_file['name']);

        // Unzip the plugin files
        $unzipped = unzip_file($upload_dir['path'] . '/' . $zip_file['name'], $upload_dir['path']);

        // Check for errors
        if (is_wp_error($unzipped)) {
          $errors[] = 'Error unzipping file "' . $zip_file['name'] . '": ' . $unzipped->get_error_message();
        } else {
          echo '<div class="notice notice-success"><p>Files "';

          foreach ($unzipped as $file) {
            echo basename($file) . ', ';
          }

          echo '" were uploaded and installed successfully.</p></div>';
        }
      }
    }

    // Show errors
    if (count($errors) > 0) {
      echo '<div class="notice notice-error"><ul>';

      foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
      }

      echo '</ul></div>';
    }
  }
  ?>

  <div class="wrap">
    <h1>Upload Multiple Plugins</h1>

    <form method="post" enctype="multipart/form-data">
      <p>
        <input type="file" name="zip_files[]" multiple>
      </p>

      <p>
        <input type="submit" name="submit" value="Upload" class="button button-primary">
      </p>
    </form>
  </div>

  <?php
}