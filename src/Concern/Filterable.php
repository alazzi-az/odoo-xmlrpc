<?php

namespace AlazziAz\OdooXmlrpc\Concern;

trait Filterable
{
    private function prepareFilters(array $filters): array
    {
        $preparedFilters = [];
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                if (count($value) === 2 && (! is_int($key) && ! $this->isOperator($key))) {
                    $preparedFilters[] = [$key, ...$value];
                } else {
                    $preparedFilters[] = $value;
                }
            } elseif ($this->isOperator($value)) {
                $preparedFilters[] = $value;
            } else {
                $preparedFilters[] = [$key, '=', $value];
            }
        }

        return $preparedFilters;
    }

    private function isOperator(string $operator): bool
    {
        return in_array($operator,
            ['=', '!=', '<', '<=', '>', '>=', 'like', 'not like', 'ilike', 'not ilike', 'in', 'not in', 'child_of', '|', '&', '!', 'parent_of']);
    }

    private function prepareFields(array $fields): array
    {
        $preparedFields = [];
        foreach ($fields as $field) {
            $preparedFields[] = $field;
        }

        return $preparedFields;
    }
}
