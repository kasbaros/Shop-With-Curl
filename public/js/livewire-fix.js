// Fix: LiteSpeed/cPanel injects HTML into Livewire JSON responses.
(function() {
    var _f = window.fetch;
    window.fetch = function(url, opts) {
        if (typeof url === 'string' && url.indexOf('/livewire/update') !== -1) {
            console.log('[LW-Fix] Intercepting request to:', url);
            return _f.apply(this, arguments).then(function(res) {
                console.log('[LW-Fix] Response status:', res.status);
                return res.text().then(function(t) {
                    console.log('[LW-Fix] Raw response length:', t.length);
                    console.log('[LW-Fix] First 100 chars:', t.substring(0, 100));
                    console.log('[LW-Fix] Last 100 chars:', t.substring(t.length - 100));

                    var cleaned = t;
                    var i = cleaned.indexOf('{');
                    if (i > 0) {
                        console.log('[LW-Fix] Stripping', i, 'chars from start');
                        cleaned = cleaned.substring(i);
                    }
                    var j = cleaned.lastIndexOf('}');
                    if (j >= 0 && j < cleaned.length - 1) {
                        console.log('[LW-Fix] Stripping', (cleaned.length - 1 - j), 'chars from end');
                        cleaned = cleaned.substring(0, j + 1);
                    }

                    try {
                        JSON.parse(cleaned);
                        console.log('[LW-Fix] JSON.parse SUCCESS after cleaning');
                    } catch(e) {
                        console.error('[LW-Fix] JSON.parse FAILED even after cleaning:', e.message);
                        console.log('[LW-Fix] Cleaned first 200:', cleaned.substring(0, 200));
                        console.log('[LW-Fix] Cleaned last 200:', cleaned.substring(cleaned.length - 200));
                    }

                    return new Response(cleaned, { status: res.status, statusText: res.statusText, headers: res.headers });
                });
            });
        }
        return _f.apply(this, arguments);
    };
})();
