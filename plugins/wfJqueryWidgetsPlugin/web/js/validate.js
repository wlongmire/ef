$.validator.addMethod(
  'pattern',
  function(value, element, regexp) {
    if (this.optional(element) || value.length == 0) return true;
    if (/^\/.+\/$/.test(regexp)) //if it starts and ends with /, take them off
    {
      regexp = regexp.substr(1, regexp.length - 2);
    }
    return (new RegExp(regexp)).test(value);
  },
  'Please check your input.'
);

$.validator.addMethod(
  'date',
  function(value, element) {
    return this.optional(element) || value.length == 0 || !isNaN(new Date(value));
  },
  'Please enter a date (MM/DD/YYYY).'
);

$.validator.addMethod(
  'mindate',
  function(value, element, mindate) {
    return this.optional(element) || value.length == 0 || (new Date(mindate) < new Date(value));
  },
  'Please enter a date after or on {0}.'
);

$.validator.addMethod(
  'maxdate',
  function(value, element, maxdate) {
    return this.optional(element) || value.length == 0 || (new Date(maxdate) < new Date(value));
  },
  'Please enter a date before or on {0}.'
);

$.validator.addMethod(
  'required',
  function(value, element, param) {
    var eElement = $(element);
    if (eElement.is(':hidden') && eElement.hasClass('required'))
    {
      return true;
    }
    // check if dependency is met
    if ( !this.depend(param, element) )
      return "dependency-mismatch";
    switch( element.nodeName.toLowerCase() ) {
    case 'select':
      // could be an array for select-multiple or a string, both are fine this way
      var val = eElement.val();
      return val && val.length > 0;
    case 'input':
      if ( this.checkable(element) )
        return this.getLength(value, element) > 0;
    default:
      return $.trim(value).length > 0;
    }
  }
);


$.wfLive('form.validate', function() {
  var form = $(this);
  if ('noValidate' in document.createElement('form'))
  {
    form.attr('noValidate', true);
  }
  form.validate({
    ignore: '.ignore',
    errorElement: "div",
    wrapper: "div",  // a wrapper around the error message
    unhighlight: function( element, errorClass, validClass ) {
      $(element).removeClass(errorClass).addClass(validClass);
    },
    errorPlacement: function(error, element) {
      var target,
          offset,
          maxLeft = 0,
          type = element.attr('type');

      if (type == 'radio' || type == 'checkbox')
      {
        target = element.parents('.' + type + '_list, .' + type + '-list');
        offset = target.position();

        target.find('label, input').each(function(index, element) { //find the farthest left position of a child label or input
          var left = $(element).position().left + $(element).outerWidth();
          if (left > maxLeft)
          {
            maxLeft = left;
          }
        });

        if (maxLeft)
        {
          offset.left = maxLeft;
        }
      }
      else
      {
        target = element;
        offset = target.position();
        offset.left += target.outerWidth();
      }

      error.insertBefore(target)
        .addClass('wf-error-message')  // add a class to the wrapper
        .css('position', 'absolute'); //insert it and add classes so we can get proper size

      offset.top += target.outerHeight() / 2;
      offset.top -= error.outerHeight() / 2;

      error
        .css('left', offset.left)
        .css('top', offset.top);
    }
  });
});
