(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['m_list_event.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n		<li data-role=\"list-divider\" role=\"heading\" id=\"date_heading\">";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.formatDate || depth0.formatDate),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "formatDate", depth0, options)))
    + "</li>\n\n	";
  stack2 = helpers.each.call(depth0, depth0.entry, {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n\n	";
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = "", stack1, options;
  buffer += "\n	<li data-theme=\"c\" data-icon=\"arrow-r\">\n		<a href=\"http://www.eventsfilter.com/api/event/show/";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-type=\"event\" class=\"event detail\">\n			<p class=\"event_name\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</p>\n			\n			";
  stack1 = helpers['if'].call(depth0, depth0.Venue, {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n			<p class=\"time_text\">";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getTime || depth0.getTime),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getTime", depth0, options)))
    + "</p>\n		</a>\n	</li>\n	";
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += " \n			<p class=\"venue_name\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.Venue),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</p>\n			";
  return buffer;
  }

  buffer += "	";
  stack1 = helpers.each.call(depth0, depth0.dateGroup, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  return buffer;
  });
})();