<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Fix: LiteSpeed injects HTML into Livewire JSON responses --}}
<script>
    (function(){
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
</script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
