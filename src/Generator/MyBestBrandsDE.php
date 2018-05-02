<?php

namespace ElasticExportMyBestBrandsDE\Generator;

use ElasticExport\Helper\ElasticExportPriceHelper;
use ElasticExport\Helper\ElasticExportStockHelper;
use ElasticExport\Helper\ElasticExportPropertyHelper;
use ElasticExport\Services\FiltrationService;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Item\Property\Contracts\PropertySelectionRepositoryContract;
use Plenty\Modules\Item\Search\Contracts\VariationElasticSearchScrollRepositoryContract;
use Plenty\Plugin\Log\Loggable;

class MyBestBrandsDE extends CSVPluginGenerator
{
	use Loggable;

	const DELIMITER = ";";

    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportHelper;

	/**
	 * @var ElasticExportStockHelper $elasticExportStockHelper
	 */
	private $elasticExportStockHelper;

	/**
	 * @var ElasticExportPriceHelper $elasticExportPriceHelper
	 */
	private $elasticExportPriceHelper;

	/**
	 * @var ElasticExportPropertyHelper $elasticExportPropertyHelper
	 */
	private $elasticExportPropertyHelper;

    /*
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * AttributeValueNameRepositoryContract $attributeValueNameRepository
     */
    private $attributeValueNameRepository;

    /**
     * PropertySelectionRepositoryContract $propertySelectionRepository
     */
    private $propertySelectionRepository;

	/**
	 * @var array
	 */
    private $rows = array();

    /**
     * @var FiltrationService
     */
    private $filtrationService;

    /**
     * @param ArrayHelper $arrayHelper
     * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
     * @param PropertySelectionRepositoryContract $propertySelectionRepository
     */
    public function __construct(
        ArrayHelper $arrayHelper,
        AttributeValueNameRepositoryContract $attributeValueNameRepository,
        PropertySelectionRepositoryContract $propertySelectionRepository
    )
    {
        $this->arrayHelper = $arrayHelper;
        $this->attributeValueNameRepository = $attributeValueNameRepository;
        $this->propertySelectionRepository = $propertySelectionRepository;
    }

	/**
	 * @param VariationElasticSearchScrollRepositoryContract $elasticSearch
	 * @param array $formatSettings
	 * @param array $filter
	 */
	protected function generatePluginContent($elasticSearch, array $formatSettings = [], array $filter = [])
	{
		$this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
		$this->elasticExportStockHelper = pluginApp(ElasticExportStockHelper::class);
		$this->elasticExportPriceHelper = pluginApp(ElasticExportPriceHelper::class);
		$this->elasticExportPropertyHelper = pluginApp(ElasticExportPropertyHelper::class);

		$settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
		$this->filtrationService = pluginApp(FiltrationService::class, ['settings' => $settings, 'filterSettings' => $filter]);

		$this->setDelimiter(self::DELIMITER);

		$this->setHeader();

		if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
		{
			$limitReached = false;
			$lines = 0;
			do
			{
				if($limitReached === true)
				{
					break;
				}

				$resultList = $elasticSearch->execute();

				foreach($resultList['documents'] as $variation)
				{
					if($lines == $filter['limit'])
					{
						$limitReached = true;
						break;
					}

					if(is_array($resultList['documents']) && count($resultList['documents']) > 0)
					{
						if($this->filtrationService->filter($variation))
						{
							continue;
						}

						try
						{
							$this->buildRow($variation, $settings);
						}
						catch(\Throwable $throwable)
						{
							$this->getLogger(__METHOD__)->error('ElasticExportMyBestBrandsDE::logs.fillRowError', [
								'Error message ' => $throwable->getMessage(),
								'Error line'    => $throwable->getLine(),
								'VariationId'   => $variation['id']
							]);
						}
						$lines = $lines +1;
					}
				}
			}while ($elasticSearch->hasNext());
		}
	}

	private function setHeader()
	{
		$this->addCSVContent([
			'ProductID',
			'ProductCategory',
			'Deeplink',
			'ProductName',
			'ImageUrl',
			'ProductDescription',
			'BrandName',
			'Price',
			'PreviousPrice',
			'AvailableSizes',
			'Tags',
			'EAN',
			'LastUpdate',
			'UnitPrice',
			'RetailerAttributes',
			'Color',
		]);
	}

	/**
	 * @param $variation
	 * @param $settings
	 */
	private function buildRow($variation, $settings)
	{
		if(!array_key_exists($variation['data']['item']['id'], $this->rows))
		{
			$this->fillLines();
			$this->rows = array();
			$this->rows[$variation['data']['item']['id']] = $this->getMain($variation, $settings);
		}

		if(array_key_exists($variation['data']['item']['id'], $this->rows) && $variation['data']['attributes'][0]['attributeValueSetId'] > 0)
		{
			$variationAttributes = $this->getVariationAttributes($variation, $settings);

			if(array_key_exists('Color', $variationAttributes))
			{
				$this->rows[$variation['data']['item']['id']]['Color'] = array_unique(array_merge($this->rows[$variation['data']['item']['id']]['Color'], $variationAttributes['Color']));
			}

			if(array_key_exists('Size', $variationAttributes))
			{
				$this->rows[$variation['data']['item']['id']]['AvailableSizes'] = array_unique(array_merge($this->rows[$variation['data']['item']['id']]['AvailableSizes'], $variationAttributes['Size']));
			}
		}
		elseif(array_key_exists($variation['data']['item']['id'], $this->rows))
		{
			$itemPropertyList = $this->elasticExportPropertyHelper->getItemPropertyList($variation, $settings->get('referrerId'));

			foreach($itemPropertyList as $key => $value)
			{
				switch($key)
				{
					case 'color':
						array_push($this->rows[$variation['data']['item']['id']]['Color'], $value);
						$this->rows[$variation['data']['item']['id']]['Color'] = array_unique($this->rows[$variation['data']['item']['id']]['Color']);
						break;

					case 'available_sizes':
						array_push($this->rows[$variation['data']['item']['id']]['AvailableSizes'], $value);
						$this->rows[$variation['data']['item']['id']]['AvailableSizes'] = array_unique($this->rows[$variation['data']['item']['id']]['AvailableSizes']);
						break;
				}
			}
		}
	}

    /**
     * Get main information.
     * @param  array $variation
     * @param  KeyValue $settings
     * @return array
     */
    private function getMain($variation, KeyValue $settings):array
    {
        $itemPropertyList = $this->elasticExportPropertyHelper->getItemPropertyList($variation, $settings->get('referrerId'));

        $productName = array_key_exists('itemName', $itemPropertyList) && strlen((string) $itemPropertyList['itemName']) ?
            $this->elasticExportHelper->cleanName((string) $itemPropertyList['itemName'], (int) $settings->get('nameMaxLength')) :
            $this->elasticExportHelper->getName($variation, $settings);

        $priceList = $this->elasticExportPriceHelper->getPriceList($variation, $settings, 2, ',');

        $price = $priceList['price'];

        $rrp = '';

        if((float)$price > 0)
        {
			$rrp = $priceList['recommendedRetailPrice'] > $price ? $priceList['recommendedRetailPrice'] : '';
		}

        $imageUrl = $this->elasticExportHelper->getImageListInOrder($variation, $settings, 1, $this->elasticExportHelper::ITEM_IMAGES);

		if(count($imageUrl) > 0)
		{
			$imageUrl = $imageUrl[0];
		}
		else
		{
			$imageUrl = '';
		}

        $data = [
            'ProductID' 			=> $variation['data']['item']['id'],
            'ProductCategory' 		=> str_replace(array('&', '/'), array('und', ' '),
                $this->elasticExportHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId'))),
            'Deeplink' 				=> $this->elasticExportHelper->getMutatedUrl($variation, $settings, true, false),
            'ProductName'			=> $productName,
            'ImageUrl' 				=> $imageUrl,
            'ProductDescription' 	=> $this->elasticExportHelper->getMutatedDescription($variation, $settings),
            'BrandName'				=> $this->elasticExportHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id']),
            'Price'					=> $price,
            'PreviousPrice'			=> $rrp,
			'AvailableSizes'		=> [],
            'Tags'					=> $variation['data']['texts'][0]['keywords'],
            'EAN'					=> $this->elasticExportHelper->getBarcodeByType($variation, $settings->get('barcode')),
            'LastUpdate'			=> $variation['data']['item']['updatedAt'],
            'UnitPrice'				=> $this->elasticExportPriceHelper->getBasePrice($variation, (float)$price, $settings->get('lang'), '/', false, false, $priceList['currency']),
            'RetailerAttributes'	=> $variation['data']['item']['storeSpecial']['names']['name'],
            'Color'					=> [],
        ];

        return $data;
    }

    /**
     * Get variation attributes.
     * @param  array   $item
     * @param  KeyValue $settings
     * @return array<string,string>
     */
    private function getVariationAttributes($item, KeyValue $settings):array
    {
        $variationAttributes = [];

        foreach($item['data']['attributes'] as $variationAttribute)
        {
            $attributeValueName = $this->attributeValueNameRepository->findOne($variationAttribute['valueId'], $settings->get('lang'));

            if($attributeValueName instanceof AttributeValueName)
            {
                if($attributeValueName->attributeValue->attribute->amazonAttribute)
                {
                    $variationAttributes[$attributeValueName->attributeValue->attribute->amazonAttribute][] = $attributeValueName->name;
                }
            }
        }

        return $variationAttributes;
    }

    private function fillLines()
	{
		foreach($this->rows as $data)
		{
			if(array_key_exists('Color', $data) && is_array($data['Color']))
			{
				$data['Color'] = implode(';', $data['Color']);
			}

			if(array_key_exists('AvailableSizes', $data) && is_array($data['AvailableSizes']))
			{
				$data['AvailableSizes'] = implode(', ', $data['AvailableSizes']);
			}

			$this->addCSVContent(array_values($data));
		}
	}
}
