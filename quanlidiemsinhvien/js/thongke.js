document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('myPieChart')) {
        const ctx = document.getElementById('myPieChart').getContext('2d');
        
        const dataPoints = window.chartDataPoints || [0, 0, 0, 0];

        const myPieChart = new Chart(ctx, {
            type: 'pie', 
            data: {
                labels: ['Giỏi (>= 8.0)', 'Khá (6.5 - 7.9)', 'Trung bình (5.0 - 6.4)', 'Yếu (< 5.0)'],
                datasets: [{
                    data: dataPoints,
                    backgroundColor: [
                        '#27ae60', // Giỏi - Xanh lá
                        '#2980b9', // Khá - Xanh dương
                        '#f1c40f', // TB - Vàng
                        '#e74c3c'  // Yếu - Đỏ
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                // Tính phần trăm
                                let percentage = total > 0 ? Math.round((value / total) * 100) + '%' : '0%';
                                return label + ': ' + value + ' môn (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    }
});