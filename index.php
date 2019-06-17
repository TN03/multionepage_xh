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
const MULTIONEPAGE_VERSION = '0.1';

/**
 * @return array
 */
function Multionepage_templates() {
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
/**
 * @return string
 */
function Multionepage_fulltoc() {
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
function Multionepage_toc() {
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
 * @return string
 */
function Multionepage_content() {
    $pages = Multionepage\Controller::getSubPages();
    return Multionepage\Controller::getContent($pages);
}

/**
 * @param string $id
 * @return string
 */
function Multionepage_toplink($id = '') {
    return Multionepage\Controller::renderTopLink($id);
}

Multionepage\Controller::dispatch();
