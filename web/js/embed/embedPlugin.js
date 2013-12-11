//embed
window.EFembed = {
  //creation methods
  generateEventList:function(container, data, type) {
    var root = window.EFembed,
      options = root.options,
      mobile = options.mobile;

    var data = $.map(data, function(obj, index) {
      return({date:index, entry:obj});
    });

    data = {"dateGroup":data};
    
    if (mobile) {
      var template = Handlebars.templates["m_list_event.tmpl"];      
      container.append(template(data));
      
      $("#ef-list").listview("refresh");
    } else {
      var template = Handlebars.templates[options.templateNames.listTemplate["event"]];
      container.append(template(data));
    }
  },

  generateProfileList:function(container, data, type) {
    var root = window.EFembed,
      options = root.options,
      mobile = options.mobile;

    var data = $.map(data, function(obj, index) {
      return({entry:obj});
    });

    data = {"profileGroup":data};
    
    var template = Handlebars.templates[options.templateNames.listTemplate["profile"]];
    container.append(template(data));
  },

  generateDetails:function(container, data, type) {
    var root = window.EFembed;
    if (root.options === undefined)
        root.options = root.user_options;
      
    var options = root.options,
      mobile = options.mobile,
      template, content;
      
    if (mobile) {
      template = {"event":Handlebars.templates["m_details_event.tmpl"],"profile":Handlebars.templates["m_details_profile.tmpl"], "venue":Handlebars.templates["m_details_venue.tmpl"]};
      content = template[type](data);
    } else {
      template = Handlebars.templates[options.templateNames[type + "DetailTemplate"]];
      content = template(data);
    }
    
    container.html(content);
  },

  //Events
  toggleDetails:function(container, url, type) {
    var root = window.EFembed,
      options = root.options;

    if (container.data("status") === "empty") {
      container.empty();

      EFembed.loadDetails(container, url, window.EFembed.generateDetails, type).done(function() {       
        container.data("status", "open");
        container.slideDown();
      });

    } else if (container.data("status") === "closed") {
      container.data("status", "open");
      container.slideDown();
    } else if (!options.styleProps.showDetails) {
      container.data("status", "closed");
      container.slideUp();
    }
  },

  onDetailsClick:function(e, ui) {
    var root = window.EFembed,
      options = root.options,
      anchor = $(this),
      new_type = anchor.data("type"),
      url = "http://" + anchor[0].hostname + anchor[0].pathname,
      divType = (options.filterProps.data_type === "event")?".event_entry":".profile_entry",
      listItem = anchor.closest('.event_entry').closest(divType),
      container = listItem.find('.details_container').first();

    if (container.data("type") !== new_type) {
      container.data("type", new_type);

      if (container.data("status") === "open") {
        container.data("status", "empty");
        container.slideUp(1, root.toggleDetails(container, url, new_type));
      } else {
        container.data("status", "empty");
        root.toggleDetails(container, url, new_type);
      }
    } else {
      root.toggleDetails(container, url, new_type);
    }

    if (e !== undefined)
      e.preventDefault();

    return false;
  },

  //load methods
  loadListView:function(type) {
    var deferred = $.Deferred();

    var listGenFunc = {"event": this.generateEventList, "profile": this.generateProfileList};
    var listEventFunc = {"event": this.eventListEvents, "profile": this.profileListEvents};
    var root = window.EFembed,
      options = root.options,
      self = this;

    this.loadJSONP(this.$elem, this.options.url, listGenFunc[type], type).done(function(data){
      var options = self.options,
        root = window.EFembed;


      //add eventFilter Branding
      if (options.showBranding)
        self.$elem.append("<div class='footer'><div class='bug'>Powered By<a href='http://www.eventsfilter.com'>EventsFilter</a></div></div>");
      
      if (!options.mobile) {
        self.resizeItems(self.$elem);

        $(window).resize(function(){ self.resizeItems(self.$elem); });
        
        //turns off clickthru for these links
        var dataTypePreparation = {"event":root.eventPagePreparation, "profile":root.profilePagePreparation, "venue":root.venuePagePreparation};
      }

      listEventFunc[type](self.$elem);

      deferred.resolve();
    });

    return(deferred);
  },

  resizeItems:function($elem){
    var elm_width = $elem.css("width"),
      min_width = 200,
      col2 = $elem.find(".col2"),
      col3 = $elem.find(".col3"),
      col4 = $elem.find(".col4");

    elm_width = Number(elm_width.substr(0,elm_width.length-2));

    elm_width = (elm_width > min_width)?elm_width:min_width;
    elm_width = elm_width - 200;
    
    col2.css("width", String(elm_width/2) + "px");
    col3.css("width", String(elm_width/4) + "px");
    col4.css("width", String(elm_width/4) + "px");
  },

  loadDetails:function(detailsContainer, url, callback, type) {
    var deferred = $.Deferred();

    EFembed.loadJSONP(detailsContainer, url, callback, type).done(function(data){
      deferred.resolve();
    });

    return(deferred);
  },

  loadJSONP:function(container, url, callback, type) {
    var deferred = $.Deferred();

    $.ajax(url, {
      dataType: 'jsonp'
    }).success(function(data) {
      callback(container, data, type);
      deferred.resolve();
    });

    return (deferred);
  },

  //page specific functions
  eventListEvents:function(container){
    var options = window.EFembed.options,
      root = window.EFembed;

    if (options.mobile) {

      //container.on('click', 'a.event', root.onDetailsMobileClick);

    } else {
      if (options.styleProps.showDetails === true) {
        $.each(container.find('a.detail.event'), function(index, value) {
          root.onDetailsClick.apply(this);
        });
      }
      
      container.on('click', 'a.event', root.onDetailsClick);
      container.on('click', 'a.profile', root.onDetailsClick);
      container.on('click', 'a.venue', root.onDetailsClick);
    }

  },


  profileListEvents:function(container){
    var options = window.EFembed.options,
      root = window.EFembed;

    if (options.styleProps.showDetails === true) {
      $.each(container.find('a.detail.profile'), function(index, value) {
        root.onDetailsClick.apply(this) ;
      });

      container.on('click', 'a', function(e){e.preventDefault();});
    }
    
    container.on('click', 'a.profile', root.onDetailsClick);
    container.on('click', 'a.events', root.onDetailsClick);
  },

  //Constructor
  load:function(options, elem) {
    var deferred = $.Deferred();
    
    var embed = Object.create( EFembed );
    $.data(elem, 'EFembed', embed);

    embed.init( options , elem).done(function(){
      deferred.resolve();
    });

    return(deferred);
  },

  refresh:function(options, elem) {
    var deferred = $.Deferred();

    this.load(options, elem).done(function() {
      deferred.resolve();
    });

    return(deferred);

  },

  init:function(options, elem) {
    var deferred = $.Deferred();
    var self = this;

    this.elem = elem;
    this.$elem = $(this.elem);

    this.$elem.html("");
    
    //load animation code
    if (options.load_div !== undefined)
      this.$load_div = this.$elem.find(options.load_div);

    window.EFembed.options = options;

    this.loadListView(options.filterProps.data_type).done(function() {
      deferred.resolve();
    });

    return(deferred);
  }
}