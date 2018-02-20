<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCWidget' ) ) {
	/**
	 * EVCWidget abstract class is main class for widgets where we implemented generic methods for generating form and updating widgets
	 * Classes that extend this class needs to implement setWidgetParameters method where $params property will be populated
	 *
	 * Class EVCWidget
	 */
	abstract class EVCWidget extends WP_Widget {
		
		/**
		 * Singleton variables
		 */
		protected $params;
		
		/**
		 * Set widget parameters
		 *
		 * @return array
		 */
		abstract protected function setWidgetParameters();
		
		/**
		 * Generate widget form based on $params attribute
		 *
		 * @param $instance array
		 *
		 * @return null
		 */
		public function form( $instance ) {
			if ( is_array( $this->params ) && count( $this->params ) ) {
				foreach ( $this->params as $param_array ) {
					$param_name    = $param_array['param_name'];
					${$param_name} = isset( $instance[ $param_name ] ) ? esc_attr( $instance[ $param_name ] ) : '';
				}
				
				foreach ( $this->params as $param ) {
					switch ( $param['type'] ) {
						case 'textfield':
							?>
							<p>
								<label for="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>"><?php echo esc_html( $param['heading'] ); ?></label>
								<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $param['param_name'] ) ); ?>" type="text" value="<?php echo esc_attr( ${$param['param_name']} ); ?>"/>
								<?php if ( ! empty( $param['description'] ) ) { ?>
									<small><?php echo esc_html( $param['description'] ); ?></small>
								<?php } ?>
							</p>
							<?php
							break;
						case 'textarea':
							?>
							<p>
								<label for="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>"><?php echo esc_html( $param['heading'] ); ?></label>
								<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $param['param_name'] ) ); ?>"><?php echo esc_attr( ${$param['param_name']} ); ?></textarea>
								<?php if ( ! empty( $param['description'] ) ) { ?>
									<small><?php echo esc_html( $param['description'] ); ?></small>
								<?php } ?>
							</p>
							<?php
							break;
						case 'dropdown':
							?>
							<p>
								<label for="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>"><?php echo esc_html( $param['heading'] ); ?></label>
								<?php if ( isset( $param['value'] ) && is_array( $param['value'] ) && count( $param['value'] ) ) { ?>
									<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( $param['param_name'] ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>">
										<?php foreach ( $param['value'] as $key => $value ) {
											if ( isset( $param['inverse_value'] ) && $param['inverse_value'] ) {
												$selected = selected( $value, ${$param['param_name']}, false );
												?>
												<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $key ); ?></option>
											<?php } else {
												$selected = selected( $key, ${$param['param_name']}, false );
												?>
												<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
											<?php }
										} ?>
									</select>
								<?php } ?>
								<?php if ( ! empty( $param['description'] ) ) { ?>
									<small><?php echo esc_html( $param['description'] ); ?></small>
								<?php } ?>
							</p>
							<?php
							break;
						case 'colorpicker':
							?>
							<p class="evc-color-picker-widget-field">
								<label for="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>"><?php echo esc_html( $param['heading'] ); ?></label>
								<input class="widefat evc-color-picker-field" id="<?php echo esc_attr( $this->get_field_id( $param['param_name'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $param['param_name'] ) ); ?>" type="text" value="<?php echo esc_attr( ${$param['param_name']} ); ?>"/>
								<?php if ( ! empty( $param['description'] ) ) { ?>
									<small><?php echo esc_html( $param['description'] ); ?></small>
								<?php } ?>
							</p>
							<?php
							break;
					}
				}
			} else { ?>
				<p><?php esc_html_e( 'Please add some options for this widget', 'extensive-vc' ); ?></p>
			<?php }
		}
		
		/**
		 * Update current widget instance
		 *
		 * @param $new_instance array
		 * @param $old_instance array
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			
			foreach ( $this->params as $param ) {
				$param_name = $param['param_name'];
				$param_type = $param['type'];
				
				if ( $param_type === 'textarea' && current_user_can( 'unfiltered_html' ) ) {
					$instance[ $param_name ] = $new_instance[ $param_name ];
				} else {
					$instance[ $param_name ] = sanitize_text_field( $new_instance[ $param_name ] );
				}
			}
			
			return $instance;
		}
	}
}
