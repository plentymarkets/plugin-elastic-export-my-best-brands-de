
# MyBestBrands.de plugin user guide

<div class="container-toc"></div>

## 1 Registering with MyBestBrands.de

mybestbrands.de is a recommendation platform for greatly discounted fashion and lifestyle products from well-known brand name manufacturers. Please note that this website is currently only available in German.

## 2 Setting up the data format MyBestBrandsDE-Plugin in plentymarkets

By installing this plugin you will receive the export format **MyBestBrandsDE-Plugin**. Use this format to exchange data between plentymarkets and MyBestBrands.de. It is required to install the Plugin **Elastic Export** from the plentyMarketplace first before you can use the format **MyBestBrandsDE-Plugin** in plentymarkets.

Once both plugins are installed, you can create the export format **MyBestBrandsDE-Plugin**. Refer to the [Exporting data formats for price search engines](https://knowledge.plentymarkets.com/en/basics/data-exchange/export-import/exporting-data#30) page of the manual for further details about the individual format settings.

Creating a new export format:

1. Go to **Data » Elastic export**.
2. Click on **New export**.
3. Carry out the settings as desired. Pay attention to the information given in table 1.
4. **Save** the settings.
→ The export format will be given an ID and it will appear in the overview within the **Exports** tab.

The following table lists details for settings, format settings and recommended item filters for the format **MyBestBrandsDE-Plugin**.

| **Setting**                                           | **Explanation**|
| :---                                                  | :--- |                                            
| **Settings**                                          | |
| **Name**                                              | Enter a name. The export format is listed under this name in the overview within the **Exports** tab. |
| **Type**                                              | Select the type **Item** from the drop-down list. |
| **Format**                                            | Choose the format **MyBestBrandsDE-Plugin**. |
| **Limit**                                             | Enter a number. If you want to transfer more than 9,999 data records to MyBestBrands, then the output file will not be generated again for another 24 hours. This is to save resources. If more than 9,999 data records are necessary, the option **Generate cache file** must be active. |
| **Generate cache file**                               | Place a check mark if you want to transfer more than 9,999 data records to MyBestBrands. The output file will not be generated for another 24 hours. We recommend not to activate this setting for more than 20 export formats. This is to ensure a high performance of the elastic export. |
| **Provisioning**                                      | Choose **URL**. This option generates a token for authentication in order to allow external access. |
| **Token, URL**                                        | If you selected the option **URL** under **Provisioning**, then click on **Generate token**. The token is entered automatically. The URL is entered automatically if the token has been generated under **Token**. |
| **File name**                                         | The file name must have the ending **.csv** or **.txt** for mybestbrands.de to be able to import the file successfully. |
| **Item filters**                                      | |
| **Add item filters**                                  | Select an item filter from the drop-down list and click on **Add**. There are no filters set in default. It is possible to add multiple item filters from the drop-down list one after the other.<br/> **Variations** = Select **Transfer all** or **Only transfer main variations**.<br/> **Markets** = Select one market, several or **ALL**. The availability for all markets selected here has to be saved for the item. Otherwise, the export will not take place.<br/> **Currency** = Select a currency.<br/> **Category** = Activate to transfer the item with its category link. Only items belonging to this category are exported.<br/> **Image** = Activate to transfer the item with its image. Only items with images are transferred.<br/> **Client** = Select a client.<br/> **Stock** = Select which stocks you want to export.<br/> **Flag 1-2** = Select the flag.<br/> **Manufacturer** = Select one, several or **ALL** manufacturers.<br/> **Active** = Choose **active**. Only active variations are exported. |
| **Format settings**                                   | |
| **Product URL**                                       | Choose which URL should be transferred to BeezUp, the item's URL or the variation's URL. Variation SKUs can only be transferred in combination with the Ceres store. |
| **Client**                                            | Select a client. This setting is used for the URL structure. |
| **URL parameter**                                     | Enter a suffix for the product URL if this is required for the export. If you have activated the **transfer** option for the product URL further up, then this character string is added to the product URL. |
| **Order referrer**                                    | Select the order referrer that should be assigned during the order import from the drop-down list. |
| **Marketplace account**                               | Select the marketplace account from the drop-down list. The selected referrer is added to the product URL so that sales can be analysed later. |
| **Language**                                          | Select the language from the drop-down list. |
| **Item name**                                         | Select **Name 1**, **Name 2**, or **Name 3**. These names are saved in the **Texts** tab of the item.<br/> Enter a number into the **Maximum number of characters (def. text)** field if desired. This specifies how many characters are exported for the item name. |
| **Preview text**                                      | This option does not affect this format. |
| **Description**                                       | Select the text that you want to transfer as description.<br/> Enter a number into the **Maximum number of characters (def. text)** field if desired. This specifies how many characters should be exported for the description. Activate the option **Remove HTML tags** if you want HTML tags to be removed during the export.<br/> If you only want to allow specific HTML tags to be exported, then enter these tags into the field **Permitted HTML tags, separated by comma (def. text**. Use commas to separate multiple tags. |
| **Target country**                                    | Select the target country from the drop-down list. |
| **Barcode**                                           | Select the ASIN, ISBN or an EAN from the drop-down list. The barcode has to be linked to the order referrer selected above. If the barcode is not linked to the order referrer, it will not be exported. |
| **Image**                                             | Select **Position 0** or **First image** to export this image.<br/> **Position 0** = An image with position 0 is transferred.<br/> **First image** = The first image is transferred. |
| **Image position of the energy efficiency label**     | This option does not affect this format. |
| **Stockbuffer**                                       | This option does not affect this format. |
| **Stock for variations without stock limitation**     | This option does not affect this format. |
| **Stock for variations without stock administration** | This option does not affect this format. |
| **Live currency conversion**                          | Activate this option to convert the price into the currency of the selected country of delivery. The price has to be released for the corresponding currency. |
| **Retail price**                                      | Select the gross price or the net price from the drop-down list. |
| **Offer price**                                       | This option does not affect this format. |
| **RRP**                                               | Activate to transfer the RRP. |
| **Shipping costs**                                    | This option does not affect this format. |
| **VAT note**                                          | This option does not affect this format. |
| **Overwrite item availability**                       | This option does not affect this format. | 

_Tab. 1: Settings for the data format **MyBestBrandsDE-Plugin**_

## 3 Available columns of the export file

| **Column name**        | **Explanation** |
| :---                   | :--- |
| ProductID              | The item ID of the variation. |
| ProductCategory        | The names of the categories that are linked to the variation separated by >. |
| Deeplink               | The URL path of the item depending on the chosen client in the format settings. |
| ProductName            | According to the format setting **Item name**. |
| ImageURL               | The image URL. Item images are prioritised over variation images. |
| ProductDescription     | According to the format setting **Description**. |
| BrandName              | The name of the manufacturer of the item. The **external name** in the menu **System » Items » Manufacturer** is preferred if existing. |
| Price                  | The sales price of the variation. |
| PreviousPrice          | If the RRP is activated in the format setting and is higher than the sales price, the RRP is exported. |
| AvailableSizes         | The sizes in which the item is available. |
| Tags                   | The keywords of the item. |
| EAN                    | According to the format setting **Barcode**. |
| LastUpdate             | Date of the last item update. |
| UnitPrice              | The base price based on the base price unit. |
| RetailerAttributes     | The store special set for this item. |
| Color                  | The value of an attribute with an attribute link for Amazon to Color. The value of a property of the type **Text** or **Selection** that is linked to **mybestbrands.de » Farbe** can also be used as an alternative. |

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE.- find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-rakuten-de/blob/master/LICENSE.md).
