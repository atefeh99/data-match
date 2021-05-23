<?php


namespace App\Helpers;


class OdataQueryParser
{
    const COUNT_KEY = "count";
    const FILTER_KEY = "filter";
    const FORMAT_KEY = "format";
    const ORDER_BY_KEY = "orderby";
    const SELECT_KEY = "select";
    const SKIP_KEY = "skip";
    const TOP_KEY = "top";
    const SEARCH_KEY = "search";
    const EXPAND_KEY = "expand";

    /**
     * @var string
     */
    private static $url = "";

    /**
     * @var string
     */
    private static $queryString = "";

    /**
     * @var array
     */
    private static $queryStrings = [];

    /**
     * @var bool
     */
    private static $withDollar = false;

    /**
     * @var string
     */
    private static $selectKey = "";

    /**
     * @var string
     */
    private static $countKey = "";

    /**
     * @var string
     */
    private static $filterKey = "";

    /**
     * @var string
     */
    private static $formatKey = "";

    /**
     * @var string
     */
    private static $orderByKey = "";

    /**
     * @var string
     */
    private static $skipKey = "";

    /**
     * @var string
     */
    private static $topKey = "";
    /**
     * @var bool
     */
    private static bool $failed = false;
    /**
     * @var array
     */
    private static array $errors = [];
    /**
     * @var string
     */
    private static $searchKey = "";
    /**
     * @var string
     */
    private static $expandKey = "";

    public static function parse(string $url, bool $withDollar = true): array
    {
        $output = [];

        static::$url = $url;
        static::$withDollar = $withDollar;

        if (static::urlInvalid()) {
            return false;
        }

        static::setQueryStrings();

        static::setQueryParameterKeys();

        if (static::selectQueryParameterIsValid()) {
            $output["select"] = static::getSelectColumns();
        }
        if (static::searchQueryParameterIsValid()) {
            $output["search"] = static::getSearchValue();
        }
        if (static::expandQueryParameterIsValid()) {
            $output["expand"] = static::getExpandValue();
        }
        if (static::countQueryParameterIsValid()) {
            $output["count"] = true;
        }

        if (static::topQueryParameterIsValid()) {
            $top = static::getTopValue();

            if (!\is_numeric($top)) {
                static::$failed = true;
                static::$errors[] = ['top' => 'top should be an integer'];
            }

            $top = $top;

            if ($top < 0) {
                static::$failed = true;
                static::$errors[] = ['top' => 'top should be greater or equal to zero'];
            }

            $output["top"] = (int)$top;
        }

        if (static::skipQueryParameterIsValid()) {
            $skip = static::getSkipValue();

            if (!\is_numeric($skip)) {
                static::$failed = true;
                static::$errors[] = ['skip' => 'skip should be an integer'];
            }

            $skip = $skip;

            if ($skip < 0) {
                static::$failed = true;
                static::$errors[] = ['skip' => 'skip should be greater or equal to zero'];
            }

            $output["skip"] = (int)$skip;
        }

        if (static::orderByQueryParameterIsValid()) {
            $items = static::getOrderByColumnsAndDirections();

            $orderBy = \array_map(function ($item) {
                $explodedItem = \explode(" ", $item);

                $explodedItem = array_values(array_filter($explodedItem, function ($item) {
                    return $item !== "";
                }));

                $property = $explodedItem[0];
                $direction = isset($explodedItem[1]) ? $explodedItem[1] : "asc";

                if ($direction !== "asc" && $direction !== "desc") {
                    static::$failed = true;
                    static::$errors[] = ['order' => 'direction should be either asc or desc'];
                }

                return [
                    "property" => $property,
                    "direction" => $direction
                ];
            }, $items);

            $output["orderBy"] = $orderBy;
        }

        if (static::filterQueryParameterIsValid()) {
            $ands = static::getFilterValue();

            $output["filter"] = $ands;
        }

        return $output;
    }

    private static function urlInvalid(): bool
    {
        return \filter_var(static::$url, FILTER_VALIDATE_URL) === false;
    }

    private static function setQueryStrings(): void
    {
        static::$queryString = static::getQueryString();
        static::$queryStrings = static::getQueryStrings();
    }

    private static function getQueryString(): string
    {
        $queryString = \parse_url(static::$url, PHP_URL_QUERY);

        return $queryString === null ? "" : $queryString;
    }

    private static function getQueryStrings(): array
    {
        $result = [];

        if (!empty(static::$queryString)) {
            \parse_str(static::$queryString, $result);
        }

        return $result;
    }

    private static function hasKey(string $key): bool
    {
        return isset(static::$queryStrings[$key]);
    }


    private static function selectQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$selectKey) && !empty(static::$queryStrings[static::$selectKey]);
    }

    private static function searchQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$searchKey) && !empty(static::$queryStrings[static::$searchKey]);
    }

    private static function expandQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$expandKey) && !empty(static::$queryStrings[static::$expandKey]);
    }

    private static function countQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$countKey) && (bool)trim(static::$queryStrings[static::$countKey]) === true;
    }

    private static function topQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$topKey);
    }

    private static function skipQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$skipKey);
    }

    private static function orderByQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$orderByKey) && !empty(static::$queryStrings[static::$orderByKey]);
    }

    private static function filterQueryParameterIsValid(): bool
    {
        return static::hasKey(static::$filterKey) && !empty(static::$queryStrings[static::$filterKey]);
    }

    private static function getSelectColumns(): array
    {
        return array_map(function ($column) {
            return trim($column);
        }, explode(",", static::$queryStrings[static::$selectKey]));
    }

    private static function getTopValue(): string
    {
        return trim(static::$queryStrings[static::$topKey]);
    }

    private static function getSearchValue(): string
    {
        return trim(static::$queryStrings[static::$searchKey]);
    }

    private static function getExpandValue(): array
    {
        return array_map(function ($and) {
            $items = [];

            preg_match("/(\w+)\((\w+\|\w+)\)/", $and, $items);

            if (count($items) > 0) {
                $keys = explode('|', $items[2]);
                return [
                    "table_name" => $items[1],
                    "foreign_key" => $keys[0],
                    "local_key" => $keys[1]
                ];
            }

        }, explode(",", static::$queryStrings[static::$expandKey]));
    }

    private static function getSkipValue(): string
    {
        return trim(static::$queryStrings[static::$skipKey]);
    }

    private static function getOrderByColumnsAndDirections(): array
    {
        return explode(",", static::$queryStrings[static::$orderByKey]);
    }

    private static function getFilterValue(): array
    {
        return array_map(function ($and) {
            $items = [];

            preg_match("/(contains|\w+)\s?\(?(eq|ne|gt|ge|lt|le|in)?\s?\(?([a-zA-Z0-9 \"\.,'پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأءًٌٍَُِّ]*)\)?/", $and, $items);
            if (count($items) > 0) {
                if ($items[1] == 'contains') {
                    $item = explode(',', $items[3]);
                    $items[1] = str_replace('/', '.', $item[0]);
                    $items[2] = 'contains';
                    $items[3] = str_replace(['"', "'"], '', $item[1]);
                }
                $left = $items[1];
                $operator = static::getFilterOperatorName($items[2]);
                $right = static::getFilterRightValue($operator, $items[3]);
                return [
                    "left" => $left,
                    "operator" => $operator,
                    "right" => $right
                ];
            }

        }, explode("and", static::$queryStrings[static::$filterKey]));
    }

    private static function setQueryParameterKeys(): void
    {
        static::$selectKey = static::getSelectKey();
        static::$countKey = static::getCountKey();
        static::$filterKey = static::getFilterKey();
        static::$formatKey = static::getFormatKey();
        static::$orderByKey = static::getOrderByKey();
        static::$skipKey = static::getSkipKey();
        static::$topKey = static::getTopKey();
        static::$searchKey = static::getSearchKey();
        static::$expandKey = static::getExpandKey();
    }

    private static function getSelectKey(): string
    {
        return static::$withDollar ? '$' . static::SELECT_KEY : static::SELECT_KEY;
    }

    private static function getCountKey(): string
    {
        return static::$withDollar ? '$' . static::COUNT_KEY : static::COUNT_KEY;
    }

    private static function getFilterKey(): string
    {
        return static::$withDollar ? '$' . static::FILTER_KEY : static::FILTER_KEY;
    }

    private static function getFormatKey(): string
    {
        return static::$withDollar ? '$' . static::FORMAT_KEY : static::FORMAT_KEY;
    }

    private static function getOrderByKey(): string
    {
        return static::$withDollar ? '$' . static::ORDER_BY_KEY : static::ORDER_BY_KEY;
    }

    private static function getSkipKey(): string
    {
        return static::$withDollar ? '$' . static::SKIP_KEY : static::SKIP_KEY;
    }

    private static function getTopKey(): string
    {
        return static::$withDollar ? '$' . static::TOP_KEY : static::TOP_KEY;
    }

    private static function getSearchKey(): string
    {
        return static::$withDollar ? '$' . static::SEARCH_KEY : static::SEARCH_KEY;
    }

    private static function getExpandKey(): string
    {
        return static::$withDollar ? '$' . static::EXPAND_KEY : static::EXPAND_KEY;
    }

    private static function getFilterOperatorName(string $operator): string
    {
        switch ($operator) {
            case $operator === "eq":
                return "=";

            case $operator === "ne":
                return "<>";

            case $operator === "gt":
                return ">";

            case $operator === "ge":
                return ">=";

            case $operator === "lt":
                return "<";

            case $operator === "le":
                return "<=";

            case $operator === "in":
                return "in";
            case $operator === "contains":
                return "contains";
            default:
                return "unknown";
        }
    }

    private static function getFilterRightValue(string $operator, string $value)
    {
        if ($operator !== "in") {
            if (is_numeric($value)) {
                if ((int)$value != $value) {
                    return (float)$value;
                } else {
                    return (int)$value;
                }
            } else {
                return str_replace("'", "", trim($value));
            }
        } else {
            $value = preg_replace("/^\s*\(|\)\s*$/", "", $value);
            $values = explode(",", $value);

            return array_map(function ($value) {
                return static::getFilterRightValue("equal", $value);
            }, $values);
        }
    }

    /**
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    /**
     * @return bool
     */
    public static function isFailed(): bool
    {
        return self::$failed;
    }

}
