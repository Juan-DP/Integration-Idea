<?php

final class APIConnector extends Connector
{
    public function __construct(String $connectionString = null, String $key = null)
    {
        parent::__construct($connectionString, $key);
    }

    /**
     * Function that returns a categories resource
     *
     * @return Resource
     **/
    public function categories(): Resource
    {
        return new Resource(
            connector: $this,
            name: "categories"
        );
    }

    /**
     * Function that returns a products resource
     *
     * @return Resource
     **/
    public function products(): Resource
    {
        return new Resource(
            connector: $this,
            name: "products"
        );
    }

}