
# MyBestBrands.de plugin user guide

<div class="container-toc"></div>

## 1 Registering with MyBestBrands.de

mybestbrands is a recommendation platform for greatly discounted fashion and lifestyle products, as well as for electronic products from well known, brand name manufacturers. Please note that this website is currently only available in German.

## 2 Setting up the data format MyBestBrandsDE-Plugin in plentymarkets

The plugin Elastic Export is required to use this format.

Refer to the [Exporting data formats for price search engines](https://knowledge.plentymarkets.com/en/basics/data-exchange/exporting-data#30) page of the manual for further details about the individual format settings.

The following table lists details for settings, format settings and recommended item filters for the format **MyBestBrandsDE-Plugin**.
<table>
    <tr>
        <th>
            Settings
        </th>
        <th>
            Explanation
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Settings
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            Choose <b>SchuheDE-Plugin</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Provisioning
        </td>
        <td>
            Choose <b>URL</b>.
        </td>        
    </tr>
    <tr>
        <td>
            File name
        </td>
        <td>
            The file name must have the ending <b>.csv</b> or <b>.txt</b> for Shopzilla.de to be able to import the file successfully.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Item filter
        </td>
    </tr>
    <tr>
        <td>
            Active
        </td>
        <td>
            Choose <b>active</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Markets
        </td>
        <td>
            Choose one or multiple order referrer. The chosen order referrer has to be active at the variation for the item to be exported.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Format settings
        </td>
    </tr>
    <tr>
        <td>
            Order referrer
        </td>
        <td>
            Choose the order referrer that should be assigned during the order import.
        </td>        
    </tr>
    <tr>
        <td>
            Preview text
        </td>
        <td>
            This option does not affect this format.
        </td>        
    </tr>
    <tr>
        <td>
            Offer price
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
    <tr>
        <td>
            VAT note
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
</table>

## 3 Overview of available columns

<table>
    <tr>
        <th>
            Column name
        </th>
        <th>
            Explanation
        </th>
    </tr>
    <tr>
        <td>
            ProductID
        </td>
        <td>
            <b>Content:</b> The <b>item ID</b> of the variation.
        </td>        
    </tr>
    <tr>
		<td>
			ProductCategory
		</td>
		<td>
			  <b>Content:</b> The names of the categories that are linked to the variation separeted with >.
		</td>        
	</tr>
	<tr>
		<td>
			Deeplink
		</td>
		<td>
			<b>Content:</b> The <b>URL path</b> of the item depending on the chosen <b>client</b> in the format settings.
		</td>        
	</tr>
	<tr>
		<td>
			ProductName
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>item name</b>.
		</td>        
	</tr>
	<tr>
		<td>
			ImageUrl
		</td>
		<td>
			<b>Content:</b> The image url. Item images are prioritizied over variation images.
		</td>        
	</tr>
	<tr>
		<td>
			ProductDescription
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>Description</b>.
		</td>        
	</tr>
	<tr>
		<td>
			BrandName
		</td>
		<td>
			<b>Content:</b> The <b>name of the manufacturer</b> of the item. The <b>external name</b> within <b>Settings » Items » Manufacturer</b> will be preferred if existing.
		</td>        
	</tr>
	<tr>
		<td>
			Price
		</td>
		<td>
			<b>Content:</b> The <b>sales price</b> of the variation.
		</td>        
	</tr>
	 <tr>
		<td>
			PreviousPrice
		</td>
		<td>
			<b>Content:</b> If the <b>RRP</b> is activated in the format setting and is higher than the <b>sales price</b>, the <b>sales price</b> will be exported.
		</td>        
	</tr>
    <tr>
        <td>
            AvailableSizes
        </td>
        <td>
            <b>Content:</b> The <b>sizes</b> in which the item is available.
        </td>        
    </tr>
    <tr>
        <td>
            Tags
        </td>
        <td>
            <b>Content:</b> The <b>Keywords</b> of the item.
        </td>        
    </tr>
    <tr>
		<td>
			EAN
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>Barcode</b>.
		</td>        
	</tr>
    <tr>
        <td>
            LastUpdate
        </td>
        <td>
            <b>Content:</b> Date of the last item update.
        </td>        
    </tr>
    <tr>
		<td>
			UnitPrice
		</td>
		<td>
			<b>Content:</b> The base price based on the <b>base price unit</b>.
		</td>        
	</tr>
	 <tr>
		<td>
			RetailerAttributes
		</td>
		<td>
			<b>Content:</b> The <b>store special</b>, set for this item.
		</td>        
	</tr>
    <tr>
        <td>
            Color
        </td>
        <td>
            <b>Content:</b> The value of an attribute, with an attribute link for <b>Amazon</b> to  <b>Color</b>. As an alternative the value of a property of the type <b>Text</b> or <b>Selection</b>, that is linked to <b>mybestbrands.de » Farbe</b> can also be used.
        </td>        
    </tr>
</table>

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE.- find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-rakuten-de/blob/master/LICENSE.md).
