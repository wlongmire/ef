$.wfLive('form.ajax', function() {
  var form = $(this);
  form.ajaxForm({
    error: function() {
      alert('Unable to save form.');
    },
    success: function(val) {
      if (val.success === false)
      {
        alert('Unable to save' + (val.errors ? ':\n' + val.errors.join("\n") : '.'));
      }
    }
  });
  if (form.hasClass('live'))
  {
    form.delegate(form.data('live-selector') ? form.data('live-selector') : ':input', 'change', function() { form.submit(); });
  }
});