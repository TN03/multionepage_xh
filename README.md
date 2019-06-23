# Multionepage\_XH

Multionepage\_XH bietet Werkzeuge um so genannte "Onepage" Websites zu
erstellen und zu Administrieren. Im Backend können Sie die Site wie
gewohnt administrieren, aber im Frontend wird ein besonderes Template
verwendet, das alle sichtbaren Seiten unterhalb einer Level-1-Seite auf einmal 
zeigt. Das Navigationsmenü verlinkt zu automatisch eingefügten Ankern auf der 
selben Seite. Durch anlegen mehrerer Level-1-Seiten mit Unterseiten lassen
sich in einer Site mehrere "Onepager" realisieren.

  - [Voraussetzungen](#voraussetzungen)
  - [Download](#download)
  - [Installation](#installation)
  - [Einstellungen](#einstellungen)
  - [Verwendung](#verwendung)
      - [Template](#template)
      - [Page-Data Reiter](#page-data-reiter)
  - [Beschränkungen](#beschränkungen)
  - [Fehlerbehebung](#fehlerbehebung)
  - [Lizenz](#lizenz)
  - [Danksagung](#danksagung)

## Voraussetzungen

Multionepage\_XH ist ein Plugin für CMSimple\_XH. Es benötigt CMSimple\_XH ≥
1.6.3 und PHP ≥ 5.3.0 mit der *JSON* Extension.

## Download

Das [aktuelle Release](https://github.com/tn03/multionepage_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple\_XH-Plugins
auch. Im
[CMSimple\_XH-Wiki](https://wiki.cmsimple-xh.org/doku.php/de:installation#plugins)
finden Sie weitere Details.

1.  Sichern Sie die Daten auf Ihrem Server.
2.  Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
3.  Laden Sie das ganze Verzeichnis multionepage/ auf Ihren Server in
    CMSimple\_XHs Plugin-Verzeichnis hoch.
4.  Machen Sie die Unterverzeichnisse config/, css/ und languages/
    beschreibbar.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen
CMSimple\_XH-Plugins auch im Administrationsbereich der Website. Wählen
Sie Plugins → Multionepage.

Sie können die Voreinstellungen von Multionnepage\_XH unter "Konfiguration"
ändern. Hinweise zu den Optionen werden beim Überfahren der Hilfe-Icons
mit der Maus angezeigt.

Die Lokalisierung wird unter "Sprache" vorgenommen. Sie können die
Sprachtexte in Ihre eigene Sprache übersetzen, falls keine entsprechende
Sprachdatei zur Verfügung steht, oder diese Ihren Wünschen gemäß
anpassen.

Das Aussehen von Multionepage\_XH kann unter "Stylesheet" angepasst werden.

## Verwendung

### Template

"Multionepage" Websites benötigen ein besonderes Template, bei dem einige der
normalen CMSimple\_XH Template-Tags durch Alternativen ersetzt sind, die
Multionepage\_XH zur Verfügung stellt.

#### `multionepage_topleveltoc()`

Dies ist ein **alternativer** Ersatz für `toc(1, 1)`, der Links zu allen
sichtbaren Level-1-Seiten ausgibt, um mehrere "Onepager" in einer Seite 
zu verlinken. Die Ausgabe ist identisch zu `toc(1, 1)`.
Für ein Navigationsmenü, bei dem alle Einträge klickbar bleiben, muss die 
Funktion mit dem Parameter `true` aufgerufen werden: `multionepage_topleveltoc(true)`.

#### `multionepage_toc()`

Dies ist ein **erforderlicher** Ersatz für `toc()`, der Links zu allen
sichtbaren Seiten, bzw. Seiten-IDs der aktuellen "Onepage" anzeigt.
Um mehrere "Onepager" in einer Seite zu verlinken, muss das Template
ein Navigationsmenü zu allen Level-1-Seiten enthalten, zum Beispiel
`toc(1,1)` oder `multionepage_topleveltoc()`.

#### `multionepage_content()`

Dies ist ein **erforderlicher** Ersatz für `content()`, der den Inhalt einer
Level-1-Seite inklusive den Inhalten aller sichtbaren Unterseiten 
auf einer Seite anzeigt.

#### `multionepage_toplink()`

Dies ist ein **optionaler** Ersatz für `top()`, der konfigurierbares sanftes
Scrollen anbietet, und nur angezeigt wird, wenn der Anwender bereits
etwas nach unten gescrollt hat. Ohne JavaScript-Unterstützung wird der
Link immer angezeigt, und statt des sanften Scrollens wird gesprungen.
Das Bild des Links kann geändert werden, indem Sie eine Bilddatei mit
dem Namen up.png im images/ Ordner des Templates ablegen.

Dieses Template-Tag akzeptiert einen optionalen Parameter, die ID eines
Elements. Auf diese Weise können Sie den Anfang der Seite individuell
definieren. Wenn Sie der Funktion kein Argument übergeben, verweist der
Link ganz oben auf die Seite.

#### Nicht unterstützte Template-Tags

Mehrere Template-Tags werden für "Onepage" Templates nicht unterstützt:
`content()`, `li()`, `locator()`, `mailformlink()`, `nextpage()`, `previouspage()`,
`printlink()`, `searchbox()`, `sitemaplink()`, `submenu()`, `toc()`.

#### Menue- und Inhaltsstruktur

In der Hoffnung, dass Templates einfach angepasst werden können, verwendet
Multionepage\_XH die selben CSS-Klassen für die Naviagtionsmenues
und die selbe Struktur für die ausgegebenen Inhalte, wie das Originale
[Onepage\_XH-Plugin](https://github.com/cmb69/onepage_xh).

#### Edit-Link

Wenn in der Konfiguration aktiviert, blendet das Plugin dem Administrator
im Ansichtsmodus einen Link zum direkten Editieren der aktuellen
Seite ein. Die Position und das Aussehen des Links kann im Template
mittels CSS angepasst werden.

#### Preview-Link

Wenn in der Konfiguration aktiviert, blendet das Plugin im Bearbeitungsmodus 
einen Link ein, der direkt zur ensprechenden Position des aktuell
bearbeiteten Inhaltes in der Voransicht springt. Der Link wird oberhalb des
Editors eingeblendet und kann durch das Template mittels CSS angepasst werden.
Bei Seiten, für die mttels PageData-Tab direkter Zugriff / direkte Anzeige
aktiviert ist, wird kein Link angezeigt. In diesem Fall muss die Vorschau 
über den entsprechenden Link im Adminmenü aktiviert werden.

### Page-Data Reiter

Im Reiter "Multionepage" (oberhalb des Editors) kann optional eine
zusätzliche CSS Klasse für die jeweilige Seite vergeben werden. Dies
ermöglicht individuelles und robustes Seitendesign.

Die direkte Verlinkung bzw. Ausgabe von Unterseiten wird durch
Multionepage\_XH unterbunden. Um trotzdem Seiten > Level 1 einzeln anzuzeigen,
gibt es im Reiter "Multionepage" eine Checkbox die die Ausgabe für
die jeweilige Seite erlaubt. Die Ausnahme gilt auch eine versteckten Unterseite, 
was für diverse Plugins und besondere Verlinkungen nützlich sein könnte.
**Wichtig:**
da der direkte Zugriff auf Unterseiten unterbunden wird, ist 
die Vorschau einer versteckten Unterseite auch nur möglich, wenn der direkte 
Zugriff auf die Seite erlaubt wurde.

## Beschränkungen

Vermutlich werden nicht alle Plugins reibungslos unter "Onepage"
Websites funktionieren. Z.B. können Sie mit page\_params keine
seitenspezischen Templates wählen, und keine Seitenweiterleitung
konfigurieren.

## Fehlerbehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf [Github](https://github.com/tn03/multionepage_xh/issues)
oder im [CMSimple_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Multionepage\_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Multionepage\_XH erfolgt in der Hoffnung, daß es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Onepage\_XH erhalten haben. Falls nicht, siehe
<http://www.gnu.org/licenses/>.

Copyright © Holger Irmler

## Danksagung

Multionepage\_XH basiert auf dem Onepage\_XH-Plugin von 
[Christoph M. Becker](https://github.com/cmb69/onepage_xh).

Das "nach oben scrollen" Icon wurde vom [Oxygen
Team](http://www.iconarchive.com/show/oxygen-icons-by-oxygen-icons.org.html)
gestaltet. Vielen Dank für die Veröffentlichung unter GPL.

Vielen Dank an die Community im [CMSimple\_XH
forum](http://www.cmsimpleforum.com/) für Hinweise, Anregungen und das
Testen. Besonderer Dank gebührt *frase* und *lck* für lange und fruchtbare 
Diskussionen und reichlich Vorschlägen rund um "Onepager" und natürlich 
*cmb69*, dessen Onepage_XH.- Plugin als Vorlage diente.

Und zu guter letzt vielen Dank an [Peter Harteg](http://www.harteg.dk/),
den "Vater" von CMSimple, und allen Entwicklern von
[CMSimple\_XH](http://www.cmsimple-xh.org/de/) ohne die es dieses
phantastische CMS nicht gäbe.
