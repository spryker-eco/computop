<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class AuthorizeResponseHandler extends AbstractResponseHandler
{

    use DatabaseTransactionHandlerTrait;

    const METHOD = 'AUTHORIZE';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function handle(
        ComputopCreditCardAuthorizeResponseTransfer $responseTransfer
    ) {
        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopOrderDetails($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails($responseTransfer)
    {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);
        // TODO: implement saving
    }

}
