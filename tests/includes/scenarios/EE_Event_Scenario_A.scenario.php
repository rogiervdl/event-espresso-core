<?php
if (!defined('EVENT_ESPRESSO_VERSION'))
	exit('No direct script access allowed');

/**
 * This scenario creates an event that has:
 * - Three Datetimes
 *      - D1 - reg limit 5
 *      - D2 - reg limit 20
 *      - D3 - reg limit 12
 * - Four Tickets
 *      - TA - qty 30 (D1, D2, D3)
 *      - TB - qty 15 (D1,D2)
 *      - TC - qty 5 (D1)
 *      - TD - qty 20 (D2, D3)
 *
 * @package    Event Espresso
 * @subpackage tests/scenarios
 * @author     Darren Ethier
 */
class EE_Event_Scenario_A extends EE_Test_Scenario {

	public function __construct( EE_UnitTestCase $eetest ) {
		$this->type = 'event';
		$this->name = 'Event Scenario A';
		parent::__construct( $eetest );
	}

	protected function _set_up_expected(){
		$this->_expected_values = array(
			'total_available_spaces' => 15,
			'total_remaining_spaces' => 15
		);
	}


	protected function _set_up_scenario(){
		$build_artifact = array(
			'Event' => array(
				0 => array(
					'fields' => array( 'EVT_name' => 'Test Scenario EVT A' )
				)
			),
			'Datetime' => array(
				0 => array(
					'fields' => array(
						'DTT_name' => 'Datetime 1',
						'DTT_reg_limit' => 5
					),
					'relations' => array(
						'Event' => array( 0 )
					)
				),
				1 => array(
					'fields' => array(
						'DTT_name' => 'Datetime 2',
						'DTT_reg_limit' => 20
					),
					'relations' => array(
						'Event' => array( 0 )
					)
				),
				2 => array(
					'fields' => array(
						'DTT_name' => 'Datetime 3',
						'DTT_reg_limit' => 12
					),
					'relations' => array(
						'Event' => array( 0 )
					)
				),
			),
			'Ticket' => array(
				0 => array(
					'fields' => array(
						'TKT_name' => 'Ticket A',
						'TKT_qty' => 30
					),
					'relations' => array(
						'Datetime' => array( 0, 1, 2 )
					)
				),
				1 => array(
					'fields' => array(
						'TKT_name' => 'Ticket B',
						'TKT_qty' => 5
					),
					'relations' => array(
						'Datetime' => array( 0, 1 )
					)
				),
				2 => array(
					'fields' => array(
						'TKT_name' => 'Ticket C',
						'TKT_qty' => 15
					),
					'relations' => array(
						'Datetime' => array( 0 )
					)
				),
				3 => array(
					'fields' => array(
						'TKT_name' => 'Ticket D',
						'TKT_qty' => 10
					),
					'relations' => array(
						'Datetime' => array( 1, 2 )
					)
				),
			)
		);

		$build_objects = $this->_eeTest->factory->complex_factory->build( $build_artifact );

		//assign the event object as the scenario object
		$this->_scenario_object = reset( $build_objects['Event'] );
	}



	protected function _get_scenario_object(){
		return $this->_scenario_object;
	}
}