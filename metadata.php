<?php
$sMetadataVersion = '2';
$aModule          = [
    'id'          => 'ivoba_delivery_must_fit_all',
    'title'       => '<strong>Ivo Bathke</strong>:  <i>Delivery Must Fit All</i>',
    'description' => [
        'de' => 'Versandregeln muss für alle Produkte passen.',
        'en' => 'Delivery must fit for all products.',
    ],
    'thumbnail'   => 'ivoba-oxid.png',
    'version'     => '1.0',
    'author'      => 'Ivo Bathke',
    'email'       => 'ivo.bathke@gmail.com',
    'url'         => 'https://oxid.ivo-bathke.name#delivery-must-fit-all',
    'extend'      => [\OxidEsales\Eshop\Application\Model\Delivery::class => \IvobaOxid\DeliveryMustFitAll\Application\Model\Delivery::class],
    'blocks'      => [],
    'settings'    => [
        [
            'group' => 'ivoba_delivery_must_fit_all',
            'name'  => 'ivoba_delivery_must_fit_all_deliveries',
            'type'  => 'arr',
            'value' => []
        ],
    ],
];