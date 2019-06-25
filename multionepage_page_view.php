<?php

/**
 * Copyright (c) Holger Irmler
 *
 * This file is part of Multionepage_XH.
 * 
 * 
 * Page-Parameters - module page_params_view
 *
 * Creates the menu for the user to change
 * page-parameters per page.
 *
 * @category  CMSimple_XH
 * @package   Pageparams
 * @author    Martin Damken <kontakt@zeichenkombinat.de>
 * @author    Jerry Jakobsfeld <mail@simplesolutions.dk>
 * @author    The CMSimple_XH developers <devs@cmsimple-xh.org>
 * @copyright 2009-2017 The CMSimple_XH developers <http://cmsimple-xh.org/?The_Team>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://cmsimple-xh.org/
 */

 /**
  * Returns the caption of a page param section.
  *
  * @param string $label A label.
  * @param string $hint  A help tooltip text.
  *
  * @return string HTML
  *
  * @since 1.6
  */
function Multionepage_Pageparams_caption($label, $hint)
{
    return "\n\t" . XH_helpIcon($hint)
        . "\n\t" . '<span class="pp_label">' . $label . '</span>';
}

/**
 * Returns a checkbox.
 *
 * @param string $name    Name of the checkbox.
 * @param bool   $checked Whether the checkbox is checked.
 *
 * @return string HTML
 *
 * @since 1.6
 */
function Multionepage_Pageparams_checkbox($name, $checked)
{
    $checkedAttr = $checked ? ' checked="checked"' : '';
    $o = "\n\t\t" . '<input type="hidden" name="' . $name . '" value="0">'
        . '<input type="checkbox" name="' . $name . '" value="1"'
        . $checkedAttr . '>';
    return $o;
}

/**
 * Returns a text INPUT element for the scheduling.
 *
 * @param string $name     An element name.
 * @param string $value    An element value.
 * @param bool   $disabled Whether the input is disabled.
 *
 * @return string HTML
 *
 * @since 1.6
 */
function Multionepage_Pageparams_scheduleInput($name, $value, $disabled)
{
    $disabled = $disabled ? ' disabled="disabled"' : '';
    return '<input type="datetime-local" size="16" maxlength="16" name="' . $name . '"'
        . ' value="' . $value . '"' . $disabled . '>';
}

/**
 * Returns the editor tab view.
 *
 * @param array $page Page data of the current page.
 *
 * @return string HTML
 *
 * @global string The script name.
 * @global string The URL of the current page.
 * @global string Document fragment to insert into the HEAD element.
 * @global array  The localization of the plugins.
 */
function Multionepage_Page_view(array $page)
{
    global $sn, $su, $plugin_tx;

    //$hjs .= Pageparams_hjs();

    $lang = $plugin_tx['page_params'];

    $view = "\n" . '<form action="' . $sn . '?' . $su
        . '" method="post" id="multionepage_page_params" '
        . 'name="multionepage_page_params">';
    $view .= "\n\t" . '<p><b>' . $lang['form_title'] . '</b></p>';

    /*
     * published
     */
    $view .= Multionepage_Pageparams_caption($lang['published'], $lang['hint_published']);
    $view .= Multionepage_Pageparams_checkbox('published', $page['published'] != '0');
    $view .= '<br>';
    $view .= "\n\t" . XH_helpIcon($lang['hint_publication_period']);
    $view .= "\n\t\t" . $plugin_tx['page_params']['publication_period'];
    $view .= Multionepage_Pageparams_scheduleInput('publication_date', $page['publication_date'], $page['published'] == '0');
    $view .= ' - ';
    $view .= Multionepage_Pageparams_scheduleInput('expires', $page['expires'], $page['published'] == '0');
    $view .= '<br>';
    $view .= "\n\t" . '<hr>';

    /*
     * linked to menu
     */
    $view .= Multionepage_Pageparams_caption($lang['linked_to_menu'], $lang['hint_linked_to_menu']);
    $view .= Multionepage_Pageparams_checkbox('linked_to_menu', $page['linked_to_menu'] !== '0');
    $view .= '<br>';

    $view .= "\n\t" . '<input name="save_page_data" type="hidden">'
        . "\n\t" . '<div style="text-align: right">'
        . "\n\t\t" . '<input type="submit" value="' . $lang['submit'] . '">'
        . '<br>'
        . "\n\t" . '</div>'
        . "\n" . '</form>';
    return $view;
}
