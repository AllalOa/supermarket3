use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagazinierController extends Controller
{
    // ... existing methods ...

    /**
     * Get pending order notifications for the magazinier
     */
    public function getPendingOrderNotifications()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'details' => "Commande de " . $order->total_items . " articles, total: " . number_format($order->total_amount, 2) . " DA",
                    'created_at' => $order->created_at
                ];
            });

        return response()->json([
            'pendingOrders' => $pendingOrders,
            'pendingCount' => Order::where('status', 'pending')->count()
        ]);
    }

    // ... existing methods ...
} 