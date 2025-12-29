<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    public int $totalOrdersMonth = 0;
    public float $estimatedRevenueMonth = 0;
    public string $topProductName = '-';
    
    // Chart Data
    public array $lineChartLabels = [];
    public array $lineChartData = [];
    
    public array $doughnutLabels = [];
    public array $doughnutData = [];

    public function mount()
    {
        // 1. KPIs
        $startOfMonth = now()->startOfMonth();
        $ordersThisMonth = Order::where('created_at', '>=', $startOfMonth)->get();
        
        $this->totalOrdersMonth = $ordersThisMonth->count();
        $this->estimatedRevenueMonth = $ordersThisMonth->sum('total') / 100;

        // Top Product Logic (Parsing JSON items)
        $allItemNames = $ordersThisMonth->flatMap(fn($order) => collect($order->items)->pluck('name'));
        if ($allItemNames->isNotEmpty()) {
            $this->topProductName = $allItemNames->countBy()->sortDesc()->keys()->first();
        }

        // 2. Line Chart: Orders last 30 days
        $startDate = now()->subDays(29)->startOfDay();
        $ordersDaily = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime(now()->endOfDay())
        );

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $this->lineChartLabels[] = $date->format('d/m');
            $record = $ordersDaily->firstWhere('date', $formattedDate);
            $this->lineChartData[] = $record ? $record->count : 0;
        }

        // 3. Doughnut Chart: Revenue by Category (Last 30 days)
        // Since items are JSON, we need to map names back to products to find categories.
        // Optimization: Fetch all products with categories first.
        $productsMap = Product::with('category')->get()->keyBy('name');
        
        $categoryRevenue = [];
        $recentOrders = Order::where('created_at', '>=', $startDate)->get();

        foreach ($recentOrders as $order) {
            foreach ($order->items as $item) {
                $pName = $item['name'];
                $subtotal = $item['subtotal'] ?? 0; // Assuming subtotal is in cents
                
                if (isset($productsMap[$pName]) && $productsMap[$pName]->category) {
                    $catName = $productsMap[$pName]->category->name;
                    if (!isset($categoryRevenue[$catName])) $categoryRevenue[$catName] = 0;
                    $categoryRevenue[$catName] += $subtotal;
                } else {
                    if (!isset($categoryRevenue['Otros'])) $categoryRevenue['Otros'] = 0;
                    $categoryRevenue['Otros'] += $subtotal;
                }
            }
        }
        
        $this->doughnutLabels = array_keys($categoryRevenue);
        // Convert to float currency
        $this->doughnutData = array_map(fn($val) => round($val / 100, 2), array_values($categoryRevenue));
    }
}; ?>

<div class="space-y-8">
    {{-- HEADER --}}
    <x-mary-header title="Dashboard" subtitle="Resumen ejecutivo del último mes" separator />
    
    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-mary-stat 
            title="Pedidos (Mes)" 
            value="{{ $totalOrdersMonth }}" 
            icon="o-shopping-bag" 
            class="shadow-sm"
        />
        <x-mary-stat 
            title="Ingresos Est." 
            value="${{ number_format($estimatedRevenueMonth, 2) }}" 
            icon="o-currency-dollar" 
            class="shadow-sm"
            description="Basado en pedidos generados"
        />
        <x-mary-stat 
            title="Top Producto" 
            value="{{ Str::limit($topProductName, 15) }}" 
            icon="o-trophy" 
            class="shadow-sm"
            tooltip="{{ $topProductName }}"
        />
    </div>

    {{-- CHARTS SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- LINE CHART --}}
        <x-mary-card title="Evolución de Pedidos" subtitle="Últimos 30 días" class="shadow-sm">
            <div 
                x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: {
                                labels: @js($lineChartLabels),
                                datasets: [{
                                    label: 'Pedidos',
                                    data: @js($lineChartData),
                                    borderColor: '#d4af37', // Gold
                                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                                }
                            }
                        });
                    }
                }"
                class="h-64"
            >
                <canvas x-ref="canvas"></canvas>
            </div>
        </x-mary-card>

        {{-- DOUGHNUT CHART --}}
        <x-mary-card title="Ventas por Categoría" subtitle="Distribución estimada" class="shadow-sm">
             <div 
                x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js($doughnutLabels),
                                datasets: [{
                                    data: @js($doughnutData),
                                    backgroundColor: [
                                        '#1f2937', // Dark
                                        '#d4af37', // Gold
                                        '#9ca3af', // Gray
                                        '#f3f4f6', // Light
                                        '#4b5563'  // Slate
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'right' }
                                }
                            }
                        });
                    }
                }"
                class="h-64"
            >
                <canvas x-ref="canvas"></canvas>
            </div>
        </x-mary-card>
    </div>
</div>
