<?php

// enqueue Divi

function get_divi() { wp_enqueue_style( 'divi', get_template_directory_uri() . '/style.css' ); }

add_action( 'wp_enqueue_scripts', 'get_divi' );

// Only load if not loaded already
if ( ! function_exists( 'sj_divi_customizer_options' ) ) {
// Add basic controls to the customizer
function sj_divi_customizer_options( $wp_customize ) {

	    // Add custom customizer controls to search and select latest et_pb_layouts

		class ET_Divi_SJ_use_layouts extends WP_Customize_Control {

		public $type = 'layout_dropdown';  // New control type

		public $post_type = 'et_pb_layout';  // Query filter for library items
 
		public function render_content() {

		$latest = new WP_Query( array(
			'post_type'   => $this->post_type,
			'orderby'     => 'date',
			'order'       => 'DESC'
		));

		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php 
					while( $latest->have_posts() ) {
						$latest->the_post();
						echo "<option " . selected( $this->value(), get_the_ID() ) . " value='" . get_the_ID() . "'>" . the_title( '', '', false ) . "</option>";
					}
					?>
				</select>
			</label>
		<?php
		}
	    }

    // Add new section under General Settings

	$wp_customize->add_section( 'et_divi_new_header_footer' , array(
		'title'		=> esc_html__( 'New Header & Footer', 'SJ' ),
		'panel' => 'et_divi_general_settings',
	) );

    // Settings & Controls for the new header

	$wp_customize->add_setting( 'sj_divi_new_header', array(
		'type'          => 'option',
		'capability'    => 'edit_theme_options',
	) );

	$wp_customize->add_control( new ET_Divi_SJ_use_layouts( $wp_customize,'sj_divi_new_header', array(
		'label' => __( 'Select A New Header', 'mytheme' ),
		'section' => 'et_divi_new_header_footer',
		'type' => 'layout_dropdown',
		'settings' => 'sj_divi_new_header',
    ) ) );

    // Settings & Controls for the new footer

 	$wp_customize->add_setting( 'sj_divi_new_footer', array(
		'type'          => 'option',
		'capability'    => 'edit_theme_options',
	) );

	$wp_customize->add_control( new ET_Divi_SJ_use_layouts( $wp_customize,'sj_divi_new_footer', array(
		'label' => __( 'Select A New Footer', 'mytheme' ),
		'section' => 'et_divi_new_header_footer',
		'type' => 'layout_dropdown',
		'settings' => 'sj_divi_new_footer',
    ) ) );

}
}

add_action( 'customize_register', 'sj_divi_customizer_options', 999 );

// ------------- CUSTOM PHP GOES HERE -----------------
