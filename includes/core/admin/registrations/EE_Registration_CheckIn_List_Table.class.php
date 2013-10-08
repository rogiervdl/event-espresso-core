<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			{@link http://eventespresso.com/support/terms-conditions/}   * see Plugin Licensing *
 * @ link			{@link http://www.eventespresso.com}
 * @ since		 	4.0
 *
 * ------------------------------------------------------------------------
 *
 * EE_Registration_CheckIn_List_Table
 * This class handles all the logic for the display of the Checkin List table.  When viewed, all checkins for a given registration are displayed.
 *
 * @package			Event Espresso
 * @subpackage		includes/core/admin/registrations/EE_Registration_CheckIn_List_Table
 * @author			Darren Ethier
 *
 * ------------------------------------------------------------------------
 */


class EE_Registration_CheckIn_List_Table extends EE_Admin_List_Table {



	public function __construct( $admin_page ) {
		parent::__construct($admin_page);
	}




	protected function _setup_data() {
		$this->_per_page = $this->get_items_per_page( $this->_screen . '_per_page' );
		$this->_data = $this->_get_checkins( $this->_per_page );
		$this->_all_data_count = $this->_get_checkins(  $this->_per_page, TRUE );
	}




	protected function _set_properties() {
		$this->_wp_list_args = array(
			'singular' => __('checkin', 'event_espresso'),
			'plural' => __('checkin', 'event_espresso'),
			'ajax' => TRUE,
			'screen' => $this->_admin_page->get_current_screen()->id
			);

		$this->_columns = array(
			'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
			'CHK_in' => __('Check In', 'event_espresso'),
			'CHK_timestamp' => __('Timestamp', 'event_espresso')
			);

		$this->_sortable_columns = array(
			'CHK_timestamp' => array( 'CHK_timestamp' => TRUE )
			);

		$this->_hidden_columns = array();
	}


	protected function _get_table_filters() {
		return array();
	}

	//remove the search box
	function search_box() {
		return '';
	}


	protected function _add_view_counts() {
		$this->_views['all']['count'] = $this->_get_checkins(NULL);
	}


	function column_cb( EE_Checkin $item ) {
		return sprintf( '<input type="checkbox" name="checkbox[%1$s]" />', $item->ID() );
	}


	function column_CHK_in( EE_Checkin $item ) {
		$checkinstatus = $item->get('CHK_in');
		$checkinstatus = $checkinstatus ? 1 : 2;
		return '<span class="checkin-icons checkedin-status-' . $checkinstatus . '"></span>';
	}



	function column_CHK_timestamp( EE_Checkin $item ) {
		$actions = array();
		$delete_url = EE_Admin_Page::add_query_args_and_nonce( array('action' => 'delete_checkin_row', 'DTT_ID' => $this->_req_data['DTT_ID'], 'REGID' => $this->_req_data['REGID'], 'CHK_ID' => $item->ID() ) );
		$actions['delete_checkin'] = '<a href="' . $delete_url . '" title="' . __('Click here to delete this check in record', 'event_espresso') . '">' . __('Delete', 'event_espresso') . '</a>';

		return sprintf( '%1$s %2$s', $item->get_datetime('CHK_timestamp'), $this->row_actions($actions) );
	}




	/**
	 * This retrieves all the checkins for the given parameters.
	 * experimenting with having the query for the table values within the list table.
	 *
	 * @access protected
	 * @param int     $per_page     How many to retrieve per page
	 * @param bool    $count        Whether to return a count or not
	 * @return EE_Checkin[]|int
	 */
	protected function _get_checkins( $per_page = 10, $count = FALSE ) {
		$REG_ID = isset( $this->_req_data['REGID'] ) ? $this->_req_data['REGID'] : FALSE;
		$DTT_ID = isset( $this->_req_data['DTT_ID'] ) ? $this->_req_data['DTT_ID'] : FALSE;

		//if no reg id then get out cause need a reg id
		if ( empty( $REG_ID ) || empty( $DTT_ID ) )
			throw new EE_Error(__('This route cannot be viewed unless registration and datetime IDs are included in the request (via REG_ID and DTT_ID parameters)', 'event_espresso') );

		//set orderby
		$orderby = 'CHK_timestamp'; //note that with this table we're only providing the option to orderby the timestamp value.

		$order = !empty( $this->_req_data['order'] ) ? $this->_req_data['order'] : 'ASC';

		$current_page = isset( $this->_req_data['paged'] ) && !empty( $this->_req_data['paged'] ) ? $this->_req_data['paged'] : 1;
		$per_page = isset( $this->_req_data['perpage'] ) && !empty( $this->_req_data['perpage'] ) ? $this->_req_data['perpage'] : $per_page;
		$limit = array();
		if ( !empty( $perpage ) ) {
			$offset = ($current_page-1)*$per_page;
			$limit = array( $offset, $per_page );
		}

		$_where = array(
			'REG_ID' => $REG_ID,
			'DTT_ID' => $DTT_ID
			);

		$query_params = array( $_where, 'order_by' => array( $orderby => $order ), 'limit' => $limit );

		//if no per_page value then we just want to return a count of all checkins
		if ( empty( $per_page ) )
			return EEM_Checkin::instance()->count( array( $_where ) );

		return $count ? EEM_Checkin::instance()->count( array( $_where ) ) : EEM_Checkin::instance()->get_all($query_params);
	}

} //end class EE_Registration_CheckIn_List_Table