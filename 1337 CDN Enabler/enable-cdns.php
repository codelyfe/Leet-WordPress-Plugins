<?php
/*
Plugin Name: 1337 CDN Enabler
Plugin URI: https://codelyfe.github.io  
Description: Allows users to enable or disable 22 predefined CDNs
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io
License: GPL2
*/

// Register an options page to manage the enabled CDNs
function cdn_enabler_options_page() {
  // Add the options page to the Settings > CDN Enabler menu
  add_options_page(
    'Hosted CDN Enabler',            // Page title
    'Hosted CDN Enabler',            // Menu title
    'manage_options',         // Capability required to access the page
    'cdn-enabler-options',    // Unique ID of the page
    'cdn_enabler_options'     // Callback function to display the page content
  );
}
add_action( 'admin_menu', 'cdn_enabler_options_page' );

// Define the list of CDNs and their URLs
function get_cdn_list() {
  return array(
    'jquery' => 'https://code.jquery.com/jquery-3.6.0.min.js',
    'font-awesome' => 'https://use.fontawesome.com/releases/v5.8.1/css/all.css',
    'font-awesome1' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css',
    'cdnjs' => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js',
    'animate' => 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
    'react' => 'https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js',
    'threejs' => 'https://cdnjs.cloudflare.com/ajax/libs/three.js/0.150.1/three.min.js',
    'typescript' => 'https://cdnjs.cloudflare.com/ajax/libs/typescript/5.0.2/typescript.min.js',
    'materialui' => 'https://cdnjs.cloudflare.com/ajax/libs/material-ui/4.12.4/index.min.js',
    'revealjs' => 'https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.4.0/reveal.min.js',
    'revealcss' => 'https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.4.0/reveal.min.css',
    'pdfjs' => 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js',
    'elementui' => 'https://cdnjs.cloudflare.com/ajax/libs/element-ui/2.15.13/index.js',
    'socketio' => 'https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.6.1/socket.io.js',
    'angularanimate' => 'https://cdnjs.cloudflare.com/ajax/libs/angular-animate/1.8.3/angular-animate.min.js',
    'videojs' => 'https://cdnjs.cloudflare.com/ajax/libs/video.js/8.2.1/video.min.js',
    'phaser' => 'https://cdnjs.cloudflare.com/ajax/libs/phaser/3.55.2/phaser.min.js',
    'impress' => 'https://cdnjs.cloudflare.com/ajax/libs/impress.js/0.5.3/impress.min.js',
    'foundation' => 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.7.5/js/foundation.min.js',
    'popperjs' => 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.7/umd/popper.min.js',
    'feathericon' => 'https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js',
    
    
    // Add more CDNs here... '' => '',
  );
}

// Display the options page
function cdn_enabler_options() {
  // Get the current list of enabled CDNs
  $enabled_cdns = get_option( 'cdn_enabler_enabled_cdns', array() );

  // If the form has been submitted, process the data
  if ( isset( $_POST['submit'] ) ) {
    // Save the new list of enabled CDNs
    $enabled_cdns = array();
    foreach ( get_cdn_list() as $cdn => $url ) {
      if ( isset( $_POST["cdn-$cdn"] ) && $_POST["cdn-$cdn"] == 'on' ) {
        $enabled_cdns[] = $cdn;
      }
    }
    update_option( 'cdn_enabler_enabled_cdns', $enabled_cdns );

    // Display a success message
    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>CDNs have been updated.</p>';
    echo '</div>';
  }

  // Display the form to enable or disable the CDNs
  ?>
  <div class="wrap">
    <h1>Hosted CDN Enabler</h1>
    <form method="post">
      <table class="form-table">
        <?php foreach ( get_cdn_list() as $cdn => $url ) { ?>
          <tr>
            <th scope="row">
              <label><u><?php echo esc_html( $cdn ); ?></u>:</label>
              <br/>
              <label><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></label>
            </th>
            <td>
              <label>
                <input type="checkbox" name="cdn-<?php echo esc_attr( $cdn ); ?>" <?php checked( in_array( $cdn, $enabled_cdns ) ); ?>>
                Enable CDN
              </label>
            </td>
          </tr>
        <?php } ?>
      </table>
      <?php submit_button( 'Save Changes' ); ?>
    </form>
  </div>
  <?php
}

// Enqueue the selected CDNs on the front-end
function enqueue_selected_cdns() {
  // Get the current list of enabled CDNs
  $enabled_cdns = get_option( 'cdn_enabler_enabled_cdns', array() );

  // Enqueue the selected CDNs
  foreach ( get_cdn_list() as $cdn => $url ) {
    if ( in_array( $cdn, $enabled_cdns ) ) {
      switch ( $cdn ) {
        case 'jquery':
          wp_enqueue_script( 'jquery', $url, array(), null, true );
          break;
        case 'font-awesome':
          wp_enqueue_style( 'font-awesome', $url, array(), null );
          break;
        case 'font-awesome1':
          wp_enqueue_style( 'font-awesome', $url, array(), null );
          break;
        case 'cdnjs':
          wp_enqueue_script( 'chartjs', $url, array(), null, true );
          break;
        case 'animate':
          wp_enqueue_script( 'animate', $url, array(), null, true );
          break;
        case 'react':
          wp_enqueue_script( 'react', $url, array(), null, true );
          break;
        case 'threejs':
          wp_enqueue_script( 'threejs', $url, array(), null, true );
          break;
        case 'typescript':
          wp_enqueue_script( 'typescript', $url, array(), null, true );
          break;
        case 'materialui':
          wp_enqueue_script( 'materialui', $url, array(), null, true );
          break;
        case 'revealjs':
          wp_enqueue_script( 'revealjs', $url, array(), null, true );
          break;
        case 'revealcss':
          wp_enqueue_script( 'revealcss', $url, array(), null, true );
          break;
        case 'pdfjs':
          wp_enqueue_script( 'pdfjs', $url, array(), null, true );
          break;
        case 'elementui':
          wp_enqueue_script( 'elementui', $url, array(), null, true );
          break;
       case 'socketio':
          wp_enqueue_script( 'socketio', $url, array(), null, true );
          break;
        case 'angularanimate':
          wp_enqueue_script( 'angularanimate', $url, array(), null, true );
          break;
        case 'videojs':
          wp_enqueue_script( 'videojs', $url, array(), null, true );
          break;
        case 'phaser':
          wp_enqueue_script( 'phaser', $url, array(), null, true );
          break;
        case 'impress':
          wp_enqueue_script( 'impress', $url, array(), null, true );
          break;          
        case 'foundation':
          wp_enqueue_script( 'foundation', $url, array(), null, true );
          break;      
        case 'popperjs':
          wp_enqueue_script( 'popperjs', $url, array(), null, true );
          break;  
        case 'feathericon':
          wp_enqueue_script( 'eathericon', $url, array(), null, true );
          break;   


          
        // Add cases for other CDNs here...
      }
    }
  }
}
add_action( 'wp_enqueue_scripts', 'enqueue_selected_cdns' );