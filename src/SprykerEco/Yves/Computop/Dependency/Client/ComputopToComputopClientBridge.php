<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Client\Computop\ComputopClientInterface;

class ComputopToComputopClientBridge implements ComputopToComputopClientInterface
{

    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * ComputopToComputopClientBridge constructor.
     *
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct(ComputopClientInterface $computopClient)
    {
        $this->computopClient = $computopClient;
    }

    /**
     * @inheritdoc
     */
    public function logResponse(ComputopResponseHeaderTransfer $cardPaymentTransfer)
    {
        return $this->computopClient->logResponse($cardPaymentTransfer);
    }

}
