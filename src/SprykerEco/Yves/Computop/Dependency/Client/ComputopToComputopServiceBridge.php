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
    public function computopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->computopMacHashHmacValue($cardPaymentTransfer);
    }

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function blowfishEncryptedValue($plaintext, $len, $password)
    {
        return $this->computopService->blowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function blowfishDecryptedValue($cipher, $len, $password)
    {
        return $this->computopService->blowfishDecryptedValue($cipher, $len, $password);
    }

}
