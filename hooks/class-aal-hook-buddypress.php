<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_BuddyPress extends AAL_Hook_Base {

    public function hooks_aal_bp_join_group( $group_id, $user_id ){
        $group = groups_get_group( array( 'group_id' => $group_id ) );

        aal_insert_log( array(
            'action'      => 'joined group',
            'object_type' => 'BuddyPress',
            'user_id'     => $user_id,
            'object_id'   => $group_id,
            'object_name' => $group->name,
        ) );
    }

    public function __construct(){
        add_action('groups_join_group', array( &$this, 'hooks_aal_bp_join_group'), 5, 2);

        parent::__construct();
    }
}