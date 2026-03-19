// Fix: LiteSpeed/cPanel injects HTML into Livewire JSON responses.
// Approach: Patch JSON.parse to strip HTML when input starts with '<'
// This is safe because '<' is never valid JSON, so it would fail anyway.
(function() {
    var _parse = JSON.parse;
    JSON.parse = function(text) {
        if (typeof text === 'string' && text.charAt(0) === '<') {
            var i = text.indexOf('{');
            if (i > 0) text = text.substring(i);
            var j = text.lastIndexOf('}');
            if (j >= 0 && j < text.length - 1) text = text.substring(0, j + 1);
        }
        return _parse.call(this, text);
    };
})();
