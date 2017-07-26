<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use SprykerEco\Service\Computop\ComputopServiceInterface;

class ComputopToComputopServiceBridge implements ComputopToComputopServiceInterface
{

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     */
    public function __construct(ComputopServiceInterface $computopService)
    {
        $this->computopService = $computopService;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function computopMacEncode(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->computopMacEncode($cardPaymentTransfer);
    }

    /**
     * @param string $plaintext
     * @param string $password
     *
     * @return string
     */
    public function computopBlowfishEncode($plaintext, $password)
    {
        return $this->computopService->computopBlowfishEncode($plaintext, $password);
    }

}
