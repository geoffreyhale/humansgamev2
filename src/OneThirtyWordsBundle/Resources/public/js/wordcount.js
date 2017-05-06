$(function() {
    Wordcount.init(".count_this", ".wordcount");
});

var Wordcount = (function() {
    let text_selector;
    let count_selector;
    let offset;

    function init(_text_selector, _count_selector, _offset) {
        text_selector = _text_selector;
        count_selector = _count_selector;
        offset = _offset ? parseInt(_offset) : 0;
        updateDisplay();
        $(text_selector).keyup(function() { updateDisplay() });
    }

    function getText() {
        let el = $(text_selector);
        if (el[0].value !== undefined) {
            return el.val();
        } else {
            return el.text();
        }
    }

    function getWordCount() {
        let text = getText();
        let text_wordcount = text ? text.split(/[^\s\d_\+\-\.,!@#\$%\^&\*\(\);\\\/\|<>"]+/g).length - 1 : 0;
        let final_wordcount = text_wordcount + offset;
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