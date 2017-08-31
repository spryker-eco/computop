<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Computop\ComputopFactory getFactory()
 */
class ComputopClient extends AbstractClient implements ComputopClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponse(ComputopResponseHeaderTransfer $responseTransfer)
    {
         $this->getFactory()->createZedStub()->logResponse($responseTransfer);
    }

}
