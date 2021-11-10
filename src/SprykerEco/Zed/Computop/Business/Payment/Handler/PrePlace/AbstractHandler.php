<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;

abstract class AbstractHandler implements PrePlaceHandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface
     */
    protected $computopApiFacade;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface $computopApiFacade
     */
    public function __construct(ComputopToComputopApiFacadeInterface $computopApiFacade)
    {
        $this->computopApiFacade = $computopApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer
     */
    protected function createComputopHeaderPayment(QuoteTransfer $quoteTransfer): ComputopApiHeaderPaymentTransfer
    {
        $headerPayment = new ComputopApiHeaderPaymentTransfer();
        $computopEntity = $quoteTransfer->getPayment()->getComputopEasyCredit();
        $headerPayment->fromArray($computopEntity->toArray(), true);

        return $headerPayment;
    }
}
