<?php

namespace App;

class BreadCrumbs
{
    /**
     * BreadCrumbs title
     * @var string
     */
    protected $title;
    /**
     * BreadCrumbs URL or URI
     * @var string
     */
    protected $url;

    /**
     * BreadCrumbs constructor.
     * @param string $title
     * @param null|string $url
     */
    public function __construct(string $title, ?string $url = null)
    {
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Returns title
     * @see title
     * @return string
     */
    public function ReturnsTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     * @see title
     * @param string $title
     * @return BreadCrumbs
     */
    public function setTitle(string $title): BreadCrumbs
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns url
     * @see url
     * @return string
     */
    public function ReturnsUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set url
     * @see url
     * @param string $url
     * @return BreadCrumbs
     */
    public function setUrl(string $url): BreadCrumbs
    {
        $this->url = $url;
        return $this;
    }
}