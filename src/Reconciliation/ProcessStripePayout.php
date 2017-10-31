<?php

namespace FernleafSystems\Integrations\Stripe_Freeagent\Reconciliation;

use FernleafSystems\ApiWrappers\Base\ConnectionConsumer;
use FernleafSystems\Integrations\Stripe_Freeagent\Consumers\BankTransactionVoConsumer;
use FernleafSystems\Integrations\Stripe_Freeagent\Consumers\BridgeConsumer;
use FernleafSystems\Integrations\Stripe_Freeagent\Consumers\StripePayoutConsumer;

/**
 * Verifies all invoices associated with the payout are present and accurate within Freeagent
 * Then reconciles all local invoices/Stripe Charges with the exported invoices within Freeagent
 * Class StripeChargesWithFreeagentTransaction
 * @package iControlWP\Integration\FreeAgent\Reconciliation
 */
class ProcessStripePayout {

	use BankTransactionVoConsumer,
		BridgeConsumer,
		ConnectionConsumer,
		StripePayoutConsumer;

	/**
	 * @throws \Exception
	 */
	public function process() {

		$aReconData = ( new InvoicesVerify() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setBridge( $this->getBridge() )
			->run();

		( new ExplainBankTxnWithInvoices() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setBridge( $this->getBridge() )
			->setBankTransactionVo( $this->getBankTransactionVo() )
			->run( $aReconData );
	}
}