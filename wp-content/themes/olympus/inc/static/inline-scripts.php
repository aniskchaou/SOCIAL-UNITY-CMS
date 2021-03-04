<?php
/**
 * Scripts to include on front pages
 *
 * @package olympus-wp
 */


function olympus_add_alpine_functions_js(){
	echo '<script>
		function olympusModal() {
			return {
				show: false,
				modalOpen() { this.show = true; document.querySelector(\'body\').classList.add(\'overlay-enable\') },
				modalClose() { this.show = false; document.querySelector(\'body\').classList.remove(\'overlay-enable\') },
				isModalOpen() { return this.show === true },
			}
		}
		</script>';
}

add_action( 'wp_head', 'olympus_add_alpine_functions_js' );