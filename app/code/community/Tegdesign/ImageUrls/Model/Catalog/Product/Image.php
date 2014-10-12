<?php
/**
 * Image.php
 * Overwrites frontend display of images allowing Image Urls for product images
 * allows for both local images and image with urls to live in harmony (fallback)
 *
 * @category    Tegdesign
 * @package     ImageUrls
 * @author      Tegan Snyder <tsnyder@tegdesgin.com>
 */

class Tegdesign_ImageUrls_Model_Catalog_Product_Image extends Mage_Catalog_Model_Product_Image {

    protected $_ogFile;

    public function getUrl() {

        $helper = Mage::helper('tegdesign_imageurls');

        if (!$helper->getModuleEnabled()) {
            return parent::getUrl();
        }

        if (isset($this->_ogFile)) {

            if (Mage::app()->getStore()->isCurrentlySecure()) {

                $url = str_replace('http://', 'https://', $this->_ogFile);

            } else {

                $url = $this->_ogFile;

            }

            return $url;

        } else {

            return parent::getUrl();

        }

    }

    protected function _fileExists($filename) {

        $helper = Mage::helper('tegdesign_imageurls');

        if ($helper->getRelativePathsEnabled()) {
 
            if (strpos($filename, $helper->getImageServerBaseUrl()) !== false) {

                $tmp = array();
                $tmp = explode('http://', $filename);

                $image_url = 'http://' . $tmp[1];

                $this->_ogFile = $image_url;

                return true;

            }
            
        } else {

            if (file_exists($filename)) {

                return true;

            } else {

                return Mage::helper('core/file_storage_database')->saveFileToFilesystem($filename);
            }

        }

    }

}
