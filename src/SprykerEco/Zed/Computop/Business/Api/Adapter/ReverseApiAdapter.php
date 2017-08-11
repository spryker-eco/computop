<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

class ReverseApiAdapter extends AbstractApiAdapter
{

    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->config->getReverseAction();
    }

}
