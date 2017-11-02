<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class SofortMapper extends AbstractPostPlaceMapper
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSofortPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopSofortPaymentTransfer();

        $computopPaymentTransfer->setCapture(ComputopSharedConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::SOFORT_SUCCESS))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getTestModeDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }
}