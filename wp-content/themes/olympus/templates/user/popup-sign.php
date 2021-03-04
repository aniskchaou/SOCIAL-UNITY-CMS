<div class="crumina-module crumina-window-popup" id="registration-login-form-popup" tabindex="-1" role="dialog" aria-hidden="true"
		 x-show="isModalOpen()"
		 x-cloak
		 x-transition:enter="transition ease-out duration-300"
		 x-transition:enter-start="opacity-0 transform -translate-y-40"
		 x-transition:enter-end="opacity-100 transform translate-y-0"
		 x-transition:leave="transition ease-in duration-300"
		 x-transition:leave-start="opacity-100 transform translate-y-0"
		 x-transition:leave-end="opacity-0 transform -translate-y-40"
	>
		<div class="modal-dialog window-popup registration-login-form-popup" role="document" @click.away="modalClose">
			<div class="modal-content">
				<div class="close icon-close"
				   @click="modalClose"
				>
					<i class="olymp-close-icon olympus-icon-Close-Icon"></i>
				</div>
				<div class="modal-body no-padding">
					<?php
					if ( function_exists( 'crumina_get_reg_form_html' ) ) {
						echo crumina_get_reg_form_html();
					} else {
						esc_html_e( 'Crumina Sign in Form extension required', 'olympus' );
					}
					?>
				</div>
			</div>
		</div>
	</div>
