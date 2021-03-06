/*
 * Multionepage_XH browser scripting with jQuery.
 *
 * @copyright 2016-2019 Holger Irmler <http://cmsimple.holgerirmler.de/>
 * @license   GNU GPLv3 <http://www.gnu.org/licenses/gpl-3.0.en.html>
 */
jQuery(function ($) {

    "use strict";

    //Container fuer Navigation
    //var $nav = $("ul.menulevel1");
    var $nav = $("ul.onepage_menu");
    //Container einer "Seite"
    var $sections = $("div.onepage_page");
    
    
    //Benutzerdefiniertes Offset fuer z.B. fixierte Header.
    //Entweder aus Konfig, oder besser dynamisch mittels Template(-Funktion)
    //Default: 0
    var customOffset = typeof onepage_customOffset !== "undefined" ?
            onepage_customOffset : 0;
    //Scroll-Offset zur Markierung des aktiven Menuepunktes
    //Default: 0
    var navSpyOffset = typeof onepage_navSpyOffset !== "undefined" ?
            onepage_navSpyOffset : 0;
    //Menueklassen beim Scrollen updaten?
    //Default: true
    var updateNavOnScroll = typeof onepage_updateNavOnScroll !== "undefined" ?
            onepage_updateNavOnScroll : true;
    //Browser-Historie und Hash aktualisieren?
    //Default: true
    var updateHash = typeof onepage_updateHash !== "undefined" ?
            onepage_updateHash : true;

            
    function toggleStatus($anchor, status) {
        if (status === true) {
            $anchor.addClass("onepage_current");
            $anchor.parent("li").addClass("sdoc");
            $anchor.parent("li").removeClass("doc");
        }
        else {
            $anchor.parent("li").addClass("doc");
            $anchor.removeClass("onepage_current");
            $anchor.parent("li").removeClass("sdoc");
        }
    }

    /*
    function initMenuClasses() {
        var hash = window.location.hash;
        if (hash.length) {
            $nav.find("a").each(function () {
                toggleStatus($(this), false);
            });
            var $active = $nav.find("a[href$=" + "'" + hash + "'" + "]");
            toggleStatus($active, true);
        }
        else {
            var $firstAnchor = $nav.find("a").first();
            toggleStatus($firstAnchor, true);
        }
    }
    */

    function initMenuClasses() {
        var $active;
        var hash = window.location.hash;
        
        if (hash.length) {
            $nav.find("a").each(function () {
                toggleStatus($(this), false);
            });
            $active = $nav.find("a[href$=" + "'" + hash + "'" + "]");
            toggleStatus($active, true);
        } else {
            var url = $(location).attr('href');
            if (url.indexOf('?') > 0 && url.indexOf('&edit') > 0) {
                url = url.replace('&edit', '');
                url = url.split('?').pop();
                url = window.location.pathname + '?' + url + '#';
                $active = $nav.find("a[href^=" + "'" + url + "'" + "]");
                toggleStatus($active, true);
            } else {
                var $firstAnchor = $nav.find("a").first();
                toggleStatus($firstAnchor, true);
            }
        }
    }   
    
    function updateMenuClasses() {
        var curPos = $(window).scrollTop();
        $sections.each(function () {
            var top = $(this).offset().top - customOffset - navSpyOffset;
            var bottom = top + $(this).outerHeight();
            if (curPos >= top - 1 && curPos <= bottom) {
                $nav.find("a").each(function () {
                    toggleStatus($(this), false);
                });
                var $active = $nav.find("a[href$=" + "'#" +
                        $(this).attr('id') + "'" + "]");
                toggleStatus($active, true);
            }
        });
    }

    function scrollToId(hash) {
        //"Refresh", falls "?" in Url enthalten, z.B. nach Versand
        //des Mailforms oder nach Logout noetig,
        //aber generell nicht im Admin-Mode!
        /*
        var admin = document.getElementById("xh_adminmenu");
        var url = window.location.toString();
        if (url.indexOf("?") > 0 && !admin) {
            var refreshUrl = url.substring(0, url.indexOf("?"));
            window.location.href = refreshUrl + hash;
        }
        */
        //Hash aktualisieren, damit Browser-Historie passt,
        if (updateHash) {
            if(history.pushState) {
                history.pushState(null, null, hash);
            } else {
                window.location.hash = hash;
            }
        }

        var dest;
        if (hash === '#') {
            dest = 0;
        } else {
            if ($(hash).length > 0) {
                dest = $(hash).offset().top - customOffset;
            }
        }

        //Scrollen mit konstanter Geschwindigkeit
        /*
         var pos = $(window).scrollTop();
         var pixPerSec = 2500;
         var distance = Math.abs(pos - dest);
         console.log(distance);
         var duration = (distance / pixPerSec) * 1000;
         console.log(duration);
         
         $("html,body").stop().animate({scrollTop: dest},
         duration, "linear");
         */

        $("html,body").stop().animate({scrollTop: dest},
            +MULTIONEPAGE.scrollDuration, "swing");
    }

    function toggleTopLink() {
        if ($(window).scrollTop() > +MULTIONEPAGE.toplinkScrollposition) {
            $("#onepage_toplink").stop().fadeIn();
        } else {
            $("#onepage_toplink").stop().fadeOut();
        }
    }
    
    function init() {
        //Position berichtigen, wenn Seite mit Hash aufgerufen
        var hash = window.location.hash;
        if (hash.length) {
            scrollToId(hash);
        }
        initMenuClasses();
        $("ul.onepage_menu a, a.scrollTo").click(function (e) {
            hash = this.hash;
            if (hash.length > 0 && MULTIONEPAGE.isOnepage) {
                scrollToId(hash);
                e.preventDefault();
            }
        });
        $("a#onepage_toplink").click(function (e) {
            hash = this.hash;
            if (hash.length) {
                scrollToId(hash);
            } else {
                scrollToId("#");
            }
            e.preventDefault();
        });
        $(window).scroll(function () {
            toggleTopLink();
            if (updateNavOnScroll) {
                updateMenuClasses();
            }
        });
    }
    
    init();
});