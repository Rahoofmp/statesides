/*!function(e,n){"function"==typeof define&&define.amd?define(["exports"],n):n("undefined"!=typeof exports?exports:e.dragscroll={})}(this,function(e){var n=window,o=document,t="mousemove",l="mouseup",i="mousedown",r="EventListener",c="add"+r,f="remove"+r,m=[],s=function(e,r){for(e=0;e<m.length;)r=m[e++],r[f](i,r.md,0),n[f](l,r.mu,0),n[f](t,r.mm,0);for(m=o.getElementsByClassName("dragscroll"),e=0;e<m.length;)!function(e,o,r,f){e[c](i,e.md=function(e){f=1,o=e.clientX,r=e.clientY,e.preventDefault(),e.stopPropagation()},0),n[c](l,e.mu=function(){f=0},0),n[c](t,e.mm=function(n,t){t=e.scroller||e,f&&(t.scrollLeft-=-o+(o=n.clientX),t.scrollTop-=-r+(r=n.clientY))},0)}(m[e++])};"complete"==o.readyState?s():n[c]("load",s,0),e.reset=s});
*
 * @fileoverview dragscroll - scroll area by dragging
 * @version 0.0.6
 * 
 * @license MIT, see http://github.com/asvd/intence
 * @copyright 2015 asvd <heliosframework@gmail.com> 
 */

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['exports'], factory);
    } else if (typeof exports !== 'undefined') {
        factory(exports);
    } else {
        factory((root.dragscroll = {}));
    }
}(this, function (exports) {
    var _window = window;
    var _document = document;
    var mousemove = 'mousemove';
    var mouseup = 'mouseup';
    var mousedown = 'mousedown';
    var EventListener = 'EventListener';
    var addEventListener = 'add'+EventListener;
    var removeEventListener = 'remove'+EventListener;

    var dragged = [];
    var reset = function(i, el) {
        for (i = 0; i < dragged.length;) {
            el = dragged[i++];
            el = el.container || el;
            el[removeEventListener](mousedown, el.md, 0);
            _window[removeEventListener](mouseup, el.mu, 0);
            _window[removeEventListener](mousemove, el.mm, 0);
        }

        // cloning into array since HTMLCollection is updated dynamically
        dragged = [].slice.call(_document.getElementsByClassName('dragscroll'));
        for (i = 0; i < dragged.length;) {
            (function(el, lastClientX, lastClientY, pushed, scroller, cont){
                (cont = el.container || el)[addEventListener](
                    mousedown,
                    cont.md = function(e) {
                        if (!el.hasAttribute('nochilddrag') ||
                            _document.elementFromPoint(
                                e.pageX, e.pageY
                            ) == cont
                        ) {
                            pushed = 1;
                            lastClientX = e.clientX;
                            lastClientY = e.clientY;

                            e.preventDefault();
                        }
                    }, 0
                );

                _window[addEventListener](
                    mouseup, cont.mu = function() {pushed = 0;}, 0
                );

                _window[addEventListener](
                    mousemove,
                    cont.mm = function(e) {
                        if (pushed) {
                             (scroller = el.scroller||el).scrollLeft -=
                                 (- lastClientX + (lastClientX=e.clientX));
                             scroller.scrollTop -=
                                 (- lastClientY + (lastClientY=e.clientY));
                        }
                    }, 0
                );
             })(dragged[i++]);
        }
    }

      
    if (_document.readyState == 'complete') {
        reset();
    } else {
        _window[addEventListener]('load', reset, 0);
    }

    exports.reset = reset;
}));

