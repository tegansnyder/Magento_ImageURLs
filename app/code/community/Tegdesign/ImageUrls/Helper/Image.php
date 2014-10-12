<?php
/**
 * Image.php
 * Rewrites the core image helper for assisting in loading images via URL
 * @category    Tegdesign
 * @package     ImageUrls
 * @author      Tegan Snyder <tsnyder@tegdesign.com>
 */

class Tegdesign_ImageUrls_Helper_Image extends Mage_Catalog_Helper_Image
{

    protected $_imageAttributeName;

    /**
     * Initialize Helper to work with Image
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeName
     * @param mixed $imageFile
     * @return Mage_Catalog_Helper_Image
     */
    public function init(Mage_Catalog_Model_Product $product, $attributeName, $imageFile=null)
    {

        $this->_imageAttributeName = $attributeName;

        $this->_reset();
        $this->_setModel(Mage::getModel('catalog/product_image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->setProduct($product);

        $this->setWatermark(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")
        );
        $this->setWatermarkImageOpacity(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity")
        );
        $this->setWatermarkPosition(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position")
        );
        $this->setWatermarkSize(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size")
        );

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            // add for work original size
            $this->_getModel()->setBaseFile($this->getProduct()->getData($this->_getModel()->getDestinationSubdir()));
        }
        return $this;
    }

    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {

        try {
            $model = $this->_getModel();

            if ($this->getImageFile()) {
                $model->setBaseFile($this->getImageFile());
            } else {
                $model->setBaseFile($this->getProduct()->getData($model->getDestinationSubdir()));
            }

            if ($model->isCached()) {

                // Tegdesign_ImageUrls modification
                return $this->_tryForImageUrlsImage($model->getUrl());

            } else {

                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                if ($this->getWatermark()) {
                    $model->setWatermark($this->getWatermark());
                }

                $url = $model->saveFile()->getUrl();

            }

        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }


        // Tegdesign_ImageUrls modification
        return $this->_tryForImageUrlsImage($url);

    }


    // Tegdesign_ImageUrls modification
    public function _tryForImageUrlsImage($url) {

        $helper = Mage::helper('tegdesign_imageurls');

        if ($helper->getModuleEnabled()) {

            if ($this->_scheduleResize) {

                // capture dynamic resizes
                // example: 
                // Mage::helper('catalog/image')->init($product, 'small_image')->resize(99, 108);

                if ($helper->getDynamicResizingEnabled()) {

                    $resize_param = $helper->getResizeParam();

                    $url = $url . '&' . $resize_param . '=' . $this->_getModel()->getWidth();

                }

            } else {

                if ($helper->getDynamicResizingEnabled()) {

                    $resize_param = $helper->getResizeParam();

                    if ($this->_imageAttributeName == 'image') {
                        $url = $url . '&' . $resize_param . $helper->getImageSize();
                    } elseif ($this->_imageAttributeName == 'small_image') {
                        $url = $url . '&' . $resize_param . $helper->getSmallImageSize();
                    } elseif ($this->_imageAttributeName == 'thumbnail') {
                        $url = $url . '&' . $resize_param . $helper->getThumbnailSize();
                    } else {
                        Mage::log('Warning: Image attribute name not found: ' . $this->_imageAttributeName, null, 'tegdesign_imageurls.log');
                    }

                }

            }

        }

        return $url;

    }

}