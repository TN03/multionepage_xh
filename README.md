



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
      - [Javascript](#javascript)
      - [Seitenbearbeitung](#seitenbearbeitung)
      - [Page-Data Reiter](#page-data-reiter)
  - [Beschränkungen](#beschränkungen)
  - [Fehlerbehebung](#fehlerbehebung)
  - [Lizenz](#lizenz)
  - [Danksagung](#danksagung)

## Voraussetzungen

Multionepage\_XH ist ein Plugin für CMSimple\_XH. Es benötigt CMSimple\_XH ≥
1.6.3 und PHP ≥ 5.4.0 mit der *JSON* Extension. 
Für volle Funktionsfähigkeit wird das Plugin Fa_XH benötigt, welches in 
CMSimple\_XH ab Version 1.7 enthalten ist, oder von [GitHub](https://github.com/cmb69/fa_xh/releases) geladen werden kann. 

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
`printlink()`, `searchbox()`, ~~`sitemaplink()`~~\*, `submenu()`, `toc()`.

\* `sitemaplink()` wird ab v1.0beta2 unterstützt. Die Links in der Sitemap verweisen direkt an die entsprechende Seiten-Id im jeweiligen Onepager. Die Konfigurationseinstellung 
"*Versteckte Seiten anzeigen → Inhaltsverzeichnis"*  
wird nicht unterstützt, da diese Seiten nicht in einem Onepager angezeigt werden und daher keine 
sinnvolle Verlinkung möglich ist.

#### Menue- und Inhaltsstruktur

In der Hoffnung, dass Templates einfach angepasst werden können, verwendet
Multionepage\_XH die selben CSS-Klassen für die Naviagtionsmenues
und die selbe Struktur für die ausgegebenen Inhalte, wie das Originale
[Onepage\_XH-Plugin](https://github.com/cmb69/onepage_xh).

### Javascript

Multionepage_XH nutzt jQuery für Browserscripting. Empfohlen ist eine aktuelle
jQuery-Version > 3. Der Code befindet sich in der Datei */plugins/multionepage/multionepage.js*.
Sofern in der Konfiguration aktiviert, wird die Datei automatisch geladen und stellt 
zum Beispiel Funktionen für "smooth scrolling", Hervorhebung des aktuellen 
Menüpunktes im Onepage-Menü, aktualisieren der Browser-Historie usw. bereit.
Im Template kann eine alternative Javascriptdatei bereit gestellt werden. Befindet sich
im Template-Ordner eine Datei *multionepage.js* bzw. *multionepage.min.js*, wird diese Datei
vom Plugin automatisch geladen.

Zur Konfiguration einzelner Javascript-Funktionen stehen folgende Optionen zur Verfügung,
die in der *template.htm* konfiguriert werden können:

#### Benutzerdefiniertes Scroll-Offset

Beim Scrollen zu IDs / Ankern per Onepage-Menü, kann, z.B. bei fixierten Seiten-Headern 
ein Offset definiert werden, damit das Scroll-Ziel unter die fixierten Elemente verschoben wird:
Beispiel: `<script>onepage_customOffset = 80</script>`

#### Offset bei der Markierung des aktiven Links im Menü

Beim Scrollen der Seite wird je nach aktuell angezeigter Seiten-ID der passende Menüpunkt 
in der Navigation markiert. Hierzu werden die bekannten Menüklassen `.sdoc / . sdocs` 
dynamisch angepasst. Zusätzlich bekommt der aktive Link die Klasse .onepage_current zugewiesen.
Ein Offset zur Beeinflussung der Scrollposition, ab der der jeweilige Menüpunkt als aktiv markiert 
wird, kann über eine Variable  gesetzt werden: 
Beispiel: `<script>onepage_navSpyOffset = 30</script>`

### Seitenbearbeitung

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

#### Smooth-Scroll für interne Links
Wird einem Link zu einem internen Anker, bzw. einer gültigen Seiten-ID, 
die Klasse `scrollTo` zugewiesen, wird der Browser "weich" zum Linkziel skrollen, 
sofern "Javascript laden" in der Konfiguration von Multionepage_XH ausgewählt ist.

### Page-Data Reiter

#### CSS-Klasse
Im Reiter "Multionepage" (oberhalb des Editors) kann optional eine
zusätzliche CSS Klasse für die jeweilige Seite vergeben werden. Dies
ermöglicht individuelles und robustes Seitendesign.

#### Direktzugriff auf Einzelseiten erlauben
Die direkte Verlinkung bzw. Ausgabe von Unterseiten wird durch Multionepage\_XH 
unterbunden. Um trotzdem Seiten > Level 1 als eigenständige Einzelseiten  
anzuzeigen, gibt es im Reiter "Multionepage" eine Checkbox, welche die Ausgabe für
die jeweilige Seite erlaubt. Die Ausnahme gilt auch für versteckte Unterseiten, 
was für diverse Plugins und besondere Verlinkungen nützlich sein könnte.
**Wichtig:**
da der direkte Zugriff auf Unterseiten unterbunden wird, ist die Vorschau einer versteckten 
Unterseite auch nur möglich, wenn der direkte Zugriff auf die Seite erlaubt wurde. 
Bei versteckten Seiten wird kein Vorschau-Link über dem Editor angezeigt. Hier muss der 
entsprechende Button im Admin-Menü verwendet werden.
Weiterhin wird das interne Seitenmenü (.multionepage_menu) in der Vorschau ausgeblendet.

#### Nicht verwendbare PageData-Einstellungen
In Onepagern sind einzelne Optionen aus dem PageData-Tab "Seite", sowie der
Tab "Meta" nicht sinnvoll verwendbar. Multionepage_XH kann diese Tabs
ausblenden, wenn das Feature über die Plugin-Konfiguration aktiviert wird.
Der Tab "Meta" wird dann nur auf Level-1 Seiten eingeblendet, wo die dort 
hinterlegten Einstellungen dann für den jeweiligen "Onepager" insgesamt gelten. 
Der Tab "Seite" wird komplett deaktiviert und durch den Tab "Seite (MOP)" ersetzt.

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
*cmb69*, dessen Onepage_XH- Plugin als Vorlage diente.

Und zu guter letzt vielen Dank an [Peter Harteg](http://www.harteg.dk/),
den "Vater" von CMSimple, und allen Entwicklern von
[CMSimple\_XH](http://www.cmsimple-xh.org/de/) ohne die es dieses
phantastische CMS nicht gäbe.
