<?php
namespace EventEspresso\core\services\commands\ticket;

use EventEspresso\core\services\commands\Command;

if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}



/**
 * Class CreateTicketLineItemCommand
 * DTO for passing data to CreateTicketLineItemCommandHandler
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 * @since         4.9.0
 */
class CreateTicketLineItemCommand extends Command
{


	/**
	 * @var \EE_Transaction $transaction
	 */
	private $transaction;

	/**
	 * @var \EE_Ticket $ticket
	 */
	private $ticket;

	/**
	 * @var int $quantity
	 */
	private $quantity;

	/**
	 * @var \EE_Line_Item $ticket_line_item
	 */
	protected $ticket_line_item;



	/**
	 * CreateTicketLineItemCommand constructor.
	 *
	 * @param \EE_Transaction     $transaction
	 * @param \EE_Ticket          $ticket
	 * @param int                 $quantity
	 */
	public function __construct(
		\EE_Transaction $transaction,
		\EE_Ticket $ticket,
		$quantity = 1
	) {
		$this->transaction = $transaction;
		$this->ticket = $ticket;
		$this->quantity = $quantity;
        // commands have moved to different directory so this is deprecated
        // can't use $this in Closures, so make a copy to pass in
        $this_command = $this;
        add_filter(
            'FHEE__EventEspresso\core\services\commands\CommandHandlerManager__getCommandHandler__command_handler',
            function ($command_name, Command $command) use ($this_command) {
                if ($command === $this_command) {
                    $command_name = 'EventEspresso\core\services\commands\ticket\CreateTicketLineItemCommand';
                }
                return $command_name;
            },
            10, 2
        );
        \EE_Error::doing_it_wrong(
            'EventEspresso\core\services\commands\ticket\CreateTicketLineItemCommand',
            esc_html__(
                'All Commands found in "/core/services/commands/ticket/" have been moved to "/core/domain/services/commands/ticket/"',
                'event_espresso'
            ),
            '4.9.35',
            '5.0.0'
        );
    }



	/**
	 * @return \EE_Transaction
	 */
	public function transaction() {
		return $this->transaction;
	}



	/**
	 * @return \EE_Ticket
	 */
	public function ticket() {
		return $this->ticket;
	}



	/**
	 * @return int
	 */
	public function quantity() {
		return $this->quantity;
	}



	/**
	 * @return \EE_Line_Item
	 */
	public function ticketLineItem() {
		return $this->ticket_line_item;
	}


}
// End of file CreateTicketLineItemCommand.php
// Location: /CreateTicketLineItemCommand.php