<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Saver;

use Generated\Shared\Transfer\ComputopApiCrifResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopCrifDetails;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CrifSaver extends AbstractSaver
{
    use TransactionTrait;

    protected const METHOD = 'CRIF';

    /**
     * @param \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function save(ComputopApiCrifResponseTransfer $responseTransfer, QuoteTransfer $quoteTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($responseTransfer) {
                $this->saveComputopDetails($responseTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(
        ComputopApiCrifResponseTransfer $responseTransfer
    ): void {
        $this->logHeader($responseTransfer->getHeader(), static::METHOD);

        $paymentEntityDetails = new SpyPaymentComputopCrifDetails();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();
    }
}
