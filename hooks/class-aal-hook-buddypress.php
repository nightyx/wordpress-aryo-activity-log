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

    public function hooks_aal_bp_leave_group ($group_id, $user_id) {
        $group = groups_get_group( array( 'group_id' => $group_id ) );

        aal_insert_log( array(
            'action'      => 'left group',
            'object_type' => 'BuddyPress',
            'user_id'     => $user_id,
            'object_id'   => $group_id,
            'object_name' => $group->name,
        ) );
    }

    public function hooks_aal_bp_group_created($group_id, $user_id) {
        $group = groups_get_group( array( 'group_id' => $group_id ) );

        aal_insert_log( array(
            'action'      => 'created group',
            'object_type' => 'BuddyPress',
            'user_id'     => $user_id,
            'object_id'   => $group_id,
            'object_name' => $group->name,
        ) );
    }

    public function hooks_aal_bp_membershiprequest_group($requesting_user_id, $admins, $group_id, $requesting_user ) {
        $group = groups_get_group( array( 'group_id' => $group_id ) );

        aal_insert_log( array(
            'action'      => 'requested membership for group',
            'object_type' => 'BuddyPress',
            'user_id'     => $requesting_user_id,
            'object_id'   => $group_id,
            'object_name' => $group->name,
        ) );
    }

    public function hooks_aal_bp_request_accepted( $user_id, $group_id, $bool ){
        $group = groups_get_group( array( 'group_id' => $group_id ) );
        $current_user = get_current_user();
        $accepted_user = get_user_by( 'id', $user_id );

        aal_insert_log( array(
            'action'      => 'Accepted',
            'object_type' => 'BuddyPress',
            'user_id'     => $current_user->ID,
            'object_id'   => $group_id,
            'object_name' => 'Request of User ' . $accepted_user->user_nicename . ' (ID: ' . $accepted_user->ID . ') in Group: ' . $group->name,
        ) );

        // get additional entry for user joined group
        //TODO: why is this line added before the insert above?
        $this->hooks_aal_bp_join_group($group_id, $user_id);
    }

    public function hooks_aal_bp_request_rejected( $user_id, $group_id, $bool ){
        $group = groups_get_group( array( 'group_id' => $group_id ) );
        $current_user = get_current_user();
        $rejected_user = get_user_by( 'id', $user_id );

        aal_insert_log( array(
            'action'      => 'Rejected',
            'object_type' => 'BuddyPress',
            'user_id'     => $current_user->ID,
            'object_id'   => $group_id,
            'object_name' => 'Request of User ' . $rejected_user->user_nicename . ' (ID: ' . $rejected_user->ID . ') in Group: ' . $group->name,
        ) );
    }

    public function __construct(){
        //TODO: class exists for buddypress
        add_action('groups_join_group', array( &$this, 'hooks_aal_bp_join_group'), 10, 2);
        add_action('groups_leave_group', array( &$this, 'hooks_aal_bp_leave_group'), 10, 2);

        add_action('groups_membership_requested', array( &$this, 'hooks_aal_bp_membershiprequest_group'), 10, 4);
        //TODO: Invite members

        add_action('groups_membership_accepted', array( &$this, 'hooks_aal_bp_request_accepted'), 10, 3);
        add_action('groups_membership_rejected', array( &$this, 'hooks_aal_bp_request_rejected'), 10, 3);

        //TODO: show name as link in description column
        add_action('groups_group_create_complete', array( &$this, 'hooks_aal_bp_group_created'), 10, 2);

        parent::__construct();
    }
}