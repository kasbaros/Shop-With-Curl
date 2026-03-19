// Fix: LiteSpeed/cPanel injects HTML into Livewire JSON responses.
// This must load as a regular <script> (not module) before Livewire.
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
