<?php get_template_part('templates/partials/_footer'); ?>
<?php wp_footer(); ?>
<?php
$user = wp_get_current_user();
$is_agent_tester = in_array( 'agent_tester', (array) $user->roles, true ) || current_user_can('administrator');
?>

<?php if( $is_agent_tester ) : ?>
<script defer src="https://cdn.customgpt.ai/js/chat.js"></script> <script defer> (function(){ function init(){ CustomGPT.init({ p_id:'98865', p_key:'f12d51cc482847f28a6333cf7f6a5c9d' }) } document.readyState === 'complete' ? init() : window.addEventListener('load', init); })(); </script> 
<!-- <script defer src="https://cdn.customgpt.ai/js/chat.js"></script> <script defer> (function(){ function init(){ CustomGPT.init({ p_id:'98043', p_key:'8c7e9ac540d9dd825d6cf4eab0ade038' }) } document.readyState === 'complete' ? init() : window.addEventListener('load', init); })(); </script>  -->
<?php else : ?>
<!-- <script defer src="https://cdn.customgpt.ai/js/chat.js"></script> <script defer> (function(){ function init(){ CustomGPT.init({ p_id:'97474', p_key:'b53f0fe49da7c1843edb69e542282c3d' }) } document.readyState === 'complete' ? init() : window.addEventListener('load', init); })(); </script>  -->
<?php endif; ?>


<script>
document.addEventListener('click', function (e) {
    const toggle = e.target.closest('.customgpt-toggle');
    if (!toggle) return;

    e.preventDefault();

    // const target = document.querySelector('#cgptcb-chat-circle');
    // if (target) target.click();

  if (document.body.classList.contains('portal-home-2')) {
    const target = document.querySelector('#customgpt-chat-1');
    if (!target) return;

    const header = document.querySelector('header');
    const offset = header ? header.offsetHeight + 20 : 100;
    const y = target.getBoundingClientRect().top + window.pageYOffset - offset;

    window.scrollTo({ top: y, behavior: 'smooth' });
  } else {
    const target = document.querySelector('#cgptcb-chat-circle');
    if (target) target.click();
  }
});
(function($) {
    var added = false;

    var tooltipHtml =
        '<div class="cgptcb-tooltip">' +
            '<span>AI assistant to source the insights you need</span>' +
            '<span class="cgptcb-tooltip-close">' +
                '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M9.33333 0.94L8.39333 0L4.66667 3.72667L0.94 0L0 0.94L3.72667 4.66667L0 8.39333L0.94 9.33333L4.66667 5.60667L8.39333 9.33333L9.33333 8.39333L5.60667 4.66667L9.33333 0.94Z" fill="white"/>' +
                '</svg>' +
            '</span>' +
            '<svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M6.92819 9L13.8564 0H-1.09673e-05L6.92819 9Z" fill="#222222"/>' +
            '</svg>' +
        '</div>';

    var tooltipInterval = setInterval(function() {
        var $body = $('#cgptcb-body');

        if ($body.length && !added) {
            added = true;
            $body.append(tooltipHtml);
            clearInterval(tooltipInterval);
        }
    }, 300);

    $(document).on('click', '.cgptcb-tooltip-close', function(e) {
        e.preventDefault();
        $('.cgptcb-tooltip').remove();
    });
})(jQuery);
</script>



</body>
</html>