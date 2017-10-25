<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

interface ComputopConfigInterface
{
    /**
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath();

    /**
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath();

    /**
     * @return string
     */
    public function getCallbackFailureRedirectPath();
}
