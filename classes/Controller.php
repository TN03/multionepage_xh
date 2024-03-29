<?php

/**
 * Copyright (c) Holger Irmler
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Multionepage_XH.
 *
 * Multionepage_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Multionepage_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Onepage_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Multionepage;

use Fa;

class Controller {

    public static function dispatch() {
        global $edit, $plugin_cf, $plugin_tx, $pd_router, $pth;

        $pd_router->add_interest('multionepage_class');
        $pd_router->add_interest('multionepage_access');
        if ($plugin_cf['multionepage']['use_javascript']) {
            self::emitJavaScript();
        }
        if (XH_ADM) {
            if ($edit) {
                $template = trim($plugin_cf['multionepage']['admin_template']);
                if ($template != '') {
                    self::setTemplate($template);
                }
            }
            if (class_exists('Fa\\RequireCommand')) {
                (new Fa\RequireCommand)->execute();
            }
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            $pd_router->add_tab(
                    $plugin_tx['multionepage']['tab_title'],
                    "{$pth['folder']['plugins']}multionepage/multionepage_view.php"
            );
            self::fixPagedataTabs();
            if (self::isAdministrationRequested()) {
                self::handleAdministration();
            }
            if (!$edit) {
                XH_afterPluginLoading(array('Multionepage\\Controller', 'evaluateScripting'));
            }
        } else {
            XH_afterPluginLoading(array('Multionepage\\Controller', 'evaluateScripting'));
        }
    }

    protected static function emitJavaScript() {
        global $pth, $u, $bjs, $plugin_cf, $edit, $s;

        $pcf = $plugin_cf['multionepage'];
        $config = array(
            'isOnepage' => !$edit && (!XH_ADM || $s >= 0),
            'numericUrls' => $pcf['url_numeric'],
            'scrollDuration' => $pcf['scroll_duration'],
            'scrollEasing' => $pcf['scroll_easing'],
            'toplinkScrollposition' => $pcf['toplink_scrollposition']
        );
        if (XH_ADM && $pcf['url_numeric']) {
            $config['urls'] = array_flip($u);
        }
        $file = $pth['folder']['plugins'] . 'multionepage/multionepage.js';
        if (is_readable($pth['folder']['template'] . 'multionepage.min.js')) {
            $file = $pth['folder']['template'] . 'multionepage.min.js';
        } elseif (is_readable($pth['folder']['template'] . 'multionepage.js')) {
            $file = $pth['folder']['template'] . 'multionepage.js';
        }
        include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php');
        include_jQuery();
        $bjs .= '<script>var MULTIONEPAGE = ' . json_encode($config) . ';</script>'
                . '<script src="' . $file . '"></script>';
    }
    
    protected static function fixPagedataTabs() {
        global $bjs, $l, $plugin_cf, $plugin_tx, $pd_router, $pth, $s;

        if ($plugin_cf['multionepage']['pagedata_hide_unused_fields']) {
            $pd_router->add_tab(
                $plugin_tx['multionepage']['tab_page_title'],
                "{$pth['folder']['plugins']}multionepage/multionepage_page_view.php"
            );
            $bjs .= '<script>jQuery("#xh_tab_Pageparams_view, '
                    . '#xh_view_Pageparams_view")'
                    . '.css("cssText", "display: none !important;");</script>';
            if ($s > -1 && $l[$s] != 1) {
                $bjs .= '<script>jQuery("#xh_tab_Metatags_view, '
                    . '#xh_view_Metatags_view")'
                    . '.css("cssText", "display: none !important;");</script>';
            }
        }
    }

    /**
     * @param string $template
     */
    protected static function setTemplate($template) {
        global $pth;

        $pth['folder']['template'] = $pth['folder']['templates'] . $template . '/';
        $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
        $pth['file']['stylesheet'] = $pth['folder']['template'] . 'stylesheet.css';
        $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
        $pth['folder']['templateimages'] = $pth['folder']['template'] . 'images/';
    }

    /**
     * @return bool
     */
    protected static function isAdministrationRequested() {
        global $multionepage;

        return function_exists('XH_wantsPluginAdministration') && XH_wantsPluginAdministration('multionepage')
                || isset($multionepage) && $multionepage == 'true';
    }

    protected static function handleAdministration() {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $o .= self::renderInfo();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'multionepage');
        }
    }

    /**
     * @return string
     */
    private static function renderInfo() {
        $view = new View('info');
        $view->logo = self::logoPath();
        $view->version = MULTIONEPAGE_VERSION;
        return $view->render();
    }

    /**
     * @return string
     */
    protected static function logoPath() {
        global $pth;

        return $pth['folder']['plugins'] . 'multionepage/onepage.png';
    }

    public static function evaluateScripting() {
        global $c, $cl, $s;

        $oldS = $s;
        for ($i = 0; $i < $cl; $i++) {
            if (hide($i)) {
                continue;
            }
            $s = $i;
            $c[$i] = evaluate_scripting($c[$i]);
        }
        $s = $oldS;
    }

    /**
     * @param int
     * @return int
     */
    public static function getRoot($i) {
        global $l;

        if (isset($l[$i]) && $l[$i] > 1) {
            while ($l[$i] > 1) {
                $i --;
            }
        }
        return $i;
    }

    /**
     * @return array
     */
    public static function getSubPages() {
        global $cl, $l, $s;

        $start = self::getRoot($s);
        $pages = array();
        if ($start > -1 && $l[$start] == 1 && !hide($start)) {
            $pages[] = $start;
            $i = $start + 1;
            while ($i < $cl) {
                if ($l[$i] <= 1) {
                    break;
                }
                if (!hide($i)) {
                    $pages[] = $i;
                }
                $i++;
            }
        }
        return $pages;
    }

    /**
     * @param int $pageindex
     * @return mixed
     */
    protected static function renderEditlink($pageindex) {
        global $plugin_cf, $sn, $u, $tx;

        if ($plugin_cf['multionepage']['show_editlink']) {
            return '<div class="multionepage_editlink">'
                    . '<a title="' . $tx['editmenu']['edit'] . '" '
                    . 'href="' . $sn . '?' . $u[$pageindex] . '&amp;edit">'
                    . '<span class="fa fa-pencil-square-o"></span>'
                    . '<span class="multionepage_editlink_text">&nbsp;'
                    . $tx['editmenu']['edit'] . '</span></a>'
                    . '</div>' . PHP_EOL;
        } else {
            return '';
        }
    }
    
    /**
     * @param int $pageindex
     * @return mixed
     */
    public static function renderPreviewLink() {
        global $s, $plugin_cf, $pth, $tx, $pd_router;

        $pageData = $pd_router->find_page($s);
        if ($plugin_cf['multionepage']['show_previewlink'] &&
                empty($pageData['multionepage_access'])) {
            return '<div class="multionepage_previewlink" '
                    . 'style="visibility:hidden;">'
                    . '<a title="' . $tx['editmenu']['normal'] . '" href="#">'
                    . '<span class="fa fa-eye"></span>'
                    . '<span class="multionepage_previewlink_text">&nbsp;'
                    . $tx['editmenu']['normal'] . '</span></a>'
                    . '</div>' . PHP_EOL
                    . '<script src="' . $pth["folder"]["plugins"] 
                    . 'multionepage/multionepage_admin.js"></script>';
        } else {
            return '';
        }
    }

    /**
     * @param array $pages
     * @return string
     */
    public static function getContent($pages) {
        global $s, $o, $c, $edit, $plugin_cf, $pd_router;

        if (!($edit && XH_ADM) && $s > -1) {
            $contents = '';
            foreach ($pages as $i) {
                if ($plugin_cf['multionepage']['url_numeric']) {
                    $url = $i;
                } else {
                    $url = Urlify::makeUniqueUrl($i);
                }
                $pageData = $pd_router->find_page($i);
                $content = self::replaceAlternativeHeading($c[$i], $pageData);
                if (XH_ADM && !$edit) {
                    $content = self::renderEditlink($i) . $content;
                }
                $contents .= sprintf(
                        '<div id="%s" class="onepage_page %s">%s</div>', $url,
                        $pageData['multionepage_class'],
                        sprintf(
                                '<div class="%s">%s</div>',
                                $plugin_cf['multionepage']['inner_class'],
                                $content
                        )
                );
            }
            $o .= preg_replace('/#CMSimple (.*?)#/is', '', $contents);
        }
        return preg_replace('/<!--XH_ml[1-9]:.*?-->/is', '', $o);
    }

    /**
     * @param string $content
     * @return string
     * @todo Use Pageparams_replaceAlternativeHeading() if available.
     */
    protected static function replaceAlternativeHeading($content,
            array $pageData) {
        global $cf;

        if (isset($pageData['show_heading']) && $pageData['show_heading'] == '1') {
            $pattern = '/(<h[1-' . $cf['menu']['levels'] . '].*>).+(<\/h[1-'
                    . $cf['menu']['levels'] . ']>)/isU';
            if (trim($pageData['heading']) == '') {
                return preg_replace($pattern, '', $content);
            } else {
                return preg_replace(
                        $pattern,
                        '${1}' . addcslashes($pageData['heading'], '$\\') . '$2',
                        $content
                );
            }
        } else {
            return $content;
        }
    }

    /**
     * @param string $id
     * @param string $imgfile
     * @return string
     */
    public static function renderTopLink($id, $imgfile) {
        global $pth, $plugin_tx;

        if ($id != '' && $id[0] == '#') {
            $id = substr($id, 1);
        }
        $image = $pth['folder']['templateimages'] . $imgfile;
        if (!file_exists($image) || $imgfile == '') {
            $image = $pth['folder']['plugins'] . 'multionepage/images/up.png';
        }
        $alt = $plugin_tx['multionepage']['alt_toplink'];
        return '<a id="onepage_toplink" href="#' . $id . '">'
                . tag('img src="' . $image . '" alt="' . $alt . '"')
                . '</a>';
    }
    
    /**
     * @return void
     */
    public static function renderSitemap() {
        global $cl, $tx, $o, $pd_router, $title;

        $title = $tx['title']['sitemap'];
        $pages = array();
        $o .= '<h1>' . $title . '</h1>' . "\n";
        for ($i = 0; $i < $cl; $i++) {
            $pageData = $pd_router->find_page($i);
            if (XH_ADM || (!hide($i) && $pageData['linked_to_menu'])) {
                $pages[] = $i;
            }
        }
        $t = new Sitemapli();
        $o .= $t->render($pages, 'sitemaplevel');
    }

}
