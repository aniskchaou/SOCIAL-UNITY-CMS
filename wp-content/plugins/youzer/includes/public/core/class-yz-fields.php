<?php

class Youzer_Fields {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	function __construct() { /** **/ }

	/**
	 * # Fields Generator.
	 */
	function get_field( $option, $is_user = false, $options_name = 'youzer_options' ) {

		// Get Data.
		if ( 'open' != $option['type']  && 'close' != $option['type']  && ! empty( $option['id'] ) ) {

			// Set Up Variables.
			$real_value = null;
			$default_value = ! empty( $option['std'] ) ? $option['std'] : null;

			if ( ! $is_user ) {
				// Get Option Value.
				$option_value = yz_options( $option['id'] );
				$user_defined_value = ! empty( $option_value ) ? $option_value : $default_value;
			} else {
				// Get Value From User Meta.
				$data_value = yz_data( $option['id'] );
				$user_defined_value = ! empty( $data_value ) ? $data_value : $default_value;
			}

		}

		// Get Option Value.
		$real_value = ! empty( $user_defined_value ) ? $user_defined_value : null;

		// Forbidden types.
		$forbidden_types = array(
			'open', 'close', 'start', 'end', 'msgBox',
			'imgSelect', 'hidden', 'openBox', 'closeBox',
			'openDiv', 'closeDiv', 'bpDiv', 'endbpDiv'
		);

		if ( ! in_array( $option['type'], $forbidden_types ) ) {
			$field_description = isset( $option['desc'] ) ?
			 '<p class="option-desc">' . $option['desc'] .'</p>' : null;

		$option_class = isset( $option['class'] ) ? ' ' . $option['class']: null;

		if ( isset( $option['opts'] ) && $option['type'] != 'select' ) {
			$option_class = ' yz-field-with-options';
		}

		?>

			<div class="uk-option-item <?php echo "yz-{$option['type']}-field"; ?><?php echo $option_class; ?>">
				<div class="option-infos">
					<label for="<?php echo $option['id']; ?>" class="option-title"><?php if ( ! empty( $option['title'] ) ) echo $option['title']; ?></label><?php echo $field_description; ?>
				</div>
				<div class="option-content">

		<?php

		}

		$this->get_option( $options_name, $is_user, $option, $real_value );

		// Close Option Divs
		if ( ! in_array( $option['type'], $forbidden_types ) ) {
			echo '</div></div>';
		}

	}

	function get_option( $options_name, $is_user, $option, $real_value ) {

		// Get Filed Data.
		$field_id    = isset( $option['id'] ) ? $option['id'] : null;
		$field_title = isset( $option['title'] ) ? $option['title'] : null;
		$field_name  = ! empty( $field_id ) ? 'name="' . $options_name . '[' . $field_id . ']"' : null;

		// Standard Field Name.
		if ( isset( $option['no_options'] ) ) {
		   $field_name = "name='$field_id'";
		}

		// Hide Field Name.
		if ( isset( $option['hide_name'] ) ) {
			$field_name = null;
		}

		// Get Disabled Field.
		$disabled = isset( $option['disabled'] ) && $option['disabled'] == true ? 'disabled="disabled"' : '';

		// Get Disabled Field.
		$required = isset( $option['required'] ) && $option['required'] == true ? 'required' : '';

		switch ( $option['type'] ) :

		case 'open':

			// Get Tab ID
			if ( empty( $option['id'] ) ) {
				$tab_id = str_replace( ' ', '-', strtolower( $option['title'] ) );
			} else {
				$tab_id = $option['id'];
			}

			$tab_class = ! isset( $option['widget_section'] ) ? 'yz-no-widgets' : null;

			// Get Button Data.
			$button_id 	 = isset( $option['button_id'] ) ? $option['button_id'] : null;
			$button_name = isset( $option['button_name'] ) ? $option['button_name'] : 'save';
			$submit_id 	 = isset( $option['submit_id'] ) ? 'id="' . $option['submit_id'] . '"' : null;
			$button_value= isset( $args['button_value'] ) ? 'value="' . $args['button_value'] . '"' : null;

			// Get Form Data.
			$form_name = isset( $option['form_name'] ) ? 'name="' . $option['form_name'] . '"' : null ;
			$form_action = isset( $option['form_action'] ) ? 'action="' . $option['form_action'] . '"' : null ;

			?>

			<form <?php echo $form_action; ?> id="yz-<?php echo $tab_id; ?>" <?php echo $form_name; ?> method="post" class="yz-settings-form" enctype="multipart/form-data">
				<div class="options-section-title">
					<h2>
                    	<i class="<?php echo $option['icon']; ?>"></i>
                    	<?php echo $field_title; ?>
                    </h2>
					<div class="yza-form-actions">
						<?php if ( $button_id ) : ?>
						<a id="<?php echo $button_id; ?>" class="yza-item-button">
							<?php echo $option['button_text']; ?>
						</a>
						<?php endif; ?>

						<?php if ( ! isset( $option['hide_save_button'] ) ) : ?>
						<button <?php echo $submit_id; ?> <?php echo $button_value; ?> name="<?php echo $button_name; ?>" class="yz-save-options" type="submit">
							<?php _e( 'Save Changes', 'youzer' ); ?>
						</button>
						<?php endif; ?>
					</div>
				</div>
				<div class="youzer-section-content <?php echo $tab_class ?>">

					<?php do_action( 'yz_before_account_settings_form' ); ?>
		<?php break;

		case 'bpDiv':

			// Get Tab ID
			$tab_id = empty( $option['id'] ) ? str_replace( ' ', '-', strtolower( $option['title'] ) ) : $option['id'];

			$tab_class = ! isset( $option['widget_section'] ) ? 'yz-no-widgets' : null;

			?>

			<div id="yz-<?php echo $tab_id; ?>" class="yz-settings-form">
				<div class="options-section-title">
					<h2>
                    	<i class="<?php echo $option['icon']; ?>"></i>
                    	<?php echo $field_title; ?>
                    </h2>
				</div>
				<div class="youzer-section-content <?php echo $tab_class ?>">

					<?php do_action( 'yz_before_account_settings_form' ); ?>
		<?php break;

		case 'endbpDiv': ?>

				</div><!-- .yz-settings-form-->
			</div>

		<?php break;

		case 'close':

			?>

				</div><!-- .yz-settings-form-->

				<?php $this->form_action( $option ); ?>

			</form>

		<?php break;

		case 'start':

			// Get Form Class
			$form_class = isset( $option['class'] ) ? 'yz-settings-form ' . $option['class'] : 'yz-settings-form';

			?>

			<form id="<?php echo $option['id']; ?>" class="<?php echo $form_class; ?>">
				<div class="ukai-panel-actions uk-header-actions">
	            	<div class="ukai-panel-title">
						<h2><i class="<?php echo $option['icon']; ?>"></i><?php echo $option['title']; ?></h2>
	                </div>
	                <?php $this->admin_form_actions( 'top' ); ?>
				</div>
				<div class="youzer-section-content">

		<?php	break;

		case 'end':
			echo '</div><div class="ukai-panel-actions uk-footer-actions">';
	        $this->admin_form_actions( 'bottom' );
	        echo '</div></form>';
			break;

		case 'openDiv':

			$class_name = $option['class'];
			echo "<div class='$class_name'>";
			break;

		case 'closeDiv':

			echo '</div>';
			break;

		case 'openBox':

			// Init Vars
			$box_class = array( 'uk-box-item' );

			// Get Box Class
			$box_class[] = isset( $option['class'] ) ? $option['class'] : null;

			// Get Hide Box Class.
			$box_class[] = isset( $option['hide'] ) ? 'kl-hide-box': null;

			?>

			<div class="<?php echo yz_generate_class( $box_class ); ?>">
				<?php if ( isset( $option['hide'] ) ) : ?>
				<i class="fas fa-angle-up kl-hide-box-icon"></i>
				<?php endif; ?>
				<div class="uk-box-title">
					<h2><?php echo $field_title; ?></h2>
				</div>
				<div class="uk-box-content">

			<?php

			break;

		case 'closeBox';

			echo '</div></div>';

			break;

		case 'sectionTitle'; ?>

			<div class="uk-box-title">
				<h2><?php echo $field_title; ?></h2>
			</div>

		<?php break;

		case 'text':
		case 'email':

		$placeholder = isset( $option['placeholder'] ) ? $field_title : null;

		?>

			<input type="<?php echo $option['type']; ?>" id="<?php echo $field_id; ?>" <?php echo $field_name; ?> placeholder="<?php echo $placeholder; ?>" value="<?php echo $real_value; ?>" <?php echo $disabled; echo $required; ?>/>

		<?php break;

		case 'password': ?>

			<input type="password" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" placeholder="<?php echo $field_title; ?>" value="<?php echo $real_value; ?>">

		<?php break;

		case 'number':

			$step = isset( $option['step'] ) ? $option['step'] : '1';

		?>

			<input type="number"  class="yz-number-input" value="<?php echo $real_value; ?>" id="<?php echo $field_id; ?>" <?php echo $field_name; ?>>

		<?php break;

		case 'hidden':

			$class = isset( $option['class'] ) ? 'yz-hidden-input ' . $option['class'] : 'yz-hidden-input';

		?>

			<input class="<?php echo $class; ?>" type="hidden" <?php echo $field_name; ?> value="<?php echo $real_value; ?>">

		<?php break;

		case 'textarea': ?>

			<textarea <?php echo $field_name; ?> ><?php echo $real_value; ?></textarea>

		<?php break;

		case 'wp_editor':
			$wp_editor_settings = apply_filters( 'yz_fields_wp_editor_settings', array( 'media_buttons' => false, 'textarea_rows' => 5, 'textarea_name' => $options_name . '[' . $field_id . ']') );
	        wp_editor( $real_value, $field_id, $wp_editor_settings );
			break;

		case 'button':

			if ( ! isset( $option['button_title'] ) || empty( $option['button_title'] ) ) {
				break;
			}

			$button_class = isset( $option['button_class'] ) ? 'uk-option-button ' . $option['button_class'] : 'uk-option-button';

		?>

			<a id="<?php echo $field_id; ?>" class="<?php echo $button_class; ?>" <?php if ( isset( $option['button_data'] ) ) { foreach ( $option['button_data'] as $data_key => $data_value ) { echo "data-$data_key='$data_value'"; } }; ?> ><?php echo $option['button_title']; ?></a> <?php break;

		case 'image':

			$default_img =  YZ_PA . 'images/default-img.png';
			$img_preview = ! empty( $real_value ) ? wp_get_attachment_image_url( $real_value, 'youzify-thumbnail' ) : $default_img;

			// Show/Hide Trash Icon.
			$trash_icon_class = 'fas fa-trash-alt yz-delete-photo';
			$trash_icon_class .= $img_preview != $default_img ? ' yz-show-trash' : '';

		?>

			<div class="yz-uploader-item">
	            <label for="upload_<?php echo $field_id; ?>" class="yz-upload-photo" ><?php _e( 'Upload Image', 'youzer' ) ?></label>
	            <input id="upload_<?php echo $field_id; ?>" type="file" name="upload_<?php echo $field_id; ?>" class="yz_upload_file" accept="image/*" <?php if ( isset( $option['source'] ) ) { ?> data-source="<?php echo $option['source']; ?>" <?php } ?> <?php if ( bp_is_user() ) { ?> data-user-id="<?php echo bp_displayed_user_id(); ?>" <?php } ?>>
	            <div class="yz-photo-preview" style="background-image: url(<?php echo $img_preview; ?>);">
					<i class="<?php echo $trash_icon_class; ?>"></i>
	            </div>
				<input type="hidden" class="yz-photo-url" name="<?php echo $options_name . '[' . $field_id . ']'; ?>" value="<?php echo $real_value; ?>"/>
			</div>

		<?php break;

		case 'icon':

			$icons_type = empty( $option['icons_type'] ) ? "web_application" : $option['icons_type'];

			$real_value = apply_filters( 'yz_field_icon', $real_value );

			?>

			<div id="<?php echo $field_id; ?>" class="ukai_iconPicker" data-icons-type="<?php echo $icons_type; ?>">
				<div class="ukai_icon_selector">
					<i class="<?php echo $real_value; ?>"></i>
					<span class="ukai_select_icon">
						<i class="fas fa-sort-down"></i>
					</span>
				</div>
				<input type="hidden" class="ukai-selected-icon" <?php echo $field_name; ?> value="<?php echo $real_value; ?>">
			</div>

		<?php break;

		case 'upload':

			// Get Image Preview.
			$img_preview = yz_get_image_url( $real_value );

			?>

			<div id="<?php echo $field_id; ?>" class="uk-uploader">
				<div class="uk-upload-photo">
					<input type="text" class="uk-photo-url" <?php echo $field_name; ?> value="<?php echo $real_value; ?>"/>
					<input type="button" class="uk-upload-button" value="Upload"/>
				</div>
				<div class="uk-photo-preview" style="background-image: url( <?php echo $img_preview ?> );">
				</div>
			</div>

		<?php break;

		case 'select':

			echo "<div class='yz-select-field'><select id='$field_id' $field_name>";

			// Loop options
			foreach ( $option['opts'] as $key => $value ) {

				// Which options should be selected
				if ( $key == $real_value ) {
					$active_attr = 'selected';
				} else {
					$active_attr = null;
				}

				// Print Option.
				echo "<option value='$key' $active_attr>$value</option>";

			}

			echo '</select><div class="yz-select-arrow"></div></div>';

		break;

		case 'radio':
			foreach ( $option['opts'] as $value => $key ) {

				// Which options should be selected
				if ( $value == $real_value ) {
					$active_attr = 'checked';
				} else {
					$active_attr = null;
				}

				$radio_id = "$field_id-$value";

				?>

				<label class="yz-label-radio" for="<?php echo $radio_id; ?>"><input type="radio" id="<?php echo $radio_id; ?>" <?php echo $field_name; ?> value="<?php echo $value;?>" <?php echo $active_attr;?>><div class="yz_field_indication"></div><?php echo $key; ?></label>

				<?php
			}

		break;

		case 'checkbox':

			$active_attr = ( 'on' == $real_value ) ? 'checked' : null;

			// Convert Registration Value
			if ( $field_id == 'bp-disable-account-deletion' && bp_get_option( 'bp-disable-account-deletion' ) == 0 ) {
				$active_attr = 'checked';
			}

			// Convert Registration Value
			if ( $field_id == 'users_can_register' && get_option( 'users_can_register' ) == 1 ) {
				$active_attr = 'checked';
			}

			if ( ! isset( $option['opts'] ) ) :
				$field_id = $options_name == 'youzer_options' ? $field_id : str_replace( array('[', ']') , '_', $options_name ) . $field_id;
			?>

			<div class="ukai-checkbox-item">
				<input class="yz-hidden-input" value="off" type="hidden" <?php echo $field_name; ?>>
				<input id="<?php echo $field_id; ?>" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" value="on" <?php echo $field_name; ?> <?php echo $active_attr; ?>>
				<label for="<?php echo $field_id;?>"></label>
			</div>
			<?php

			else :
				foreach ( $option['opts'] as $key => $value ) {

					if ( isset( $option['no_options'] ) ) {
					   $field_name = "name='{$field_id}[]'";
					} else {
						$field_name  = ! empty( $field_id ) ? 'name="' . $options_name . '[' . $field_id . '][' . $key . ']"' : null;
					}

					// Which options should be selected
					if ( isset( $real_value[ $key ] ) && 'on' == $real_value[ $key ] ) {
						$active_attr = 'checked';
					} else {
						$active_attr = null;
					}

					$checkbox_id = "$field_id-$key";

					?>

					<div class="ukai-checkbox-item ukai-multiple-checkbox">
						<label for="<?php echo $checkbox_id;?>"><?php echo $value; ?></label>
						<div class="ukai-checkbox-item">
							<input class="yz-hidden-input" value="off" type="hidden" <?php echo $field_name; ?>>
							<input id="<?php echo $checkbox_id; ?>" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" value="on" <?php echo $field_name; ?> <?php echo $active_attr; ?>>
							<label for="<?php echo $checkbox_id;?>"></label>
						</div>
					</div>

					<?php
				}

			endif;

			?>

		<?php break;

		case 'color';

			// Get Color Value
			$color_value = ! empty( $real_value['color'] ) ? $real_value['color'] : null;
			$field_name  = ! empty( $field_id ) ? 'name="' . $options_name . '[' . $field_id . '][color]"' : null;

			// Standard Field Name.
			if ( isset( $option['no_options'] ) ) {
			   $field_name = "name='$field_id'";
			}

			?>

			<input type="text" class="yz-picker-input" <?php echo $field_name; ?> value="<?php echo $color_value; ?>">

		<?php break;

		case 'connect':

			// Get Ajax Nonce
			$ajax_nonce = wp_create_nonce( 'yz-unlink-provider-account' );

			$connect_class = ! empty( $real_value ) ? 'yz-user-provider-connected' : 'yz-user-provider-unconnected';

			// Get Provider.
			$provider = strtolower( $option['provider'] );

			// Get User Data
			$user_data = (array) yz_data( 'wg_' . $provider . '_account_user_data' );

			// Remove empty data.
			$user_data = array_filter( $user_data );

			?>

			<div class="<?php echo $connect_class; ?>" >
				<?php

				if ( ! empty( $user_data ) ) :

				// Get User Thumbnail
				$user_thumb = ! empty( $user_data['photoURL'] ) ? $user_data['photoURL'] : bp_core_avatar_default();

				// Get User Nmae
				$user_name = ! empty( $user_data['displayName'] ) ? $user_data['displayName'] : $user_data['username'];

				?>
				<div class="yz-user-provider-box">
					<div class="yz-user-provider-img" style="background-image: url(<?php echo $user_thumb; ?>);"></div>
					<div class="yz-user-provider-data" >
						<div class="yz-user-provider-data-name"><?php echo $user_name; ?></div>
						<div class="yz-user-provider-status"><?php _e( 'Account Linked', 'youzer' ); ?></div>
					</div>
					<div class="yz-user-provider-unlink" data-yztooltip="<?php _e( 'Unlink Account', 'youzer' ); ?>" data-provider="<?php echo $option['provider']; ?>" data-nonce="<?php echo $ajax_nonce; ?>"><i class="fas fa-trash-alt"></i></div>
				</div>
				<?php endif; ?>

				<div class="yz-connect-btn yz-connect-btn-<?php echo $provider;?>">
					<a href="<?php echo home_url( '/yz-auth/feed/' . $option['provider'] );?>"><i class="<?php echo $option['icon']; ?>"></i><?php echo $option['button']; ?></a>
				</div>
				<!-- <input type="hidden" class="yz-user-provider-token" < ?php echo $field_name; ?> value="< ?php echo $real_value; ?>"> -->

			</div>

		<?php

		break;
		case 'taxonomy':

			$field_name  = ! empty( $field_id ) ?  $options_name . '[' . $field_id . ']' : null;

			// Standard Field Name.
			if ( isset( $option['no_options'] ) ) {
			   $field_name = "$field_id";
			}

		?>

			<ul class="ukai_tags" data-option-name="<?php echo $field_name; ?>[]">

				<li class="tagAdd taglist">
					<input type="text" class="ukai_tags_field" tabindex="-1" placeholder="<?php _e( 'Type something then hit enter!', 'youzer' ); ?>">
				</li>

				<?php

				if ( $is_user ) {
					$tags = yz_data( $field_id );
				} else {
					$tags = $real_value;
				}

				?>

				<?php if ( $tags ) : foreach ( $tags as $tag ) : ?>

					<li class="addedTag">
						<?php echo $tag; ?>
						<span class="ukai-tagRemove">x</span>
						<input type="hidden" value="<?php echo $tag; ?>" name="<?php echo $field_name; ?>[]">
					</li>

				<?php endforeach; endif; ?>

			</ul>

		<?php break;

		case 'msgBox':

			$show_msg = $real_value;

			// Hide Message if its disabled by the user or there's no message content.
			if ( 'never' == $show_msg || empty( $option['msg'] ) ) {
				return false;
			}

			// Message Default Class.
			$msg_class[] = 'uk-panel-msg';

			// Get User Message Class.
			if ( isset( $option['msg_type'] ) ) {
				$msg_class[] = 'uk-' . $option['msg_type'] . '-msg';
			}

			// Show Or Hide Message
			if ( 'on' == $show_msg ) {
				$msg_class[] = 'uk-show-msg';
			}

			?>

            <div class="<?php echo yz_generate_class( $msg_class ); ?>">
            	<div class="uk-msg-head">
	                <span class="dashicons dashicons-editor-help uk-msg-icon"></span>
	                <h3><?php echo $option['title']; ?></h3>
	                <div class="uk-msg-actions">
		                <span class="dashicons dashicons-arrow-down-alt2 uk-toggle-msg"></span>
		                <span class="dashicons dashicons-no-alt uk-close-msg" title="<?php _e( "Don't show me this again", 'youzer' ); ?>"></span>
	                </div>
            	</div>
                <div class="uk-msg-content">
                	<p><?php echo $option['msg']; ?></p>
                </div>
                <input type="hidden" <?php echo $field_name; ?> value="<?php echo $real_value; ?>">
            </div>

	        <?php break;

		case 'imgSelect':

			foreach( $option['opts'] as $key => $value ) {

				// Which options should be selected
				if ( $value == $real_value ) {
					$active_attr = 'checked';
				} else {
					$active_attr = '';
				}

				// Get Key Value
				$key = is_numeric( $key ) ? $value : $key;

				// Get item ID
				$item_id = "$field_id-$key";

				?>

				<div class="imgSelect">
					<input type="radio" id="<?php echo $item_id ; ?>" <?php echo $field_name; ?> value="<?php echo $value; ?>" <?php echo $active_attr; ?>>
					<label for="<?php echo $item_id; ?>">
						<?php if ( ! isset( $option['use_class'] ) ) : ?>
							<img class="img-selection2" src="<?php echo YZ_AA . "images/imgSelect/$key.png"; ?>" alt="">
						<?php endif; ?>
					</label>
				</div>

				<?php

			}

		break;

		endswitch;

	}

	/**
	 * # Form Save Changes Area.
	 */
	function form_action( $args = false ) {

		// Get Security Nounce
		$security_nonce = wp_create_nonce( 'yz_nonce_security' );

		// Get Button Data.
		$button_name = isset( $args['button_name'] ) ? $args['button_name'] : 'save';
		$submit_id 	 = isset( $args['submit_id'] ) ? 'id="' . $args['submit_id'] . '"' : null;
		$button_value= isset( $args['button_value'] ) ? 'value="' . $args['button_value'] . '"' : null;

		?>

		<div class="youzer-settings-actions">
			<?php if ( ! isset( $args['hide_save_button'] ) ) : ?>
			<?php if ( ! isset( $args['hide_action'] ) ) : ?>
	            <input type="hidden" name="action" value="youzer_profile_settings_save_data">
	            <input type="hidden" name="security" value="<?php echo $security_nonce; ?>">
        	<?php endif; ?>

            <button <?php echo $submit_id; ?> <?php echo $button_value; ?> name="<?php echo $button_name; ?>" class="yz-save-options" type="submit">
            	<?php _e( 'Save Changes', 'youzer' ) ?>
            </button>

        	<?php endif; ?>

        </div>

		<?php

	}

	/**
	 * # Form Actions Area.
	 */
	function admin_form_actions( $position ) {

		?>

        <div class="panel-<?php echo $position; ?>-actions">

            <div class="ukai-actions-buttons">
                <input type="hidden" name="action" value="youzer_admin_data_save">
                <input type="hidden" name="security" value="<?php echo wp_create_nonce( "youzer-settings-data" )?>">
                <button name="save" class="yz-save-options" type="submit">
                	<?php _e( 'Save Changes', 'youzer' );  ?>
                </button>

        		<?php if ( 'bottom' == $position ) : ?>
                	<a class="yz-reset-options"><?php _e( 'Reset Settings', 'youzer' ); ?></a>
                <?php endif; ?>

            </div>

        	<?php if ( 'bottom' == $position ) : ?>
				<div class="ukai-copyright">
            		<p>
            			<?php _e( 'Designed & Developed By' ); ?>
            			<a href="http://www.kainelabs.com" target="_blank">KAINELABS.COM</a>
            		</p>
            	</div>
         	<?php endif; ?>

        </div>

		<?php

	}

	/**
	 * # Field Options .
	 */
	function get_field_options( $element ) {
		$options = array(
			'icons_colors'      => array(
				'silver'        => __( 'Silver', 'youzer' ),
				'colorful'      => __( 'Colorful', 'youzer' ),
				'transparent'   => __( 'Transparent', 'youzer' ),
				'no-bg'         => __( 'No Background', 'youzer' )
			),
			'wg_icons_colors'   => array(
				'silver'        => __( 'Silver', 'youzer' ),
				'colorful'      => __( 'Colorful', 'youzer' ),
				'no-bg'         => __( 'No Background', 'youzer' )
			),
			'icons_sizes'       => array(
				'small'         => __( 'Small', 'youzer' ),
				'medium'        => __( 'Medium', 'youzer' ),
				'big'           => __( 'Big', 'youzer' ),
				'full-width'    => __( 'Full Width', 'youzer' )
			),
			'border_styles'     => array(
				'flat'          => __( 'Flat', 'youzer' ),
				'radius'        => __( 'Radius', 'youzer' ),
				'circle'        => __( 'Circle', 'youzer' )
			),
			'card_border_styles'     => array(
				'flat'          => __( 'Flat', 'youzer' ),
				'oval'        	=> __( 'Oval', 'youzer' ),
				'radius'        => __( 'Radius', 'youzer' ),
			),
			'buttons_border_styles'     => array(
				'flat'          => __( 'Flat', 'youzer' ),
				'oval'        	=> __( 'Oval', 'youzer' ),
				'radius'        => __( 'Radius', 'youzer' ),
			),
			'image_formats'     => array(
				'flat', 'radius', 'circle'
			),
			'loading_effects'   => array(
				'fadeIn'        => __( 'FadeIn', 'youzer' ),
				'fadeInUp'      => __( 'FadeInUp', 'youzer' ),
				'fadeInLeft'    => __( 'FadeInLeft', 'youzer' ),
				'fadeInDown'    => __( 'FadeInDown', 'youzer' ),
				'fadeInRight'   => __( 'FadeInRight', 'youzer' ),
				'bounceInLeft'  => __( 'BounceInLeft', 'youzer' ),
				'fadeInUpDelay' => __( 'FadeInUpDelay', 'youzer' ),
				'bounceInRight' => __( 'BounceInRight', 'youzer' ),
			),
			'header_meta_types' => array(
				'location'      => __( 'Location', 'youzer' ),
				'username'      => __( 'Username', 'youzer' ),
				'website'       => __( 'Website', 'youzer' ),
				'email'         => __( 'E-mail', 'youzer' ),
				'phone-number'  => __( 'Phone Number', 'youzer' )
			),
			'friends_layout' => array(
				'list'  	=> __( 'List', 'youzer' ),
				'avatars'   => __( 'Avatars Only', 'youzer' )
			),
			'widgets_formats' => array(
				'flat'     => __( 'Flat', 'youzer' ),
				'radius'   => __( 'Radius', 'youzer' ),
			),
			'card_buttons_layout' => array(
				'block' => __( 'Block', 'youzer' ),
				'inline-block' => __( 'Inline Block', 'youzer' ),
			),
			'height_types' => array(
				'fixed' => __( 'Fixed', 'youzer' ),
				'auto'  => __( 'Auto', 'youzer' ),
			),
			'tabs_list_icons_style' => array(
				'yz-tabs-list-gradient' => __( 'Gradient', 'youzer' ),
				'yz-tabs-list-colorful' => __( 'Colorful', 'youzer' ),
				'yz-tabs-list-silver' 	=> __( 'Silver', 'youzer' ),
				'yz-tabs-list-white' 	=> __( 'White', 'youzer' ),
				'yz-tabs-list-gray' 	=> __( 'Gray', 'youzer' ),
			),
			'header_layouts' => array(
                'hdr-v1', 'hdr-v2', 'hdr-v3', 'hdr-v4', 'hdr-v5', 'hdr-v6', 'hdr-v7',
                'hdr-v8', 'yzb-author-v1', 'yzb-author-v2', 'yzb-author-v3', 'yzb-author-v4',
                'yzb-author-v5', 'yzb-author-v6'
			),
			'vertical_layout_navbar' => array(
                'wild-navbar', 'boxed-navbar'
			),
			'services_layout' => array(
                'vertical-services-layout', 'horizontal-services-layout'
			),
			'navbar_icons_style' => array(
                'navbar-inline-icons', 'navbar-block-icons'
			),
			'group_header_layouts' => array(
                'hdr-v1', 'hdr-v2', 'hdr-v3', 'hdr-v4', 'hdr-v5', 'hdr-v6', 'hdr-v7',
                'hdr-v8'
			),
			'author_box_layouts' => array(
                'yzb-author-v1' => __( 'Layout Version 1', 'youzer' ),
                'yzb-author-v2' => __( 'Layout Version 2', 'youzer' ),
                'yzb-author-v3' => __( 'Layout Version 3', 'youzer' ),
                'yzb-author-v4' => __( 'Layout Version 4', 'youzer' ),
                'yzb-author-v5' => __( 'Layout Version 5', 'youzer' ),
                'yzb-author-v6' => __( 'Layout Version 6', 'youzer' )
			),
			'user_login_redirect_pages' => array(
				'home'      => __( 'Home', 'youzer' ),
				'profile' 	=> __( 'Profile', 'youzer' ),
			),
			'admin_login_redirect_pages' => array(
				'home'      => __( 'Home', 'youzer' ),
				'profile' 	=> __( 'Profile', 'youzer' ),
				'dashboard' => __( 'Dashboard', 'youzer' ),
			),
			'logout_redirect_pages' => array(
				'profile'   => __( 'User Profile', 'youzer' ),
				'home'      => __( 'Home', 'youzer' ),
				'login'   	=> __( 'Login', 'youzer' ),
				'members_directory' => __( 'Members Directory', 'youzer' ),
			),
			'form_icons_position' => array(
				'logy-icons-left'   => __( 'Left', 'youzer' ),
				'logy-icons-right'  => __( 'Right', 'youzer' ),
			),
			'fields_format' => array(
				'logy-border-flat'     => __( 'Flat', 'youzer' ),
				'logy-border-radius'   => __( 'Radius', 'youzer' ),
				'logy-border-rounded'  => __( 'Rounded', 'youzer' ),
			),
			'social_buttons_type' => array(
				'logy-only-icons'  => __( 'Only Icons', 'youzer' ),
				'logy-full-button' => __( 'Full Width', 'youzer' ),
			),
			'media_layouts' => array(
				'3columns'  => __( '3 Columns', 'youzer' ),
				'4columns' => __( '4 Columns', 'youzer' ),
			)
		);
		return $options[ $element ];
	}
}

/**
 * Get a unique instance of Youzer Fields.
 */
function yz_fields() {
	return Youzer_Fields::get_instance();
}

global $Yz_Settings;

/**
 * Launch Youzer Fields!
 */
// yz_fields();

$Yz_Settings = yz_fields();