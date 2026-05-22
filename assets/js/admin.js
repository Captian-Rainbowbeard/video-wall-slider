(function ($) {
  'use strict';

  $(document).ready(function () {
    // Video URL Input Handler
    $(document).on('click', '#vws-add-video', function () {
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
    });

    // Remove Video
    $(document).on('click', '.vws-remove-video', function () {
      if (confirm(vwsAdmin.i18n.confirmDelete)) {
        $(this).closest('.vws-video-item').remove();
      }
    });

    // Make videos sortable
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

    // Save form handler
    $(document).on('submit', '#post', function () {
      // Collect video URLs before form submission
      const videos = [];
      $('#vws-videos-container .vws-video-url').each(function () {
        const url = $(this).val().trim();
        if (url) {
          videos.push(url);
        }
      });

      // Create hidden inputs for videos if they don't exist
      const container = $('#vws-videos-container');
      container.find('input[name="vws_video_url[]"]').each(function () {
        if (!$(this).parents('.vws-video-item').find('input[name="vws_video_url[]"]:first').is(this)) {
          $(this).attr('name', 'vws_video_url[]');
        }
      });
    });
  });
})(jQuery);
