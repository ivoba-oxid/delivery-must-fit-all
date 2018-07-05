# Delivery must fit all articles Oxid eShop v6

Unfortunatly Oxid eShop shows a delivery when just one article matches or when a the sum amount fits the delivery rule.
This becomes a bug when you have deliveries that only work for certain articles, f.e. letters.
So if you have a letter and a cup in the cart, you dont want the letter delivery to be shown.

With this module you can select certain delivery rules that have to fit all articles to be shown.

---
Leider zeigt Oxid Versandarten an wenn nur ein Artikel passt oder die Gesamtsumme.  
Das wird dann zum Bug wenn man Versandarten hat, die nur für bestimmte Artikel passen, zB Briefversand.  
Ist ein Karte und eine Tasse im Warenkorb, soll der Briefversand nicht angezeigt werden.  
Mit diesem Modul kann man Versandregeln bestimmen bei denen alle Artikel passen müssen bevor die Versandart angezeigt wird.

https://forum.oxid-esales.com/t/versandkosten-nur-gultig-wenn-alle-artikel-passen/93764

## Installation

    composer require ivoba-oxid/delivery-must-fit-all

## Usage
In "Erweiterungen -> Module -> Ivo Bathke: Delivery Must Fit All" enter your settings in the "Settings" tab  

- Add the title of the delivery-rule in the field, if multiple rules add one per line.
- Den Titel der Versandregel in das Feld unter Einstellungen eintragen. Mehrere Regeln untereinander eintragen.

## Requirements
- UTF-8
- PHP >= 7
- Oxid eShop >= CE 6

## License MIT

© [Ivo Bathke](https://oxid.ivo-bathke.name)
