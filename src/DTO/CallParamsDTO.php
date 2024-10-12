<?php

namespace AlazziAz\OdooXmlrpc\DTO;

use AlazziAz\OdooXmlrpc\Enums\OperationMethods;

class CallParamsDTO
{
    /**
     * @param  array  $args represents the arguments of the method or  filters
     */
    public function __construct(
        public string $model,
        public OperationMethods $method,
        public array $args = [],
        public ?array $fields = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?string $order = null,
    ) {
    }

    public function toArray(): array
    {
        $params = [
            $this->model,
            $this->method->value,
            $this->args,
        ];

        $params[] = $this->prepareArgs();

        return $params;
    }

    private function prepareArgs(): array
    {
        $args = [];
        if (! empty($this->fields)) {
            $args['fields'] = $this->fields;
        }

        if (! is_null($this->limit)) {
            $args['limit'] = $this->limit;
        }

        if (! is_null($this->offset)) {
            $args['offset'] = $this->offset;
        }
        
        if (! is_null($this->order)) {
            $args['order'] = $this->order;
        }

        return $args;
    }
}
