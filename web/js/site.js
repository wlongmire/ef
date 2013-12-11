var efConstructor = function() {
  this.filterTrees = function(selector)
  {
    var filterTrees = $(selector);
    
    if (!filterTrees.length) return;
    
    filterTrees.filter('.filter')
      .find('li.active').addClass('open')
      .parents('li').addClass('open');
    
    filterTrees.filter('.widget.radio')
      .find('.active').closest('li').parents('li').addClass('open');
      
    filterTrees.treeview({ collapsed: true });
    filterTrees.find('li:not(.expand-only)').delegate('a, label', {
      mouseover: function(event) { 
        var target = $(this);
        event.stopPropagation();
        target.closest('.filter-tree').hasClass('filter') ?
          target.closest('li').addClass('hover') :
          target.addClass('hover');
      },
      mouseout: function(event) {
        var target = $(this);
        event.stopPropagation();
        target.closest('.filter-tree').hasClass('filter') ?
          target.closest('li').removeClass('hover') :
          target.removeClass('hover');
      }
    });                
    filterTrees.delegate('a, label', 'click', this._onFilterItemClick);
    filterTrees.find('li.expand-only').click(function(event) {
      if ($(event.target).hasClass('expand-only'))
      {
        $(this).children('.hitarea').click();
      }
    });
    
    var checkboxTrees = filterTrees.filter('.widget.checkbox');
    if (checkboxTrees.length)
    {
      //omg hack attack
      $('body').delegate('.filter-tree-selected a', 'click', function() { 
        var checkbox = $('#' + $(this).data('for'));
        checkbox.siblings('label').click();
      });
      checkboxTrees
        .bind('widget.refresh', this._onFilterTreeCheckboxRefresh)
        .trigger('widget.refresh');    
    }
  }
  
  this._onFilterItemClick = function() {
    var item = $(this),
        filterTree = item.closest('.filter-tree'),    
        target = filterTree.hasClass('filter') ? item.closest('li') : item,
        isCurrentlyActive = target.hasClass('active');
        
    if (!isCurrentlyActive || filterTree.hasClass('removable'))
    {
      var clearAnchor = item.closest('.filter-block').find('.clear-next-tree');
      if (!filterTree.hasClass('multi-select'))
      {
        filterTree.find('.active').removeClass('active');
      }
      else if (isCurrentlyActive)
      {
        target.removeClass('active');        
      }
      if (!isCurrentlyActive)
      {
        target.addClass('active');
        clearAnchor.show();      
      }
      else
      {
        if (filterTree.hasClass('radio')) //clicking won't clear a radio value
        {
          $('#' + item.attr('for'))[0].checked = false;
          $('#' + item.attr('for')).trigger('change');
          return false;
        }
        clearAnchor.hide();
      }
      if (filterTree.hasClass('checkbox'))
      {
        var target = $('#' + item.attr('for'));
        if (target.length)
        {
          target[0].checked = !isCurrentlyActive;
          filterTree.trigger('widget.refresh');
          return false;
        }
        else
        {
          item.siblings('.hitarea').trigger('click');
        }
      }
    }
    else
    {
      return false;
    }
  }
  
  this._onFilterTreeCheckboxRefresh = function(event)
  {
    var filterTree = $(this);
    if (!filterTree.data('initialized'))
    {
      filterTree.before('<h4>Selected</h4><ul class="filter-tree-selected"></ul><h4>Choose</h4>');
      filterTree.data('initialized', true);
    }
    
    var selectedList = filterTree.prevAll('.filter-tree-selected').empty();
    
    filterTree.find(':input:checked').each(function() {
      var checkbox = $(this),
          label = checkbox.siblings('label'),
          title = label.html(),
          parent = label.closest('ul'),
          listItem = $('<li>'),
          anchor = '<a href="javascript:;" data-for="' + checkbox.attr('id') + '"></a>';

      while (parent.length && !parent.hasClass('filter-tree'))
      {
        label = parent.siblings('label');
        title = label.html() + ' &raquo; ' + title;
        parent = label.closest('ul');
      }
      
      listItem.html(title + anchor);
      selectedList.append(listItem);
    });
    
    if (selectedList.find('li').length == 0)
    {
      selectedList.html('<li class="empty">None selected.</li>');
    }
  }
  
  this.loadUrlParams = function()
  {
    var urlParams = {},
        match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    while (match = search.exec(query))
       urlParams[decode(match[1])] = decode(match[2]); 
     
    ef.urlParams = urlParams;
  }
}

window.ef = new efConstructor();

$(document).ready(function() {
  $('form').attr('novalidate', true);
  $('body').delegate('a', 'click', function(event) {
    var anchor = $(this);
      
    if (anchor.hasClass('blurb-expand'))
    {
      anchor.closest('.blurb').siblings('.blurb.full').show();
      anchor.closest('.blurb').remove();
      event.preventDefault();
    }
  });
  ef.loadUrlParams();
});