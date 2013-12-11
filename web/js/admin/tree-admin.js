$(document).ready(function()  {
  $('#a-admin-list-batch-checkbox-toggle').click(function(){
    $('.a-admin-batch-checkbox').each( function() {
      $(this)[0].checked = !$(this)[0].checked;
    });
  });
  
  $('#update-tree-button').click(function() {
    $('#a-admin-batch-form').attr('action', $(this).data('action')).submit();
  });
  
  $("#tree-list").treeTable({
    treeColumn: 0,
    initialState: 'expanded'
  });

  // Configure draggable nodes
  $("#tree-list .file, #tree-list .folder").draggable({
    helper: "clone",
    opacity: .75,
    refreshPositions: true, // Performance?
    revert: "invalid",
    revertDuration: 300,
    scroll: true
  });

  // Configure droppable rows
  $("#tree-list .file, #tree-list .folder").each(function() {
    $(this).parents("tr").droppable({
      accept: ".file, .folder",
      drop: function(e, ui) { 
        // Call jQuery treeTable plugin to move the branch
        var parentTr = $($(ui.draggable).parents("tr"));
        parentTr.appendBranchTo(this);
        var parentId = parentTr.attr("id");
        $("#moved_" + parentId).val(this.id.substr(5));
        $('#a-admin-batch-form .tree-order-changed').fadeIn();
      },
      hoverClass: "accept",
      over: function(e, ui) {
        // Make the droppable branch expand when a draggable node is moved over it.
        if(this.id != ui.draggable.parents("tr")[0].id && !$(this).is(".expanded")) {
          $(this).expand();
        }
      }
    });
  });

  // Make visible that a row is clicked
  $("table#tree-list tbody tr").mousedown(function() {
    $("tr.selected").removeClass("selected"); // Deselect currently selected rows
    $(this).addClass("selected");
  });

  // Make sure row is selected when span is clicked
  $("table#tree-list tbody tr span").mousedown(function() {
    $($(this).parents("tr")[0]).trigger("mousedown");
  });
});