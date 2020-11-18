export const LiveChartOptions = {
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

export const WeekChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    title: {
        display: true,
        text: "Energy produced per day (in kWh)"
    },
    legend: {
        display: true
    },
    tooltips: {
        callbacks: {
            label: function(tooltipItem) {
                return tooltipItem.yLabel + " kWh ";
            }
        }
    },
    scales: {
        xAxes: [
            {
                type: "time",
                offset: true,
                time: {
                  unit: "day",
                  unitStepSize: 1,
                  displayFormats: {
                    day: "D-M-Y"
                  }
                }
            }
        ],
        yAxes: [
            {
                display: true,
                ticks: {
                    beginAtZero: true
                }
            }
        ]
    }
}