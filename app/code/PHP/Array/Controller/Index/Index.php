<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PHP\Array\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use PHP\Array\Model\MagentoArrayTypes;
use PHP\Array\Model\SimpleTypes;

class Index extends Action
{
    private SimpleTypes $simpleTypes;
    private MagentoArrayTypes $magentoArrayTypes;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param SimpleTypes $simpleTypes
     * @param MagentoArrayTypes $magentoArrayTypes
     */
    public function __construct(
        Context $context,
        SimpleTypes $simpleTypes,
        MagentoArrayTypes $magentoArrayTypes,
    ) {
        $this->simpleTypes = $simpleTypes;
        $this->magentoArrayTypes = $magentoArrayTypes;
        parent::__construct($context);
    }

    public function execute()
    {
        // Indexed Array
        // dd($this->types->indexedArray(), "The Array Length is". count($this->types->indexedArray()));

        // dd($this->types->associativeArray(), "The Array Length is " . count($this->types->indexedArray()));

        // dd($this->simpleTypes->multiDimensionalArray(), "The Array Length is " . count($this->simpleTypes->indexedArray()));

        //        $array = [
        //            true => 'a',
        //            1 => 'b',
        //            '1' => 'z',
        //        ];
        //        dd($array);

        $_arr = [
            'new',
            'pending',
            'processing',
           50 => 'complete',
            'canceled',
            'closed'
        ];

        // unset($_arr[0]);
        unset($_arr[50]);

        //        array_shift($_arr); # re-index
        //        array_pop($_arr);

        dd($_arr);
        //        dd($this->simpleTypes->multiDimensionalArray());
    }

}
