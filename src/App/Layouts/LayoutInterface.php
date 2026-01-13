<?php

namespace App\Layouts;

use App\BreadCrumbs;

interface LayoutInterface
{
    /**
     * Get response_code
     * @see response_code
     * @return int
     */
    public function getResponseCode();

    /**
     * Set response_code
     * @see response_code
     * @param int $response_code
     * @return $this
     */
    public function setResponseCode($response_code);

    /**
     * Get content_mime_type
     * @see content_mime_type
     * @return string
     */
    public function getContentMimeType();

    /**
     * Set content_mime_type
     * @see content_mime_type
     * @param string $content_mime_type
     * @return $this
     */
    public function setContentMimeType($content_mime_type);

    /**
     * Get location_redirect_uri
     * @see location_redirect_uri
     * @return string
     */
    public function getLocationRedirectUri();

    /**
     * Set location_redirect_uri
     * @see location_redirect_uri
     * @param string $location_redirect_uri
     * @param int $response_code
     * @return $this
     */
    public function setLocationRedirectUri($location_redirect_uri, $response_code = 302);

    /**
     * Get window_title
     * @see window_title
     * @return string
     */
    public function getWindowTitle();

    /**
     * Set window_title
     * @see window_title
     * @param string $window_title
     * @return $this
     */
    public function setWindowTitle($window_title);

    /**
     * Get header_title
     * @see header_title
     * @return string
     */
    public function getHeaderTitle();

    /**
     * Set header_title
     * @see header_title
     * @param string $header_title
     * @return $this
     */
    public function setHeaderTitle($header_title);

    /**
     * Get content
     * @see content
     * @return string
     */
    public function getContent();

    /**
     * Set content
     * @see content
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get bread_crumbs
     * @see bread_crumbs
     * @return BreadCrumbs[]
     */
    public function getBreadCrumbs(): array;

    /**
     * Set bread_crumbs
     * @see bread_crumbs
     * @param BreadCrumbs[] $bread_crumbs
     * @return BaseLayout
     */
    public function setBreadCrumbs(array $bread_crumbs): BaseLayout;

    /**
     * @param BreadCrumbs $bread_crumbs
     * @return BaseLayout
     */
    public function addBreadCrumbs(BreadCrumbs $bread_crumbs): BaseLayout;

    /**
     * @return string
     */
    public function out();
}