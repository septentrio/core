<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2012 Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://www.isotopeecommerce.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

namespace Isotope\Model;


/**
 * Abstract class of notification channels
 *
 * @author  Andreas Schempp <andreas.schempp@terminal42.ch>
 */
abstract class NotificationChannel extends TypeAgent
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_iso_notification_channel';

    /**
     * Interface to validate shipping method
     * @var string
     */
    protected static $strInterface = '\Isotope\Interfaces\IsotopeNotificationChannel';

    /**
     * List of types (classes) for this model
     * @var array
     */
    protected static $arrModelTypes = array();

}
