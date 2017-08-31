<?php

use Page\EventsAdmin;

$I = new EventEspressoAcceptanceTester($scenario, false);

$I->wantTo('Test various features/elements in the event editor.');
$I->amGoingTo('Test ticket total calculations via js in the ticket editor.');
$I->loginAsAdmin();
$I->amOnDefaultEventsListTablePage();
$I->click(EventsAdmin::ADD_NEW_EVENT_BUTTON_SELECTOR);
$I->fillField(EventsAdmin::eventEditorTicketPriceFieldSelector(), '32.50');
$I->toggleAdvancedSettingsViewForTicketRow();
$I->waitForElementVisible(EventsAdmin::eventEditorTicketAdvancedDetailsSubtotalSelector());
$I->see('$32.50', EventsAdmin::eventEditorTicketAdvancedDetailsSubtotalSelector());
$I->toggleTicketIsTaxableForTicketRow();
$I->see('$4.88', EventsAdmin::eventEditorTicketTaxAmountDisplayForTaxIdAndTicketRowSelector());
$I->see('$37.38', EventsAdmin::eventEditorTicketAdvancedDetailsTotalSelector());
$I->saveEvent();

$I->amGoingTo('Test the same ticket total calculations via js in the ticket editor but with this format: 1.000,00');
$I->amOnCountrySettingsAdminPage();
$I->setCurrencyDecimalMarkTo(',');
$I->setCurrencyThousandsSeparatorTo('.');
$I->saveCountrySettings();
$I->amOnDefaultEventsListTablePage();
$I->click(EventsAdmin::ADD_NEW_EVENT_BUTTON_SELECTOR);
$I->fillField(EventsAdmin::eventEditorTicketPriceFieldSelector(), '32,50');
$I->toggleAdvancedSettingsViewForTicketRow();
$I->waitForElementVisible(EventsAdmin::eventEditorTicketAdvancedDetailsSubtotalSelector());
$I->see('$32,50', EventsAdmin::eventEditorTicketAdvancedDetailsSubtotalSelector());
$I->toggleTicketIsTaxableForTicketRow();
$I->see('$4,88', EventsAdmin::eventEditorTicketTaxAmountDisplayForTaxIdAndTicketRowSelector());
$I->see('$37,38', EventsAdmin::eventEditorTicketAdvancedDetailsTotalSelector());

//restore country settings to original
$I->saveEvent();
$I->amOnCountrySettingsAdminPage();
$I->setCurrencyDecimalMarkTo('.');
$I->setCurrencyThousandsSeparatorTo(',');
$I->saveCountrySettings();