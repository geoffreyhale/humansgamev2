$(function() {
    Wordcount.init("#form_body", "#wordcount");
});

var Wordcount = (function() {
    var textarea_selector,
        count_selector,
        offset;

    function init(_textarea_selector, _count_selector, _offset) {
        textarea_selector = _textarea_selector;
        count_selector = _count_selector;
        offset = _offset ? parseInt(_offset) : 0;
        updateDisplay();
        $(textarea_selector).keyup(function() { updateDisplay() });
    }

    function getWordCount() {
        var text = $(textarea_selector).val();
        var text_wordcount = text ? text.split(/[^\s\d_\+\-\.,!@#\$%\^&\*\(\);\\\/\|<>"]+/g).length - 1 : 0;
        var final_wordcount = text_wordcount + offset;
        return final_wordcount;
    }

    function updateDisplay() {
        $(count_selector).html(getWordCount());
    }

    return {
        init: init,
        updateCount: updateDisplay
    };

})();