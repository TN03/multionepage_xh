/*
 * Multionepage_XH browser scripting with jQuery.
 *
 * @copyright 2016-2019 Holger Irmler <http://cmsimple.holgerirmler.de/>
 * @license   GNU GPLv3 <http://www.gnu.org/licenses/gpl-3.0.en.html>
 */
jQuery(function ($) {

    "use strict";

    function renderPreviewLink() {
        var temp, url, $link, href, path, page, hash;
        var $nav = $("ul.onepage_menu");

        url = $(location).attr('href');
        if (url.indexOf('?') > 0) {
            url = url.replace('&edit', '');
            url = url.split('?').pop();
            $link = $nav.find("a[href*=" + "'" + url + "'" + "]");
            href = $link.attr('href');
            if (href) {
                temp = (href.split('?'));
                path = temp[0];
                temp = (temp[1].split('#'));
                hash = temp[1];
                temp = (temp[0].split('/'));
                page = temp[0];
                $('div.multionepage_previewlink a:first')
                        .attr('href', path + '?' + page + '&normal#' + hash);
                $('div.multionepage_previewlink')
                        .css('visibility', 'visible');
            }
        }
    }
    renderPreviewLink();
});
