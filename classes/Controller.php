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

class Controller {

    public static function dispatch() {
        global $edit, $plugin_cf, $plugin_tx, $pd_router, $pth;

        $pd_router->add_interest('multionepage_class');
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
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            $pd_router->add_tab(
                    $plugin_tx['multionepage']['tab_title'],
                    "{$pth['folder']['plugins']}multionepage/multionepage_view.php"
            );
            if (self::isAdministrationRequested()) {
                self::handleAdministration();
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
     * @return int
     */
    public static function getRoot() {
        global $l, $s;

        $x = $s;
        if (isset($l[$x]) && $l[$x] > 1) {
            while ($l[$x] > 1) {
                $x --;
            }
        }
        return $x;
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
                    . '<a title="'. $tx['editmenu']['edit'] . '" '
                    . 'href="' . $sn . '?' . $u[$pageindex] . '&amp;edit">'
                    . '<span class="fa fa-pencil-square-o"></span></a>'
                    . '</div>' . PHP_EOL;
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
                        $pageData['onepage_class'],
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
     * @return string
     */
    public static function renderTopLink($id) {
        global $pth, $plugin_tx;

        if ($id != '' && $id[0] == '#') {
            $id = substr($id, 1);
        }
        $image = $pth['folder']['templateimages'] . 'up.png';
        if (!file_exists($image)) {
            $image = $pth['folder']['plugins'] . 'multionepage/images/up.png';
        }
        $alt = $plugin_tx['multionepage']['alt_toplink'];
        return '<a id="onepage_toplink" href="#' . $id . '">'
                . tag('img src="' . $image . '" alt="' . $alt . '"')
                . '</a>';
    }

}
