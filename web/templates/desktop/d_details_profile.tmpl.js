(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['d_details_profile.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1, options;
  buffer += "\n	<img class=\"profile_image\" alt=\"";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" src=\"";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getImageUrl || depth0.getImageUrl),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getImageUrl", depth0, options)))
    + "\">\n";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1, options;
  buffer += "\n	<p class=\"profile_disciplines\">";
  options = {hash:{},data:data};
  buffer += escapeExpression(((stack1 = helpers.getDisciplines || depth0.getDisciplines),stack1 ? stack1.call(depth0, depth0, options) : helperMissing.call(depth0, "getDisciplines", depth0, options)))
    + "</p>\n";
  return buffer;
  }

  buffer += "<h4 class=\"profile_name\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</h4>\n\n";
  stack1 = helpers['if'].call(depth0, depth0.original_picture_url, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n";
  stack1 = helpers['if'].call(depth0, depth0.disciplines, {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n<p class=\"profile_blurb\">";
  options = {hash:{},data:data};
  stack2 = ((stack1 = helpers.addExternalLink || depth0.addExternalLink),stack1 ? stack1.call(depth0, depth0.blurb, options) : helperMissing.call(depth0, "addExternalLink", depth0.blurb, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "</p>\n<div class=\"clearfix\"></div>";
  return buffer;
  });
})();