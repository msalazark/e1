/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
$jqST.cookiesef = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
path=';path=/';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = $jqST.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

$jqST.removeCookie = function (key, options) {
    if ($jqST.cookiesef(key, options) === undefined) { // this line is the fix
        return false;
    }

    // Must not alter options, thus extending a fresh object...
    $jqST.cookiesef(key, '', $.extend({}, options, { expires: -1 }));
    return !$.cookiesef(key);
};

$jqST.placeholdersTranslate = function () {

    // Find all placeholders
    var placeholders = document.querySelectorAll('input[placeholder]');

    if (placeholders.length) {
         // convert to array
        placeholders = Array.prototype.slice.call(placeholders);

        // copy placeholder text to a hidden div
//        var div = $jqST('<div id="placeholders" style="display:none;"></div>');
        var div = $jqST('<div id="placeholders" ></div>');

        placeholders.forEach(function(input){

          var text = input.placeholder;
          div.append('<div>' + text + '</div>');    
        });

        $jqST('body').append(div);

        // save the first placeholder in a closure
        var originalPH = placeholders[0].placeholder;

        // check for changes and update as needed
        placeholder_translate_intervale = setInterval(function(){
          if (!isTranslated()) {
            updatePlaceholders();
            originalPH = placeholders[0].placeholder;
            //clearInterval(placeholder_translate_intervale);
          } 
        }, 500);

        // hoisted ---------------------------
        function isTranslated() { // true if the text has been translated
           var currentPH = $jqST($jqST('#placeholders > div')[0]).text();
           return (originalPH == currentPH);
        }

        function updatePlaceholders() {
          $jqST('#placeholders > div').each(function(i, div){
            placeholders[i].placeholder = $jqST(div).text();
          });
        }
    }
}; 