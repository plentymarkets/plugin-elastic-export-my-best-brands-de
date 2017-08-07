<?php

namespace ElasticExportMyBestBrandsDE;

use Plenty\Modules\DataExchange\Services\ExportPresetContainer;
use Plenty\Plugin\DataExchangeServiceProvider;

class ElasticExportMyBestBrandsDEServiceProvider extends DataExchangeServiceProvider
{
    public function register()
    {

    }

    public function exports(ExportPresetContainer $container)
    {
        $container->add(
            'MyBestBrandsDE-Plugin',
            'ElasticExportMyBestBrandsDE\ResultField\MyBestBrandsDE',
            'ElasticExportMyBestBrandsDE\Generator\MyBestBrandsDE',
            '',
            true,
			true
        );
    }
}