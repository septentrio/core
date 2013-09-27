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
     * @used-by onsubmit_callback
     *
     * @param   \DataContainer
     */
    public function inheritSettingsOnSubmit(\DataContainer $dc)
    {
        $this->_inheritSettings($dc->id);
    }

    /**
     * Hand down root page ID and reader page setting to all children
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @used-by onsubmit_callback
     *
     * @param   \DataContainer
     */
    public function handDownSettingsOnSubmit(\DataContainer $dc)
    {
        // Do not take $dc->activeRecord because values might have changed by other callbacks (inheritSettings)
        $objCurrent = \Database::getInstance()->prepare('SELECT id,iso_rootPage,iso_readerJumpTo FROM tl_page WHERE id=?')->execute($dc->id);

        $this->_handDownSettings($objCurrent->id, $objCurrent->iso_rootPage, $objCurrent->iso_readerJumpTo);
    }

    /**
     * Inherit root page ID and reader page setting from parent page
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @used-by oncopy_callback
     *
     * @param   \DataContainer
     */
    public function inheritSettingsOnCopy($intNew, \DataContainer $dc)
    {
        $this->_inheritSettings($intNew);
    }

    /**
     * Hand down root page ID and reader page setting to all children
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @used-by oncopy_callback
     *
     * @param   \DataContainer
     */
    public function handDownSettingsOnCopy($intNew, \DataContainer $dc)
    {
        // Do not take $dc->activeRecord because values might have changed by other callbacks (inheritSettings)
        $objCurrent = \Database::getInstance()->prepare('SELECT id,iso_rootPage,iso_readerJumpTo FROM tl_page WHERE id=?')->execute($intNew);

        $this->_handDownSettings($objCurrent->id, $objCurrent->iso_rootPage, $objCurrent->iso_readerJumpTo);
    }

    /**
     * Inherit root page ID and reader page setting from parent page
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @used-by oncut_callback
     *
     * @param   \DataContainer
     */
    public function inheritSettingsOnCut(\DataContainer $dc)
    {
        $this->_inheritSettings($dc->id);
    }

    /**
     * Hand down root page ID and reader page setting to all children
     * This slows down editing in the back end but brings massive performance
     * improvements in the front end
     * @used-by oncut_callback
     *
     * @param   \DataContainer
     */
    public function handDownSettingsOnCut(\DataContainer $dc)
    {
        // Do not take $dc->activeRecord because values might have changed by other callbacks (inheritSettings)
        $objCurrent = \Database::getInstance()->prepare('SELECT id,iso_rootPage,iso_readerJumpTo FROM tl_page WHERE id=?')->execute($dc->id);

        $this->_handDownSettings($objCurrent->id, $objCurrent->iso_rootPage, $objCurrent->iso_readerJumpTo);
    }

    /**
     * Internal method to inherit root page ID and reader page setting from parent page
     * @internal
     *
     * @param   integer The page id
     */
    private function _inheritSettings($intId)
    {
        $objCurrent = \Database::getInstance()->prepare('SELECT id,pid,type,iso_setReaderJumpTo,iso_readerJumpTo FROM tl_page WHERE id=?')->execute($intId);
        $intRootPage = 0;
        $intReaderId = 0;

        // We don't need to inherit any settings if we are a root page
        if ($objCurrent->type != 'root') {
            // Otherwise we walk over all parents and inherit the reader page
            // settings and the root page id
            $pid = $objCurrent->pid;
            $objParentPage = \PageModel::findParentsById($pid);
            $blnFoundReader = false;

            if ($objParentPage !== null) {
                while ($objParentPage->next() && $pid > 0) {
                    $pid = $objParentPage->pid;
                    $type = $objParentPage->type;

                    if ($type == 'root') {
                        $intRootPage = $objParentPage->id;
                    }

                    // If we don't have a reader jump to page set ourselves
                    if ($objCurrent->iso_setReaderJumpTo) {
                        $intReaderId = $objCurrent->iso_readerJumpTo;
                    } else {
                        // If we found a reader, we stop
                        if ($objParentPage->iso_setReaderJumpTo && !$blnFoundReader) {
                            $intReaderId = $objParentPage->iso_readerJumpTo;
                            $blnFoundReader = true;
                        }
                    }

                }
            }
        } else {
            $intRootPage = $objCurrent->id;
            $intReaderId = $objCurrent->iso_readerJumpTo;
        }

        $arrSet = array
        (
            'iso_rootPage'      => $intRootPage,
            'iso_readerJumpTo'  => $intReaderId
        );

        \Database::getInstance()->prepare("UPDATE tl_page %s WHERE id=?")->set($arrSet)->execute($intId);
    }

    /**
     * Internal recursive method for handing down the settings
     * @internal
     *
     * @param   int Current page id
     * @param   int Root page id
     * @param   int Reader page id
     * @see     tl_page::handDownSettings()
     */
    private function _handDownSettings($intPid, $intRootId, $intReaderId)
    {
        $objChild = \Database::getInstance()->prepare('SELECT id,iso_setReaderJumpTo,iso_readerJumpTo FROM tl_page WHERE pid=?')->execute($intPid);

        while ($objChild->next()) {

            // Do not override subpages but update the root id
            if ($objChild->iso_setReaderJumpTo) {
                $intReaderId = $objChild->iso_readerJumpTo;
            }

            $this->_handDownSettings($objChild->id, $intRootId, $intReaderId);
        }

        $arrSet = array
        (
            'iso_rootPage'      => $intRootId,
            'iso_readerJumpTo'  => $intReaderId
        );

        \Database::getInstance()->prepare('UPDATE tl_page %s WHERE id=?')->set($arrSet)->execute($objChild->id);
    }
}
