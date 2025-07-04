<?php

namespace PHP\Array\Model;

class SimpleTypes
{

    public function indexedArray(): array
    {
        $skills = ['php', 'js', 'css'];

        // Update Element at the index 0
        $skills[0] = 'C++';

        // Push Element at the end of the array
        $skills[] = 'Python';

        // Push Element at the end of the array using array_push()
        array_push($skills, 'Java', 'Ruby');

        return $skills;
    }

    public function associativeArray(): array
    {
        $userInfo = [
            'name' => 'Mohamed',
            'age' => '27',
            'email' => 'mohamed@php.com',
            'mobile' => '0123456789'
        ];
        $userInfo['city'] = 'Alex';
        $userInfo['country'] = 'Egypt';
        $userInfo['address'] = 'Cairo';

        return $userInfo;

    }

    public function multiDimensionalArray(): array
    {
        $productInfo = [];
        $productInfo['name'] = 'New Product';
        $productInfo['type_id'] = 'configurable';
        $productInfo['status'] = 'active';

        $productInfo['configurable_options'] = [
            'color' => [
                'attribute_id' => 93,
                'attribute_code' => 'color',
                'label' => 'Color',
                'values' => [
                    ['value_index' => 49, 'label' => 'Black'],
                    ['value_index' => 50, 'label' => 'Blue'],
                    ['value_index' => 51, 'label' => 'Red']
                ]
            ],
            'size' => [
                'attribute_id' => 142,
                'attribute_code' => 'size',
                'label' => 'Size',
                'values' => [
                    ['value_index' => 166, 'label' => 'XS'],
                    ['value_index' => 167, 'label' => 'S'],
                    ['value_index' => 168, 'label' => 'M'],
                    ['value_index' => 169, 'label' => 'L']
                ],
                'test' => []
            ]
        ];

        # How to push inside a multidimensional array
        $productInfo['configurable_options']['size']['test']['t_1'] = 'test_1';
        $productInfo['configurable_options']['size']['test']['t_2'] = 1870;

        $productInfo['configurable_options']['size']['test']['t_2'] = true;

        return $productInfo;
    }

}
