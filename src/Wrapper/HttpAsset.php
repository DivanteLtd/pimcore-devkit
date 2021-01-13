<?php
/**
 * @author      Wojciech Peisert <wojtekp@sauron.pl>
 */

namespace PimcoreDevkitBundle\Wrapper;

use GuzzleHttp\ClientInterface;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Folder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class HttpAsset
 * @package PimcoreDevkitBundle\Wrapper
 */
class HttpAsset implements HttpAssetInterface
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var Folder
     */
    protected $targetFolder;

    /**
     * @var string
     */
    protected $defaultFileType;

    /**
     * @var Asset
     */
    protected $asset;

    /**
     * HttpAsset constructor.
     * @param ClientInterface $httpClient
     * @param string $url
     * @param Folder $targetFolder
     * @param string|null $filename
     * @param string $defaultFileType
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct(
        ClientInterface $httpClient,
        string $url,
        Folder $targetFolder,
        string $filename = null,
        string $defaultFileType = 'txt',
        array  $headers = []
    ) {
        $this->response = $httpClient->request('GET', $url, ['headers' => $headers, 'connect_timeout' => 1]);
        $statusCode = $this->response->getStatusCode();

        if ($statusCode != 200) {
            throw new FileNotFoundException("Error $statusCode while downloading url: $url");
        }

        $this->setAssetFilename($filename);
        $this->targetFolder = $targetFolder;
        $this->defaultFileType = $defaultFileType;
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return strval(array_search(
            $this->getContentType(),
            \Pimcore::getContainer()->getParameter('pimcore.mime.extensions')
        ));
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return strval($this->getHeader('content-type')[0]);
    }

    /**
     * @return Asset
     * @throws \Exception
     */
    public function getAsset(): Asset
    {
        if (!$this->asset) {
            $this->saveAsset();
        }

        return $this->asset;
    }

    /**
     * @return self
     * @throws \Exception
     */
    protected function saveAsset(): self
    {
        $asset = Asset::getByPath($this->targetFolder->getFullPath() . "/" . $this->filename);

        if (!$asset) {
            $asset = new Asset();
            $asset->setFilename($this->filename);
            $asset->setParent($this->targetFolder);
            $asset->addMetadata("origin", "text", "url");
            $asset->addMetadata("origin_url", "text", $this->url);
            $asset->setData($this->getBody());
            $asset->save();
        }

        $this->asset = $asset;

        return $this;
    }

    /**
     * @param string $filename
     * @return void
     */
    protected function setAssetFilename(?string $filename)
    {
        if ($filename) {
            $this->filename = $filename;
            return;
        }

        $this->generateAssetFilename();
    }

    /**
     * @return void
     */
    protected function generateAssetFilename(): void
    {
        $fileType = $this->getFileType();

        if (false === $fileType) {
            $fileType = $this->defaultFileType;
        }

        $this->filename = md5((string) $this->getBody()) . "." . $fileType;
    }

    /**
     * @return StreamInterface
     */
    protected function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * @param string $name
     * @return string[]
     */
    protected function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }
}
