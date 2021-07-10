<?php

/**
 * This function will return an array of submission types
 *
 * @return Array
 */
function get_submission_types() {
	return array(
		'wedding' => 'Wedding',
		'engagement' => 'Engagement',
		'bridals' => 'Bridals',
		'styled_shoot' => 'Styled Shoot'
	);
}

/**
 * This function will return an array of priority types
 *
 * @return Array
 */
function get_priority_types() {
	return array(
		'norush' => 'No',
		'yesrush' => 'Yes',
	);
}

/**
 * This function will return an array of submission status
 *
 * @return Array
 */
function get_submission_status_list() {
	return array(
		'pending' => 'Pending',
		'approved' => 'Approved',
		'approved_need_vendors' => 'Approved - Need Vendors',
		'placed' => 'Placed',
		'rejected' => 'Rejected',
		'reassigned' => 'Save for Next Issue',		
	);
}

/**
 * This function will return an array of submission status
 *
 * @return Array
 */
function get_ad_sizes_graphic_design_ticket() {
	return array(
		'full_page'         => 'Full Page',
		'one_and_half_page' => '½ Page',
		'one_fourth_page'   => '¼ Page',
		'one_eight_page'    => '⅛ Page',
		'two_page_spread'   => '2 Page Spread',
		'front_cover'       => 'Front Cover',
		'back_cover'       => 'Back Cover',	
		'square_coupon'       => 'Square Coupon',
		'brochure_coupon'       => 'Small Coupon',
		'back_coupon'       => 'Back Cover Coupon',		
	);
}

/**
 * This function will return an array of ticket status
 *
 * @return Array
 */
function get_ticket_status_list() {
	return array(
		'pending' => 'New Ticket',
		'in_progress' => 'In Progress',
		'submitted_by_designer' => 'Proof Ready',
		'awaiting_approval' => 'Awaiting Approval from Advertiser',
		'revision_requested' => 'Revision Requested',
		'revision_in_progress' => 'Revision In Progress',
		'revision_completed' => 'Revision Completed',
		'approved' => 'Approved',
		'completed_ticket' => 'Completed',		
	);
}

/**
 * This function will return an array of issue status
 *
 * @return Array
 */
function get_issue_status_list() {
	return array(
		//'in_progress' => 'Requesting Submissions',
		'requesting_submissions' => 'Selling Advertising',
		'ready_start' => 'Ready to Start Draft',		
		'draft_production' => 'Draft Started',
		'draft_completed' => 'Draft Approval Needed',
		'draft_approved' => 'Draft Approved',
		'revision_requested' => 'Revision Requested',
		'revision_completed' => 'Revision Completed',
		'ready_to_finalize' => 'Ready to Finalize',
		'issue_finalized' => 'Issue Finalized',		
		'completed' => 'Completed',
	);
}

function get_advertiser_payment_status_list() {
	return array(
		'pending' => 'Pending',
		'invoice_sent' => 'Invoice Sent',
		'paid' => 'Paid',
		'free' => 'Free Ad'
	);
}

function get_advertiser_ad_size_list() {
	return array(
		'1by8_page' => '1/8 Page',
		'1by4_page' => '1/4 Page',
		'1by2_page' => '1/2 Page',
		'full_page' => 'Full Page',
		'2_page_spread' => '2 Page Spread',
		'coupon_ad' => 'Coupon',
		'front_cover' => 'Front Cover',
		'back_cover' => 'Back Cover',
		'back_coupon' => 'Back Cover Coupon',
		'still_deciding' => 'Still Deciding',		
	);
}

/**
 * This function will extract status value from given type key.
 *
 * @param String $key
 * @return String
 */
function get_submission_type( $key ) {
	$types = get_submission_types();

	if ( array_key_exists( $key, $types ) ) {
		return $types[ $key ];
	}

	return '';
}

/**
 * This function will extract status value from given status key.
 *
 * @param String $key
 * @return String
 */
function get_submission_status( $key ) {
	$status_list = get_submission_status_list();

	if ( array_key_exists( $key, $status_list ) ) {
		return $status_list[ $key ];
	}

	return '';
}

/**
 * This function will extract status value from given status key.
 *
 * @param String $key
 * @return String
 */
function get_ticket_status( $key ) {
	$status_list = get_ticket_status_list();

	if ( array_key_exists( $key, $status_list ) ) {
		return $status_list[ $key ];
	}

	return '';
}

/**
 * This function will extract status value from given status key.
 *
 * @param String $key
 * @return String
 */
function get_issue_status( $key ) {
	$status_list = get_issue_status_list();

	if ( array_key_exists( $key, $status_list ) ) {
		return $status_list[ $key ];
	}

	return $status_list['draft_production'];
}

/**
 * This function will extract status value from given status key.
 *
 * @param String $key
 * @return String
 */
function get_advertiser_payment_status( $key ) {
	$status_list = get_advertiser_payment_status_list();

	if ( array_key_exists( $key, $status_list ) ) {
		return $status_list[ $key ];
	}

	return '-';
}

/**
 * This function will extract size value from given size key.
 *
 * @param String $key
 * @return String
 */
function get_advertiser_ad_size( $key ) {
	$size_list = get_advertiser_ad_size_list();

	if ( array_key_exists( $key, $size_list ) ) {
		return $size_list[ $key ];
	}

	return '-';
}