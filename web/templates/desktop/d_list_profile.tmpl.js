(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['d_list_profile.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this, blockHelperMissing=helpers.blockHelperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n";
  stack1 = helpers['with'].call(depth0, depth0.entry, {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n";
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n	<div class=\"profile_entry\">\n		<table class=\"filter-data event\">\n			<tr class=\"list-item\">\n\n			";
  options = {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data};
  if (stack1 = helpers.ifImageFlag) { stack1 = stack1.call(depth0, options); }
  else { stack1 = depth0.ifImageFlag; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  if (!helpers.ifImageFlag) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n			<td class=\"col2\">\n				<p><a id=\"profile_title\" class=\"detail profile text\" data-id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-type=\"profile\" href=\"http://www.eventsfilter.com/api/profile/show/";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</a></p>\n			</td>\n\n			<td class=\"col3\">\n				";
  stack1 = helpers['if'].call(depth0, depth0.Disciplines, {hash:{},inverse:self.noop,fn:self.program(10, program10, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n				";
  stack1 = helpers['if'].call(depth0, depth0.Categories, {hash:{},inverse:self.noop,fn:self.program(13, program13, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n				";
  stack1 = helpers['if'].call(depth0, depth0.Location, {hash:{},inverse:self.noop,fn:self.program(16, program16, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				\n			</td>\n\n			<td class=\"col4\">\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(19, program19, data),data:data};
  stack2 = ((stack1 = helpers.hasEvents || depth0.hasEvents),stack1 ? stack1.call(depth0, depth0.event_count, options) : helperMissing.call(depth0, "hasEvents", depth0.event_count, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n			</td>\n			</tr>\n		</table>\n\n		<div class=\"details_container\" data-status=\"empty\"></div>\n	</div>\n";
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n			";
  options = {hash:{},inverse:self.noop,fn:self.program(4, program4, data),data:data};
  stack2 = ((stack1 = helpers.ifShow || depth0.ifShow),stack1 ? stack1.call(depth0, "image", options) : helperMissing.call(depth0, "ifShow", "image", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n			";
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n			\n			<td class=\"col1\">\n			";
  options = {hash:{},inverse:self.program(8, program8, data),fn:self.program(5, program5, data),data:data};
  stack2 = ((stack1 = helpers.ifImageExists || depth0.ifImageExists),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "ifImageExists", depth0, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n			</td>\n\n			";
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n			";
  stack1 = helpers['with'].call(depth0, depth0.Picture, {hash:{},inverse:self.noop,fn:self.program(6, program6, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n			";
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n				<img alt=\"";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" width=\"";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getImageWidth || depth0.getImageWidth),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getImageWidth", depth0, options)))
    + "\" height=\"";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getImageHeight || depth0.getImageHeight),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getImageHeight", depth0, options)))
    + "\" src=\"http://www.eventsfilter.com/uploads/media_items/";
  if (stack2 = helpers.slug) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.slug; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + ".120.75.s.";
  if (stack2 = helpers.format) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.format; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\">\n			";
  return buffer;
  }

function program8(depth0,data) {
  
  
  return "\n				<div style=\"width:120px; height:75px; display:block\"></div>\n			";
  }

function program10(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(11, program11, data),data:data};
  stack2 = ((stack1 = helpers.ifShow || depth0.ifShow),stack1 ? stack1.call(depth0, "disciplines", options) : helperMissing.call(depth0, "ifShow", "disciplines", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n				";
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<p id=\"discipline_name\" class=\"text\">"
    + escapeExpression(((stack1 = ((stack1 = ((stack1 = depth0.Disciplines),stack1 == null || stack1 === false ? stack1 : stack1[0])),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</p>\n				";
  return buffer;
  }

function program13(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(14, program14, data),data:data};
  stack2 = ((stack1 = helpers.ifShow || depth0.ifShow),stack1 ? stack1.call(depth0, "categories", options) : helperMissing.call(depth0, "ifShow", "categories", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n				";
  return buffer;
  }
function program14(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<p id=\"discipline_name\" class=\"text\">"
    + escapeExpression(((stack1 = ((stack1 = ((stack1 = depth0.Categories),stack1 == null || stack1 === false ? stack1 : stack1[0])),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</p>\n				";
  return buffer;
  }

function program16(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(17, program17, data),data:data};
  stack2 = ((stack1 = helpers.ifShow || depth0.ifShow),stack1 ? stack1.call(depth0, "location", options) : helperMissing.call(depth0, "ifShow", "location", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n				";
  return buffer;
  }
function program17(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<p id=\"discipline_name\" class=\"text\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.Location),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</p>\n				";
  return buffer;
  }

function program19(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(20, program20, data),data:data};
  stack2 = ((stack1 = helpers.ifShow || depth0.ifShow),stack1 ? stack1.call(depth0, "event_count", options) : helperMissing.call(depth0, "ifShow", "event_count", options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n				";
  return buffer;
  }
function program20(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<a id=\"profile_title\" class=\"detail events text\" data-type=\"events\" href=\"#\">";
  if (stack1 = helpers.event_count) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.event_count; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " events</a>\n				";
  return buffer;
  }

  buffer += "<div class=\"ef\">\n\n";
  stack1 = helpers.each.call(depth0, depth0.profileGroup, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n</div>";
  return buffer;
  });
})();