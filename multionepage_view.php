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

function Multionepage_view($pageData)
{
    global $sn, $su, $plugin_tx;

    $action = XH_hsc("$sn?$su");
    $class = XH_hsc($pageData['multionepage_class']);
    $checkedAttr = $pageData['multionepage_access'] ? ' checked="checked"' : '';
    return <<<HTML
<form id="multionepage" action="$action" method="post">
    <p>
        <b>{$plugin_tx['multionepage']['tab_title']}</b>
    <p>
        <label>
            <span>{$plugin_tx['multionepage']['tab_class']}</span>
            <input type="text" name="multionepage_class" value="$class">
        </label>
    </p>
    <p>
        <label>
            <span>{$plugin_tx['multionepage']['tab_access']}</span>
            <input type="hidden" name="multionepage_access" value="0">
            <input type="checkbox" name="multionepage_access" value="1" {$checkedAttr}>
        </label>
    </p>
    <p>
        <button name="save_page_data">{$plugin_tx['multionepage']['tab_save']}</button>
    </p>
</form>
HTML;
}
