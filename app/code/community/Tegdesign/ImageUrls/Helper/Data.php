<?php
/**
 * Data.php
 * Generic helper functions
 *
 * @category    Tegdesign
 * @package     ImageUrls
 * @author      Tegan Snyder <tsnyder@tegdesign.com>
 *
 */
class Tegdesign_ImageUrls_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function getModuleEnabled()
	{
		return Mage::getStoreConfigFlag('imageurls/images/enabled');
	}

	public function getRelativePathsEnabled()
	{
		return Mage::getStoreConfigFlag('imageurls/images/relative_paths_enabled');
	}

	public function getDynamicResizingEnabled()
	{
		return Mage::getStoreConfigFlag('imageurls/images/use_dynamic_resizing');
	}

	public function getResizeParam()
	{
		return Mage::getStoreConfig('imageurls/images/resize_param');
	}

	public function getImageServerBaseUrl()
	{
		return Mage::getStoreConfig('imageurls/images/base_url');
	}

	public function getImageSize()
	{
		return Mage::getStoreConfig('imageurls/images/image_size');
	}

	public function getSmallImageSize()
	{
		return Mage::getStoreConfig('imageurls/images/small_image_size');
	}

	public function getThumbnailSize()
	{
		return Mage::getStoreConfig('imageurls/images/thumbnail_size');
	}

}