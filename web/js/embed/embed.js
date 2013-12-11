(function($, window, document, undefined) {

window.EFHelpers = {
  //helper functions
  loadCSS:function(url) {
    var head = document.getElementsByTagName('head')[0],
    link = document.createElement('link');
    link.type = 'text/css';
    link.rel = 'stylesheet';
    link.href = url;
    head.appendChild(link);
    return link;
  },

  constructURL:function(options, allProps) {
    var url = "http://eventsfilter.com",
      validProps = options.allProps[options.filterProps.data_type];

    url += ((options.localhost)?".localhost":"") + "/";

    if (options.format === "html") {
      url += options.filterProps.data_type + "/permalink";
    } else {
      url += "api/" + options.filterProps.data_type + "/list";
    }

    var props = $.restrictKeys(options.filterProps, validProps);

    url += "?";

    $.each(props, function (val, index) {
      url += ((url.charAt(url.length-1) !== "k")?"&":"") + val + "=" + encodeURIComponent(options.filterProps[val]);
    });
    
    return(url);
  },

  deconstructURL:function(options, allProps) {
    var url = options.url,
      elements = url.split("?"),
      urlElements = elements[0].split(":");
      urlElements = $.grep(urlElements[urlElements.length-1].split("/"), function(value, index){return(value != "");});
      format = "json";
      filterProps = ((elements.length > 1)?elements[1].split("&"):{}),
      rootElms = urlElements[0].split("."),
      localhost = rootElms[rootElms.length-1] === "localhost",
      data_type = urlElements[(format==="json")?2:1],
      props = {},
      styleProps = {},
      systemProps = {};

      if (filterProps !== {}) {
      // if we have additional properties passed in
        $.each(filterProps, function(index, val){ 
          //go through each
          $.extend(props, val.keyPair());
          //and change the key=value strings into object proporties
        });

      
        filterProps = $.restrictKeys(props, allProps[data_type]);
        styleProps = $.restrictKeys(props, allProps['style']);
        systemProps = $.restrictKeys(props, allProps['system']);
        //and find all valid prop types
      }

      //Special cases//
      filterProps.data_type = data_type;


      var items = ["showDetails", "images", "24hrFormat", "stripeListing"];

      $.map(items, function(obj, idx) {
        if (styleProps[obj] !== undefined)
          styleProps[obj] = $.stringToBool(styleProps[obj]);
      });

      if (styleProps['hide'] !== undefined) {
        styleProps['hide'] = styleProps['hide'].split(",");

        styleProps['hide'] = $.map(styleProps['hide'], function(val, index) {
          return(val.trim());
        });
      }

      systemProps.format = format;

      systemProps.localhost = localhost;

      //add local host to systemProps

      var items = ["showBranding", "run_now", "mobile"]
      $.map(items, function(obj, idx) {
        if (systemProps[obj] !== undefined)
          systemProps[obj] = $.stringToBool(systemProps[obj]);
      });

      //templates
      items = ["listTemplate", "eventDetailTemplate", "venueDetailTemplate", "profileDetailTemplate"];

      $.map(items, function(obj, idx){
        if (systemProps[obj] !== undefined) {
          var filename = systemProps[obj],
            templateName = filename.substring(filename.lastIndexOf("/") + 1, filename.lastIndexOf("."));
            if (obj === "listTemplate") {
              options.templateNames[obj][data_type] = templateName;
            } else {
              options.templateNames[obj] = templateName;
            }
        }
      });
      
      $.extend(options, systemProps);
      //system props are the root props of options

      $.extend(options, {filterProps:filterProps, styleProps:styleProps});
      //and we add system and filter props seperately

    return(options);
  }
}

$.fn.EFembed = function( options ) {
  //extend options if passed in
  var options = $.extend(true, {}, $.fn.EFembed.options, options );

  //get the container element
  var elm = $(this[0]);
  
  if (options.url === undefined)
    options.url = EFHelpers.constructURL(options, options.allProps);
  else
    options = EFHelpers.deconstructURL(options, options.allProps);

  options = $.extend(true, {}, $.fn.EFembed.options, options );

  var url = "http://www.eventsfilter.com" + ((options.localhost)?".localhost":"") + "/css/embed/embed.css";
  //var url = "http://www.eventsfilter.com.localhost/css/embed/embed.css";

  EFHelpers.loadCSS(url);
  
  if (options.stripeListing) {
    $(".ef .event_entry:nth-child(2n+1)").css("background-color", rgb(245, 245, 245));
    $(".ef .event_entry:nth-child(2n)").css("background-color", "white");

    $(".ef .profile_entry:nth-child(2n+1)").css("background-color", rgb(245, 245, 245));
    $(".ef .profile_entry:nth-child(2n)").css("background-color", "white");
  }

  if (options.css !== undefined && options.css !== "") {
    EFHelpers.loadCSS(options.css);
  }

  return (options);
};

$.fn.EFembed.options = {
  format:"json", //can be json or formatedjson
  localhost:false,
  showBranding:true,
  run_now:true,
  mobile:false,

  styleProps:{
    images:"yes",
    backgroundColor:"#eeeeee",
    stripeListing: false,
    "24hrFormat":false
  },

  filterProps:{
    data_type: "event"
  },

  templateNames:{
    listTemplate:{"event":"d_list_event.tmpl", "profile": "d_list_profile.tmpl"},
    eventDetailTemplate: "d_details_event.tmpl",
    venueDetailTemplate: "d_details_venue.tmpl",
    profileDetailTemplate: "d_details_profile.tmpl",
  },

  allProps: 
    {"event":["event_type", "venue", "category", "discipline", "location", "start_date", "date_range", "tag"],
     "profile":["discipline", "category", "tag"],
     "style":["images", "showDetails", "hide", "backgroundColor", "24hrFormat", "stripeListing"],
     "system":["css", "format", "showBranding", "run_now", "mobile", "listTemplate", "eventDetailTemplate", "venueDetailTemplate", "profileDetailTemplate"]
    }
};

}(jQuery, window, document));
