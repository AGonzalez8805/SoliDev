export class MyCharts {
    constructor() {
        // window.chartData doit contenir :
        // { distribution: { blog, projects, snippets }, monthlyUsers: [12,13,15,...], monthlyLabels: ['Jan',...] }
        this.data = globalThis.chartData ?? null;
        this.init();
    }

    init() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js n’est pas chargé.');
            return;
        }

        const distributionCanvas = document.getElementById('distributionChart');
        const userCanvas = document.getElementById('userChart');

        // Graphique Répartition contenus
        if (distributionCanvas) {
            new Chart(distributionCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Blog', 'Projets', 'Snippets'],
                    datasets: [{
                        data: this.data?.distribution
                            ? [this.data.distribution.blog, this.data.distribution.projects, this.data.distribution.snippets]
                            : [30, 50, 10], // fallback
                        backgroundColor: ['#d95d30', '#f48703', '#0f1726'],
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, font: { size: 14 } } },
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

        // Graphique nouvelles inscriptions
        if (userCanvas) {
            new Chart(userCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: this.data?.monthlyLabels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Nouvelles inscriptions',
                        data: this.data?.monthlyUsers || [12, 13, 13, 7, 17, 16],
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
