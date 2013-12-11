//template functions
var templateFunctions = {
    'formatDate': function(dateGroup) {

      var dow = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      var dateItem = dateGroup.date.split("-");
      var date = new Date(dateItem[0], Number(dateItem[1]) -1, dateItem[2]);

      var today = new Date();
      var rtn = "";

      if (date.toDateString() === today.toDateString()) {
        rtn = "Today, ";
      } else if (date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear() && date.getDate() === today.getDate() + 1) {
        rtn = "Tomorrow, ";
      } else {
        rtn = dow[date.getDay()] + ", ";
      }

      rtn += months[date.getMonth()] + " " + date.getUTCDate() + ", " + date.getFullYear();
      return(rtn);
  },

  'getDisciplines': function(entry) {
    var dis = entry.Disciplines,
      rtn = "";

    if (!dis)
      return("");

    
    $.map(dis, function(obj, index){
      if (index < 4) {
        rtn += ((index>0)?", ":"") + obj.name;
      }
    });

    return(rtn);
  },

'getCategories': function(entry) {
    var dis = entry.Catagories,
      rtn = "";

    if (!dis)
      return("");

    
    $.map(dis, function(obj, index){
      if (index < 4) {
        rtn += ((index>0)?", ":"") + obj.name;
      }
    });

    return(rtn);
  },

  'ifImageFlag':function(block) {
    if (window.EFembed.options.styleProps.images) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },

  'ifImageExists': function(entry, block) {
    if (entry.Picture !== null) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },

  'ifImageExists': function(entry, block) {
    if (entry.Picture !== null) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },

  'hasEvents': function(count, block) {
    if (count > 0) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },
  
  'getCost': function(entry) {

    var minCost = (entry.min_cost !== null && entry.min_cost !== "")?entry.min_cost:"";
      maxCost = (entry.max_cost !== null  && entry.max_cost !== ""  && entry.min_cost !== entry.max_cost)?entry.max_cost:"",
      rtn = ((minCost !== "")?$.createMoney(minCost):"");

    rtn += ((minCost !== maxCost && minCost !== "" && maxCost !== "")?" - ":"");
    rtn += ((maxCost !== "")?$.createMoney(maxCost):"");
    
    if (rtn === "0.00")
        rtn = "";
    
    return(rtn);
  },

  'getTime': function(entry) {
    var startTime, endTime, do24Format = window.EFembed.options.styleProps['24hrFormat'];

    if (entry.occurences !== undefined) {
      entry = entry.occurences[0];
    }

    if (entry.start_time !== null){
      var timeItem = entry.start_time.split(":");

      startTime = new Date("2013", "1", "13", timeItem[0], timeItem[1], timeItem[2]);
      if (do24Format) {
        startTime = startTime.getHours() + ":" + $.zeroPadding(String(startTime.getMinutes()), 2, false);
      } else {
        var hours = ((startTime.getHours() > 12)?startTime.getHours()-12:startTime.getHours());
        hours = (hours === 0)?12:hours;
        startTime = hours + ":" + $.zeroPadding(String(startTime.getMinutes()), 2, false) + " " + ((startTime.getHours() > 12)?"pm":"am");
      }

    } else {
      startTime = "";
    }

    if (entry.end_time !== null){
      var timeItem = entry.end_time.split(":");
      endTime = new Date("2013", "1", "13", timeItem[0], timeItem[1], timeItem[2]);

      if (do24Format) {
        endTime = endTime.getHours() + ":" + $.zeroPadding(String(endTime.getMinutes()), 2, false);
      } else {
        var hours = ((endTime.getHours() > 12)?endTime.getHours()-12:endTime.getHours());
        hours = (hours === 0)?12:hours;

        endTime = hours + ":" + $.zeroPadding(String(endTime.getMinutes()), 2, false) + " " + ((endTime.getHours() > 12)?"pm":"am");
      }

    } else {
      endTime = "";
    }
    
    var rtn = ((startTime !== "")?startTime:"") + ((startTime !== "" && endTime !== "")?" - ":"")  +  ((endTime !== "")?endTime:"");
  
    return(rtn);
  },
  
  'getDate': function(entry) {
    var startDate = (entry.start_date !== null)?entry.start_date:"",
      endDate = (entry.end_date !== null)?entry.end_date:"",
      rtn = ((startDate !== "")?startDate:"") + ((startDate !== "" && endDate !== "")?" - ":"")  + ((endDate !== "")?endDate:"");

    return(rtn);
  },

  'showOccurences': function(occ) {
    var rtn = "";
    for (var i=0; i<occ.length && i < 4; i++) {
      rtn = rtn + "<li>" + templateFunctions.formatDate({date:occ[i].start_date}) + "</li>";
    }

    return(rtn);
  },

  'getImageUrl': function(entry) {
    var scaledImg = $(entry.scaled_picture_url);

    url = scaledImg[0].src;
    url = "http://www.eventsfilter.com" + url.substring(url.search("/uploads/"), url.length);

    return(url);
  },

  'getImageRatio': function(entry) {
	var width = entry.width;
	var height = entry.height;
	
    return(width/height);
  },

  'getImageWidth': function(entry) {
  	if (entry.width === null)
  		return(120);
  		
  	var wRatio = entry.width/120;
  	var hRatio = entry.height/75;
  	
  	if (wRatio > hRatio) {
	  	w = entry.width/wRatio;
  	} else {
	  	w = entry.width/hRatio;
  	}
  	
    return(w);
  },

  'getImageHeight': function(entry) {
  	if (entry.height === null)
  		return(75);
  		
  	var wRatio = entry.width/120;
  	var hRatio = entry.height/75;
  	
  	if (wRatio > hRatio) {
	  	h = entry.height/wRatio;
  	} else {
	  	h = entry.height/hRatio;
  	}	
    return(h);
  },

  'ifShow' : function(value, block) {
    if (window.EFembed.options.styleProps.hide === undefined)
      return(block.fn(this));

    if (window.EFembed.options.styleProps.hide.indexOf(value) === -1) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },


  'ifNotEqual' : function(entry, element, value, block) {
    if (entry[element] !== value) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },

  'addExternalLink' : function(blurb) {
    blurb = blurb.replace(/<a/, "<a target='_blank'");
    return(blurb);
  },

  'getAddressLink' : function(entry) {
    var address = "";
    entry = entry.venue
    address += (entry.address_1 !== null)?entry.address_1.replace(" ", "+"):"";
    address +=  (entry.address_2 !== null)?entry.address_2.replace(" ", "+"):"";

    if (address === "")
      address = "#";
    else {
      address = entry.name + "+" + address + "+" + entry.city + "," + entry.state + "+" + entry.zip_code;
    }
    
    //return("http://maps.apple.com/maps?q=" + address);
    return("maps:q=" + address);
  },

  'showHeader' : function(data, block) {
    if (data.dateGroup.length > 1) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },

  'showDontHeader' : function(data, block) {
    if (data.dateGroup.length <= 1) {
      return(block.fn(this));
    } else {
      return(block.inverse(this)); 
    }
  },
  
};

$.map(templateFunctions, function(obj, index){
  Handlebars.registerHelper(index, obj);
});