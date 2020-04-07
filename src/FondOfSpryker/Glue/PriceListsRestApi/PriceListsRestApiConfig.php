<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class PriceListsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRICE_LISTS = 'price-lists';
    public const CONTROLLER_PRICE_LISTS = 'price-lists-resource';
    public const ACTION_PRICE_LISTS_GET = 'get';

    public const RESPONSE_CODE_EXTERNAL_REFERENCE_MISSING = '800';
    public const RESPONSE_DETAILS_EXTERNAL_REFERENCE_MISSING = 'External reference is missing.';

    public const RESPONSE_CODE_PRICE_LIST_NOT_FOUND = '801';
    public const RESPONSE_DETAILS_PRICE_LIST_NOT_FOUND = 'Price list not found.';

    public const RESPONSE_CODE_NO_PERMISSION = '802';
    public const RESPONSE_DETAILS_NO_PERMISSION = 'No permission to read price list.';
}
