export let LiveChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    title: {
        display: true,
        text: "Generated energy (W)"
    },
    scales: {
        xAxes: [
            {
                type: "time",
                time: {
                    round: true,
                    parser: "HH:mm",
                    unit: "minute",
                    unitStepSize: 60,
                    displayFormats: {
                        minute: "HH:mm",
                        hour: "HH:mm"
                    }
                }
            }
        ],
        yAxes: [
            {
                ticks: {
                    beginAtZero: true,
                    max: 3500
                }
            }
        ]
    },
    legend: {
        display: true
    },
}
