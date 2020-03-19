<?php
    $id = $_GET['id'];
    // echo $id;
?>
<head>
    <!-- Load plotly.js into the DOM -->
    <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
    <script>
        Plotly.d3.csv('<?php echo $id.".csv"?>', function(err, rows){
            function unpack(rows, key) {
            return rows.map(function(row) {
                return row[key];
            });
            }

            var trace = {
            x: unpack(rows, 'Date'),
            close: unpack(rows, 'Close'),
            high: unpack(rows, 'High'),
            low: unpack(rows, 'Low'),
            open: unpack(rows, 'Open'),   

            // cutomise colors
            //   increasing: {line: {color: }},
            increasing: {line: {color: '#17BECF'}}, 
            decreasing: {line: {color: '#7F7F7F'}},
            line: {color: 'rgba(31,119,180,1)'},  
            //   decreasing: {line: {color: 'red'}},

            type: 'candlestick',
            xaxis: 'x',
            yaxis: 'y'
            };

            var data = [trace];

            var layout = {
            dragmode: 'zoom', 
            margin: {
                r: 10, 
                t: 25, 
                b: 40, 
                l: 60
            }, 
            showlegend: false, 
            xaxis: {
                autorange: true,
                title: '<?php echo $id?>',
                rangeselector: {
                    x: 0,
                    y: 1.2,
                    xanchor: 'left',
                    font: {size:12},
                    buttons: [{
                        step: 'month',
                        stepmode: 'backward',
                        count: 1,
                        label: '1 month'
                    }, {
                        step: 'month',
                        stepmode: 'backward',
                        count: 6,
                        label: '6 months'
                    },{
                        step: 'month',
                        stepmode: 'backward',
                        count: 12,
                        label: '1 Year'
                    }]
                }
            },
            yaxis: {
                autorange: true,
            }
            };

            Plotly.newPlot('myDiv', data, layout);
});

    </script>
    <style type="text/css">
        .modebar{
            display: none !important;
        }
        .graph{
            display: grid;
            grid-template-columns: 30%;
        }
		 *{
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        html {
            font-weight: 300;
            font-size: 20px;
            text-rendering: optimizeLegibility;
            font-family: 'Raleway','Arial',sans-serif;
        }
        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            min-height: 8vh;
            background-color:  #1abc9c;
        }

        .logo {
            color: aliceblue;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-size: 20px;
        }
        .nav-links {
            display: flex;
            justify-content: space-around;  
            width: 25%;  
            list-style: none;
        }
        .nav-links a {
            color: aliceblue;
            text-decoration: none;
            letter-spacing: 3px;
            font-weight: bold;
            font-size: 14px;
        }
        .burger {
            display: none;
        }
        .burger div{
            width: 25px;
            height: 3px;
            background-color: rgb(226,226,226);
            margin: 5px;
            transition: all 0.3s ease;
        }
        @media screen and (max-width:1024px) {
            .nav-links {
                width: 40%;  
            }
        }
        @media screen and (max-width:768px) {
            body {
                overflow: hidden;
                cursor: pointer;
            }
            .nav-links {
                position: absolute;
                right: 0px;
                height: 92vh;
                top: 8vh;
                background-color:  #1abc9c;
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 50%;
                transform: translateX(100%);
                transition: transform 0.5s ease-in;
            }
            .nav-links li {
                opacity: 0;
            }
            .burger {
                display: block;
            }
        }
        .nav-active{
            transform: translateX(0%);
        }
        @keyframes navLinkFade {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0px);
            }
        }
        .toggle .line1 {
            transform: rotate(-45deg) translate(-5px,6px);
        }
        .toggle .line2 {
            opacity: 0;
        }
        .toggle .line3 {
            transform: rotate(45deg) translate(-5px,-6px);
        }
        .nav-links li a:hover,
        .nav-links li a:active{
            border-bottom: 2px solid #169e83;
            color: #26138e;
        }
	</style>
</head>

<body>
    <nav>
        <div class="logo">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z"/></svg>
            </i>
        </div>
        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../news.html">News</a></li>
            <li><a href="../logout.php">Log out</a></li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>
    <div class="graph"><style></style>
    <div id='myDiv'><!-- Plotly chart will be drawn inside this DIV --></div>
    </div>
    <!-- <script src="graph.js"></script> -->
</body>