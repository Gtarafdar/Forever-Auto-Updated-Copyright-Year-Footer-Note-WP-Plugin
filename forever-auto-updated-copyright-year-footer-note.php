<?php
/*
Plugin Name: Forever Auto Updated Copyright Year Footer Note
Description: It will set automated current year allows you to customize the footer text color, size, alignment, copyright text, background Color and font styling.
Version: 1.0
Author: Gtarafdar
*/

class Forever_Auto_Updated_Copyright_Year_Footer_Note {

    // Enqueue plugin styles
    public function enqueue_styles() {
        wp_enqueue_style( 'custom-footer-style', plugins_url( '/css/custom-footer.css', __FILE__ ) );
    }

    // Add settings page
    public function add_settings_page() {
        add_options_page( 'Footer Copyright Text Settings', 'Footer Copyright Text Settings', 'manage_options', 'footer-settings', array( $this, 'render_settings_page' ) );
    }

    // Render settings page
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h2>Footer Copyright Text Settings Page</h2>
            <p>Copyright Text Settings</p>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'footer-settings-group' );
                do_settings_sections( 'footer-settings-group' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // Register settings and fields with validation and sanitization
    public function register_settings() {
        register_setting( 'footer-settings-group', 'footer_text_color', array( $this, 'sanitize_hex_color' ) );
        register_setting( 'footer-settings-group', 'footer_text_size', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_alignment', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_padding_top', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_padding_right', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_padding_bottom', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_padding_left', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_padding_unit', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_margin_top', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_margin_right', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_margin_bottom', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_margin_left', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_margin_unit', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_copyright_text', 'sanitize_text_field' );
        register_setting( 'footer-settings-group', 'footer_text_italic', 'absint' ); // Sanitize as integer
        register_setting( 'footer-settings-group', 'footer_text_bold', 'absint' ); // Sanitize as integer
        register_setting( 'footer-settings-group', 'footer_copyright_background_color', array( $this, 'sanitize_hex_color' ) );

        add_settings_section( 'footer-settings-section', 'Copyright Text Settings', array( $this, 'settings_section_callback' ), 'footer-settings-group' );

        add_settings_field( 'footer_text_color', 'Copyright Text Color', array( $this, 'footer_text_color_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_size', 'Copyright Text Size', array( $this, 'footer_text_size_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_alignment', 'Copyright Text Alignment', array( $this, 'footer_text_alignment_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_padding', 'Padding', array( $this, 'footer_text_padding_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_margin', 'Margin', array( $this, 'footer_text_margin_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_copyright_text', 'Copyright Text', array( $this, 'footer_copyright_text_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_italic', 'Italic', array( $this, 'footer_text_italic_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_text_bold', 'Bold', array( $this, 'footer_text_bold_callback' ), 'footer-settings-group', 'footer-settings-section' );
        add_settings_field( 'footer_copyright_background_color', 'Background Color', array( $this, 'footer_copyright_background_color_callback' ), 'footer-settings-group', 'footer-settings-section' );
    }

    // Callback functions for settings fields
    public function footer_text_color_callback() {
        $color = get_option( 'footer_text_color', '#000000' );
        echo "<input type='color' name='footer_text_color' value='$color' />";
        echo wp_nonce_field( 'footer_text_color', 'footer_text_color_nonce' );
    }
        public function footer_copyright_background_color_callback() {
        $bg_color = get_option( 'footer_copyright_background_color', '#ffffff' );
        echo "<input type='color' name='footer_copyright_background_color' value='$bg_color' />";
    }

    public function footer_text_size_callback() {
        $size = get_option( 'footer_text_size', '14px' );
        echo "<input type='text' name='footer_text_size' value='$size' />";
        echo wp_nonce_field( 'footer_text_size', 'footer_text_size_nonce' );
    }

    public function footer_text_alignment_callback() {
        $alignment = get_option( 'footer_text_alignment', 'left' );
        echo "<select name='footer_text_alignment'>
                <option value='left' " . selected( $alignment, 'left', false ) . ">Left</option>
                <option value='center' " . selected( $alignment, 'center', false ) . ">Center</option>
                <option value='right' " . selected( $alignment, 'right', false ) . ">Right</option>
              </select>";
               echo wp_nonce_field( 'footer_text_alignment', 'footer_text_alignment_nonce' );
    }

    public function footer_text_padding_callback() {
        $padding_top = get_option( 'footer_text_padding_top', '' );
        $padding_right = get_option( 'footer_text_padding_right', '' );
        $padding_bottom = get_option( 'footer_text_padding_bottom', '' );
        $padding_left = get_option( 'footer_text_padding_left', '' );
        $padding_unit = get_option( 'footer_text_padding_unit', 'px' );

        echo '<div class="padding-inputs">';
        echo '<input type="text" name="footer_text_padding_top" value="' . esc_attr( $padding_top ) . '" placeholder="Top" maxlength="4" style="margin: 2px;" />';
        echo '<input type="text" name="footer_text_padding_right" value="' . esc_attr( $padding_right ) . '" placeholder="Right" maxlength="4" style="margin: 2px;" />';
        echo '</div>';
        echo '<div class="padding-inputs">';
        echo '<input type="text" name="footer_text_padding_bottom" value="' . esc_attr( $padding_bottom ) . '" placeholder="Bottom" maxlength="4" style="margin: 2px; margin-top: 10px;" />';
        echo '<input type="text" name="footer_text_padding_left" value="' . esc_attr( $padding_left ) . '" placeholder="Left" maxlength="4" style="margin: 2px; margin-top: 10px;" />';
        echo '</div>';
               echo '<select name="footer_text_padding_unit" style="margin: 2px; margin-top: 10px;">';
        echo '<option value="px" ' . selected( $padding_unit, 'px', false ) . '>px</option>';
        echo '<option value="%" ' . selected( $padding_unit, '%', false ) . '>%</option>';
        echo '<option value="rem" ' . selected( $padding_unit, 'rem', false ) . '>rem</option>';
        echo '</select>';
        echo wp_nonce_field( 'footer_text_padding', 'footer_text_padding_nonce' );
    }

    public function footer_text_margin_callback() {
        $margin_top = get_option( 'footer_text_margin_top', '' );
        $margin_right = get_option( 'footer_text_margin_right', '' );
        $margin_bottom = get_option( 'footer_text_margin_bottom', '' );
        $margin_left = get_option( 'footer_text_margin_left', '' );
        $margin_unit = get_option( 'footer_text_margin_unit', 'px' );

        echo '<div class="margin-inputs">';
        echo '<input type="text" name="footer_text_margin_top" value="' . esc_attr( $margin_top ) . '" placeholder="Top" maxlength="4" style="margin: 2px;" />';
        echo '<input type="text" name="footer_text_margin_right" value="' . esc_attr( $margin_right ) . '" placeholder="Right" maxlength="4" style="margin: 2px;" />';
        echo '</div>';
        echo '<div class="margin-inputs">';
        echo '<input type="text" name="footer_text_margin_bottom" value="' . esc_attr( $margin_bottom ) . '" placeholder="Bottom" maxlength="4" style="margin: 2px; margin-top: 10px;" />';
        echo '<input type="text" name="footer_text_margin_left" value="' . esc_attr( $margin_left ) . '" placeholder="Left" maxlength="4" style="margin: 2px; margin-top: 10px;" />';
        echo '</div>';
        echo '<select name="footer_text_margin_unit" style="margin: 2px; margin-top: 10px;">';
        echo '<option value="px" ' . selected( $margin_unit, 'px', false ) . '>px</option>';
        echo '<option value="%" ' . selected( $margin_unit, '%', false ) . '>%</option>';
        echo '<option value="rem" ' . selected( $margin_unit, 'rem', false ) . '>rem</option>';
        echo '</select>';
        echo wp_nonce_field( 'footer_text_margin', 'footer_text_margin_nonce' );
    }

    public function footer_copyright_text_callback() {
        $copyright_text = get_option( 'footer_copyright_text', '©️' );
        echo "<input type='text' name='footer_copyright_text' value='$copyright_text' />";
        echo wp_nonce_field( 'footer_copyright_text', 'footer_copyright_text_nonce' );
    }

    public function footer_text_italic_callback() {
        $italic = get_option( 'footer_text_italic', 0 ); // Default to 0 if not set

        echo '<label><input type="checkbox" name="footer_text_italic" value="1" ' . checked( $italic, 1, false ) . ' /> Italic</label>';
        echo wp_nonce_field( 'footer_text_italic', 'footer_text_italic_nonce' );
    }

    public function footer_text_bold_callback() {
        $bold = get_option( 'footer_text_bold', 0 ); // Default to 0 if not set

        echo '<label><input type="checkbox" name="footer_text_bold" value="1" ' . checked( $bold, 1, false ) . ' /> Bold</label>';
        echo wp_nonce_field( 'footer_text_bold', 'footer_text_bold_nonce' );
    }

    // Settings section callback
    public function settings_section_callback() {
        echo '<p>Customize the appearance of your copyright text:</p>';
    }

    // Sanitize hexadecimal color
    public function sanitize_hex_color( $color ) {
        if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) {
            return $color;
        } else {
            add_settings_error( 'footer_text_color', 'invalid_color', 'Please enter a valid color code.', 'error' );
            return '#000000'; // Default color
        }
    }

    // Display the Footer
    public function display_footer() {
        $color = get_option( 'footer_text_color', '#000000' );
        $size = get_option( 'footer_text_size', '14px' );
        $alignment = get_option( 'footer_text_alignment', 'left' );
        $padding_top = get_option( 'footer_text_padding_top', '' );
        $padding_right = get_option( 'footer_text_padding_right', '' );
        $padding_bottom = get_option( 'footer_text_padding_bottom', '' );
        $padding_left = get_option( 'footer_text_padding_left', '' );
        $padding_unit = get_option( 'footer_text_padding_unit', 'px' );
        $margin_top = get_option( 'footer_text_margin_top', '' );
        $margin_right = get_option( 'footer_text_margin_right', '' );
        $margin_bottom = get_option( 'footer_text_margin_bottom', '' );
        $margin_left = get_option( 'footer_text_margin_left', '' );
        $margin_unit = get_option( 'footer_text_margin_unit', 'px' );
        $copyright_text = get_option( 'footer_copyright_text', '©️' );
        $italic = get_option( 'footer_text_italic', 0 ); // Default to 0 if not set
        $bold = get_option( 'footer_text_bold', 0 ); // Default to 0 if not set
        $bg_color = get_option( 'footer_copyright_background_color', '#ffffff' );

        // Adjusted padding and margin generation
        $padding_style = "padding: ";
        $margin_style = "margin: ";

        // Check and add top padding
        if (!empty($padding_top)) {
            $padding_style .= $padding_top . $padding_unit . ' ';
        }

        // Check and add right padding
        if (!empty($padding_right)) {
            $padding_style .= $padding_right . $padding_unit . ' ';
        }

        // Check and add bottom padding
        if (!empty($padding_bottom)) {
            $padding_style .= $padding_bottom . $padding_unit . ' ';
        }

        // Check and add left padding
        if (!empty($padding_left)) {
            $padding_style .= $padding_left . $padding_unit . ' ';
        }

        // Remove the trailing space
        $padding_style = rtrim($padding_style);

        // Check and add top margin
        if (!empty($margin_top)) {
            $margin_style .= $margin_top . $margin_unit . ' ';
        }

        // Check and add right margin
        if (!empty($margin_right)) {
            $margin_style .= $margin_right . $margin_unit . ' ';
        }

        // Check and add bottom margin
        if (!empty($margin_bottom)) {
            $margin_style .= $margin_bottom . $margin_unit . ' ';
        }

        // Check and add left margin
        if (!empty($margin_left)) {            $margin_style .= $margin_left . $margin_unit . ' ';
        }

        // Remove the trailing space
        $margin_style = rtrim($margin_style);

        // Add semicolons
        $padding_style .= ';';
        $margin_style .= ';';

        // Additional styles for italic and bold
        $font_style = '';
        if ($italic) {
            $font_style .= 'font-style: italic;';
        }
        if ($bold) {
            $font_style .= 'font-weight: bold;';
        }

        echo "<footer class='custom-footer' style='color: $color; font-size: $size; text-align: $alignment; $padding_style $margin_style $font_style background-color: $bg_color;'>
            <hr>
            <div class='footer-content'>
                <p class='footer-copyright'>$copyright_text " . date('Y') . "</p>
            </div>
          </footer>";
    }

    // Initialize the plugin
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'wp_footer', array( $this, 'display_footer' ) );
    }
}

// Initialize the plugin
new Forever_Auto_Updated_Copyright_Year_Footer_Note();
