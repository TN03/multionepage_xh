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

class Liclick extends XH_Li
{ 
    /**
     * @param int $i
     * @return string
     */
    public function renderMenuItem($i)
    {
        global $h;

        return $this->renderAnchorStartTag($i) . $h[$this->ta[$i]] . '</a>';
    }
}
