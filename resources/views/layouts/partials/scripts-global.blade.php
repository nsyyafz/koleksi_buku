<!-- jQuery dari CDN (pasti jalan) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4 Bundle dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Purple Admin JS (skip yang error) -->
<script>
// Cek file mana yang ada
var scripts = [
    '{{ asset("purple/assets/vendors/js/vendor.bundle.base.js") }}',
    '{{ asset("purple/assets/js/off-canvas.js") }}',
    '{{ asset("purple/assets/js/misc.js") }}'
];

scripts.forEach(function(src) {
    var script = document.createElement('script');
    script.src = src;
    script.onerror = function() {
        console.warn('⚠️ Skip file:', src);
    };
    document.head.appendChild(script);
});
</script>

<!-- Manual Collapse untuk Dropdown Menu -->
<script>
$(document).ready(function() {
    console.log('✅ jQuery version:', jQuery.fn.jquery);
    console.log('✅ Bootstrap loaded');
    
    // INIT SEMUA COLLAPSE MENU
    $('[data-toggle="collapse"]').each(function() {
        var $trigger = $(this);
        var target = $trigger.attr('href') || $trigger.data('target');
        
        $trigger.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle collapse
            $(target).collapse('toggle');
            
            // Toggle aria-expanded
            var isExpanded = $trigger.attr('aria-expanded') === 'true';
            $trigger.attr('aria-expanded', !isExpanded);
            
            console.log('🔧 Menu toggled:', target, 'Expanded:', !isExpanded);
        });
    });
    
    // SIDEBAR COLLAPSE (untuk mobile)
    $('.navbar-toggler').on('click', function() {
        $('.sidebar-offcanvas').toggleClass('active');
    });
    
    console.log('✅ Dropdown menu initialized');
});
</script>