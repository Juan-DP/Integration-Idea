<?php

class Resource
{
    /** @param array $customQueryParameters*/
    private $customQueryParameters;

    /** @param array $includes*/
    private $includes;

    /** @param array $filters*/
    private $filters;

    public function __construct(private Connector $connector, private $name)
    {
    }

    /**
     * Include related entities in the response.
     *
     * @param array|string $includes
     * @return $this
     */
    public function include($includes)
    {
        if (is_array($includes)) {
            $includes = implode(',', $includes);
        }

        $this->includes = $includes;

        return $this;
    }

    /**
     * Add filters to the request.
     *
     * @param array $filters
     * @return $this
     */
    public function filter(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    public function addCustomQueryParameters(array $customQueryParameters)
    {
        $this->customQueryParameters = $customQueryParameters;
        return $this;
    }

    public function list(): string
    {
        try {
            $response = $this->connector->send(
                method: "GET",
                uri: $this->setupQueryString($this->name)
            );

        } catch (Throwable $th) {
            throw new Exception($th->getMessage());
        }

        return $response;
    }

    /**
     * Function that sets up filters, includes and custom query params
     *
     * @param String $resource API resource to query
     * @return String
     **/
    public function setupQueryString($resource): String
    {
        $queryString = '';

        if (!empty($this->includes)) {
            $queryString .= "include={$this->includes}";
        }

        if (!empty($this->filters)) {
            $filterString = '';
            foreach ($this->filters as $field => $value) {
                if (!empty($filterString)) {
                    $filterString .= '&';
                }
                $filterString .= "filter[{$field}]={$value}";
            }

            if (!empty($queryString)) {
                $queryString .= '&';
            }
            $queryString .= $filterString;
        }

        // Include the custom query parameters in the query string
        if (!empty($this->customQueryParameters)) {
            $customQueryString = http_build_query($this->customQueryParameters);
            if (!empty($queryString)) {
                $queryString .= '&';
            }
            $queryString .= $customQueryString;
        }
        // Append the query string to the URI
        $uri = $resource;
        if (!empty($queryString)) {
            $uri .= "?{$queryString}";
        }

        return $uri;
    }
}
