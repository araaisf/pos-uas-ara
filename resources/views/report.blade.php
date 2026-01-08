<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KASHARA | Executive Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; color: #2D3748; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        .bg-mesh-gradient {
            background: radial-gradient(at 0% 0%, rgba(255, 165, 185, 0.7) 0px, transparent 50%), 
                        radial-gradient(at 98% 1%, rgba(190, 160, 255, 0.7) 0px, transparent 50%), 
                        linear-gradient(to right, #f0f2f5, #ffffff);
            background-size: 200% 200%;
            animation: gradient-shift 20s ease infinite;
        }
        @keyframes gradient-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        .glass-panel {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .btn-holo {
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            color: white; border: none; transition: all 0.3s;
        }
        .btn-holo:hover { box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); transform: translateY(-2px); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e9d5ff; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d8b4fe; }
    </style>
</head>
<body class="p-8 md:p-12 min-h-screen bg-mesh-gradient selection:bg-purple-200">
    
    <div class="max-w-7xl mx-auto flex justify-between items-end mb-12">
        <div>
             <h1 class="font-serif text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-purple-600 italic drop-shadow-sm">Performance Report</h1>
             <p class="text-purple-800/60 uppercase tracking-[0.3em] mt-3 font-bold text-xs">Executive Sales Overview</p>
        </div>
        <a href="{{ route('pos.index') }}" class="px-8 py-3 rounded-full border border-white/60 bg-white/30 backdrop-blur text-purple-600 hover:bg-white hover:text-pink-600 transition uppercase text-xs font-bold tracking-[0.2em] shadow-sm flex items-center gap-2">
            <span>←</span> Back to POS
        </a>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-panel rounded-[2.5rem] p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] mb-2 font-bold">Lifetime Revenue</p>
                    <h3 class="text-3xl font-serif font-bold text-gray-800">Rp {{ number_format($total_income, 0, ',', '.') }}</h3>
                    <div class="mt-4 text-xs text-green-500 font-bold bg-green-100/50 inline-block px-2 py-1 rounded-lg">
                        + Today: Rp {{ number_format($today_income, 0, ',', '.') }}
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 001-1l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                     </div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] mb-2 font-bold">Items Sold</p>
                    <h3 class="text-4xl font-serif font-bold text-gray-800">{{ $total_items_sold }} <span class="text-lg text-purple-400 font-sans font-normal">pcs</span></h3>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 relative overflow-hidden bg-gradient-to-br from-purple-500 to-pink-500 text-white shadow-lg transform hover:-translate-y-1 transition duration-500">
                    <p class="text-[10px] text-white/80 uppercase tracking-[0.2em] mb-2 font-bold">Best Seller</p>
                    @if($best_seller)
                        <h3 class="text-xl font-serif font-bold leading-tight">{{ $best_seller->product->name }}</h3>
                        <p class="text-xs mt-2 opacity-90">{{ $best_seller->total_qty }} units sold</p>
                    @else
                        <h3 class="text-xl italic opacity-50">No Data</h3>
                    @endif
                    <div class="absolute -bottom-4 -right-4 text-white/20">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </div>

            <div class="glass-panel rounded-[2.5rem] p-8 relative">
                <h4 class="font-serif text-xl text-gray-800 mb-6 italic">Revenue Trend (Last 7 Days)</h4>
                <div class="h-64 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-[3rem] p-8 h-full flex flex-col relative overflow-hidden">
            <h3 class="font-serif text-2xl mb-6 italic text-gray-700 sticky top-0 bg-white/0 backdrop-blur-sm z-10 py-2">Recent Transactions</h3>
            
            <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                @foreach($transactions as $trx)
                <div class="group bg-white/40 hover:bg-white/70 transition p-4 rounded-3xl border border-white/50 flex flex-col gap-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold text-purple-500 bg-purple-100 px-2 py-1 rounded-md">{{ $trx->invoice_code }}</span>
                            <h4 class="font-bold text-gray-800 text-sm mt-2">{{ $trx->customer_name ?? 'Guest' }}</h4>
                        </div>
                        <span class="font-serif text-gray-800 font-bold">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="text-xs text-gray-500 border-t border-gray-200/50 pt-2 mt-1">
                        {{ $trx->created_at->format('d M Y, H:i') }}
                    </div>
                    
                    <a href="{{ route('pos.print', $trx->id) }}" target="_blank" class="text-[10px] text-center w-full mt-2 py-2 rounded-xl border border-purple-200 text-purple-500 hover:bg-purple-500 hover:text-white transition uppercase font-bold tracking-widest">
                        Reprint Receipt
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Gradient Warna untuk Grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(236, 72, 153, 0.5)'); // Pink
        gradient.addColorStop(1, 'rgba(139, 92, 246, 0.0)'); // Purple Transparent

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!}, // Data Tanggal dari Controller
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode($data) !!}, // Data Uang dari Controller
                    borderColor: '#db2777', // Warna Garis Pink Tua
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#db2777',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4 // Garis Melengkung Halus
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#333',
                        bodyColor: '#db2777',
                        borderColor: '#fce7f3',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { family: 'Outfit' }, callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Outfit' } }
                    }
                }
            }
        });
    </script>
</body>
</html>