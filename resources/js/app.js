// Fix: LiteSpeed/cPanel injects HTML into Livewire JSON responses.
// Strip injected HTML before/after JSON so Livewire can parse it.
(function() {
    var _f = window.fetch;
    window.fetch = function(url, opts) {
        if (typeof url === 'string' && url.indexOf('/livewire/update') !== -1) {
            return _f.apply(this, arguments).then(function(res) {
                return res.text().then(function(t) {
                    var i = t.indexOf('{');
                    if (i > 0) t = t.substring(i);
                    var j = t.lastIndexOf('}');
                    if (j >= 0 && j < t.length - 1) t = t.substring(0, j + 1);
                    return new Response(t, { status: res.status, statusText: res.statusText, headers: res.headers });
                });
            });
        }
        return _f.apply(this, arguments);
    };
})();

// jQuery, Bootstrap, and Swiper are loaded via CDN <script> tags in app-layout.blade.php.
// Do NOT import them here — they use CommonJS/UMD which breaks Vite's ESM build.

import './lazysize.min.js';
import './count-down.js';
import './wow.min.js';
import './multiple-modal.js';
import './main.js';
import './carousel.js';
import './home_page.js';
