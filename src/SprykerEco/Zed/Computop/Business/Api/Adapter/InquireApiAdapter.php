<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

class InquireApiAdapter extends AbstractApiAdapter
{

    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->config->getInquireAction();
    }

}
