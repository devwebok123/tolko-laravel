<?php


namespace App\DataObjects\AdsApi\Announcements;

class Image
{
    /** @var string $url */
    protected $url;
    /** @var string $fileName */
    protected $fileName;

    /**
     * Image constructor.
     * @param string $url
     */
    protected function __construct(string $url)
    {
        $this->url = $url;
        $data = explode('/', $url);
        $this->fileName = $data[count($data) - 1];
    }

    /**
     * @param string $url
     * @return static
     */
    public static function getInstance(string $url): self
    {
        return new self($url);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}
