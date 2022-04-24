<?php


namespace Sw\Oop;

use GuzzleHttp\Client;

class Scanner
{
    /**
     * @var array URL数组
     */
    protected $urls;
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * Scanner constructor.
     * @param array $urls 要扫描的url数组
     */
    public function __construct(array $urls)
    {
        $this->urls       = $urls;
        $this->httpClient = new Client();
    }

    /**
     * 返回死链
     * @return array
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }
            if ($statusCode > 400) {
                $invalidUrls[] = [
                    'url'    => $url,
                    'status' => $statusCode
                ];
            }
        }
        return $invalidUrls;
    }

    /**
     * 获取指定URL的HTTP状态码
     * @param $url
     * @return int
     */
    protected function getStatusCodeForUrl($url)
    {
        $response = $this->httpClient->head($url);
        return $response->getStatusCode();
    }
}
