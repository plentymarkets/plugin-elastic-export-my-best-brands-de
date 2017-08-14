
# User Guide für das ElasticExportMyBestBrandsDE Plugin

<div class="container-toc"></div>

## 1 Bei MyBestBrands.de registrieren

mybestbrands betreibt eine Empfehlungsplattform für stark reduzierte Mode und Lifestyle- sowie Elektronikprodukte von bekannten Markenherstellern.

## 2 Das Format MyBestBrandsDE-Plugin in plentymarkets einrichten

Um dieses Format nutzen zu können, benötigen Sie das Plugin Elastic Export.

Auf der Handbuchseite [Daten exportieren](https://www.plentymarkets.eu/handbuch/datenaustausch/daten-exportieren/#4) werden die einzelnen Formateinstellungen beschrieben.

In der folgenden Tabelle finden Sie Hinweise zu den Einstellungen, Formateinstellungen und empfohlenen Artikelfiltern für das Format **MyBestBrandsDE-Plugin**.
<table>
    <tr>
        <th>
            Einstellung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Einstellungen
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            <b>MyBestBrandsDE-Plugin</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Bereitstellung
        </td>
        <td>
            <b>URL</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Dateiname
        </td>
        <td>
            Der Dateiname muss auf <b>.csv</b> oder <b>.txt</b> enden, damit MyBestBrands.de die Datei erfolgreich importieren kann.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Artikelfilter
        </td>
    </tr>
    <tr>
        <td>
            Aktiv
        </td>
        <td>
            <b>Aktiv</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Märkte
        </td>
        <td>
            Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Formateinstellungen
        </td>
    </tr>
    <tr>
        <td>
            Auftragsherkunft
        </td>
        <td>
            Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll.
        </td>        
    </tr>
    <tr>
        <td>
            Vorschautext
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            Angebotspreis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            MwSt.-Hinweis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
</table>


## 3 Übersicht der verfügbaren Spalten

<table>
    <tr>
        <th>
            Spaltenbezeichnung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
        <td>
            ProductID
        </td>
        <td>
            <b>Inhalt:</b> Die <b>Artikel-ID</b> der Variante.
        </td>        
    </tr>
    <tr>
		<td>
			ProductCategory
		</td>
		<td>
			<b>Inhalt:</b> Die Namen der Kategorien getrennt durch >, die mit der Variante verknüpft sind.
		</td>        
	</tr>
	<tr>
		<td>
			Deeplink
		</td>
		<td>
			<b>Inhalt:</b> Der <b>URL-Pfad</b> des Artikels abhängig vom gewählten <b>Mandanten</b> in den Formateinstellungen.
		</td>        
	</tr>
	<tr>
		<td>
			ProductName
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Artikelname</b>.
		</td>        
	</tr>
	<tr>
		<td>
			ImageUrl
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Artikelbilder werden vor Variantenbilder priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			ProductDescription
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Beschreibung</b>.
		</td>        
	</tr>
	<tr>
		<td>
			BrandName
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Name des Herstellers</b> des Artikels. Der <b>Externe Name</b> unter <b>Einstellungen » Artikel » Hersteller</b> wird priorisiert, wenn vorhanden.
		</td>        
	</tr>
	<tr>
		<td>
			Price
		</td>
		<td>
			<b>Ausgabe:</b> Hier steht der <b>Verkaufspreis</b>.
		</td>        
	</tr>
	 <tr>
		<td>
			PreviousPrice
		</td>
		<td>
			<b>Ausgabe:</b> Der <b>Verkaufspreis</b> der Variante. Wenn der <b>UVP</b> in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen.
		</td>        
	</tr>
    <tr>
        <td>
            AvailableSizes
        </td>
        <td>
            <b>Inhalt:</b> Die <b>Größen</b> der Variante, welche verfügbar sind.
        </td>        
    </tr>
    <tr>
        <td>
            Tags
        </td>
        <td>
            <b>Inhalt:</b> Die <b>Keywords</b> des Artikels.
        </td>        
    </tr>
    <tr>
		<td>
			EAN
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Barcode</b>.
		</td>        
	</tr>
    <tr>
        <td>
            LastUpdate
        </td>
        <td>
            <b>Inhalt:</b> Datum der <b>letzten Aktualisierung</b> des Artikels.
        </td>        
    </tr>
    <tr>
		<td>
			UnitPrice
		</td>
		<td>
			<b>Inhalt:</b> Der berechnete Grundpreis bezogen auf die Grundpreis Einheit.
		</td>        
	</tr>
	 <tr>
		<td>
			RetailerAttributes
		</td>
		<td>
			<b>Inhalt:</b> Der Wert der eingestellten <b>Shop-Aktion</b>.
		</td>        
	</tr>
    <tr>
        <td>
            Color
        </td>
        <td>
            <b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>Amazon</b> mit <b>Color</b> gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ <b>Text</b> oder <b>Auswahl</b>, das mit <b>mybestbrands.de » Farbe</b> verknüpft wurde.
        </td>        
    </tr>
</table>

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-rakuten-de/blob/master/LICENSE.md).
