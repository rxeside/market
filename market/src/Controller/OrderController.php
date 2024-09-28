<?php
namespace App\Controller;

use App\Model\ListOrdersSpecification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'order_index')]
    public function index(): Response
    {
        return $this->render('orders/index.html.twig');
    }

    #[Route('/orders/data', name: 'order_data')]
    public function getData(Request $request): JsonResponse
    {
        $orderFilter = new ListOrdersSpecification($request->query->all());

        $allOrders = $this->getAllOrders();

        $filteredOrders = $this->filterOrders($allOrders, $orderFilter);

        $sortedOrders = $this->sortOrders($filteredOrders, $orderFilter);

        $paginatedOrders = $this->paginateOrders($sortedOrders, $orderFilter->getPage(), $orderFilter->getLimit());

        $totalOrders = count($sortedOrders);

        return new JsonResponse([
            'orders' => $paginatedOrders,
            'total' => $totalOrders,
            'page' => $orderFilter->getPage(),
            'pages' => ceil($totalOrders / $orderFilter->getLimit()),
            'sort' => $orderFilter->getSortColumn(),
            'order' => $orderFilter->getSortOrder(),
        ]);
    }

    private function getAllOrders(): array
    {
        return [
            ['id' => 1, 'customer' => 'John Doe', 'total' => 100.50, 'status' => 'Completed'],
            ['id' => 2, 'customer' => 'Jane Smith', 'total' => 230.00, 'status' => 'Pending'],
            ['id' => 3, 'customer' => 'Michael Brown', 'total' => 320.75, 'status' => 'Shipped'],
            ['id' => 4, 'customer' => 'Alice Johnson', 'total' => 150.25, 'status' => 'Cancelled'],
            ['id' => 5, 'customer' => 'Brenda Crul', 'total' => 150.75, 'status' => 'Completed'],
            ['id' => 6, 'customer' => 'Ulqiorra Shifer', 'total' => 275.50, 'status' => 'Pending'],
            ['id' => 7, 'customer' => 'Ichigo Kurosaki', 'total' => 320.00, 'status' => 'Shipped'],
            ['id' => 8, 'customer' => 'Tomioka Giu', 'total' => 85.25, 'status' => 'Cancelled'],
            ['id' => 9, 'customer' => 'Rengoku Kyodjiro', 'total' => 200.10, 'status' => 'Completed'],
            ['id' => 10, 'customer' => 'Ilya Milyarchuk', 'total' => 400.00, 'status' => 'Pending'],
            ['id' => 11, 'customer' => 'Minato Namikaze', 'total' => 150.00, 'status' => 'Shipped'],
            ['id' => 12, 'customer' => 'Madara Uchiha', 'total' => 300.45, 'status' => 'Cancelled'],
            ['id' => 13, 'customer' => 'Nagato Uzumaki', 'total' => 120.99, 'status' => 'Completed'],
            ['id' => 14, 'customer' => 'Leukhin Denis', 'total' => 60.50, 'status' => 'Pending'],
        ];
    }

    private function filterOrders(array $orders, ListOrdersSpecification $filter): array
    {
        return array_filter($orders, static function ($order) use ($filter) {
            $customerMatch = !$filter->getCustomerFilter() || stripos($order['customer'], $filter->getCustomerFilter()) !== false;
            $statusMatch = !$filter->getStatusFilter() || stripos($order['status'], $filter->getStatusFilter()) !== false;
            return $customerMatch && $statusMatch;
        });
    }

    private function sortOrders(array $orders, ListOrdersSpecification $filter): array
    {
        usort($orders, static function ($a, $b) use ($filter) {
            if ($filter->getSortOrder() === 'asc') {
                return $a[$filter->getSortColumn()] <=> $b[$filter->getSortColumn()];
            }
            return $b[$filter->getSortColumn()] <=> $a[$filter->getSortColumn()];
        });
        return $orders;
    }

    private function paginateOrders(array $orders, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        return array_slice($orders, $offset, $limit);
    }
}