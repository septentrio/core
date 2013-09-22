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
 */


/**
 * Table tl_iso_notification
 */
$GLOBALS['TL_DCA']['tl_iso_notification'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'                 => 'Table',
        'enableVersioning'              => true,
//        'closed'                      => true,
//        'notEditable'                 => true,
        'ctable'                      => array('tl_iso_notification_channel'),
        'switchToEdit'                => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                      => 1,
            'fields'                    => array('name'),
            'flag'                      => 1,
            'panelLayout'               => 'limit',
        ),
        'label' => array
        (
            'fields'                    => array('name'),
            'format'                    => '%s',
//            'format'                    => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                  => 'act=select',
                'class'                 => 'header_edit_all',
                'attributes'            => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ),
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification']['edit'],
                'href'                  => 'table=tl_iso_notification_channel',
                'icon'                  => 'edit.gif',
                'attributes'            => 'class="contextmenu"'
            ),
            'editheader' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification']['editheader'],
                'href'                  => 'act=edit',
                'icon'                  => 'header.gif',
                'attributes'            => 'class="edit-header"'
            ),
            'copy' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification']['copy'],
                'href'                  => 'act=copy',
                'icon'                  => 'copy.gif'
            ),
            'delete' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification']['delete'],
                'href'                  => 'act=delete',
                'icon'                  => 'delete.gif',
                'attributes'            => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification']['show'],
                'href'                  => 'act=show',
                'icon'                  => 'show.gif'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                       => '{name_legend},name',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                       =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp' => array
        (
            'sql'                       =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'name' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification']['name'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'eval'                      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
            'sql'                       => "varchar(255) NOT NULL default ''",
        ),
    )
);


class tl_iso_notification extends Backend
{

    /**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Check permissions to edit table tl_page
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// @todo: add permission checks here
    }
}
