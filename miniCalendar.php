<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link rel="stylesheet" href="./resources/css/javascript-calendar.css" type="text/css">
    <style>
        html,* { font-family: 'Arial'; }
        body { background: #fafafa; }
        .container {max-width: 960px; }
    </style>
</head>
<body>


    <div class="container">
       
<div class="icalendar">
    <div class="icalendar__month">
        <div class="icalendar__prev" onclick="moveDate('prev')">
            <span>&#10094</span>
        </div>
        <div class="icalendar__current-date">
            <h2 id="icalendarMonth"></h2>
            <div>
                <div id="icalendarDateStr"></div>
            </div>

        </div>
        <div class="icalendar__next" onclick="moveDate('next')">
            <span>&#10095</span>
        </div>
    </div>
    <div class="icalendar__week-days">
        <div>Dom</div>
        <div>Lun</div>
        <div>Mar</div>
        <div>Mie</div>
        <div>Jue</div>
        <div>Vie</div>
        <div>Sab</div>
    </div>
    <div class="icalendar__days"></div>

    <script src="./resources/js/javascript-calendar.js" type="text/javascript"></script>
</div>
<div style="background-color: red; width:15px; height:15px; float: left;"></div>&nbsp;&nbsp; Día inhabil<br>
</div>

