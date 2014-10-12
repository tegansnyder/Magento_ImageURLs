Magento_ImageURLs
=================

> NOTE: Please do not use this yet. It is still in development. I haven't finished it yet. The code is likely to be refactored and tweaked. I haven't writing tests for any of it yet.

This extension allows you to load images on your Magento store via an image URL.

#### How it works:
* This extension piggy backs off the _fileExists function and tells Magento to try and grab an image from a URL if the file doesn’t exist.
* Hooks into the getImage function for “image”, “small_image”, “thumbnail” and appends a resize parameter to the url if your CDN or Multimedia server supports dynmaic resizing.
* Simplifies the admin view of product images grouping images together if they share the same URL with resize paramater, reducing clutter.

#### More Features:
* Allows native Magento file uploads and Image Urls to coexist peacefully.
* Error reporting