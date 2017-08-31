<?php
defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * plz see: https://events.codebasehq.com/projects/event-espresso/tickets/10980
 *
 * This scenario creates an event that has:
 * - 10 Datetimes that each have
 *      - reg limit 200
 *
 * - 11 Tickets
 *      - 10 tickets that ONLY have access to their single corresponding datetime (ie Ticket 1 => Datetime 1)
 *      - 1 ALL ACCESS PASS that allows access to ALL 10datetimes
 *
 *  MAX SELLOUT:
 *        200 ALL ACCESS PASSES
 *
 * @package    Event Espresso
 * @subpackage tests/scenarios
 * @author     Brent Christensen
 */
class EE_Event_Scenario_L extends EE_Test_Scenario
{

    public function __construct(EE_UnitTestCase $eetest)
    {
        $this->type = 'event';
        $this->name = 'Event Scenario K';
        parent::__construct($eetest);
    }

    protected function _set_up_expected()
    {
        $this->_expected_values = array(
            'total_available_spaces' => 200,
            'total_remaining_spaces' => 100,
        );
    }


    protected function _set_up_scenario()
    {
        $build_artifact = array(
            'Event'    => array(
                0 => array(
                    'fields' => array('EVT_name' => 'Test Scenario EVT K'),
                ),
            ),
            'Datetime' => array(
                0 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 1',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                1 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 2',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                2 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 3',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                3 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 4',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                4 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 5',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                5 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 6',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                6 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 7',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                7 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 8',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                8 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 9',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
                9 => array(
                    'fields'    => array(
                        'DTT_name'      => 'Band 10',
                        'DTT_reg_limit' => 200,
                    ),
                    'relations' => array(
                        'Event' => array(0),
                    ),
                ),
            ),
            'Ticket'   => array(
                0 => array(
                    'fields'    => array(
                        'TKT_name' => 'ALL ACCESS PASS',
                        'TKT_sold' => 100,
                    ),
                    'relations' => array(
                        'Datetime' => array(0,1,2,3,4,5,6,7,8,9),
                    ),
                ),
                1 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 1',
                    ),
                    'relations' => array(
                        'Datetime' => array(0),
                    ),
                ),
                2 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 2',
                    ),
                    'relations' => array(
                        'Datetime' => array(1),
                    ),
                ),
                3 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 3',
                    ),
                    'relations' => array(
                        'Datetime' => array(2),
                    ),
                ),
                4 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 4',
                    ),
                    'relations' => array(
                        'Datetime' => array(3),
                    ),
                ),
                5 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 5',
                    ),
                    'relations' => array(
                        'Datetime' => array(4),
                    ),
                ),
                6 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 6',
                    ),
                    'relations' => array(
                        'Datetime' => array(5),
                    ),
                ),
                7 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 7',
                    ),
                    'relations' => array(
                        'Datetime' => array(6),
                    ),
                ),
                8 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 8',
                    ),
                    'relations' => array(
                        'Datetime' => array(7),
                    ),
                ),
                9 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 9',
                    ),
                    'relations' => array(
                        'Datetime' => array(8),
                    ),
                ),
                10 => array(
                    'fields'    => array(
                        'TKT_name' => 'Ticket 10',
                    ),
                    'relations' => array(
                        'Datetime' => array(9),
                    ),
                ),
            ),
        );
        $build_objects  = $this->_eeTest->factory->complex_factory->build($build_artifact);
        //assign the event object as the scenario object
        $this->_scenario_object = reset($build_objects['Event']);
    }



    protected function _get_scenario_object()
    {
        return $this->_scenario_object;
    }
}
// Location: EE_Event_Scenario_L.scenario.php
