<?php 
namespace app\source\http;

/**
 * Represents a URL.
 */
class Url {
    /**
     * @var string $protocol The protocol of the URL.
     */
    protected $protocol;
    /**
     * @var string $host The host of the URL.
     */
    protected $host;
    /**
     * @var string $path The path of the URL.
     */
    protected $path;
    /**
     * @var array $query The query of the URL.
     */
    protected $query;

    /**
     * Constructor for the Url class.
     *
     * @param array $server The server array.
     */
    public function __construct(array $server) {
        $this->protocol = isset($server['HTTPS']) && $server['HTTPS'] === 'on' ? "https" : "http";
        $this->host = $server['HTTP_HOST'];
        $this->path = parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $this->query = parse_url($server['REQUEST_URI'], PHP_URL_QUERY);
    }

    /**
     * Gets the protocol of the URL.
     *
     * @return string The protocol of the URL.
     */
    public function getProtocol(): string {
        return $this->protocol;
    }

    /**
     * Gets the host of the URL.
     *
     * @return string The host of the URL.
     */
    public function getHost(): string {
        return $this->host;
    }

    /**
     * Gets the path of the URL.
     *
     * @return string The path of the URL.
     */
    public function getPath(): string  {
        return $this->path;
    }

    /**
     * Gets the query of the URL.
     *
     * @return string The query of the URL.
     */
    public function getQuery(): array {
        return $this->query;
    }

    /**
     * Gets the URL as a string.
     *
     * @return string The URL as a string.
     */
    public function __toString(): string {
        $url = $this->protocol . '://' . $this->host . $this->path;
        if (!empty($this->query)) {
            $url .= '?' . http_build_query($this->query);
        }
        return $url;
    }
}
