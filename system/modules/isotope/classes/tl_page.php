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

namespace Isotope;


/**
 * Class tl_page
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_page extends \Backend
{

    /**
     * Limit reader page choices for categories to the current root
     * Everything else does not make sense
     *
     */
    public function limitReaderPageChoice(\DataContainer $dc)
    {
        if (\Input::get('do') == 'page' && \Input::get('table') == 'tl_page' && \Input::get('field') == 'iso_readerJumpTo') {
            if (($objPage = \PageModel::findWithDetails($dc->id)) !== null) {
                $GLOBALS['TL_DCA']['tl_page']['fields']['iso_readerJumpTo']['rootNodes'] = array($objPage->rootId);
            }
        }
    }

    /**
     * Inherit root page ID and reader page setting from parent page
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @param   \DataContainer
     */
    public function inheritSettings(\DataContainer $dc)
    {
        if ($dc->activeRecord->type == 'root') {
            // If root page then we don't have to inherit from any parent
            $dc->activeRecord->iso_rootPage = $dc->activeRecord->id;
        } else {
            // Otherwise we walk over all parents and inherit the reader page
            // settings and the root page id
            $pid = $dc->activeRecord->pid;
            $objParentPage = \PageModel::findParentsById($pid);
            $blnFoundReader = false;

            if ($objParentPage !== null) {
                while ($objParentPage->next() && $pid > 0) {
                    $pid = $objParentPage->pid;
                    $type = $objParentPage->type;

                    if ($type == 'root') {
                        $dc->activeRecord->iso_rootPage = $objParentPage->id;
                    }

                    // If we found a reader, we stop
                    if ($objParentPage->iso_setReaderJumpTo && !$blnFoundReader) {
                        $dc->activeRecord->iso_readerJumpTo = $objParentPage->iso_readerJumpTo;
                        $blnFoundReader = true;
                    }
                }
            }
        }

        $arrSet = array
        (
            'iso_rootPage'      => $dc->activeRecord->iso_rootPage,
            'iso_readerJumpTo'  => $dc->activeRecord->iso_readerJumpTo
        );

        \Database::getInstance()->prepare("UPDATE tl_page %s WHERE id=?")->set($arrSet)->execute($dc->id);
    }
}
