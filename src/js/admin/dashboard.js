(function ($) {
    'use strict';

    let labelsGlobal = []
    let dataGlobal = []
    actionToCall()

    function getParam(param) {
        const url = new URLSearchParams(window.location.search)
        return url.get(param)
    }

    function actionToCall() {
        const action = getParam("act")
        if (action) {
            callData(action)
        } else {
            console.log("no action param -> return")
            return
        }
    }

    function callData(action) {
        let url = "?mod=requestAdmin&act=" + action
        $.ajax({
            url: url,
            method: "POST",
            success: function (res) {
                const data = JSON.parse(res)
                if (data.status) {
                    // console.log(data)
                    labelsGlobal = generateDateList(data.min_max_date)
                    initDataChart(data.result)
                }
            }
        })
    }

    function generateDateList(minMaxdate) {
        let datesList = []
        const currentDate = new Date()
        const minDate = minMaxdate.min_created_at
        const endDate = new Date(minDate);

        while (currentDate >= endDate) {
            let year = currentDate.getFullYear();
            let month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Ensure two digits for month
            let formattedDate = year + '-' + month;
            datesList.push(formattedDate);
            currentDate.setMonth(currentDate.getMonth() - 1);
        }
        return datesList
    }

    // function makeDataGlobal(dateToCompare, revenue) {
    //     const index = labelsGlobal.indexOf(dateToCompare)
    //     if (index !== -1) {
    //         dataGlobal[index] = revenue;
    //         for (let i = index + 1; i < labelsGlobal.length; i++) {
    //             dataGlobal[i] = 0;
    //         }
    //     } else {
    //         const newIndex = labelsGlobal.length;
    //         for (let i = 0; i < newIndex; i++) {
    //             if (typeof dataGlobal[i] === 'undefined') {
    //                 dataGlobal[i] = 0;
    //             }
    //         }
    //         dataGlobal.push(revenue);
    //     }
    // }

    function makeDataGlobal(dateToCompare, revenue) {
        const index = labelsGlobal.indexOf(dateToCompare);

        if (index !== -1) {
            // If the date is found, update revenue for that index
            dataGlobal[index] = revenue;
            // Fill preceding months with zeros
            for (let i = 0; i < index; i++) {
                if (!(i in dataGlobal)) {
                    dataGlobal[i] = 0;
                }
            }
        } else {
            // If the date is not found, push zeros for all months before
            const newIndex = labelsGlobal.length;
            for (let i = 0; i < 20; i++) {
                if (!(i in dataGlobal)) {
                    dataGlobal[i] = 0;
                }
            }
            // Push the revenue to dataGlobal
            dataGlobal.push(revenue);
        }
    }

    function initDataChart(data) {
        data.forEach((el, index) => {
            const orderDate = el.order_date
            const revenue = el.revenue_order
            makeDataGlobal(orderDate, revenue)
        })
        drawChart()
        // console.log(dataGlobal)
        // console.log(labelsGlobal)
        dataGlobal = []
    }

    function drawChart() {
        if ($("#performaneLine").length) {
            var graphGradient = document.getElementById("performaneLine").getContext('2d');
            var graphGradient2 = document.getElementById("performaneLine").getContext('2d');
            var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 100);
            saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.3)');
            saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.02)');
            var saleGradientBg2 = graphGradient2.createLinearGradient(100, 0, 50, 150);
            saleGradientBg2.addColorStop(0, 'rgba(0, 208, 255, 0.3)');
            saleGradientBg2.addColorStop(1, 'rgba(0, 208, 255, 0.03)');
            var salesTopData = {
                labels: labelsGlobal,
                datasets: [{
                    label: 'Số lượng',
                    data: dataGlobal,
                    backgroundColor: saleGradientBg,
                    borderColor: [
                        '#1F3BB3',
                    ],
                    borderWidth: 1.5,
                    fill: true, // 3: no fill
                    pointBorderWidth: 1,
                    pointRadius: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                    pointHoverRadius: [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
                    pointBackgroundColor: ['#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3)'],
                    pointBorderColor: ['#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff',],
                }]
            };

            var salesTopOptions = {
                locale: 'vi-VN',
                currency: "VND",
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            color: "#F0F0F0",
                            zeroLineColor: '#F0F0F0',
                        },
                        ticks: {
                            fontSize: 15,
                            callback: (value, index, values) => {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND',
                                }).format(value)
                            }
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: false,
                            autoSkip: true,
                            maxTicksLimit: 7,
                            fontSize: 10,
                            color: "#6B778C"

                        }
                    }]
                },
                legend: false,
                legendCallback: function (chart) {
                    var text = [];
                    text.push('<div class="chartjs-legend"><ul>');
                    for (var i = 0; i < chart.data.datasets.length; i++) {
                        // console.log(chart.data.datasets[i]); // see what's inside the obj.
                        text.push('<li>');
                        text.push('<span style="background-color:' + chart.data.datasets[i].borderColor + '">' + '</span>');
                        text.push(chart.data.datasets[i].label);
                        text.push('</li>');
                    }
                    text.push('</ul></div>');
                    return text.join("");
                },

                elements: {
                    line: {
                        tension: 0.4,
                    }
                },
                tooltips: {
                    backgroundColor: 'rgba(31, 59, 179, 1)',
                }
            }

            // const formattedData = dataGlobal.map(revenue => formatRevenue(revenue));

            var salesTop = new Chart(graphGradient, {
                type: 'line',
                data: salesTopData,
                options: salesTopOptions,

            });
            document.getElementById('performance-line-legend').innerHTML = salesTop.generateLegend();
        }
    }

})(jQuery);