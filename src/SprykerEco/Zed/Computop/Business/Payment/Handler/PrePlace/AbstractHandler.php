<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\PrePlaceRequestInterface;

abstract class AbstractHandler implements PrePlaceHandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\PrePlaceRequestInterface
     */
    protected $request;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\PrePlaceRequestInterface $request
     */
    public function __construct(
        PrePlaceRequestInterface $request
    ) {
        $this->request = $request;
    }
    
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer
     */
    protected function createComputopHeaderPayment(QuoteTransfer $quoteTransfer)
    {
        $headerPayment = new ComputopHeaderPaymentTransfer();
        $computopEntity = $quoteTransfer->getPayment()->getComputopEasyCredit();
        $headerPayment->fromArray($computopEntity->toArray(), true);

        return $headerPayment;
    }
}
