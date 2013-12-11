(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['m_details_event.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n	<a data-role=\"none\" href=\"";
  if (stack1 = helpers.ticket_url) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.ticket_url; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"> Get Tickets</a>\n";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1, options;
  buffer += "\n	<img class=\"event_image\" alt=\"";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" src=\"";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getImageUrl || depth0.getImageUrl),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getImageUrl", depth0, options)))
    + "\">\n";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n	";
  stack1 = helpers['with'].call(depth0, depth0.venue, {hash:{},inverse:self.noop,fn:self.program(6, program6, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n";
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n		<p class=\"event_venue_name\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</p>\n\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(7, program7, data),data:data};
  stack2 = ((stack1 = helpers.ifNotEqual || depth0.ifNotEqual),stack1 ? stack1.call(depth0, depth0, "name", "Various locations in Philadelphia", options) : helperMissing.call(depth0, "ifNotEqual", depth0, "name", "Various locations in Philadelphia", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n\n	";
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n\n		<p class=\"event_address_1\">";
  if (stack1 = helpers.address_1) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.address_1; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</p>\n		";
  stack1 = helpers['if'].call(depth0, depth0.address_2, {hash:{},inverse:self.noop,fn:self.program(8, program8, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n		";
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n		<p class=\"event_address_2\">";
  if (stack1 = helpers.address_2) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.address_2; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</p>\n		";
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = "", stack1, options;
  buffer += "\n\n<a data-role=\"none\" data-theme=\"a\" class=\"map\" href=\"";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getAddressLink || depth0.getAddressLink),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getAddressLink", depth0, options)))
    + "\">Map</a>\n\n";
  return buffer;
  }

  buffer += "<h4 class=\"event_name\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</h4>\n<p class=\"time_text\">";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getTime || depth0.getTime),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getTime", depth0, options)))
    + "</p>\n<p class=\"time_text\">\n";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getCost || depth0.getCost),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getCost", depth0, options)))
    + "\n";
  stack2 = helpers['if'].call(depth0, depth0.ticket_url, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n\n</p>\n\n\n";
  stack2 = helpers['if'].call(depth0, depth0.original_picture_url, {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n\n<div class=\"event_blurb\">\n	";
  options = {hash:{},data:data};
  stack2 = ((stack1 = helpers.addExternalLink || depth0.addExternalLink),stack1 ? stack1.call(depth0, depth0.blurb, options) : helperMissing.call(depth0, "addExternalLink", depth0.blurb, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n</div>\n\n";
  stack2 = helpers['if'].call(depth0, depth0.venue, {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n\n\n<a data-role=\"none\" data-rel=\"back\" class=\"back\" href=\"#\">Close</a>\n\n";
  options = {hash:{},inverse:self.noop,fn:self.program(10, program10, data),data:data};
  stack2 = ((stack1 = helpers.ifNotEqual || depth0.ifNotEqual),stack1 ? stack1.call(depth0, depth0.venue, "name", "Various locations in Philadelphia", options) : helperMissing.call(depth0, "ifNotEqual", depth0.venue, "name", "Various locations in Philadelphia", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n";
  return buffer;
  });
})();