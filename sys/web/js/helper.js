document.addEventListener('DOMContentLoaded', function () {
    helper_moment();
    helper_numeral_init();
    helper_numeral();
    helper_imask();
    helper_zero2null();
    helper_scrollEndless_init();
});

// async function () { 
// const response = await helper_getData(url, data);
// const res = await response.json();
async function helper_getData(url = '', data = {}) {
    url += '?' + (new URLSearchParams(data)).toString();
    const response = await fetch(url, {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        mode: 'no-cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        //headers: {
        //'Content-Type': 'application/json'
        //'Content-Type': 'application/x-www-form-urlencoded'
        //},
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *client        
    });
    return response;
}

// async function () { 
// const response = await helper_postData(url, data);
// const res = await response.json();
async function helper_postData(url = '', data = {}) {
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'no-cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            //'Content-Type': 'application/json'
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *client
        body: data = Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&')
        //body: JSON.stringify( data )    if 'Content-Type': 'application/json'
    });
    return response;
    //return await response.json(); // parses JSON response into native JavaScript objects
}

/*
 Month	M	1 2 ... 11 12
 Mo	1st 2nd ... 11th 12th
 MM	01 02 ... 11 12
 MMM	Jan Feb ... Nov Dec
 MMMM	January February ... November December
 Quarter	Q	1 2 3 4
 Qo	1st 2nd 3rd 4th
 Day of Month	D	1 2 ... 30 31
 Do	1st 2nd ... 30th 31st
 DD	01 02 ... 30 31
 Day of Year	DDD	1 2 ... 364 365
 DDDo	1st 2nd ... 364th 365th
 DDDD	001 002 ... 364 365
 Day of Week	d	0 1 ... 5 6
 do	0th 1st ... 5th 6th
 dd	Su Mo ... Fr Sa
 ddd	Sun Mon ... Fri Sat
 dddd	Sunday Monday ... Friday Saturday
 Day of Week (Locale)	e	0 1 ... 5 6
 Day of Week (ISO)	E	1 2 ... 6 7
 Week of Year	w	1 2 ... 52 53
 wo	1st 2nd ... 52nd 53rd
 ww	01 02 ... 52 53
 Week of Year (ISO)	W	1 2 ... 52 53
 Wo	1st 2nd ... 52nd 53rd
 WW	01 02 ... 52 53
 Year	YY	70 71 ... 29 30
 YYYY	1970 1971 ... 2029 2030                       
 Y	1970 1971 ... 9999 +10000 +10001
 Note: This complies with the ISO 8601 standard for dates past the year 9999
 Week Year	gg	70 71 ... 29 30
 gggg	1970 1971 ... 2029 2030
 Week Year (ISO)	GG	70 71 ... 29 30
 GGGG	1970 1971 ... 2029 2030
 AM/PM	A	AM PM
 a	am pm
 Hour	H	0 1 ... 22 23
 HH	00 01 ... 22 23
 h	1 2 ... 11 12
 hh	01 02 ... 11 12
 k	1 2 ... 23 24
 kk	01 02 ... 23 24
 Minute	m	0 1 ... 58 59
 mm	00 01 ... 58 59
 Second	s	0 1 ... 58 59
 ss	00 01 ... 58 59
 Fractional Second	S	0 1 ... 8 9
 SS	00 01 ... 98 99
 SSS	000 001 ... 998 999
 SSSS ... SSSSSSSSS	000[0..] 001[0..] ... 998[0..] 999[0..]
 Time Zone	z or zz	EST CST ... MST PST
 Note: as of 1.6.0, the z/zz format tokens have been deprecated from plain moment objects.
 Read more about it here. However, they do work if you are using a specific time zone with the moment-timezone addon.
 Z	-07:00 -06:00 ... +06:00 +07:00
 ZZ	-0700 -0600 ... +0600 +0700
 Unix Timestamp	X	1360013296
 Unix Millisecond Timestamp	x	1360013296123
 */

function helper_moment() {
    moment.locale('ru');
    $("[moment]").each((i, elem) => {
        const jqElem = $(elem);
        const tpl = jqElem.attr('moment');
        let m = moment(jqElem.text());
        if (!m.isValid()) {
            m = moment.unix(jqElem.text());
        }
        jqElem.text(m.format(tpl));
        jqElem.removeAttr('moment');
    });
}

/*
 var string = numeral(1000).format('0,0');
 // '1,000'
 Numbers
 Number	Format	String
 10000	'0,0.0000'	10,000.0000
 10000.23	'0,0'	10,000
 10000.23	'+0,0'	+10,000
 -10000	'0,0.0'	-10,000.0
 10000.1234	'0.000'	10000.123
 100.1234	'00000'	00100
 1000.1234	'000000,0'	001,000
 10	'000.00'	010.00
 10000.1234	'0[.]00000'	10000.12340
 -10000	'(0,0.0000)'	(10,000.0000)
 -0.23	'.00'	-.23
 -0.23	'(.00)'	(.23)
 0.23	'0.00000'	0.23000
 0.23	'0.0[0000]'	0.23
 1230974	'0.0a'	1.2m
 1460	'0 a'	1 k
 -104000	'0a'	-104k
 1	'0o'	1st
 100	'0o'	100th
 Currency
 Number	Format	String
 1000.234	'$0,0.00'	$1,000.23
 1000.2	'0,0[.]00 $'	1,000.20 $
 1001	'$ 0,0[.]00'	$ 1,001
 -1000.234	'($0,0)'	($1,000)
 -1000.234	'$0.00'	-$1000.23
 **/


function helper_numeral_init() {
    (function (global, factory) {
        if (typeof define === 'function' && define.amd) {
            define(['../numeral'], factory);
        } else if (typeof module === 'object' && module.exports) {
            factory(require('../numeral'));
        } else {
            factory(global.numeral);
        }
    }(this, function (numeral) {
        numeral.register('locale', 'ru', {
            delimiters: {
                thousands: ' ',
                decimal: '.'
            },
            abbreviations: {
                thousand: 'тыс.',
                million: 'млн.',
                billion: 'млрд.',
                trillion: 'трлн.'
            },
            ordinal: function () {
                // not ideal, but since in Russian it can taken on
                // different forms (masculine, feminine, neuter)
                // this is all we can do
                return '.';
            },
            currency: {
                symbol: 'р.'
            }
        });

        numeral.register('format', 'my10k', {
            regexps: {
                format: /()/
            },
            format: function (value, format, roundingFunction) {
                if (value > 9999) {
                    output = numeral._.numberToFormat(value, '0,0', roundingFunction);
                } else {
                    output = value;
                }
                return output;
            }
        });

    }));
    numeral.locale('ru');
}

function helper_numeral() {
    $("[numeral]").each((i, elem) => {
        const jqElem = $(elem);
        const tpl = jqElem.attr('numeral');
        const val = parseFloat(jqElem.text()) || 0;
        jqElem.text(numeral(val).format(tpl));
        jqElem.removeAttr('numeral');
    });
}

function helper_imask() {
    const els = Array.from(document.querySelectorAll('[data-component="mobilePhone"]'));
    for (el of els) {
        IMask(
            el,
            {
                mask: '+{7}(000)000-00-00',
                lazy: false // make placeholder always visible 
            }
        );
    }
}

function helper_highlight_text(str, objs, style) {
    if (str.length <= 2) {
        return;
    }
    const words = str.split(' ');
    for (word of words) {
        if (word.length > 2) {
            helper_highlight_word(word, objs, style);
        }
    };
}

function helper_highlight_word(word, objs, style) {
    word = word.toLowerCase();
    length = word.length;
    for (elem of objs) {
        const text = elem.innerHTML;
        const textL = text.toLowerCase();
        const textStart = textL.indexOf(word);
        const textEnd = textStart + length;

        const htmlR = text.substring(0, textStart)
            + '<span style="' + style + '">'
            + text.substring(textStart, textEnd)
            + '</span>' + text.substring(textStart + length);

        if (textStart !== -1) {
            elem.innerHTML = htmlR;
        }
    };
}

function helper_hide(el) {
    el.classList.add("d-none");
}

function helper_show(el) {
    el.classList.remove("d-none");
}

function helper_removeParamFromUrl(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function helper_zero2null() {
    $('input[zero2null]').each((i, elem) => {
        const v = parseInt($(elem).val());
        if (v === 0) {
            $(elem).val('');
        }
    });
}

let helper_scrollEndless__functionsArray = [];
function helper_scrollEndless_functionAdd(funcVar) { //add a function for WindowScrollEnd event
    helper_scrollEndless__functionsArray.push(funcVar);
}
function helper_scrollEndless_init() {
    document.addEventListener("scroll", helper_scrollEndless_process);

    let helper_scrollEndless__timeoutId;
    function helper_scrollEndless_process() {
        if (helper_scrollEndless__functionsArray.length === 0) {
            return true;
        }

        if (helper_scrollEndless__timeoutId) {
            clearTimeout(helper_scrollEndless__timeoutId);
        }
        helper_scrollEndless__timeoutId = setTimeout(() => {
            const scrollTop = $(window).scrollTop();
            const documentHeight = $(document).height();
            const windowInnerHeight = window.visualViewport.height; // window.innerHeight;
            const delta = Math.abs(documentHeight - windowInnerHeight - scrollTop);
            if (delta < 50) {
                //call all functions from array                
                helper_scrollEndless__functionsArray.forEach((elem) => elem());
            }
        }, 50);
    }
}

