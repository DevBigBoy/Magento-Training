<?php

namespace Training\DispatchExample\Block;

class Example extends \Magento\Framework\View\Element\Template
{

    protected function _toHtml(): string
    {
        return 'This is an example block. From dispatch event tutorial';
    }
}
