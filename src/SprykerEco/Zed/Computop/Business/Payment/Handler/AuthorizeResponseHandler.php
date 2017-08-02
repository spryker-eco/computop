<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopCreditCardResponseTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class AuthorizeResponseHandler extends AbstractResponseHandler
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function handle(
        ComputopCreditCardResponseTransfer $responseTransfer
    ) {
        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopOrderDetails($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails($responseTransfer)
    {
        // TODO: implement
    }

}
