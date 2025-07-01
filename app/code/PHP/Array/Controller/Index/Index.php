<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PHP\Array\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;


class Index extends Action
{



    /**
     * Index constructor.
     *
     * @param Context $context
     */
    public function __construct(
        Context $context,
    ) {
        parent::__construct($context);
    }


    public function execute($coreRoute = null)
    {
        dd('Hello PHP Array');
    }
}
