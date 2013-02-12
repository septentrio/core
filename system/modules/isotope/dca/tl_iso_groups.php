<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2012 Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://www.isotopeecommerce.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 *
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 */

/**
 * Table tl_iso_groups
 */
$GLOBALS['TL_DCA']['tl_iso_groups'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'                    => 'Table',
        'label'                            => &$GLOBALS['TL_LANG']['tl_iso_groups']['label'],
        'enableVersioning'                => true,
        'onload_callback' => array
        (
            array('Isotope\tl_iso_groups', 'checkPermission'),
        ),
        'ondelete_callback' => array
        (
            array('Isotope\tl_iso_groups', 'deleteGroup'),
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                        => 5,
            'fields'                    => array('sorting'),
            'flag'                        => 1,
            'icon'                        => 'system/modules/isotope/assets/folders.png',
        ),
        'label' => array
        (
            'fields'                    => array('name'),
            'format'                    => '%s',
            'label_callback'            => array('Isotope\tl_iso_groups', 'addIcon')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                    => 'act=select',
                'class'                    => 'header_edit_all',
                'attributes'            => 'onclick="Backend.getScrollOffset();"'
            ),
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['tl_iso_groups']['edit'],
                'href'                    => 'table=tl_iso_groups&amp;act=edit',
                'icon'                    => 'edit.gif'
            ),
            'copy' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['tl_iso_groups']['copy'],
                'href'                    => 'table=tl_iso_groups&amp;act=paste&amp;mode=copy',
                'icon'                    => 'copy.gif',
                'button_callback'        => array('Isotope\tl_iso_groups', 'copyButton'),
            ),
            'cut' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['tl_iso_groups']['cut'],
                'href'                    => 'table=tl_iso_groups&amp;act=paste&amp;mode=cut',
                'icon'                    => 'cut.gif',
                'attributes'            => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['tl_iso_groups']['delete'],
                'href'                    => 'table=tl_iso_groups&amp;act=delete',
                'icon'                    => 'delete.gif',
                'attributes'            => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
                'button_callback'        => array('Isotope\tl_iso_groups', 'deleteButton'),
            ),
            'show' => array
            (
                'label'                    => &$GLOBALS['TL_LANG']['tl_iso_groups']['show'],
                'href'                    => 'act=show',
                'icon'                    => 'show.gif'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                        => '{group_legend},name,product_type;',
    ),

    // Fields
    'fields' => array
    (
         'name' => array
         (
             'label'                        => &$GLOBALS['TL_LANG']['tl_iso_groups']['name'],
            'exclude'                    => true,
            'inputType'                    => 'text',
            'eval'                        => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'product_type' => array
        (
            'label'                        => &$GLOBALS['TL_LANG']['tl_iso_groups']['product_type'],
            'exclude'                    => true,
            'inputType'                    => 'select',
            'options_callback'            => array('Isotope\ProductCallbacks', 'getProductTypes'),
            'eval'                        => array('includeBlankOption'=>true, 'tl_class'=>'w50')
         ),
    )
);
