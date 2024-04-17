jQuery(document).ready(function($) {
    // Show the active tab on page load
    var activeTab = $('.nav-tab-active').attr('href');
    $(activeTab).fadeIn();

    // Switch tabs
    $('.nav-tab-wrapper a').click(function(e) {
        e.preventDefault();

        // Hide all sections
        $('form > div').hide();

        // Show selected section
        var target = $(this).attr('href');
        $(target).fadeIn();

        // Update active tab
        $('.nav-tab-active').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
    });
});
