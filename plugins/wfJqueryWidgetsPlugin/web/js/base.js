/**
 * 1. Global Behaviors
 * 2. Live Each
 */


/**
 * 1. wfLive (essentially a live $.each call)
 * A system for calling a function on each element that matches a selector, even if that element is added via AJAX
 */
$.wfLive = function(selector, handler, name) {
  var id = $.wfLive.guid(name);

  if (!$.wfLive.handlers[selector])
  {
    $.wfLive.handlers[selector] = {};
  }
  $.wfLive.handlers[selector][id] = {
    handler: handler,
    guid: id
  };
};

$.extend($.wfLive, {
  _guid: 1,
  guid: function(name) {
    return 'wfLive' + (name || $.wfLive._guid++);
  },
  handlers: {},
  run: function() {
    $.each($.wfLive.handlers, function(selector, handlers) {
      $(selector).each(function(ignored, element) {
        var jqElement = $(element);
        $.each(handlers, function(ignored, handlerObj) {
          if (!jqElement.data(handlerObj.guid))
          {
            handlerObj.handler.call(element);
            jqElement.data(handlerObj.guid, true);
          }
        });
      });
    });
  }
});

$(document).ready(function() {
  $.wfLive.run();
  $('body').ajaxComplete(function() {
    $.wfLive.run();
  });
});

/**
 * 2. Global Behaviors
 */

$.fn.wfFor = function() {
  var target = $(this).data('for') || $(this).attr('for');
  return target ? $('#' + target) : $();
};

$(document).ready(function() {
  $('a.wf-toggler, a.wf-closer, a.wf-opener').live('click', function(event) {
    var self = $(this),
        target = self.wfFor();
        
    event.preventDefault();
    if (self.hasClass('wf-toggler'))
    {
      target.slideToggle();
    }
    else if (self.hasClass('wf-opener'))
    {
      target.slideDown();
    }
    else if (self.hasClass('wf-closer'))
    {
      target.slideUp();
    }
  });
  
  $('a.wf-external-popup').live('click', function(event) {
    event.preventDefault();
    var self = $(this),
        options = {
          toolbar: self.data('toolbar') || 'no',
          menubar: self.data('menubar') || 'no',
          location: self.data('location') || 'yes',
          width: self.data('width'),
          height: self.data('height')
        },
        params = '';
    $.each(options, function(name, value) {
      params += (params ? ',' : '') + name + '=' + value;
    });
    window.open(self.attr('href'), '', params);
  });
});
