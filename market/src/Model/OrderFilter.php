<?php
namespace App\Model;

class OrderFilter
{
    private int $page;
    private int $limit;
    private string $sortColumn;
    private string $sortOrder;
    private string $customerFilter;
    private string $statusFilter;

    public function __construct(array $queryParams)
    {
        $this->page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $this->limit = isset($queryParams['limit']) ? (int)$queryParams['limit'] : 10;
        $this->sortColumn = $queryParams['sort'] ?? 'id';
        $this->sortOrder = $queryParams['order'] ?? 'asc';
        $this->customerFilter = $queryParams['customer'] ?? '';
        $this->statusFilter = $queryParams['status'] ?? '';

        $this->validateSortOrder();
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSortColumn(): string
    {
        return $this->sortColumn;
    }

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function getCustomerFilter(): string
    {
        return $this->customerFilter;
    }

    public function getStatusFilter(): string
    {
        return $this->statusFilter;
    }

    private function validateSortOrder(): void
    {
        if (!in_array($this->sortOrder, ['asc', 'desc'], true)) {
            $this->sortOrder = 'asc';
        }
    }
}
