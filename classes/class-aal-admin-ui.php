<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Admin_Ui {

	public function create_admin_menu() {
		$menu_capability = current_user_can( 'view_all_aryo_activity_log' ) ? 'view_all_aryo_activity_log' : 'edit_pages';
		
		add_menu_page( __( 'Activity Log', 'aryo-aal' ), __( 'Activity Log', 'aryo-aal' ), $menu_capability, 'activity_log_page', array( &$this, 'activity_log_page_func' ), '', '2.1' );
		//add_dashboard_page( __( 'Activity Log', 'aryo-aal' ), __( 'Activity Log', 'aryo-aal' ), $menu_capability, 'activity_log_page', array( &$this, 'activity_log_page_func' ) );
	}

	public function activity_log_page_func() {
		$activity_table = new AAL_Activity_Log_List_Table();
		$activity_table->prepare_items();
		?>
		<div class="wrap">
			<h2><?php _e( 'Activity Log', 'aryo-aal' ); ?></h2>

			<form id="activity-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php $activity_table->display(); ?>
			</form>
		</div>
		
		<?php // TODO: move to a separate file. ?>
		<style>
			.aal-pt {
				color: #ffffff;
				padding: 1px 4px;
				margin: 0 5px;
				font-size: 1em;
				border-radius: 3px;
				background: #808080;
				font-family: inherit;
			}
			.toplevel_page_activity_log_page .column-author {
				width: 15%;
			}
			#adminmenu #toplevel_page_activity_log_page div.wp-menu-image:before {
				content: "\f321";
			}
		</style>
		<?php
	}
	
	public function admin_header() {
		// TODO: move to a separate file.
		?><style>
			#adminmenu #toplevel_page_activity_log_page div.wp-menu-image:before {
				content: "\f321";
			}
		</style>
	<?php
	}
	
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'create_admin_menu' ), 20 );
		add_action( 'admin_head', array( &$this, 'admin_header' ) );
	}
}
