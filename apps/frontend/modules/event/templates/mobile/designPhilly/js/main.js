window.MenuView = Backbone.View.extend({

    template:_.template($('#menu').html()),

    render:function (eventName) {
        $(this.el).html(this.template());
        return this;
    }
});

window.CalenderView = Backbone.View.extend({

    template:_.template($('#calender').html()),

    render:function (eventName) {
        $(this.el).html(this.template());
        return this;
    }
});

window.ListView = Backbone.View.extend({

    template:_.template($('#list').html()),

    initialize: function (params) {
        this.filterParams = params;
    },

    render:function (eventName) {
        var tplate = $(this.template()),
            container = tplate.find("#ef-list"),
            options = $.extend(true, {}, EFembed.user_options);


        if( this.filterParams.hasFilter) {
            options.url = options.url + "&" + this.filterParams.filterString;
        }

        console.log(options.url);

        EFembed.refresh(options, container[0]).done(function(e){
            tplate.find("a.event").on('click', function(e){
                var anchor = $(this),
                    event_type = anchor.data("type"),
                    event_url = "http://" + anchor[0].hostname + anchor[0].pathname,
                    event_id = anchor.data("id");

                window.history.pushState({}, "", document.baseURI + "#detail/" + event_type + "/" + event_id);
                AppRouter.prototype.detail(event_type, event_id);

                e.preventDefault();
            });
        });

        $(this.el).html(tplate);
        return this;
    }
});

window.DetailView = Backbone.View.extend({

    template:_.template($('#detail').html()),

    initialize:function(params){
        this.filterParams = params;
    },

    render:function (eventName) {
        $(this.el).html(this.template());

        var tplate = $(this.template()),
            container = tplate.find(".details_container"),
            event_url = "http://www.eventsfilter.com/api/event/show/" + this.filterParams.id,
            event_type = this.filterParams.type;

        EFembed.loadDetails(container, event_url, EFembed.generateDetails, event_type).done(function() {
            container.data("status", "open");
            container.slideDown();
            
            $('.event_button').button();
            
            //set return and venue links
            /*
            $("a").on("click", function() {
                var previous = $.mobile.activePage.prev('[data-role=page]');
                $.mobile.changePage(previous, {
                    transition: 'pop',
                    reverse:true
                });

                e.preventDefault();
            });
            */
        });

        $(this.el).html(tplate);
        return this;
    }
});

var AppRouter = Backbone.Router.extend({

    routes:{
        "":"menu",
        "calender":"calender",
        "by_date/:date":"datelist",
        "by_type/:type":"typelist",
        "detail/:type/:id":"detail"
    },

    initialize:function () {
        // Handle back button throughout the application
        $('.back').live('click', function(e) {

            console.log("back");

            window.history.back();

            var curFrag = Backbone.history.fragment;
            curFrag = curFrag.substr(0, curFrag.search("/"));
            
            if (curFrag === "by_date" || curFrag === "by_type" || curFrag === "detail") {
                Backbone.history.loadUrl();
            }
            e.preventDefault();

            return false;
        });
        this.firstPage = true;
    },

    menu:function () {
        console.log('#menu');
        this.changePage(new MenuView());
    },

    calender:function () {
        console.log('#calender');
        this.changePage(new CalenderView());
    },

    datelist:function (date) {
        console.log('#datelist');
        var filterObj = {};

        if (date !== "") {
            date = date.replace(".", "/");
            date = date.replace(".", "/");
            filterObj = {hasFilter:true, type:"date", value: date, filterString:"start_date=" + date + "&date_range=1&event_type=3"};    
        } else {
            filterObj = {hasFilter:false};    
        }

        this.changePage(new ListView(filterObj));
    },

    typelist:function (type, filter) {
        console.log('#typelist');
        var getIndexOf = {"featured":"event_type=3&discipline=0&location=0&tag=253", "events":"event_type=30&discipline=0&location=0&tag=235", "lectures_talks": "event_type=30&discipline=0&location=0&tag=235", "workshops":"event_type=33&discipline=0&location=0&tag=235", "open_studios":"event_type=32&discipline=0&location=0&tag=235", "tours":"event_type=24&discipline=0&location=0&tag=235", "exhibitions":"date_range=1&event_type=1&discipline=0&location=0&tag=235", "openings":"event_type=21&discipline=0&location=0&tag=235"};
        var filterObj = {};
        
        if (type !== "") {
            filterObj = {hasFilter:true, type:type,filterString: getIndexOf[type]};
        } else {
            filterObj = {hasFilter:false};    
        }

        this.changePage(new ListView(filterObj));
    },

    detail:function (type, id) {
        console.log('#detail');
        this.changePage(new DetailView({id:id, type:type}));
    },

    changePage:function (page) {
        $(page.el).attr('data-role', 'page');
        page.render();

        $('body').append($(page.el));

        var transition = $.mobile.defaultPageTransition;
        // We don't want to slide the first page
        if (this.firstPage) {
            transition = 'none';
            this.firstPage = false;
        }
        $.mobile.changePage($(page.el), {changeHash:false, transition: transition});
    }

});


//init
var container = $("#query_item"),
    url = container.data("url");

var options = container.EFembed({url:url});
EFembed.user_options = options;

EFembed.user_options.templateNames.listTemplate["event"] = "m_list_event.tmpl";
EFembed.user_options.templateNames.eventDetailTemplate = "m_details_event.tmpl";

app = new AppRouter();
Backbone.history.start();
