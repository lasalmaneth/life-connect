document.addEventListener("DOMContentLoaded", () => {
    // Initialize Quota Chart
    const ctx = document.getElementById('quotaChart');
    if (ctx) {
        const intake = parseInt(ctx.dataset.intake) || 0;
        const remaining = parseInt(ctx.dataset.remaining) || 0;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Current Intake', 'Remaining Quota'],
                datasets: [{
                    data: [intake, remaining],
                    backgroundColor: [
                        '#0ea5e9', // var(--accent)
                        '#f1f5f9'  // var(--g100)
                    ],
                    borderWidth: 0,
                    hoverOffset: 2
                }]
            },
            options: {
                cutout: '80%',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' bodies';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
