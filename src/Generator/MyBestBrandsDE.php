<?php

namespace ElasticExportMyBestBrandsDE\Generator;

use Plenty\Modules\DataExchange\Contracts\CSVGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\DataLayer\Models\RecordList;
use Plenty\Modules\DataExchange\Models\FormatSetting;
use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Item\Property\Contracts\PropertySelectionRepositoryContract;
use Plenty\Modules\Item\Property\Models\PropertySelection;

class MyBestBrandsDE extends CSVGenerator
{
    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportHelper;

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
    private $idlVariations = array();

    /**
     * @var array
     */
    private $itemPropertyCache = [];

    /**
     * Geizhals constructor.
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
     * @param array $resultData
     * @param array $formatSettings
     */
    protected function generateContent($resultData, array $formatSettings = [])
    {
        if(is_array($resultData['documents']) && count($resultData['documents']) > 0)
        {
            $this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
            $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

            $this->setDelimiter(";");

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

            //Create a List of all VariationIds
            $variationIdList = array();
            foreach($resultData['documents'] as $variation)
            {
                $variationIdList[] = $variation['id'];
            }

            //Get the missing fields in ES from IDL
            if(is_array($variationIdList) && count($variationIdList) > 0)
            {
                /**
                 * @var \ElasticExportMyBestBrandsDE\IDL_ResultList\MyBestBrandsDE $idlResultList
                 */
                $idlResultList = pluginApp(\ElasticExportMyBestBrandsDE\IDL_ResultList\MyBestBrandsDE::class);
                $idlResultList = $idlResultList->getResultList($variationIdList, $settings);
            }

            //Creates an array with the variationId as key to surpass the sorting problem
            if(isset($idlResultList) && $idlResultList instanceof RecordList)
            {
                $this->createIdlArray($idlResultList);
            }

            $rows = [];

            foreach($resultData['documents'] as $item)
            {
                if(!array_key_exists($item['data']['item']['id'], $rows))
                {
                    $rows[$item['data']['item']['id']] = $this->getMain($item, $settings);
                }

                if(array_key_exists($item['data']['item']['id'], $rows) && $item['data']['attributes'][0]['attributeValueSetId'] > 0)
                {
                    $variationAttributes = $this->getVariationAttributes($item, $settings);

                    if(array_key_exists('Color', $variationAttributes))
                    {
                        $rows[$item['data']['item']['id']]['Color'] = array_unique(array_merge($rows[$item['data']['item']['id']]['Color'], $variationAttributes['Color']));
                    }

                    if(array_key_exists('Size', $variationAttributes))
                    {
                        $rows[$item['data']['item']['id']]['AvailableSizes'] = array_unique(array_merge($rows[$item['data']['item']['id']]['AvailableSizes'], $variationAttributes['Size']));
                    }
                }
                elseif(array_key_exists($item['data']['item']['id'], $rows))
                {
                    $itemPropertyList = $this->getItemPropertyList($item, $settings);

                    foreach($itemPropertyList as $key => $value)
                    {
                        switch($key)
                        {
                            case 'color':
                                array_push($rows[$item['data']['item']['id']]['Color'], $value);
                                $rows[$item['data']['item']['id']]['Color'] = array_unique($rows[$item['data']['item']['id']]['Color']);
                                break;

                            case 'available_sizes':
                                array_push($rows[$item['data']['item']['id']]['AvailableSizes'], $value);
                                $rows[$item['data']['item']['id']]['AvailableSizes'] = array_unique($rows[$item['data']['item']['id']]['AvailableSizes']);
                                break;
                        }
                    }
                }
            }

            foreach($rows as $data)
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

    /**
     * Get main information.
     * @param  array $item
     * @param  KeyValue $settings
     * @return array
     */
    private function getMain($item, KeyValue $settings):array
    {
        $itemPropertyList = $this->getItemPropertyList($item, $settings);

        $productName = array_key_exists('itemName', $itemPropertyList) && strlen((string) $itemPropertyList['itemName']) ?
            $this->elasticExportHelper->cleanName((string) $itemPropertyList['itemName'], $settings->get('nameMaxLength')) :
            $this->elasticExportHelper->getName($item, $settings);
        $price = (float)$this->idlVariations[$item['id']]['variationRetailPrice.price'];
        $rrp = (float)$this->elasticExportHelper
            ->getRecommendedRetailPrice($this->idlVariations[$item['id']]['variationRecommendedRetailPrice.price'], $settings);


        $data = [
            'ProductID' 			=> $item['data']['item']['id'],
            'ProductCategory' 		=> str_replace(array('&', '/'), array('und', ' '),
                $this->elasticExportHelper->getCategory((int)$item['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId'))),
            'Deeplink' 				=> $this->elasticExportHelper->getUrl($item, $settings, true, false),
            'ProductName'			=> $productName,
            'ImageUrl' 				=> $this->elasticExportHelper->getMainImage($item, $settings),
            'ProductDescription' 	=> $this->elasticExportHelper->getDescription($item, $settings),
            'BrandName'				=> $this->elasticExportHelper->getExternalManufacturerName((int)$item['data']['item']['manufacturer']['id']),
            'Price'					=> number_format((float)$price, 2, ',', ''),
            'PreviousPrice'			=> number_format((float)$rrp > $price ? $rrp : 0, 2, ',', ''),
            'Tags'					=> $item['data']['texts'][0]['keywords'],
            'EAN'					=> $this->elasticExportHelper->getBarcodeByType($item, $settings->get('barcode')),
            'LastUpdate'			=> $item['data']['item']['updatedAt'],
            'UnitPrice'				=> $this->elasticExportHelper->getBasePrice($item, $this->idlVariations),
            'RetailerAttributes'	=> $item['data']['item']['storeSpecial'] == 2 ? 'new-arrival' : '',
            'AvailableSizes'		=> [],
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

    /**
     * @param $item
     * @param KeyValue $settings
     * @return array
     */
    protected function getItemPropertyList($item, KeyValue $settings):array
    {
        if(!array_key_exists($item['data']['item']['id'], $this->itemPropertyCache))
        {
            $characterMarketComponentList = $this->elasticExportHelper->getItemCharactersByComponent($this->idlVariations[$item['id']], $settings->get('referrerId'));

            $list = [];

            if(count($characterMarketComponentList))
            {
                foreach($characterMarketComponentList as $data)
                {
                    if((string) $data['characterValueType'] != 'file' && (string) $data['characterValueType'] != 'empty' && (string) $data['externalComponent'] != "0")
                    {
                        if((string) $data['characterValueType'] == 'selection')
                        {
                            $propertySelection = $this->propertySelectionRepository->findOne((int) $data['characterValue'], 'de');
                            if($propertySelection instanceof PropertySelection)
                            {
                                $list[(string) $data['externalComponent']] = (string) $propertySelection->name;
                            }
                        }
                        else
                        {
                            $list[(string) $data['externalComponent']] = (string) $data['characterValue'];
                        }

                    }
                }
            }

            $this->itemPropertyCache[$item['data']['item']['id']] = $list;
        }

        return $this->itemPropertyCache[$item['data']['item']['id']];
    }

    /**
     * @param RecordList $idlResultList
     */
    private function createIdlArray($idlResultList)
    {
        if($idlResultList instanceof RecordList)
        {
            foreach($idlResultList as $idlVariation)
            {
                if($idlVariation instanceof Record)
                {
                    $this->idlVariations[$idlVariation->variationBase->id] = [
                        'itemBase.id' => $idlVariation->itemBase->id,
                        'variationBase.id' => $idlVariation->variationBase->id,
                        'itemPropertyList' => $idlVariation->itemPropertyList,
                        'variationStock.stockNet' => $idlVariation->variationStock->stockNet,
                        'variationRetailPrice.price' => $idlVariation->variationRetailPrice->price,
                        'variationRecommendedRetailPrice.price' => $idlVariation->variationRecommendedRetailPrice->price,
                    ];
                }
            }
        }
    }
}
