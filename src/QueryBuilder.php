<?php

namespace AlazziAz\OdooXmlrpc;

class QueryBuilder
{
    public array $filters = [];

    public array $fields = [];

    public ?int $limit = null;

    public ?int $offset = null;

    public function __construct(
        protected string $model,
        protected OdooClient $odooClient,
    ) {
    }

    public function where(string $field, string $operator, mixed $value): static
    {
        $this->filters[] = [$field, $operator, $value];

        return $this;
    }

    public function orWhere(string $field, string $operator, mixed $value): static
    {
        $this->addOperator('|');
        $this->filters[] = [$field, $operator, $value];

        return $this;
    }

    private function addOperator(string $operator): void
    {
        //        add operator to first element of filters array
        if (empty($this->filters)) {
            $this->filters[] = $operator;

            return;
        }

        $firstElement = $this->filters[0];
        if (is_array($firstElement)) {
            $this->filters = array_merge([$operator], $this->filters);

            return;
        }
    }

    public function whereIn(string $field, array $values): static
    {
        $this->filters[] = [$field, 'in', $values];

        return $this;
    }

    public function whereNotIn(string $field, array $values): static
    {
        $this->filters[] = [$field, 'not in', $values];

        return $this;
    }

    public function whereNull(string $field): static
    {
        $this->filters[] = [$field, '=', false];

        return $this;
    }

    public function whereNotNull(string $field): static
    {
        $this->filters[] = [$field, '!=', false];

        return $this;
    }

    public function whereBetween(string $field, array $values): static
    {
        $this->filters[] = [$field, '>=', $values[0]];
        $this->filters[] = [$field, '<=', $values[1]];

        return $this;
    }

    public function whereNotBetween(string $field, array $values): static
    {
        $this->filters[] = [$field, '<', $values[0]];
        $this->filters[] = [$field, '>', $values[1]];

        return $this;
    }

    public function select(...$fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function first(): array
    {
        $this->limit(1);

        return $this->get()[0];
    }

    public function limit(int $limit, $offset = 0): static
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    public function get(): array
    {
        return $this->odooClient->get($this->model, $this->filters, $this->fields, $this->limit, $this->offset);
    }

    public function count(): int
    {
        return $this->odooClient->count($this->model, $this->filters);
    }

    public function find(int $id): ?array
    {
        $this->filters[] = ['id', '=', $id];
        $this->limit(1);
        $result = $this->get();

        return $result[0] ?? $result;
    }

    public function create(array $data): int
    {
        return $this->odooClient->create($this->model, $data);
    }

    public function update(array $data): ?int
    {
        return $this->odooClient->update($this->model, $this->ids(), $data);
    }

    public function ids(): ?array
    {
        return $this->odooClient->search($this->model, $this->filters);
    }

    public function delete(): ?int
    {
        return $this->odooClient->delete($this->model, $this->ids());
    }
}
