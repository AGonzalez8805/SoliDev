export class MyCharts {
    constructor() {
        this.init();
    }

    init() {
        const distributionCanvas = document.getElementById('distributionChart');
        const userCanvas = document.getElementById('userChart');

        if (distributionCanvas) {
            new Chart(distributionCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Blog', 'Projets', 'Snippets'],
                    datasets: [{
                        data: [30, 50, 10],
                        backgroundColor: ['#d95d30', '#f48703', '#0f1726'],
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 14 } }
                        },
                        title: {
                            display: true,
                            text: 'Répartition des contenus',
                            color: '#0f1726',
                            font: { family: 'Poppins, sans-serif', size: 24, weight: '700' },
                            padding: { bottom: 20 }
                        }
                    }
                }
            });
        }

        if (userCanvas) {
            new Chart(userCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Nouvelles inscriptions',
                        data: [12, 13, 13, 7, 17, 16],
                        backgroundColor: '#f48703',
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top', labels: { font: { size: 14 } } },
                        title: {
                            display: true,
                            text: 'Nouvelles inscriptions par mois',
                            color: '#0f1726',
                            font: { family: 'Poppins, sans-serif', size: 24, weight: '700' },
                            padding: { bottom: 20 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 5, color: '#0f1726', font: { size: 13 } },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            ticks: { color: '#0f1726', font: { size: 13 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    }
}
