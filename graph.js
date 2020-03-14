Plotly.d3.csv("https://raw.githubusercontent.com/tanujdey7/Project/master/stock_dfs/ASIANPAINT.NS.csv", function (err, rows) {

    function unpack(rows, key) {
        return rows.map(function (row) { return row[key]; });
    }


    var trace1 = {
        type: "scatter",
        mode: "lines",
        name: 'High',
        x: unpack(rows, 'Date'),
        y: unpack(rows, 'High'),
        line: { color: '#17BECF' }
    }

    var trace2 = {
        type: "scatter",
        mode: "lines",
        name: 'Low',
        x: unpack(rows, 'Date'),
        y: unpack(rows, 'Low'),
        line: { color: '#7F7F7F' }
    }

    var data = [trace1, trace2];

    var layout = {
        title: 'bajaj',
        xaxis: {
            autorange: true,
            range: ['2008-11-22', '2018-11-22'],
            rangeselector: {
                buttons: [
                    {
                        count: 1,
                        label: '1m',
                        step: 'month',
                        stepmode: 'backward'
                    },
                    {
                        count: 6,
                        label: '6m',
                        step: 'month',
                        stepmode: 'backward'
                    },
                    { step: 'all' }
                ]
            },
        },
        yaxis: {
            autorange: true,
            range: [86.8700008333, 138.870004167],
            type: 'linear'
        }
    };

    Plotly.newPlot('myDiv', data, layout, { showSendToCloud: true });
})