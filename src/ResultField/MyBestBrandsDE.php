<?php

namespace ElasticExportMyBestBrandsDE\ResultField;

use Plenty\Modules\DataExchange\Contracts\ResultFields;
use Plenty\Modules\DataExchange\Models\FormatSetting;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Search\Mutators\ImageMutator;
use Plenty\Modules\Cloud\ElasticSearch\Lib\Source\Mutator\BuiltIn\LanguageMutator;
use Plenty\Modules\Item\Search\Mutators\SkuMutator;
use Plenty\Modules\Item\Search\Mutators\DefaultCategoryMutator;

/**
 * Class MyBestBrandsDE
 * @package ElasticExport\ResultFields
 */
class MyBestBrandsDE extends ResultFields
{
    /*
	 * @var ArrayHelper
	 */
    private $arrayHelper;

    /**
     * Geizhals constructor.
     * @param ArrayHelper $arrayHelper
     */
    public function __construct(ArrayHelper $arrayHelper)
    {
        $this->arrayHelper = $arrayHelper;
    }

    /**
     * Generate result fields.
     * @param  array $formatSettings = []
     * @return array
     */
    public function generateResultFields(array $formatSettings = []):array
    {
        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
        $reference = $settings->get('referrerId') ? $settings->get('referrerId') : -1;

        $itemDescriptionFields = ['texts.urlPath'];
        $itemDescriptionFields[] = 'texts.keywords';

        switch($settings->get('nameId'))
        {
            case 1:
                $itemDescriptionFields[] = 'texts.name1';
                break;
            case 2:
                $itemDescriptionFields[] = 'texts.name2';
                break;
            case 3:
                $itemDescriptionFields[] = 'texts.name3';
                break;
            default:
                $itemDescriptionFields[] = 'texts.name1';
                break;
        }

        if($settings->get('descriptionType') == 'itemShortDescription'
            || $settings->get('previewTextType') == 'itemShortDescription')
        {
            $itemDescriptionFields[] = 'texts.shortDescription';
        }

        if($settings->get('descriptionType') == 'itemDescription'
            || $settings->get('descriptionType') == 'itemDescriptionAndTechnicalData'
            || $settings->get('previewTextType') == 'itemDescription'
            || $settings->get('previewTextType') == 'itemDescriptionAndTechnicalData')
        {
            $itemDescriptionFields[] = 'texts.description';
        }
        $itemDescriptionFields[] = 'texts.technicalData';

        //Mutator
        /**
         * @var ImageMutator $imageMutator
         */
        $imageMutator = pluginApp(ImageMutator::class);
        if($imageMutator instanceof ImageMutator)
        {
            $imageMutator->addMarket($reference);
        }
        /**
         * @var LanguageMutator $languageMutator
         */
        $languageMutator = pluginApp(LanguageMutator::class, [[$settings->get('lang')]]);
        /**
         * @var SkuMutator $skuMutator
         */
        $skuMutator = pluginApp(SkuMutator::class);
        if($skuMutator instanceof SkuMutator)
        {
            $skuMutator->setMarket($reference);
        }
        /**
         * @var DefaultCategoryMutator $defaultCategoryMutator
         */
        $defaultCategoryMutator = pluginApp(DefaultCategoryMutator::class);
        if($defaultCategoryMutator instanceof DefaultCategoryMutator)
        {
            $defaultCategoryMutator->setPlentyId($settings->get('plentyId'));
        }

        $fields = [
            [
                //item
                'item.id',
                'item.manufacturer.id',
                'item.storeSpecial',
                'item.updatedAt',

                //variation
                'id',
                'variation.availability.id',
                'variation.stockLimitation',
                'variation.vatId',
                'variation.model',
                'variation.weightG',

                //images
                'images.item.type',
                'images.item.path',
                'images.item.position',
                'images.item.fileType',
                'images.variation.type',
                'images.variation.path',
                'images.variation.position',
                'images.variation.fileType',
                'images.all.type',
                'images.all.path',
                'images.all.position',
                'images.all.fileType',

                //unit
                'unit.content',
                'unit.id',

                //sku
                'skus.sku',

                //defaultCategories
                'defaultCategories.id',

                //barcodes
                'barcodes.id',
                'barcodes.code',
                'barcodes.type',

                //attributes
                'attributes.attributeValueSetId',
                'attributes.attributeId',
                'attributes.valueId',
            ],

            [
                $imageMutator,
                $languageMutator,
                $skuMutator,
                $defaultCategoryMutator
            ],
        ];
        foreach($itemDescriptionFields as $itemDescriptionField)
        {
            $fields[0][] = $itemDescriptionField;
        }

        return $fields;
//
//        $fields = [
//            'itemBase'=> [
//                'id',                     done
//                'producerId',             done
//                'lastUpdateTimestamp',    ?
//                'storeSpecial',           done
//            ],
//
//            'itemDescription' => [
//                'params' => [
//                    'language' => $settings->get('lang') ? $settings->get('lang') : 'de',
//                ],
//                'fields' => $itemDescriptionFields, done
//            ],
//
//            'variationImageList' => [
//                'params' => [
//                    'type' => 'item_variation',
//                    'referenceMarketplace' => $settings->get('referrerId') ? $settings->get('referrerId') : -1,
//                ],
//                'fields' => [
//                    'type',               done
//                    'path',               done
//                    'position',           done
//                ]
//            ],
//
//            'variationBase' => [
//                'availability',               done
//                'attributeValueSetId',        done
//                'model',                      done
//                'limitOrderByStockSelect',    done
//                'unitId',                     done
//                'content',                    done
//            ],
//
//            'variationStock' => [
//                'params' => [
//                    'type' => 'virtual',
//                ],
//                'fields' => [
    //                    'stockNet'                todo grab from idl
//                ]
//            ],
//
//            'variationRetailPrice' => [
//                'params' => [
//                    'referrerId' => $settings->get('referrerId'),
//                ],
//                'fields' => [
//                    'price',                  todo grab from idl
//                ],
//            ],
//
//            'variationRecommendedRetailPrice' => [
//                'params' => [
//                    'referrerId' => $settings->get('referrerId'),
//                ],
//                'fields' => [
//                    'price',                  todo grab from idl
//                ],
//            ],
//
//            'variationStandardCategory' => [
//                'params' => [
//                    'plentyId' => $settings->get('plentyId'),
//                ],
//                'fields' => [
//                    'categoryId',             done
//                    'plentyId',
//                    'manually',
//                ],
//            ],
//
//            'variationBarcodeList' => [
//                'params' => [
//                    'barcodeType' => $settings->get('barcode') ? $settings->get('barcode') : 'EAN',
//                ],
//                'fields' => [
//                    'code',               done
//                    'barcodeId',          done
//                ]
//            ],
//
//            'variationAttributeValueList' => [
//                'attributeId',            done
//                'attributeValueId',       done
//            ],
//
//            'itemPropertyList' => [
//                'itemPropertyId',         todo grab from idl
//                'propertyId',             todo grab from idl
//                'propertyValue',          todo grab from idl
//                'propertyValueType',      todo grab from idl
//            ],
//        ];
//
//        return $fields;
    }
}