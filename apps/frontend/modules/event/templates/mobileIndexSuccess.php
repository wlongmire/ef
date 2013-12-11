    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="mobile/designPhilly/css/jquery.mobile-1.0.1.min.css"/>
    <link rel="stylesheet" href="mobile/designPhilly/css/style.css"/>

    <!-- The Templates -->
    <script type="text/template" id="menu">
        <div data-theme="a" class="header" data-role="header">
            <a class="logo_link" data-role="none" href= "http://www.designphiladelphia.org/">
                <img class="logo" src="mobile/designPhilly/img/Design-Philadelphia-Logo.jpg" />
            </a>
        </div>

        <div data-role="content">
            <ul data-role="listview" data-divider-theme="b" data-inset="false">
                
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#calender">
                        Calendar
                    </a>
                </li>

                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/featured">
                        Featured
                    </a>
                </li>
            
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/events">
                        Events
                    </a>
                </li>
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/lectures_talks">
                        Lectures + Talks
                    </a>
                </li>
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/workshops">
                        Workshops
                    </a>
                </li>
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/open_studios">
                        Open Studios
                    </a>
                </li>
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/exhibitions">
                        Exhibitions
                    </a>
                </li>
                <li data-theme="c" data-icon="arrow-r">
                    <a href="#by_type/openings">
                        Openings
                    </a>
                </li>
            </ul>
        </div>
        <div class='footer'><div class='bug'>Powered By<a href='http://www.eventsfilter.com'>EventsFilter</a></div></div>
    </script>

    <script type="text/template" id="calender">

    <div data-theme="a" class="header" data-role="header">
        <a class="logo_link" data-role="none" href= "http://www.designphiladelphia.org/">
            <img class="logo" src="mobile/designPhilly/img/Design-Philadelphia-Logo.jpg" />
        </a>
        <a href="#" class="home_button" data-role="none">Home</a>
        <a href="#" class="back" data-role="none">Back</a>
    </div>
    
    <ul data-role="listview" data-divider-theme="b" data-inset="false">
        <li data-theme="c">
            <a href="#by_date/" data-icon="arrow-r">Full Calendar</a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.9.2013" data-icon="arrow-r">
                Wednesday, October 9
           </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.10.2013"  data-icon="arrow-r">
                Thursday, October 10
           </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.11.2013" data-icon="arrow-r">
                Friday, October 11
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.12.2013" data-icon="arrow-r">
                Saturday, October 12
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.13.2013" data-icon="arrow-r">
                Sunday, October 13
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.14.2013" data-icon="arrow-r">
                Monday, October 14
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.15.2013" data-icon="arrow-r">
                Tuesday, October 15
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.16.2013" data-icon="arrow-r">
                Wednesday, October 16
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.17.2013" data-icon="arrow-r">
                Thursday, October 17
            </a>
        </li>

        <li data-theme="c">
            <a href="#by_date/10.18.2013" data-icon="arrow-r">
                Friday, October 18
            </a>
        </li>
        
    </ul>
    <div class='footer'><div class='bug'>Powered By<a href='http://www.eventsfilter.com'>EventsFilter</a></div></div>
    </script>

    <script type="text/template" id="list">
        <div class="header" data-role="header">
            <a class="logo_link" data-role="none" href= "http://www.designphiladelphia.org/">
                <img class="logo" src="mobile/designPhilly/img/Design-Philadelphia-Logo.jpg" />
            </a>  
            <a href="#" class="home_button" data-role="none">Home</a> 
            <a href="#" class="back" data-role="none">Back</a>
        </div>

        <div class="event_content">
            <ul id="ef-list" data-url="http://www.eventsfilter.com/api/event/list?start_date=10/9/2013&tag=235&date_range=8&mobile=true&run_now=false" class="data_list" data-role="listview" data-divider-theme="b" data-inset="false">

                    
            </ul>
        </div>
    </script>

    <script type="text/template" id="detail">
        <div class="header" data-role="header">
            <a class="logo_link" data-role="none" href= "http://www.designphiladelphia.org/">
                <img class="logo" src="mobile/designPhilly/img/Design-Philadelphia-Logo.jpg" />
            </a>
            <a href="#" class="home_button" data-role="none">Home</a>
            <a href="#" class="back" data-role="none">Back</a>
        </div>

        <div data-role="content">
            <div class="details_container"></div>
        </div>

        <div class='footer'><div class='bug'>Powered By<a href='http://www.eventsfilter.com'>EventsFilter</a></div></div>
    </script>

    <!-- placeholder for querying -->
    <div id="query_item" data-url="http://www.eventsfilter.com/api/event/list?start_date=10/9/2013&tag=235&date_range=10&mobile=true&run_now=false" class="data_list" data-role="listview" data-divider-theme="b" data-inset="false">
    </div>

    <!-- librarys -->
    <script src="mobile/designPhilly/lib/jquery-1.7.1.min.js"></script>
    <script src="mobile/designPhilly/js/jqm-config.js"></script>
    <script src="mobile/designPhilly/lib/jquery.mobile-1.0.1.min.js"></script>
    <script src="mobile/designPhilly/lib/underscore-min.js"></script>
    <script src="mobile/designPhilly/lib/backbone-min.js"></script>
    <script src="http://www.eventsfilter.com/js/plugin/handlebars.runtime.js"></script>

    <!--embed specific files-->
    <script src="http://www.eventsfilter.com/js/embed/utils.js"></script>
    <script src="http://www.eventsfilter.com/js/embed/embedPlugin.js"></script>

    <script src="http://www.eventsfilter.com/js/embed/utils.js"></script>
    <script src="http://www.eventsfilter.com/js/embed/templateUtils.js"></script>
    
    <!--templates-->
    <script src="http://www.eventsfilter.com/templates/mobile/m_details_event.tmpl.js"></script>
    <script src="http://www.eventsfilter.com/templates/mobile/m_list_event.tmpl.js"></script>

    <script src="http://www.eventsfilter.com/js/embed/embed.js"></script>

    <!-- let's go -->
    <script src="mobile/designPhilly/js/main.js"></script>
    
</head>

<body>
</body>

</html>