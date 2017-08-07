<?php

namespace ElasticExportMyBestBrandsDE\ResultField;

use Plenty\Modules\DataExchange\Contracts\ResultFields;
use Plenty\Modules\DataExchange\Models\FormatSetting;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Search\Mutators\BarcodeMutator;
use Plenty\Modules\Item\Search\Mutators\ImageMutator;
use Plenty\Modules\Cloud\ElasticSearch\Lib\Source\Mutator\BuiltIn\LanguageMutator;
use Plenty\Modules\Item\Search\Mutators\KeyMutator;
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
		$itemDescriptionFields[] = 'texts.lang';

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

		/**
		 * @var BarcodeMutator $barcodeMutator
		 */
		$barcodeMutator = pluginApp(BarcodeMutator::class);
		if($barcodeMutator instanceof BarcodeMutator)
		{
			$barcodeMutator->addMarket($reference);
		}

		/**
		 * @var KeyMutator
		 */
		$keyMutator = pluginApp(KeyMutator::class);

		if($keyMutator instanceof KeyMutator)
		{
			$keyMutator->setKeyList($this->getKeyList());
			$keyMutator->setNestedKeyList($this->getNestedKeyList());
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
				'images.all.urlMiddle',
				'images.all.urlPreview',
				'images.all.urlSecondPreview',
				'images.all.url',
				'images.all.path',
				'images.all.position',

				'images.item.urlMiddle',
				'images.item.urlPreview',
				'images.item.urlSecondPreview',
				'images.item.url',
				'images.item.path',
				'images.item.position',

				'images.variation.urlMiddle',
				'images.variation.urlPreview',
				'images.variation.urlSecondPreview',
				'images.variation.url',
				'images.variation.path',
				'images.variation.position',

				//unit
				'unit.content',
				'unit.id',

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

				//properties
				'properties.property.id',
				'properties.property.valueType',
				'properties.selection.name',
				'properties.selection.lang',
				'properties.texts.value',
				'properties.texts.lang'
			],

			[
				$languageMutator,
				$skuMutator,
				$defaultCategoryMutator,
				$barcodeMutator,
				$keyMutator
			],
        ];

        if($reference != -1)
        {
            $fields[1][] = $imageMutator;
        }

        foreach($itemDescriptionFields as $itemDescriptionField)
        {
            $fields[0][] = $itemDescriptionField;
        }

        return $fields;
    }

	/**
	 * @return array
	 */
	private function getKeyList()
	{
		return [
			// Item
			'item.id',
			'item.manufacturer.id',
			'item.storeSpecial',
			'item.updatedAt',

			// Variation
			'variation.availability.id',
			'variation.stockLimitation',
			'variation.vatId',
			'variation.model',
			'variation.weightG',

			// Unit
			'unit.content',
			'unit.id',
		];
	}

	/**
	 * @return array
	 */
	private function getNestedKeyList()
	{
		return [
			'keys' => [
				// Attributes
				'attributes',

				// Barcodes
				'barcodes',

				// Default categories
				'defaultCategories',

				// Images
				'images.all',
				'images.item',
				'images.variation',

				//properties
				'properties'
			],

			'nestedKeys' => [
				// Attributes
				'attributes' => [
					'attributeValueSetId',
					'attributeId',
					'valueId'
				],

				// Barcodes
				'barcodes' => [
					'code',
					'type'
				],

				// Default categories
				'defaultCategories' => [
					'id'
				],

				// Images
				'images.all' => [
					'urlMiddle',
					'urlPreview',
					'urlSecondPreview',
					'url',
					'path',
					'position',
				],
				'images.item' => [
					'urlMiddle',
					'urlPreview',
					'urlSecondPreview',
					'url',
					'path',
					'position',
				],
				'images.variation' => [
					'urlMiddle',
					'urlPreview',
					'urlSecondPreview',
					'url',
					'path',
					'position',
				],

				// texts
				'texts' => [
					'urlPath',
					'name1',
					'name2',
					'name3',
					'shortDescription',
					'description',
					'technicalData',
				],

				'properties'    => [
					'property.id',
					'property.valueType',
					'selection.name',
					'selection.lang',
					'texts.value',
					'texts.lang'
				],
			]
		];
	}
}