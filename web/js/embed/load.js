var path = 'http://www.eventsfilter.com/';
//var path = 'http://www.eventsfilter.com.localhost/';

var listTemplate = {"event": path + 'templates/desktop/d_list_event.tmpl.js',
 					 "profile":path + 'templates/desktop/d_list_profile.tmpl.js'};

var detailsTemplate = {"event": path + 'templates/desktop/d_details_event.tmpl.js', 
						"profile": path + 'templates/desktop/d_details_profile.tmpl.js', 
						"venue": path + 'templates/desktop/d_details_venue.tmpl.js'};

requirejs([path + 'js/plugin/jquery-1.8.3.min.js', path + 'js/plugin/handlebars.runtime.js'], function(){
console.log("base plugins load complete");

requirejs([path + 'js/embed/embedPlugin.js', path + 'js/embed/templateUtils.js', path + 'js/embed/utils.js'], function(){
console.log("embed related plugins loaded");

requirejs([path + 'js/embed/embed.js'], function(){
	console.log("embed main loaded");

	var container = $("#ef-list");

	var url = container.data("url");

	var options = container.EFembed({url:url});

	EFembed.user_options = options;

	var templates = [];
	templates.push(((options.listTemplate === undefined)?listTemplate[options.filterProps['data_type']]:options.listTemplate));
	templates.push(((options.eventDetailsTemplate === undefined)?detailsTemplate['event']:options.eventDetailsTemplate));
	templates.push(((options.profileDetailsTemplate === undefined)?detailsTemplate['profile']:options.profileDetailsTemplate));
	templates.push(((options.venueDetailsTemplate === undefined)?detailsTemplate['venue']:options.venueDetailsTemplate));
	
	requirejs(templates, function() {
		console.log("templates loaded");

		if (options.run_now) {
			EFembed.load(options, container[0]);
	 	}

	});
});

});

});