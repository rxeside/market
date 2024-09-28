<?php
namespace App\Controller;

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
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);
        $sortColumn = $request->query->get('sort', 'id');
        $sortOrder = $request->query->get('order', 'asc');
        $customerFilter = $request->query->get('customer', '');
        $statusFilter = $request->query->get('status', '');

        $allOrders = [
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

        if ($customerFilter) {
            $allOrders = array_filter($allOrders, function($order) use ($customerFilter) {
                return stripos($order['customer'], $customerFilter) !== false;
            });
        }

        if ($statusFilter) {
            $allOrders = array_filter($allOrders, function($order) use ($statusFilter) {
                return stripos($order['status'], $statusFilter) !== false;
            });
        }

        usort($allOrders, function($a, $b) use ($sortColumn, $sortOrder) {
            if ($sortOrder === 'asc') {
                return $a[$sortColumn] <=> $b[$sortColumn];
            } else {
                return $b[$sortColumn] <=> $a[$sortColumn];
            }
        });

        $totalOrders = count($allOrders);

        $offset = ($page - 1) * $limit;
        $orders = array_slice($allOrders, $offset, $limit);

        return new JsonResponse([
            'orders' => $orders,
            'total' => $totalOrders,
            'page' => $page,
            'pages' => ceil($totalOrders / $limit),
            'sort' => $sortColumn,
            'order' => $sortOrder,
        ]);
    }
}
