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
 * Table tl_iso_notification_channel
 */
$GLOBALS['TL_DCA']['tl_iso_notification_channel'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'                 => 'Table',
        'enableVersioning'              => true,
        'ptable'                        => 'tl_iso_notification',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index',
            )
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                      => 4,
            'fields'                    => array('name'),
            'flag'                      => 1,
            'panelLayout'               => 'filter;search,limit',
            'headerFields'              => array('name'),
            'disableGrouping'           => true,
            'child_record_callback'     => array('tl_iso_notification_channel', 'listRows')
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
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['edit'],
                'href'                  => 'act=edit',
                'icon'                  => 'edit.gif',
                'attributes'            => 'class="contextmenu"'
            ),
            'copy' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['copy'],
                'href'                  => 'act=copy',
                'icon'                  => 'copy.gif'
            ),
            'delete' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['delete'],
                'href'                  => 'act=delete',
                'icon'                  => 'delete.gif',
                'attributes'            => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'toggle' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['toggle'],
                'icon'                  => 'visible.gif',
                'attributes'            => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'       => array('tl_iso_notification_channel', 'toggleIcon')
            ),
            'show' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['show'],
                'href'                  => 'act=show',
                'icon'                  => 'show.gif'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                       => '{name_legend},name,type;{publish_legend},published',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                       =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid' => array
        (
            'foreignKey'                => 'tl_iso_notification.name',
            'sql'                       =>  "int(10) unsigned NOT NULL default '0'",
            'relation'                  => array('type'=>'belongsTo', 'load'=>'lazy'),
        ),
        'tstamp' => array
        (
            'sql'                       =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'name' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['name'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'eval'                      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                       => "varchar(255) NOT NULL default ''",
        ),
        'type' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['type'],
            'exclude'                   => true,
            'inputType'                 => 'select',
            'options'                   => \Isotope\Model\NotificationChannel::getModelTypeOptions(),
            'eval'                      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                       => "varchar(255) NOT NULL default ''",
        ),
        'published' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['published'],
            'exclude'                   => true,
            'inputType'                 => 'checkbox',
            'eval'                      => array('doNotCopy'=>true),
            'sql'                       => "char(1) NOT NULL default ''",
        ),
        'start' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['start'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'eval'                      => array('rgxp'=>'date', 'datepicker'=>(!method_exists($this, 'getDatePickerString') ? true : $this->getDatePickerString()), 'tl_class'=>'w50 wizard'),
            'sql'                       => "varchar(10) NOT NULL default ''",
        ),
        'stop' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_iso_notification_channel']['stop'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'eval'                      => array('rgxp'=>'date', 'datepicker'=>(!method_exists($this, 'getDatePickerString') ? true : $this->getDatePickerString()), 'tl_class'=>'w50 wizard'),
            'sql'                       => "varchar(10) NOT NULL default ''",
        ),
    )
);


class tl_iso_notification_channel extends Backend
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

    /**
     * Add an image to each record
     * @param array
     * @param string
     * @return string
     */
    public function listRows($row)
    {
        return $row['name'];
    }

    /**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_iso_notification_channel::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_iso_notification_channel::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish article ID "'.$intId.'"', 'tl_iso_notification_channel toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_iso_notification_channel', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_iso_notification_channel']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_iso_notification_channel']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_iso_notification_channel SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_iso_notification_channel', $intId);
	}
}
