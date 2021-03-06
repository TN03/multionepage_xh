<?php

/**
 * Copyright (c) Holger Irmler
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

use XH_Li;

class Sitemapli extends XH_Li {

    /**
     * @param int $i
     * @return string
     */
    public function renderMenuItem($i) {
        global $h;

        return $this->renderAnchorStartTag($i) . $h[$this->ta[$i]] . '</a>';
    }

    /**
     * @param int $i
     * @return string
     */
    public function renderAnchorStartTag($i) {
        $x = $this->shallOpenInNewWindow($i) ? '" target="_blank' : '';
        return $this->anchor($this->ta[$i], $x);
    }

    /**
     * @param int $i
     * @param string $x
     * @return string
     */
    protected function anchor($i, $x) {
        global $cf, $l, $sn, $u, $plugin_cf;

        $html = '<a href="' . $sn;
        if ($l[$i] == 1) {
            $html .= '?' . $u[$i];
        } else {
            $path = explode($cf['uri']['seperator'], $u[$i]);
            $html .= '?' . $path[0];
        }
        if ($plugin_cf['multionepage']['url_numeric']) {
            $html .= '#' . $i;
        } else {
            $url = Urlify::makeUniqueUrl($i);
            $html .= '#' . $url;
        }
        $html .= $x . '">';
        return $html;
    }

}
