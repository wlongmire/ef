$(document).ready(function() {
  var eventForm = $('#a-admin-form').eq(0),
      eventOccuranceCountSelect = eventForm.find('select[name$="[event_occurance_count]"]'),
      venueAutocomplete = eventForm.find(':input[name="autocomplete_event[venue_id]"]');

  if (!eventForm.length)
  {
    return;
  }
  
  eventOccuranceCountSelect.change(updateEventOccuranceVisibility).each(updateEventOccuranceVisibility);
  eventForm.find('[name$="[event_type_id]"]').change(updateDateVisibility);
  eventForm.find('[name$="[date_mode]"]').change(updateDateVisibility);
  eventForm.find('a.add-ticket-url').click(onAddTicketUrlClick);
  venueAutocomplete.bind({
    'autocomplete.noresults' : refreshVenue,
    'blur' : refreshVenue
  });
  if (!venueAutocomplete.val())
  {
    venueAutocomplete.val(eventForm.find(':input[name$="[suggested_venue_name]"]').val());
  }
  updateDateVisibility();
  
  function updateEventOccuranceVisibility()
  {
    var count = eventOccuranceCountSelect.val(),
        occuranceForms = eventForm.find('.event-occurances .event-occurance');

    occuranceForms.each(function(index, formRow) {
      $(formRow)[index >= count ? 'hide' : 'show']();
    });
  }

  eventForm.find('.event-occurances input[name$="[start_date]"]').bind('datepicker.select', function() {
    var startDate = $(this),
        endDate = startDate.closest('.a-admin-form-container').find('input[name$="[end_date]"]');

    if (!endDate.val())
    {
      endDate.val(startDate.val());
    }
  });
  
  function updateDateVisibility()
  {
    var dateModes = eventForm.find('[name$="[date_mode]"]'),
        dateMode = dateModes.filter(':checked'),
        dateModeVal = dateMode.length ? dateMode.val() : (dateModes.length ? null : 'manual'),
        eventType = eventForm.find('[name$="[event_type_id]"] option:selected'),
        isDaily = eventType.length && !!eventType.data('is-daily'),
        occurrencesRow = eventForm.find('.a-form-row.event-occurances'),
        recurrenceRow = eventForm.find('.event_event_recurrance_object');
      
    if (isDaily && !occurrencesRow.hasClass('daily'))
    {
      eventOccuranceCountSelect.val(1);
      updateEventOccuranceVisibility();
      occurrencesRow.addClass('daily').removeClass('single-event');
      dateModeVal = 'manual';
      dateModes.closest('li').hide();
      dateModes.filter('[value="manual"]').attr('checked', 'checked').closest('li').show();
    }
    else if (!isDaily && !occurrencesRow.hasClass('single-event'))
    {
      dateModes.closest('li').show();
      occurrencesRow.removeClass('daily').addClass('single-event');
    } 
    
    eventForm.find('.date-entry').toggle(eventType.length && !!eventType.val());
    recurrenceRow.toggle(dateModeVal === 'recur'); //.toggle requires boolean
    occurrencesRow.toggle(dateModeVal === 'manual');    
  }  
  
  function refreshVenue()
  {
    if (!eventForm.find(':input[name$="[venue_id]"]').val())
    {
      eventForm.find(':input[name$="[suggested_venue_name]"]').val($(this).val());
    }
  }
  
  function onAddTicketUrlClick()
  {
    var anchor = $(this),
        scope = anchor.closest('.event-occurance');
    anchor.hide();
    scope.find('.ticket-url').slideDown();
  }
});