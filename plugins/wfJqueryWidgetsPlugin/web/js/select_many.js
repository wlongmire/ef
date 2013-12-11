(function($){
  $.widget( "ui.wfSelectMany", {
    options: {
      optionTemplate: $.template(null, '<option value="${key}" selected="selected">hidden</option>'),
      listItemTemplate: $.template(null, '<div class="item"><span class="ui-icon-wrapper"><span class="ui-icon ui-icon-trash remove"></span></span>${name}</div>'),
      draggable: false,
      draggableOptions: {
        revert: 'invalid',
        helper: function() {
          var element = $(this),
              clone = element.clone();
          clone.width(element.width());
          clone.addClass('clone');
          return clone;
        },
        refreshPositions: true
      }
    },

    _create: function() {
      var self = this;
      self.select = self.element.find('select');
      self.list = self.element.find('.current-list');
      self.input = self.element.find('input[type="text"].autocomplete');

      self.input.autocomplete('option', 'select', $.proxy(self._onAutocompleteSelect, self));
      self.input.data('autocomplete')._renderItem = self._renderItem;
      self.list.delegate('.remove', 'click', $.proxy(self._onRemoveClick, self));
      self.list.delegate('.remove', 'mouseenter mouseleave', function(event) {
          var icon = $(this);
          if (icon.hasClass('ui-icon'))
          {
            icon.parent()[event.type == 'mouseenter' ? 'addClass' : 'removeClass']('ui-state-hover');
          }
      });
    },

    _init: function() {
      this.toggleEmpty();
      this.element.show();
      if (this.options.draggable)
      {
        this._initDraggable();
      }
    },

    _initDraggable: function() {
      var self = this;
      self.list.disableSelection();
      self.element.droppable({
        hoverClass: 'drop-hover',
        over: function() {
          var help = self.element.find('.droppable-help'),
              height = self.list.height() - help.outerHeight() + help.innerHeight();
          help.css({lineHeight: height + 'px'}).height(height);
        },
        drop: function( event, ui ) {
          var listItem = ui.draggable,
              fromSelectMany = listItem.closest('.wf-select-many');

          fromSelectMany.wfSelectMany('remove', listItem);
          self.add(listItem);
        }
      }).find('.item').draggable(this.options.draggableOptions);
    },

    _disableDraggable: function() {
      var self = this;
      self.element.droppable('disable');
      self.list.find('.item').draggable('disable');
    },

    add: function(listItem) {
      var self = this,
          key = listItem.data('key');

      if (!self._trigger('preAdd', {}, listItem))
      {
        return;
      }
      if (!key)
      {
        alert('Unable to add.');
        return;
      }
      if (!self._getSelectOption(key))
      {
        self.select.prepend(self.evaluateTemplate({ key: key }, 'optionTemplate'));
        if (self._trigger('insertListItem', {}, { item: listItem, list: self.list }))
        {
          listItem.hide().prependTo(self.list).fadeIn();
        }
        if (self.element.hasClass('ui-droppable'))
        {
          listItem.draggable(this.options.draggableOptions)
        }
        self.select.change();
        self.toggleEmpty();
      }
      else
      {
        alert('Already added.');
      }
      self._trigger('added', {}, listItem);
    },
    
    _onAutocompleteSelect: function(event, ui) {
      var self = this,
          item = ui.item && !ui.item.empty ? ui.item: false;
      event.preventDefault();
      if (item)
      {
        var items = $.merge([item], $.isArray(item.children) && item.children.length ? item.children : []);
        if (self._trigger('autocompleteSelect', {}, items))
        {
          $.each(items, function(index, aItem) {
            self.add(self.evaluateTemplate(aItem, 'listItemTemplate').data('key', aItem.key));
          });
          self._trigger('autocompleteSelected', {}, items)
        }
      }
      $(event.target).val('');
    },

    _renderItem: function renderItem(ul, item)
    {
      var li = $( "<li></li>" ).data('item.autocomplete', item ),
          anchor = $('<a></a>').text(item.label);

      if ($.isArray(item.children) && item.children.length)
      {
        var childItems = '<li>' + $.map(item.children, function(child) { return child.label; }).join('</li><li>') + '</li>';
        anchor.append('<ul class="item-children">' + childItems + '</ul>');
      }

      li.append(anchor).appendTo(ul);
    },

    remove: function(listItem) {
      var self = this;
      if (!self._trigger('remove', {}, listItem))
      {
        return;
      }
      listItem.fadeOut();
      self._getSelectOption(listItem.data('key')).remove();
      self.toggleEmpty();
      self.select.change();
      self._trigger('removed', {}, listItem);
    },

    removeByKey: function(key)
    {
      this.remove(this.list.find('div.item[data-key="' + key + '"]'));
    },

    reset: function() {
      this.select.find('option').remove();
      this.list.find('.item').remove();
      this.select.change();
      this.toggleEmpty();
    },

    _onRemoveClick: function(event) {
      var self = this;
      if (self._trigger('removeClick'))
      {
        self.remove($(event.target).closest('.item'));
      }
    },

    evaluateTemplate: function(item, templateName) {
      var callback = this.options[templateName + 'Callback'];
      if ($.isFunction(callback))
      {
        return callback(this, item);
      }
      else
      {
        return $.tmpl(this.options[templateName], item);
      }
    },

    _getSelectOption: function(value)
    {
      var option = this.select.find('option[value="' + value + '"]');
      return option.length ? option : null;
    },

    toggleEmpty: function() {
      this.element.find('.current-list .empty')[this.select.val() ? 'hide' : 'show']();
    },

    _setOption: function(key, value) {
      $.Widget.prototype._setOption.apply( this, arguments );
      if ( key === "draggable" ) 
      {
        if (value)
        {
          this._initDraggable();
        }
        else
        {
          this._disableDraggable();
        }
      }
    }
  });

  $.wfLive('.wf-select-many', function() {
    var self = $(this);
    self.wfSelectMany({
      draggable: self.hasClass('draggable')
    });
  });
})(jQuery);