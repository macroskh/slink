<?php

return [
    'length' => 8,
//    'generator' => [
//        'class' => \App\Http\Services\LinkGenerators\Pregenerated::class,
//        'config' => [
//            'count' => 1000,
//            'batch' => 250
//        ]
//    ],
    'generator' => [
        'class' => \App\Http\Services\LinkGenerators\RunTime::class,
    ],
];
