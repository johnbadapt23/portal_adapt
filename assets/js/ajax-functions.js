jQuery(function($){

    let loading = false;

    function loadPartners(reset = false) {
        if (loading) return;
        loading = true;

        let page = reset ? 1 : parseInt($('#partners-load-more').data('page'));
        let partnerType = $('.filter-dropdown[data-filter="expertise"]').data('partner-type');
        let expertise = $('.filter-dropdown[data-filter="expertise"] .filter-button.active').data('value');
        let industry = $('.filter-dropdown[data-filter="industry"] .filter-button.active').data('value');

        $.post(partners_ajax.ajax_url, {
            action: 'load_partners',
            page: page,
            partner_type: partnerType,
            expertise: expertise,
            industry: industry
        }, function(res){
            if (reset) {
                $('#partners-container').html(res.html);
            } else {
                $('#partners-container').append(res.html);
            }

            $('#partners-load-more').data('page', page + 1);

            if (page >= res.max_pages) {
                $('#partners-load-more').hide();
            } else {
                $('#partners-load-more').show();
            }

            loading = false;
        });
    }

    // Initial load
    loadPartners(true);

    // Load more
    $('#partners-load-more').on('click', function(e){
        e.preventDefault();
        loadPartners(false);
    });

    // Filters
    $('.filter-button').on('click', function(e){
        e.preventDefault();
        $(this).closest('.dropdown-list').find('.filter-button').removeClass('active');
        $(this).addClass('active');
        loadPartners(true);
    });

});