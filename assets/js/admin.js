(function ($) {
  'use strict';

  $(document).ready(function () {
    // Reinitialize sortable after adding new items
    function initSortable() {
      $('#vws-videos-container').sortable({
        placeholder: 'ui-sortable-placeholder',
        handle: '.vws-drag-handle',
        axis: 'y',
        update: function () {
          // Update indices after reorder
          $(this)
            .find('.vws-video-item')
            .each(function (index) {
              $(this).attr('data-index', index);
            });
        },
      });
    }

    // Initialize sortable on page load
    initSortable();

    // Video URL Input Handler
    $(document).on('click', '#vws-add-video', function (e) {
      e.preventDefault();
      
      const container = $('#vws-videos-container');
      const newIndex = container.find('.vws-video-item').length;
      const newItem = $(
        '<div class="vws-video-item" data-index="' +
          newIndex +
          '">' +
          '<span class="vws-drag-handle">⋮⋮</span>' +
          '<input type="text" class="vws-video-url" name="vws_video_url[]" placeholder="https://www.youtube.com/watch?v=..." />' +
          '<button type="button" class="button vws-remove-video">Remove</button>' +
          '</div>'
      );
      container.append(newItem);
      
      // Re-initialize sortable with new item
      initSortable();
      
      // Focus the new input
      newItem.find('input').focus();
    });

    // Remove Video
    $(document).on('click', '.vws-remove-video', function (e) {
      e.preventDefault();
      
      if (confirm(vwsAdmin.i18n.confirmDelete)) {
        $(this).closest('.vws-video-item').fadeOut(300, function () {
          $(this).remove();
        });
      }
    });
  });
})(jQuery);
