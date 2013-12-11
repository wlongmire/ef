$(document).ready(function() {
  var body =  $('body');
      
  if (!body.hasClass('listings'))
  {
    return;
  }
  
  if (body.hasClass('iframe'))
  {
    var data = '{ "iframe" : "' + body.data('iframe') + '", "height" : "' + $('#content').height() + '"}'; //firefox 3-4 only strings
    window.parent.postMessage(data, '*');
    if (body.hasClass('listings-only'))
    {
      return;
    }
  }

  var filterResults = $('#filter-results'),
      filters = $('#filters'),
      filterScope = $('.filter-scope'), //had to add this to support local filters header
      detailsArea = filters.find('.filter-area.details'),
      pendingXhr = null,
      lastRequestId = 0;
  $(window).scroll(refreshFilterPosition);  
  
  filters.find('.clear-next-tree').click(onFilterClearClick);
  filters.find('.my-tabs li').click(onFilterTabClick);
  filterScope.find('input.date').bind('datepicker.select', onFilterDateSelect);
  body.find('form.search-form input[type="text"].search').keyup(onFilterSearchKeyup);
  filterScope.find('select[name$="[date_range]"]').bind('change', onFilterDateRangeSelect);

  filterResults.delegate('a.paginate', 'click', onPaginateClickDelegate);
  
  if (!body.hasClass('search-only'))
  {
    body.delegate('a.details', 'click', onDetailsClickDelegate);
    body.delegate('a.filter', 'click', onFilterClickDelegate);
    body.delegate('a.clear-local-filters', 'click', onClearLocalFiltersClickDelegate);
    body.delegate('select.select-filter-tree', 'change', onSelectFilterChangeDelegate);
    body.delegate('a.show-listings', 'click', onShowListingsClick);
    filterResults.delegate('a.show-all', 'click', onShowAllClickDelegate);
    $('.a-nav-main a').click(onPageNavigationClick);
  }
      
  function loadResults(url, options)
  {
    var getParams = [];
    options = $.extend({
      data: {},
      append: false
    }, options);
    
    $.each(['sort', 'images'], function() {
      var param = this;
      if (ef.urlParams[param] && url.indexOf(param + '=') == -1)
      {
        getParams.push(param + '=' + ef.urlParams[param]);
      }
    });
    if (getParams.length)
    {
      url += (url.indexOf('?') == -1 ? '?' : '&') + getParams.join('&');
    }
    
    filterResults.find('> .loading').show();
    if (pendingXhr !== null)
    {
      pendingXhr.abort();
    }
    
    try {
    pendingXhr = $.post(url, options.data, loadResultsSuccess);
    } catch (e) {
      console.log(e);
    }
    pendingXhr.append = options.append;
    pendingXhr.requestId = ++lastRequestId;
  }
  
  function loadResultsSuccess(data)
  {
    if (pendingXhr.requestId == lastRequestId) //if it doesn't, there's another one we're waiting for
    {
      filterResults.find('.data')[pendingXhr.append ? 'append' : 'html'](data);
      filterResults.find('.loading').hide();  
      pendingXhr = null;     
      refreshFilterPosition();
    }
  }
  
  function onFilterTabClick(event)
  {
    var listItem = $(this),
        areaToShow = false;
        
    if (listItem.hasClass('selected'))
    {
      event.preventDefault();
      return;
    }
    
    if (listItem.hasClass('details'))
    {
      areaToShow = 'details';
    }
    else if (listItem.hasClass('controls'))
    {
      areaToShow = 'controls';
    }
    if (areaToShow)
    {
      event.preventDefault();
      listItem.siblings().removeClass('selected');
      listItem.addClass('selected');      
      filters.find('.filter-area').hide();
      filters.find('.filter-area.' + areaToShow).show();
      refreshFilterPosition();
    }
  }
  
  function onPaginateClickDelegate(event)
  {
    var anchor = $(this);
    event.preventDefault();
    anchor.closest('.pagination').replaceWith('<div class="loading pagination immediate">Loading more results</div>');    
    loadResults(anchor.attr('href'), {append: true});
  }
  
  function onFilterClickDelegate(event) 
  {
    event.preventDefault();
    loadResults($(this).attr('href'));
  }  
  
  function onClearLocalFiltersClickDelegate(event) 
  {
    event.preventDefault();
    $(this).closest('.filter-reset').hide();
    body.find('select.select-filter-tree').val('');
    loadResults(document.URL);
  }
  
  function onSelectFilterChangeDelegate(event) 
  {
    var select = $(this),
        selectVal = select.val(),
        filterName = select.data('filter'),
        defaultValue = ef.urlParams[filterName] ? ef.urlParams[filterName] : '0',
        url = select.data('base-filter-url').replace('-value-', selectVal ? selectVal : defaultValue);
    
    body.find('.filter-reset').show();
    loadResults(url);
  }
  
  function onFilterClearClick()
  {
    var anchor = $(this),
        block = anchor.closest('.filter-block');
    anchor.hide();
    block.find('.collapsable-hitarea').click();
    block.find('.active > a, a.active').click();    
  }
  
  function onFilterDateSelect() 
  {
    var input = $(this),
        url = input.closest('form').data('start-date-url');
    loadResults(url, {data: {value: input.val()} });
  }
  
  function onFilterDateRangeSelect() 
  {
    var select = $(this),
        url = select.closest('form').data('date-range-url');
    loadResults(url, {data: {value: select.val() } });        
  }
  
  function tryFilterSearch()
  {
    
  }
  
  function onFilterSearchKeyup()
  {
    var input = $(this);
   console.log(input.val());
    function tryFilterSearch()
    {
      if (!pendingXhr)
      {
        hasPendingSearch = false;
        loadResults(input.closest('form').data('search-url'), {data: {value: input.val()} });
      }
      else if (!hasPendingSearch) //if we already have a pending search, when that one fires it will have the current values so we can do nothing
      {
        hasPendingSearch = true;
        setTimeout(tryFilterSearch, 200);
      }      
    }
   
    if (this.timer)
    {
      clearTimeout(this.timer);
    }    
    this.timer = setTimeout(tryFilterSearch, 800); // 2000ms delay, tweak for faster/slower    
  }
  
  function refreshFilterPosition(fixToTop)
  {
    fixToTop = fixToTop || false; //true to force a fix to the top
    
    var $window = $(window),
        scrollTop = $window.scrollTop(),
        margin = 5,
        contentOffsetTop = $('#content').offset().top,
        filterHeight = filters.height();
    
    if (scrollTop <= contentOffsetTop - margin || filterHeight > filterResults.height()) 
    {
      filters.css({position: 'static'});
    } 
    else 
    {
      var filterOffset = filters.offset(),
          filterPosition = filters.position(),
          windowHeight = $window.height(),
          top = false;

      filters.css({position: 'absolute', right: $('#content').css('padding-right') }); //always absolute in this instance   
      if (fixToTop === true || filterHeight < windowHeight || filterPosition.top > scrollTop + margin) //if the pane fits, or there's space above the pane
      {
        top = scrollTop + margin;
      }
      else if (filterHeight + filterOffset.top <= scrollTop + windowHeight)
      {
        top = scrollTop - margin - (filterHeight - windowHeight);
      }
      if (top !== false)
      {
        top -= contentOffsetTop;
        filters.stop().animate({top: top}, 50);
      }               
    }    
  }
  
  function onDetailsClickDelegate(event)
  {
    var anchor = $(this);
    event.preventDefault();
    filters.find('.my-tabs .details').click();
    detailsArea.find('.loading').show();
    $.get(anchor.attr('href'), onDetailsSuccess);
  }
  
  function onDetailsSuccess(data)
  {
    detailsArea.find('.loading').hide();
    detailsArea.find('.data').html(data);
    refreshFilterPosition(true);
  }
  
  function onShowAllClickDelegate()
  {
    var anchor = $(this);
    anchor.siblings().removeClass('hidden');
    anchor.remove();
  }
  
  function onPageNavigationClick(e) 
  { 
    e.preventDefault(); 
    loadResults($(this).attr('href')); 
  }
  
  function onShowListingsClick()
  {
    loadResults(body.data('reload-url'));
  }
});