<?php
namespace App\Model;

class ListOrdersSpecification
{
    private int $page;
    private int $limit;
    private string $sortColumn;
    private string $sortOrder;
    private string $customerFilter;
    private string $statusFilter;

    public function __construct(array $queryParams)
    {
        $this->page = isset($queryParams['page']) && filter_var($queryParams['page'], FILTER_VALIDATE_INT) ? (int)$queryParams['page'] : 1;
        $this->limit = isset($queryParams['limit']) && filter_var($queryParams['limit'], FILTER_VALIDATE_INT) ? (int)$queryParams['limit'] : 10;
        $this->sortColumn = (isset($queryParams['sort']) && $queryParams['sort'] !== '' && $queryParams['sort'] !== 'null') ? (string)$queryParams['sort'] : 'id';
        $this->sortOrder = (isset($queryParams['order']) && $queryParams['order'] !== '' && $queryParams['order'] !== 'null') ? (string)$queryParams['order'] : 'asc';
        $this->customerFilter = isset($queryParams['customer']) ? (string)$queryParams['customer'] : '';
        $this->statusFilter = isset($queryParams['status']) ? (string)$queryParams['status'] : '';

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
