$.wfLive('div.ui-slider-widget', function() {
  var self = $(this),
      slider = self.children('div.slider'),
      valueSpan = self.children('span.ui-slider-value'),
      options = slider.data('options'),
      getValueText = function(value) { // THIS ALGORITHM MUST MATCH wfWidgetFormJquerySlider::getLabelStartValue()
        var text;
        value = parseInt(value);
        if (!options.valueLabelMap)
        {
          return value;
        }

        $.each(options.valueLabelMap, function(key, val) {
          if (parseInt(key) > value)
          {
            return false; // break
          }
          text = val;
          return true; // continue
        });

        return text + (options.appendValueToLabel ? ' (' + value + ')' : '');
      };

  slider.slider($.extend(options, {
    slide: function(event, ui) {
      slider.wfFor().val(ui.value);
      valueSpan.html(getValueText(ui.value));
		}
  }));
});