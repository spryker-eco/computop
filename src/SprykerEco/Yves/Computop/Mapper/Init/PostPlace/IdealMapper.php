<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class IdealMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopIdealPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopIdealPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::IDEAL_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        //ToDo:update mapper
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }
}
