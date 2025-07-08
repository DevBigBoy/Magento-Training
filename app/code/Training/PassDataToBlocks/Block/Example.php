<?php

namespace Training\PassDataToBlocks\Block;

use Magento\Framework\View\Element\Template;

class Example extends Template
{

    public function getDummyData(): string
    {
        return "Dummy data M4";
    }
}
