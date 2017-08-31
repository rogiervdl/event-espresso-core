<?php
namespace Page;


/**
 * Checkout
 * Selectors/references for elements related to the checkout.
 *
 * @package Page
 * @author  Darren Ethier
 * @since   1.0.0
 */
class Checkout
{

    /**
     * The class selector for the next step button in the checkout.
     * @var string
     */
    const NEXT_STEP_BUTTON_SELECTOR = '.spco-next-step-btn';

    /**
     * @param int $attendee_number
     * @return string
     */
    public static function firstNameFieldSelectorForAttendeeNumber($attendee_number = 1)
    {
        return self::fieldSelectorForAttendeeNumber('fname', $attendee_number);
    }


    /**
     * @param int $attendee_number
     * @return string
     */
    public static function lastNameFieldSelectorForAttendeeNumber($attendee_number = 1)
    {
        return self::fieldSelectorForAttendeeNumber('lname', $attendee_number);
    }


    /**
     * @param int $attendee_number
     * @return string
     */
    public static function emailFieldSelectorForAttendeeNumber($attendee_number = 1)
    {
        return self::fieldSelectorForAttendeeNumber('email', $attendee_number);
    }

    /**
     * @param     $field_name
     * @param int $attendee_number
     * @return string
     */
    public static function fieldSelectorForAttendeeNumber($field_name, $attendee_number = 1)
    {
        return "//div[starts-with(@id, 'spco-attendee-panel-dv-$attendee_number')]//input[contains(@class, 'ee-reg-qstn-$field_name')]";
    }
}
