$.wfLive('input.autocomplete', function() {
  var input = $(this);
  input.bind({
    autocompletefocus: function(event) {
      event.preventDefault();
    },
    autocompleteopen: function() {
      var items = $('.ui-autocomplete.ui-menu').eq(0).find('.ui-menu-item');
      if (items.length == 1)
      {
        var item = items.eq(0);
        if (item.data('item.autocomplete').label == $(this).val())
        {
          item.find('a').mouseenter().click();
        }
      }
    },
    keyup: function() {
      if(this.value.length == 0) {
        $(this).wfFor().val('');
      }
    },
    lock: function() {
      var self = $(this);
      if (self.attr('readonly'))
      {
        return;
      }
      self
        .attr('readonly', 'readonly')
        .after('<a href="javascript:;" class="unlock">Change</a>')
        .bind('click.lock', function(event) { event.preventDefault(); })
        .siblings('a.unlock').click(function() {
          self.trigger('unlock');
        })
    },
    unlock: function() {
      var self = $(this);
      self.wfFor().val('');
      self.removeAttr('readonly', null).val('').focus();
      self.siblings('a.unlock').remove();
      self.unbind('click.lock');
    }
  }).autocomplete({
    source: function (request, response) {
      $.ajax({
          url: input.data('source'),
          dataType: "json",
          data: request,
          success: function (data) {
            if (!data.length)
            {
              input.trigger('autocomplete.noresults');
              if (!input.hasClass('silent-fail'))
              {
                response([{ label: 'No Results.', empty: true }]);
              }
            }
            else
            {
              response(data);
            }
          }
      });
    },
    select: function(event, ui) {
      var self = $(this);
      event.preventDefault();
      self.val(ui.item ? ui.item.label : '');
      self.wfFor().val(ui.item ? ui.item.key : '');

      if (ui.item && ui.item.empty)
      {
        return;
      }
      if (self.hasClass('lock'))
      {
        self.trigger('lock');
      }
      self.trigger('afterSelect');
    }
  });
  if (input.val())
  {
    input.trigger('lock');
  }
});