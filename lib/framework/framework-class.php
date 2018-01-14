<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ExtensiveVCFramework' ) ) {
	class ExtensiveVCFramework {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		public $options;
		public $metaBoxes;
		
		/**
		 * Constructor
		 */
		private function __construct() {
			$this->options   = ExtensiveVCOptions::getInstance();
			$this->metaBoxes = ExtensiveVCMetaBoxes::getInstance();
		}
		
		/**
		 * Get the instance of ExtensiveVCFramework
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'extensive_vc_framework_instance' ) ) {
	/**
	 * Returns instance of ExtensiveVCFramework class
	 */
	function extensive_vc_framework_instance() {
		global $evc_options;
		
		$evc_options = ExtensiveVCFramework::getInstance();
	}
	
	add_action( 'init', 'extensive_vc_framework_instance' );
}

if ( ! class_exists( 'ExtensiveVCOptions' ) ) {
	class ExtensiveVCOptions {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'admin_init', array( $this, 'registerOptions' ) );
		}
		
		/**
		 * Get the instance of ExtensiveVCOptions
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Gets option value from database by it's name.
		 *
		 * @param $id string - id of the option to retrieve
		 *
		 * @return string
		 */
		function getOptionValueById( $id ) {
			$evc_options  = get_option( 'evc_options' );
			$option_value = ! empty( $evc_options ) && array_key_exists( $id, $evc_options ) ? $evc_options[ esc_attr( $id ) ] : '';
			
			return $option_value;
		}
		
		/**
		 * Register admin options fields
		 */
		function registerOptions() {
			
			register_setting(
				'evc_options_group',
				'evc_options'
			);
		}
		
		/**
		 * Add admin options field section
		 *
		 * @param $option array - array of current option parameters
		 */
		function addSection( $option ) {
			$id    = $option['id'];
			$title = array_key_exists( 'title', $option ) ? $option['title'] : '';
			
			add_settings_section(
				$id,
				$title,
				'',
				'evc_options_page'
			);
		}
		
		/**
		 * Add admin options field
		 *
		 * @param $option array - array of current option parameters
		 */
		function addField( $option ) {
			$page        = $option['page'];
			$type        = $option['type'];
			$id          = $option['id'];
			$title       = $option['title'];
			$description = array_key_exists( 'description', $option ) ? $option['description'] : '';
			$sb_options  = array_key_exists( 'sb_options', $option ) ? $option['sb_options'] : '';
			$cb_options  = array_key_exists( 'cb_options', $option ) ? $option['cb_options'] : '';
			
			add_settings_field(
				$id,
				$title,
				array( $this, 'renderField' ),
				'evc_options_page',
				$page,
				array(
					'id'          => $id,
					'type'        => $type,
					'description' => $description,
					'sb_options'  => $sb_options,
					'cb_options'  => $cb_options
				)
			);
		}
		
		/**
		 * Renders admin options field html
		 *
		 * @param $option array - array of current option parameters
		 *
		 * @return html
		 */
		function renderField( $option ) {
			$evc_options  = get_option( 'evc_options' );
			$option_id    = $option['id'];
			$option_name  = 'evc_options['. $option_id .']';
			$option_type  = $option['type'];
			$option_value = ! empty( $evc_options ) && array_key_exists( $option_id, $evc_options ) ? $evc_options[ $option_id ] : '';
			$description  = $option['description'];
			$sb_options   = $option['sb_options'];
			$cb_options   = $option['cb_options'];
			
			$html = '';
			
			switch ( $option_type ) {
				case 'text':
					$html .= '<input name="' . esc_attr( $option_name ) . '" type="' . esc_attr( $option_type ) . '" id="' . esc_attr( $option_id ) . '" value="' . esc_attr( $option_value ) . '" />';
					break;
				case 'select':
					$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $option_id ) . '">';
					foreach ( $sb_options as $key => $value ) {
						$selected = selected( $key, $option_value, false );
						
						$html .= '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
					}
					$html .= '</select>';
					break;
				case 'checkboxes':
					$html .= '<fieldset class="evc-admin-checkboxes">';
						foreach ( $cb_options as $key => $value ) {
							$option_name  = 'evc_options['. $key .']';
							$option_value = ! empty( $evc_options ) && array_key_exists( $key, $evc_options ) ? $evc_options[ $key ] : '';
							$checked      = checked( '1', $option_value, false );
							
							$html .= '<label class="evc-admin-checkbox" for="' . esc_attr( $key ) . '">';
							$html .= '<input name="' . esc_attr( $option_name ) . '" type="checkbox" id="' . esc_attr( $key ) . '" value="1" ' . $checked . ' />';
							$html .= esc_html( $value );
							$html .= '</label>';
						}
					$html .= '</fieldset>';
					break;
				case 'colorpicker':
					$html .= '<input class="evc-color-picker" name="' . esc_attr( $option_name ) . '" type="' . esc_attr( $option_type ) . '" id="' . esc_attr( $option_id ) . '" value="' . esc_attr( $option_value ) . '" />';
					break;
			}
			
			if ( ! empty( $description ) ) {
				$html .= '<p class="description">' . esc_html( $description ) . '</p>';
			}
			
			echo $html;
		}
	}
}

if ( ! class_exists( 'ExtensiveVCMetaBoxes' ) ) {
	class ExtensiveVCMetaBoxes {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		public $options;
		
		/**
		 * Constructor
		 */
		private function __construct() {
			$this->options = array();
		}
		
		/**
		 * Get the instance of ExtensiveVCMetaBoxes
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Add option
		 *
		 * @param $key string - option key
		 * @param $value string - option value
		 */
		function addOption( $key, $value ) {
			$this->options[ $key ] = $value;
		}
		
		/**
		 * Get option value by option key
		 *
		 * @param $key string - option key
		 *
		 * @return string
		 */
		function getOption( $key ) {
			return isset( $this->options[ $key ] ) ? $this->options[ $key ] : '';
		}
		
		/**
		 * Get all options
		 *
		 * @return array
		 */
		function getOptions() {
			return $this->options;
		}
		
		/**
		 * Gets option value from database by it's name.
		 *
		 * @param $name string - name of the option to retrieve
		 *
		 * @return string
		 */
		function getOptionValueByName( $name ) {
			global $post;
			
			$value = ! empty( $post ) ? get_post_meta( $post->ID, $name, true ) : '';
			
			return isset( $value ) && $value !== '' ? $value : $this->getOption( $name );
		}
	
		/**
		 * Render text filed html for meta boxes
		 *
		 * @param $option array - array of current option parameters
		 *
		 * @return html
		 */
		function renderField( $option ) {
			$option_type        = $option['type'];
			$option_name        = $option['name'];
			$option_label       = array_key_exists( 'label', $option ) ? $option['label'] : '';
			$option_description = array_key_exists( 'description', $option ) ? $option['description'] : '';
			$just_render_fields = $option['just_render_fields'];
			
			if ( $just_render_fields ) {
				$this->addOption( $option_name, '' );
			} else {
				$option_value = $this->getOptionValueByName( $option_name );
				?>
				
				<div class="evc-meta-box-field-holder">
					<label class="evc-meta-box-label"><?php echo esc_html( $option_label ); ?></label>
					
					<?php switch ( $option_type ) {
						case 'text':
							?>
							<input class="evc-meta-box-field evc-meta-box-text" type="text" name="<?php echo esc_attr( $option_name ); ?>" value="<?php echo esc_attr( $option_value ); ?>" />
							<?php
							break;
						case 'textarea':
							?>
							<textarea class="evc-meta-box-field evc-meta-box-textarea" type="textarea" rows="4" name="<?php echo esc_attr( $option_name ); ?>"><?php echo esc_attr( $option_value ); ?></textarea>
							<?php
							break;
						case 'image':
							$visibilityClass = ! empty( $option_value ) ? '' : 'hidden';
							?>
							<div class="evc-meta-box-image-holder">
								<?php if ( ! empty( $option_value ) ) {
									echo wp_get_attachment_image( $option_value, 'medium', false, array( 'class' => 'evc-meta-box-image' ) );
								} ?>
								
								<input class="button button-primary button-large evc-meta-box-upload-button" type="button" value="<?php esc_attr_e( 'Upload Image', 'extensive-vc' ); ?>" />
								<input class="button button-large evc-meta-box-remove-upload-button <?php echo esc_attr( $visibilityClass ); ?>" type="button" value="<?php esc_attr_e( 'Remove Image', 'extensive-vc' ); ?>" />
								
								<input type="hidden" name="<?php echo esc_attr( $option_name ); ?>" value="<?php echo esc_attr( $option_value ); ?>" />
							</div>
							<?php
							break;
					} ?>
					
					<?php if ( ! empty( $option_description ) ) { ?>
						<p class="evc-meta-box-description"><?php echo esc_html( $option_description ); ?></p>
					<?php } ?>
				</div>
				<?php
			}
		}
	}
}
