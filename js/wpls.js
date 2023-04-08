
jQuery(document).ready(function ($) {

    jQuery('.live-searchbox').hide();

    jQuery('.search-box').on('input', function () {
        var searchQuery = jQuery(this).val();
        var postType = jQuery('.search-form input[name="post_type"]').data('post-type');
        var liveSearchbox = jQuery('.live-searchbox');

        var formhtm = jQuery('.search-form');

        if (searchQuery === '') {
            liveSearchbox.removeClass('search-mode');
            formhtm.removeClass('search-md');
            liveSearchbox.hide();
        } else {
            liveSearchbox.addClass('search-mode');
            formhtm.addClass('search-md');
            jQuery('.srtrigt-icon').html('<i class="fa fa-spinner fa-spin"></i>');
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'search_function',
                    search_query: searchQuery,
                    post_type: postType
                },
                success: function (data) {
                    jQuery('.search-results').html(data);
                    liveSearchbox.show();
                    jQuery('.srtrigt-icon').css({ display: "flex" });
                    jQuery('.srtrigt-icon').html('<i class="fa fa-close"></i>');
                }
            });
        }
    });

    jQuery(document).on('click', function (event) {
        var liveSearchbox = jQuery('.live-searchbox');

        if (liveSearchbox.hasClass('search-mode') && !jQuery(event.target).closest('.live-searchbox').length) {
            liveSearchbox.removeClass('search-mode');
            formhtm.removeClass('search-md');
            liveSearchbox.hide();
        }
    });
});


jQuery(function ($) {
    $('#search-form .search-box').keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
});


jQuery(".srtrigt-icon").click(function () {
    jQuery(".live-searchbox").hide();
    jQuery(".search-box").val("");
    jQuery('.search-form').removeClass('search-md');
});



