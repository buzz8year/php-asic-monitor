<?php

namespace App\Layouts;

use App\BreadCrumbs;
use App\Views\BaseView;

abstract class BaseLayout extends BaseView implements LayoutInterface
{
    /**
    * Response code
     * @var int
     */
    protected $response_code = 200;
    /**
    * Response MIME type
     * @var string
     */
    protected $content_mime_type = "text/html";
    /**
    * Redirect URI if set
     * @var string
     */
    protected $location_redirect_uri;
    /**
    * Window title
     * @var string
     */
    protected $window_title;
    /**
    * Page header (usually <h1></h1>)
     * @var string
     */
    protected $header_title;
    /**
    * Page content
     * @var string
     */
    protected $content;
    /**
    * Breadcrumbs
     * @var BreadCrumbs[]
     */
    protected $bread_crumbs = array();

    /**
     * Returns response_code
     * @see response_code
     * @return int
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * Sets response_code
     * @see response_code
     * @param int $response_code
     * @return $this
     */
    public function setResponseCode($response_code)
    {
        $this->response_code = $response_code;
        return $this;
    }

    /**
     * Returns content_mime_type
     * @see content_mime_type
     * @return string
     */
    public function getContentMimeType()
    {
        return $this->content_mime_type;
    }

    /**
     * Sets content_mime_type
     * @see content_mime_type
     * @param string $content_mime_type
     * @return $this
     */
    public function setContentMimeType($content_mime_type)
    {
        $this->content_mime_type = $content_mime_type;
        return $this;
    }

    /**
     * Returns location_redirect_uri
     * @see location_redirect_uri
     * @return string
     */
    public function getLocationRedirectUri()
    {
        return $this->location_redirect_uri;
    }

    /**
     * Sets location_redirect_uri
     * @see location_redirect_uri
     * @param string $location_redirect_uri
     * @param int $response_code
     * @return $this
     */
    public function setLocationRedirectUri($location_redirect_uri, $response_code = 302)
    {
        $this->response_code = $response_code;
        $this->location_redirect_uri = $location_redirect_uri;
        return $this;
    }

    /**
     * Returns window_title
     * @see window_title
     * @return string
     */
    public function getWindowTitle()
    {
        return $this->window_title;
    }

    /**
     * Sets window_title
     * @see window_title
     * @param string $window_title
     * @return $this
     */
    public function setWindowTitle($window_title)
    {
        $this->window_title = $window_title;
        return $this;
    }

    /**
     * Returns header_title
     * @see header_title
     * @return string
     */
    public function getHeaderTitle()
    {
        return $this->header_title;
    }

    /**
     * Sets header_title
     * @see header_title
     * @param string $header_title
     * @return $this
     */
    public function setHeaderTitle($header_title)
    {
        $this->header_title = $header_title;
        return $this;
    }

    /**
     * Returns content
     * @see content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets content
     * @see content
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns bread_crumbs
     * @see bread_crumbs
     * @return BreadCrumbs[]
     */
    public function getBreadCrumbs(): array
    {
        return $this->bread_crumbs;
    }

    /**
     * Sets bread_crumbs
     * @see bread_crumbs
     * @param BreadCrumbs[] $bread_crumbs
     * @return BaseLayout
     */
    public function setBreadCrumbs(array $bread_crumbs): BaseLayout
    {
        $this->bread_crumbs = $bread_crumbs;
        return $this;
    }

    /**
    * Adds a breadcrumb
     * @param BreadCrumbs $bread_crumbs
     * @return BaseLayout
     */
    public function addBreadCrumbs(BreadCrumbs $bread_crumbs): BaseLayout
    {
        $this->bread_crumbs[] = $bread_crumbs;
        return $this;
    }

}