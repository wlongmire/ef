$.wfLive('input.date', function() {
  var input = $(this),
      options = {
        onSelect: function() {
          if (input.closest('form').hasClass('validate'))
          {
            input.valid();
          }  
          input.trigger('datepicker.select');     
        },
        changeMonth: input.data('change-month') || false,
        changeYear: input.data('change-year') || false
      };
  if (input.data('min-date'))
  {
    options.minDate = new Date(input.data('min-date'));
  }
  if (input.data('max-date'))
  {
    options.maxDate = new Date(input.data('max-date'));
  }
  input.datepicker(options);
});