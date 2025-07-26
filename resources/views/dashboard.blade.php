@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>
    
    <!-- Stats Cards Row -->
    <div class="stats-row">
        <!-- Total Orders Card -->
        <div class="stat-card" id="ordersCard">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-info">
                        <h6 class="card-label">Total Order</h6>
                        <small class="card-period">this month</small>
                    </div>
                    <div class="card-icon orders-icon">
                        <img src="{{ asset('images/total_orders.png') }}" alt="Orders" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                    </div>
                </div>
                <div class="card-value">{{ $totalOrdersToday }}</div>
                <div class="card-change {{ $orderPercentageChange >= 0 ? 'positive' : 'negative' }}">
                    {{ number_format(abs($orderPercentageChange), 1) }}% 
                    {{ $orderPercentageChange >= 0 ? 'Up' : 'Down' }} from yesterday
                </div>
            </div>
        </div>
        
        <!-- Total Income Card -->
        <div class="stat-card" id="incomeCard">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-info">
                        <h6 class="card-label">Total Income</h6>
                        <small class="card-period">this month</small>
                    </div>
                    <div class="card-icon income-icon">
                        <img src="{{ asset('images/total_income.png') }}" alt="Income" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                    </div>
                </div>
                <div class="card-value">Rp. {{ number_format($totalIncomeToday, 0, ',', '.') }}</div>
                <div class="card-change {{ $incomePercentageChange >= 0 ? 'positive' : 'negative' }}">
                    {{ number_format(abs($incomePercentageChange), 1) }}% 
                    {{ $incomePercentageChange >= 0 ? 'Up' : 'Down' }} from yesterday
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart Card -->
    <div class="chart-container">
        <div class="chart-card">
            <div class="chart-header">
                <h5 id="chartTitle">Total Order</h5>
                <small id="chartValue" class="chart-current-value">{{ $totalOrdersToday }}</small>
            </div>
            
            <!-- Chart Container -->
            <div class="chart-wrapper">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ordersChart').getContext('2d');
    
    // Data from backend
    const monthlyData = @json($monthlyData);
    const labels = monthlyData.map(item => item.month);
    const ordersData = monthlyData.map(item => item.orders);
    const incomeData = monthlyData.map(item => item.income);
    
    // Chart configuration
    let currentChartType = 'orders';
    let chart;
    
    function createChart(type) {
        if (chart) {
            chart.destroy();
        }
        
        const data = type === 'orders' ? ordersData : incomeData;
        const borderColor = type === 'orders' ? '#FFD60A' : '#4ADE80';
        const backgroundColor = type === 'orders' ? 'rgba(255, 214, 10, 0.1)' : 'rgba(74, 222, 128, 0.1)';
        const pointBackgroundColor = type === 'orders' ? '#FFD60A' : '#4ADE80';
        
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: type === 'orders' ? 'Orders' : 'Income',
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: backgroundColor,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: pointBackgroundColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#2E4766',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#FFD60A',
                        borderWidth: 2,
                        cornerRadius: 12,
                        displayColors: false,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                if (type === 'orders') {
                                    return context.parsed.y + ' orders';
                                } else {
                                    return 'Rp. ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#8892A6',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(136, 146, 166, 0.1)',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#8892A6',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            beginAtZero: true,
                            callback: function(value) {
                                if (type === 'orders') {
                                    return value;
                                } else {
                                    return 'Rp. ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: pointBackgroundColor
                    }
                }
            }
        });
    }
    
    // Initialize with orders chart
    createChart('orders');
    
    // Add click event listeners to cards
    document.getElementById('ordersCard').addEventListener('click', function() {
        if (currentChartType !== 'orders') {
            currentChartType = 'orders';
            createChart('orders');
            document.getElementById('chartTitle').textContent = 'Total Order';
            document.getElementById('chartValue').textContent = '{{ $totalOrdersToday }}';
            
            // Update card styles
            document.getElementById('ordersCard').classList.add('active');
            document.getElementById('incomeCard').classList.remove('active');
        }
    });
    
    document.getElementById('incomeCard').addEventListener('click', function() {
        if (currentChartType !== 'income') {
            currentChartType = 'income';
            createChart('income');
            document.getElementById('chartTitle').textContent = 'Total Income';
            document.getElementById('chartValue').textContent = 'Rp. {{ number_format($totalIncomeToday, 0, ",", ".") }}';
            
            // Update card styles
            document.getElementById('incomeCard').classList.add('active');
            document.getElementById('ordersCard').classList.remove('active');
        }
    });
    
    // Set initial active state
    document.getElementById('ordersCard').classList.add('active');
});
</script>

<style>
.dashboard-container {
    max-width: 1100px;
    margin-top: -25px;
    margin-left: 22rem;
    margin-right: auto;
    margin-bottom: 25px;    
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    border-radius: 20px;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2E4766;
    text-align: center;
    margin-bottom: 2.5rem;
    text-shadow: 0 2px 4px rgba(46, 71, 102, 0.1);
}

.stats-row {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    justify-content: center;
}

.stat-card {
    background: #fff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(46, 71, 102, 0.08);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 3px solid transparent;
    flex: 1;
    max-width: 500px;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2E4766 0%, #4A90E2 100%);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(46, 71, 102, 0.15);
}

.stat-card.active {
    border-color: #2E4766;
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(46, 71, 102, 0.12);
}

.stat-card.active::before {
    transform: scaleX(1);
}

.card-content {
    position: relative;
    z-index: 2;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.card-info {
    flex: 1;
}

.card-label {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2E4766;
    margin: 0 0 0.25rem 0;
    letter-spacing: 0.02em;
}

.card-period {
    font-size: 0.8rem;
    color: #8892A6;
    font-weight: 500;
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.orders-icon {
    background: linear-gradient(135deg, #FFD60A 0%, #FFA500 100%);
}

.income-icon {
    background: linear-gradient(135deg, #4ADE80 0%, #22C55E 100%);
}

.card-value {
    font-size: 2.5rem;
    font-weight: 900;
    color: #2E4766;
    margin-bottom: 0.75rem;
    line-height: 1.1;
}

.card-change {
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.card-change.positive {
    color: #22C55E;
}

.card-change.negative {
    color: #EF4444;
}

.chart-container {
    margin-top: 2rem;
}

.chart-card {
    background: #fff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(46, 71, 102, 0.08);
    border: 1px solid rgba(46, 71, 102, 0.05);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

#chartTitle {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2E4766;
    margin: 0;
}

.chart-current-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #8892A6;
    background: #f8fafc;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.chart-wrapper {
    height: 300px;
    position: relative;
    background: linear-gradient(180deg, rgba(46, 71, 102, 0.02) 0%, transparent 100%);
    border-radius: 12px;
    padding: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-title {
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-card {
        max-width: none;
        padding: 1.5rem;
    }
    
    .card-value {
        font-size: 2rem;
    }
    
    .chart-card {
        padding: 1.5rem;
    }
    
    .chart-wrapper {
        height: 250px;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 0.75rem;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
    }
    
    .stat-card {
        padding: 1.25rem;
    }
    
    .card-value {
        font-size: 1.75rem;
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .chart-card {
        padding: 1rem;
    }
    
    .chart-wrapper {
        height: 200px;
        padding: 0.5rem;
    }
}

/* Loading Animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.stat-card:hover .card-icon {
    animation: pulse 2s infinite;
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.3s ease;
}
</style>
@endsection