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
const MULTIONEPAGE_VERSION = '1.0beta1';

/**
 * @return array
 */
function multionepage_templates() {
    return array_merge(array(''), XH_templates());
}

/**
 * @return string
 */
/*
function Onepage_toc() {
    global $pth, $hc;

    if (!function_exists('XH_autoload')) {
        include_once $pth['folder']['classes'] . 'Menu.php';
    }
    $li = new Multionepage\Li();
    return $li->render($hc, 1);
}
*/

function multionepage_LiClickable($ta, $st) {
    global $pth;
    
    if (!function_exists('XH_autoload')) {
        include_once $pth['folder']['classes'] . 'Menu.php';
    }
    $t = new Multionepage\Liclick();
    return $t->render($ta, $st);
}

/**
 * @param bool
 * @return string
 */
function multionepage_topleveltoc($cklickable = false) {
    if ($cklickable) {
        return str_replace('onepage_menu ', '', 
                toc(1, 1, 'multionepage_LiClickable'));
    } else {
        return toc(1, 1);
    }
}

/**
 * @return string
 */
function multionepage_fulltoc() {
    global $pth, $hc;

    if (!function_exists('XH_autoload')) {
        include_once $pth['folder']['classes'] . 'Menu.php';
    }
    $li = new Multionepage\Multili();
    return $li->render($hc, 1);
}

/**
 * @return string
 */
function multionepage_toc() {
    global $pth;

    $pages = Multionepage\Controller::getSubPages();
    if (!function_exists('XH_autoload')) {
        include_once $pth['folder']['classes'] . 'Menu.php';
    }
    $li = new Multionepage\Li();
    return $li->render($pages, 1);
}

/**
 * @return string
 */
/*
function Onepage_content() {
    global $hc;

    return Multionepage\Controller::getContent($hc);
}
*/

/**
 * @return mixed
 */
function multionepage_content() {
    global $bjs, $edit, $l, $u, $s, $sn, $pd_router;

    if ($s > -1 && $l[$s] > 1 && (!XH_ADM || (XH_ADM && !$edit))) {
        $pageData = $pd_router->find_page($s);
        if ($pageData['multionepage_access']) {
            $bjs .= '<script>jQuery(".onepage_menu").hide();</script>';
            return Multionepage\Controller::getContent(array($s));
        }
        $t = Multionepage\Controller::getRoot($s);
        if (hide($t)) {
            return shead(404);
        } else {
            $path = $sn . '?' . $u[$t];
            header("Location: $path", true, 301);
            exit;
        }
    } else {
        $pages = Multionepage\Controller::getSubPages();
        return Multionepage\Controller::getContent($pages);
    }
}

/**
 * @param string $id
 * @return string
 */
function multionepage_toplink($id = '') {
    return Multionepage\Controller::renderTopLink($id);
}

//handle Sitemap
if ($f == 'sitemap') {
    Multionepage\Controller::renderSitemap();
    $f = 'multionepage_sitemap';
}

if (XH_ADM && $edit) {
    $o .= Multionepage\Controller::renderPreviewLink();
}

Multionepage\Controller::dispatch();
