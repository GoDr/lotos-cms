/**
 * Fullajax = AJAX & AHAH library
 * http://www.fullajax.ru
 * SiRusAjaX - SRAX v1.0.3 build 1
 * Copyright(c) 2007-2009, Ruslan Sinitskiy.
 * http://fullajax.ru/#:license
 **/
if (!window.SRAX || window.SRAX.TYPE != "full") {
    function log() {
        SRAX.debug("log", arguments)
    }

    function info() {
        SRAX.debug("info", arguments)
    }

    function error() {
        SRAX.debug("error", arguments)
    }

    function warn() {
        SRAX.debug("warn", arguments)
    }

    function id(A) {
        return SRAX.get(A)
    }

    function back(A) {
        SRAX.Html.thread[A].go(-1)
    }

    function forward(A) {
        SRAX.Html.thread[A].go(1)
    }

    function go(A, B) {
        SRAX.Html.thread[B].go(A)
    }

    String.prototype.trim = function () {
        return this.replace(/\s*((\S+\s*)*)/, "$1").replace(/((\s*\S+)*)\s*/, "$1")
    };
    String.prototype.replaceAll = function (B, A) {
        return this.split(B).join(A)
    };
    String.prototype.endWith = function (B, A) {
        return A ? (this.toLowerCase().substring(this.length - B.length, this.length) == B.toLowerCase()) : (this.substring(this.length - B.length, this.length) == B)
    };
    String.prototype.startWith = function (B, A) {
        return A ? (this.toLowerCase().substring(0, B.length) == B.toLowerCase()) : (this.substring(0, B.length) == B)
    };
    function abort(A) {
        if (SRAX.Html.thread[A]) {
            SRAX.Html.thread[A].abort()
        }
    }

    function hax(C, B) {
        if (!B) {
            B = {}
        }
        if (typeof C == "string") {
            B.url = C
        } else {
            B = C
        }
        if (B.nohistory == null) {
            B.nohistory = B.noHistory
        }
        var A = SRAX.Html.thread[B.id] ? SRAX.Html.thread[B.id] : new SRAX.HTMLThread(B.id);
        A.setOptions(B, 1);
        if (SRAX.Html.ASYNCHRONOUS) {
            A.request()
        } else {
            SRAX.Html.storage.push(A.id);
            if (SRAX.Html.storage.length == 1) {
                A.request()
            }
        }
        return A
    }

    function get(B, D, C, A, E) {
        if (typeof D == "object") {
            return hax(B, D)
        }
        return hax(B, {id:D, form:C, cb:A, cbo:E})
    }

    function post(B, D, C, A, E) {
        if (typeof D == "object") {
            D.method = "post";
            return hax(B, D)
        }
        return hax(B, {method:"post", id:D, form:C, cb:A, cbo:E})
    }

    function dax(C, B) {
        if (!B) {
            B = {}
        }
        if (typeof C == "string") {
            B.url = C
        } else {
            B = C
        }
        if (!B.id) {
            B.id = "undefined"
        }
        var A = SRAX.Data.thread[B.id] ? SRAX.Data.thread[B.id] : new SRAX.DATAThread(B.id);
        A.setOptions(B, 1);
        A.request();
        return A
    }

    function abortData(A) {
        if (SRAX.Data.thread[A]) {
            SRAX.Data.thread[A].abort()
        }
    }

    function getData(B, A, F, D, E, C) {
        return dax(B, {cb:A, id:F, cbo:D, anticache:E, destroy:C})
    }

    function postData(B, E, A, G, D, F, C) {
        return dax(B, {method:"post", params:E, cb:A, id:G, cbo:D, anticache:F, destroy:C})
    }

    if (!window.SRAX) {
        SRAX = {}
    }
    SRAX.extend = function (B, E, D) {
        var A = !D;
        for (var C in E) {
            if (A || !B.hasOwnProperty(C)) {
                B[C] = E[C]
            }
        }
        return B
    };
    (function (B) {
        B.extend(B, {version:"SRAX v1.0.3 build 1", TYPE:"full", Default:{prefix:"ax", sprt:":", loader:"loading", loader2:"loading2", loaderSufix:"_loading", DEBUG_AJAX:0, DEBUG_SCRIPT:0, DEBUG_LINK:0, DEBUG_STYLE:0, USE_FILTER_WRAP:1, NO_HISTORY:0, USE_HISTORY_CACHE:1, LENGTH_HISTORY_CACHE:100, LINK_REPEAT:0, USE_SCRIPT_CACHE:1, SCRIPT_SRC_REPEAT_APPLY:1, SCRIPT_NOAX:0, RELATIVE_CORRECTION:0, OVERWRITE:0, model2Marker:{ax:"<!-- :ax:", begin:":begin: //-->", end:":end: //-->"}, HAX_AUTO_DESTROY:0, HAX_ANTICACHE:0, DAX_AUTO_DESTROY:0, DAX_ANTICACHE:0, CHARSET:"UTF-8"}, debug:function (J, G) {
            var K = window.console;
            if (K && K[J]) {
                try {
                    K[J].apply(K, G)
                } catch (I) {
                    K[J](G.length == 1 ? G[0] : G)
                }
            } else {
                if (window.runtime) {
                    var F = [J + ": " + G[0]];
                    for (var H = 1, D = G.length; H < D; H++) {
                        F.push(G[H])
                    }
                    runtime.trace(F)
                }
            }
        }, getTime:function () {
            return new Date().getTime()
        }, LIST_NO_CACHE_SCRIPTS:[], LIST_NO_LOAD_SCRIPTS:[], LIST_NO_LOAD_LINKS:[], init:function () {
            var D = navigator.userAgent.toLowerCase();
            B.browser = {version:(D.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1], webkit:/webkit/.test(D), safari:/safari/.test(D), opera:/opera/.test(D), msie:/msie/.test(D) && !/opera/.test(D), mozilla:/mozilla/.test(D) && !/(compatible|webkit)/.test(D), air:/adobeair/.test(D)};
            var F = "addEventsListener";
            B[F](B.HTMLThread);
            B[F](B.History);
            B[F](B.DATAThread);
            F = "addContainerListener";
            B[F](B.Html);
            B[F](B.Data);
            B.LoadUnloadContainer = {};
            B.scriptsCache = [
                [],
                []
            ];
            B.scriptsTemp = [
                [],
                []
            ];
            B.linksCache = [];
            B.History.prefixListener.ax = B.go2Hax;
            B.readyHndlr = [];
            B.onReady(function () {
                if (C.USE_FILTER_WRAP) {
                    B.Filter.wrap()
                }
                setInterval(B.History.check, 200);
                B.initCPLNLS();
                B.initCPLNLL();
                if (B.browser.opera) {
                    var G = document.createElement("img");
                    G.setAttribute("style", "position:absolute;left:-1px;top:-1px;opacity:0;width:0px;height:0px");
                    G.setAttribute("alt", "");
                    G.setAttribute("src", 'javascript:location.href="javascript:SRAX.xssLoading=0;SRAX.History.check()"');
                    document.body.appendChild(G)
                }
                B.Include.parse()
            });
            document._write = document.write;
            document._writeln = document.writeln;
            B.write = function (G) {
                document._write(G)
            };
            B.writeln = function (G) {
                document._writeln(G)
            }
        }, initOnReady:function () {
            if (B.isReadyInited) {
                return
            }
            B.isReadyInited = 1;
            if (B.browser.mozilla || B.browser.opera) {
                B.addEvent(document, "DOMContentLoaded", B.ready)
            } else {
                if (B.browser.msie) {
                    (function () {
                        try {
                            document.documentElement.doScroll("left")
                        } catch (D) {
                            setTimeout(arguments.callee, 50);
                            return
                        }
                        B.ready()
                    })()
                } else {
                    if (B.browser.safari) {
                        B.safariTimer = setInterval(function () {
                            if (document.readyState == "loaded" || document.readyState == "complete") {
                                clearInterval(B.safariTimer);
                                B.safariTimer = null;
                                B.ready()
                            }
                        }, 10)
                    }
                }
            }
            B.addEvent(window, "load", B.ready)
        }, onReady:function (D) {
            if (B.isReady) {
                D()
            } else {
                B.readyHndlr.push(D);
                B.initOnReady()
            }
        }, ready:function () {
            if (B.isReady) {
                return
            }
            B.isReady = 1;
            for (var G = 0, D = B.readyHndlr.length; G < D; G++) {
                try {
                    B.readyHndlr[G]()
                } catch (F) {
                    error(F)
                }
            }
            B.readyHndlr = null
        }, addEvent:function (G, D, F) {
            if (G.attachEvent) {
                G.attachEvent("on" + D, F)
            } else {
                G.addEventListener(D, F, false)
            }
        }, delEvent:function (G, D, F) {
            if (G.detachEvent) {
                G.detachEvent("on" + D, F)
            } else {
                G.removeEventListener(D, F, false)
            }
        }, get:function (D) {
            return typeof D == "string" ? document.getElementById(D) : D
        }, clearLNLS:function () {
            B.LIST_NO_LOAD_SCRIPTS = []
        }, initCPLNLS:function (G) {
            if (G) {
                B.clearLNLS()
            }
            var I = document.getElementsByTagName("head")[0], F = I.getElementsByTagName("script");
            for (var H = 0, D = F.length; H < D; H++) {
                if (!F[H].src) {
                    continue
                }
                B.LIST_NO_LOAD_SCRIPTS.push(F[H].src)
            }
        }, clearLNLL:function () {
            B.LIST_NO_LOAD_LINKS = []
        }, initCPLNLL:function (F) {
            if (F) {
                B.clearLNLL()
            }
            var I = document.getElementsByTagName("head")[0], G = I.getElementsByTagName("link");
            for (var H = 0, D = G.length; H < D; H++) {
                if (!G[H].href) {
                    continue
                }
                B.LIST_NO_LOAD_LINKS.push(G[H].href)
            }
        }, linkEqual:{}, replaceLinkEqual:function (F, D) {
            var I = "replaceAll", H = B.linkEqual;
            if (!D) {
                F = F[I]("?", "[~q~]")
            }
            for (var G in H) {
                F = D ? F[I](H[G], G) : F[I](G, H[G])
            }
            if (D) {
                F = F[I]("[~q~]", "?")
            }
            return F
        }, Model2Blocks:{}, IE_XHR_ENGINE:["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"], getXHR:function () {
            if (window.XMLHttpRequest && !(window.ActiveXObject && location.protocol == "file:")) {
                return new XMLHttpRequest()
            } else {
                if (window.ActiveXObject) {
                    for (var D = 0; D < B.IE_XHR_ENGINE.length; D++) {
                        try {
                            return new ActiveXObject(B.IE_XHR_ENGINE[D])
                        } catch (F) {
                        }
                    }
                }
            }
        }, delHost:function (D) {
            if (D && D.startWith(B.host)) {
                D = D.replace(B.host, "")
            }
            return D
        }, host:location.protocol + "//" + location.host, DaxPreprocessor:function (D) {
        }, HtmlPreprocessor:function (D) {
        }, DATAThread:function (J) {
            var H, G, I = this, F = this.options = {};
            this.inprocess = 0;
            this.id = J;
            B.Data.thread[J] = this;
            B.Data.register(this);
            this.repeat = function (K) {
                F.params = K;
                I.request()
            };
            this.setOptions = function (L, K) {
                if (!L.url) {
                    L.url = L.src
                }
                if (!L.cb) {
                    L.cb = L.callback
                }
                if (L.cbo == null) {
                    L.cbo = L.callbackOps
                }
                if (L.anticache == null) {
                    L.anticache = L.nocache
                }
                if (K) {
                    F = {}
                }
                B.extend(F, L);
                if (F.async == null) {
                    F.async = true
                }
                F.url = B.delHost(F.url);
                this.options = F
            };
            this.getOptions = function () {
                return F
            };
            function D(N) {
                if (!N || !N.readyState) {
                    N = H
                }
                try {
                    if (N.readyState == 4) {
                        I.inprocess = 0;
                        B.showLoading(I.inprocess, I.getLoader());
                        var K = N.isAbort ? -1 : N.status, Q = (K >= 200 && K < 300) || K == 304 || (K == 0 && location.protocol == "file:"), P = N.responseText, L = N.responseXML, O = {xhr:N, url:F.url, id:J, status:K, success:Q, cbo:F.cbo, callbackOps:F.cbo, options:F, text:P, xml:L, thread:I, responseText:P, responseXML:L, time:B.getTime() - G};
                        I.fireEvent("response", O);
                        if (K > -1 && B.DaxPreprocessor(O) !== false && F.cb) {
                            F.cb(O, J, Q, F.cbo);
                            if (C.DEBUG_AJAX) {
                                log("callback id:" + J)
                            }
                        }
                        if ((F.destroy != null) ? F.destroy : C.DAX_AUTO_DESTROY) {
                            I.destroy()
                        }
                    }
                } catch (M) {
                    error(M);
                    I.fireEvent("exception", {xhr:N, url:F.url, id:J, exception:M, options:F});
                    I.inprocess = 0;
                    B.showLoading(I.inprocess, I.getLoader());
                    if ((F.destroy != null) ? F.destroy : C.DAX_AUTO_DESTROY) {
                        I.destroy()
                    }
                }
            }

            this.isProcess = function () {
                return I.inprocess
            };
            this.request = function () {
                var L = F.method ? F.method : (F.form ? F.form.method : "get"), R = (L && L.toLowerCase() == "post") ? "post" : "get";
                try {
                    var O = {url:F.url, id:J, options:F, xhr:I};
                    if (I.fireEvent("beforerequest", O) !== false) {
                        G = B.getTime();
                        var K = B.createQuery(F.form);
                        if (F.params) {
                            if (K != "" && !F.params.startWith("&")) {
                                K += "&"
                            }
                            K += F.params
                        }
                        if (R != "post" && K != "") {
                            if (F.url.indexOf("?") == -1) {
                                F.url += "?" + K
                            } else {
                                F.url += ((F.url.endWith("?") || F.url.endWith("&")) ? "" : "&") + K
                            }
                        }
                        if (I.inprocess) {
                            I.abort()
                        }
                        I.inprocess = 1;
                        if (F.text || F.xml) {
                            D({readyState:4, status:F.status == null ? 200 : F.status, responseText:F.text, responseXML:F.xml});
                            F.text = F.xml = null
                        } else {
                            if (!H) {
                                H = B.getXHR()
                            }
                            try {
                                H.onprogress = function (S) {
                                    I.fireEvent("progress", {id:J, xhr:I, event:S, position:S.position, total:S.totalSize, percent:Math.round(100 * S.position / S.totalSize)})
                                }
                            } catch (Q) {
                            }
                            var N = (B.browser.msie && location.protocol == "file:" && F.url.startWith("/") ? "file://" : "") + F.url;
                            if (F.user) {
                                H.open(R.toUpperCase(), N, F.async, F.user, F.pswd)
                            } else {
                                H.open(R.toUpperCase(), N, F.async)
                            }
                            H.onreadystatechange = F.async ? D : function () {
                            };
                            var M = "setRequestHeader";
                            H[M]("AJAX_ENGINE", "Fullajax");
                            if (F.anticache != null ? F.anticache : C.DAX_ANTICACHE) {
                                H[M]("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT")
                            }
                            H[M]("HTTP_X_REQUESTED_WITH", "XMLHttpRequest");
                            if (F.headers) {
                                for (var P in F.headers) {
                                    H[M](P, F.headers[P])
                                }
                            }
                            if (R == "post") {
                                H[M]("Content-Type", "application/x-www-form-urlencoded; Charset=" + C.CHARSET)
                            }
                            B.showLoading(I.inprocess, I.getLoader());
                            H.send((R == "post") ? K : null);
                            if (!F.async) {
                                D()
                            }
                        }
                        if (C.DEBUG_AJAX) {
                            log(R + " " + F.url + " params:" + K + " id:" + J)
                        }
                        I.fireEvent("afterrequest", O)
                    }
                } catch (Q) {
                    I.abort();
                    error(Q);
                    throw Q
                }
            };
            this.getLoader = function () {
                if (!I.loader) {
                    I.loader = F.loader == null ? B.getLoader(J, 1) : B.get(F.loader)
                }
                return I.loader
            };
            this.abort = function () {
                I.inprocess = 0;
                if (!H) {
                    return
                }
                try {
                    H.isAbort = 1;
                    H.abort()
                } catch (K) {
                }
                H = null;
                B.showLoading(0, I.getLoader())
            };
            this.destroy = function () {
                B.Data.thread[J] = null;
                delete B.Data.thread[J]
            }
        }, showLoading:function (D, H) {
            var F = H ? H.style : 0;
            if (F) {
                if (D) {
                    if (F.visibility) {
                        F.visibility = "visible"
                    } else {
                        F.display = "block"
                    }
                } else {
                    function G(K, I) {
                        for (var J in K) {
                            if (K[J].getLoader() != H) {
                                continue
                            }
                            if (K[J] && K[J].isProcess()) {
                                return 1
                            }
                        }
                    }

                    if (!G(B.Data.thread, 1) && !G(B.Html.thread)) {
                        if (F.visibility) {
                            F.visibility = "hidden"
                        } else {
                            F.display = "none"
                        }
                    }
                }
            }
        }, getLoader:function (G, D) {
            var F = B.get;
            if (G) {
                G = F((typeof G == "string" ? G : G.id) + C.loaderSufix)
            }
            return G || F(D ? C.loader2 : C.loader) || F(D ? C.loader : C.loader2)
        }, encode:encodeURIComponent, decode:decodeURIComponent, createQuery:function (L, D) {
            L = B.get(L);
            if (!L) {
                return""
            }
            if (!D) {
                D = {}
            }
            var N = [], M = [], T = B.encode, G = L.getElementsByTagName("input");
            for (var R = 0; R < G.length; R++) {
                var F = G[R], H = F.type.toLowerCase(), W = F.name ? F.name : F.id, O = T(F.value);
                if (!W) {
                    continue
                }
                W = T(W);
                switch (H) {
                    case"text":
                    case"password":
                    case"hidden":
                    case"button":
                        N.push(W);
                        M.push(O);
                        break;
                    case"checkbox":
                    case"radio":
                        if (F.checked) {
                            N.push(W);
                            M.push((O == null || O == "") ? F.checked : O)
                        }
                        break
                }
            }
            var V = L.getElementsByTagName("select");
            for (var R = 0; R < V.length; R++) {
                var P = V[R], H = P.type.toLowerCase(), W = P.name ? P.name : P.id;
                if (!W || P.selectedIndex == -1) {
                    continue
                }
                if (H == "select-multiple") {
                    for (var Q = 0, S = P.options.length; Q < S; Q++) {
                        if (P.options[Q].selected) {
                            N.push(W);
                            M.push(T(P.options[Q].value))
                        }
                    }
                } else {
                    N.push(T(W));
                    M.push(T(P.options[P.selectedIndex].value))
                }
            }
            var K = L.getElementsByTagName("textarea");
            for (var R = 0; R < K.length; R++) {
                var J = K[R], W = J.name ? J.name : J.id;
                if (!W) {
                    continue
                }
                N.push(T(W));
                M.push(T(J.value))
            }
            var I = [];
            for (var R = 0, S = N.length; R < S; R++) {
                if (D.skipEmpty && M[R] == "") {
                    continue
                }
                I.push(N[R] + "=" + M[R])
            }
            var U = I.join("&") + (L.submitValue || "");
            L.submitValue = null;
            return U
        }, applyParams:function (I, J) {
            var L = I.split(" ");
            for (var K = 0, M = L.length; K < M; K++) {
                var F = L[K], N = F.indexOf("=");
                if (N > -1) {
                    var H = B.indexOfAttrMarks(F, N + 1), D = F.substring(0, N).trim(), G = F.substring(H[0] + 1, H[1]).trim();
                    J[D] = G
                } else {
                    if (F.indexOf("<") == -1 && F.indexOf(">") == -1) {
                        J[F] = F
                    }
                }
            }
            return J
        }, indexOfAttrMarks:function (G, I) {
            if (I == null) {
                I = 0
            }
            var F = "'", D = G.indexOf(F, I), H = G.indexOf('"', I);
            if (H > -1 && (H < D || D == -1)) {
                D = H;
                F = '"'
            }
            if (D > -1) {
                H = G.indexOf(F, D + 1)
            } else {
                D = G.indexOf("=");
                D++;
                while (G.substring(D).startWith(" ")) {
                    D++
                }
                G = G.replaceAll(">", "");
                H = G.length - 1;
                while (G.substring(H, 1).endWith(" ")) {
                    H--
                }
                D--;
                H++
            }
            return[D, H]
        }, getParam:function (H, F) {
            var D = H.toLowerCase().indexOf(" " + F);
            if (D > -1) {
                var G = B.indexOfAttrMarks(H, D + F.length + 1);
                return H.substring(G[0] + 1, G[1])
            }
        }, entitiesConvertor:function (D) {
            if (D == null) {
                return D
            }
            if (!B.tempDiv) {
                B.tempDiv = document.createElement("div")
            }
            B.tempDiv.innerHTML = D;
            return B.tempDiv[this.browser.msie ? "innerText" : "textContent"]
        }, makeScript:function (K) {
            if (K.indexOf("SRAX.init()") > -1) {
                K = '<script><\/script>'
            }
            var G = document.createElement("script"), D = K.toLowerCase().indexOf("<script"), L = K.indexOf(">", D + 1), J = K.toLowerCase().lastIndexOf("<\/script>");
            if (D > -1 && L > -1) {
                var I = K.substring(D, L + 1);
                B.applyParams(I, G)
            }
            if (G.src) {
                G.src = B.entitiesConvertor(G.src)
            }
            if (J > -1) {
                K = K.substring(L + 1, J)
            } else {
                K = ""
            }
            var H = (G.src ? G.src : "").trim().toLowerCase(), F = H.startWith("javascript:");
            if (H == "//:" || F) {
                if (F) {
                    K += "\n" + H.substring(11)
                }
                G.src = ""
            }
            if (K.length > 0) {
                if (B.browser.msie) {
                    G.text = K
                } else {
                    G.appendChild(document.createTextNode(K))
                }
            }
            if (!G.id) {
                G.id = G.src
            }
            return G
        }, addCss:function (D, F) {
            if (D.indexOf("{") > -1) {
                B.addStyle("<style>" + D + "</style>", F, F)
            } else {
                B.addLink('<link rel="stylesheet" href="' + D + '">', F, F)
            }
        }, addStyle:function (Q, P, K) {
            Q = Q.toLowerCase();
            var M = Q.indexOf("<style"), L = Q.indexOf(">", M + 1), J = Q.indexOf("</style>", L + 1), H = Q.substring(M, L + 1), I = B.applyParams(H, {}), O = I[E("skip")];
            if (O == "true" || O == "1") {
                return
            }
            Q = Q.substring(L + 1, J);
            M = Q.indexOf("@import ");
            while (M > -1) {
                L = Q.indexOf("(", M + 1);
                J = Q.indexOf(")", L + 1);
                var F = Q.substring(L + 1, J);
                F = '<link rel="stylesheet" type="text/css" href="' + F + '"/>';
                B.addLink(F, P, K);
                Q = Q.substring(0, M) + Q.substring(J + 1);
                M = Q.indexOf("@import ")
            }
            if (K && typeof P == "string") {
                Q = B.sealStyle(Q, P)
            }
            if (Q.length > 0) {
                var D = document.createElement("style");
                D.type = "text/css";
                if (D.styleSheet) {
                    D.styleSheet.cssText = Q
                } else {
                    if (B.browser.mozilla || B.browser.opera) {
                        D.innerHTML = Q
                    } else {
                        var G = document.createTextNode(Q);
                        D.appendChild(G)
                    }
                }
                var N = document.getElementsByTagName("head")[0];
                N.appendChild(D);
                if (C.DEBUG_STYLE) {
                    log("Style " + Q)
                }
            }
        }, sealStyle:function (G, J) {
            J = J.trim();
            var D = -1, I = G.indexOf("{"), H = ((J.startWith(".") || J.startWith("#")) ? "" : "#") + J + " ", F = "";
            while (I > -1) {
                F += H + G.substring(D + 1, I).trim().replaceAll(",", "," + H);
                D = G.indexOf("}", I);
                if (D > -1) {
                    F += G.substring(I, D + 1)
                }
                I = D == -1 ? -1 : G.indexOf("{", D)
            }
            return F
        }, addLink:function (P, N, H) {
            P = P.toLowerCase();
            var J = P.indexOf("<link"), I = P.indexOf(">", J + 1);
            if (J > -1 && I > -1) {
                var G = P.substring(J, I + 1), M = document.createElement("link");
                B.applyParams(G, M);
                if (M.href) {
                    M.href = B.entitiesConvertor(M.href)
                }
                var O = M[E("skip")];
                if (O == "true" || O == "1") {
                    return
                }
                var D = (H && typeof N == "string") ? (N + ":" + M.href) : M.href;
                if (B.indexOfCacheSrc(B.linksCache, D) > -1) {
                    var F = M[E("repeat")];
                    if (!C.LINK_REPEAT || F == "false" || F == "0") {
                        return
                    }
                } else {
                    B.linksCache.push(D)
                }
                if (B.indexOfCacheSrc(B.LIST_NO_LOAD_LINKS, D) > -1) {
                    return
                }
                if (H && M.rel == "stylesheet") {
                    try {
                        dax(M.href, {cb:function (S, U, Q, T) {
                            var R = Q ? S.responseText : "";
                            B.addStyle("<style>" + R + "</style>", T, 1)
                        }, id:(N ? N + ":" : "") + M.href, cbo:N});
                        return
                    } catch (K) {
                        error("error seal " + M.href)
                    }
                }
                if (document.createStyleSheet) {
                    document.createStyleSheet(M.href)
                } else {
                    var L = document.getElementsByTagName("head")[0];
                    L.appendChild(M)
                }
                if (C.DEBUG_LINK) {
                    log("append LINK " + M.href)
                }
            }
        }, isHTMLComment:function (F) {
            var D = F.lastIndexOf("<!--"), G = F.indexOf("-->", D + 4);
            return(D > -1 && G == -1)
        }, isHTML:function (F) {
            F = F.toLowerCase();
            function D(H) {
                var G = F.lastIndexOf("<" + H), K = F.indexOf("</" + H + ">", G + 1), J = F.indexOf(">", G + 1), I = F.indexOf("/>", G + 1);
                return !(G > -1 && J > -1 && K == -1 && I != J + 1)
            }

            return D("script") && D("style")
        }, relativeCorrection:function (J, F, G) {
            if (F.indexOf("/") == -1) {
                F = location.pathname
            }
            var D = F.lastIndexOf("/");
            F = F.substring(0, D + 1);
            D = J.toLowerCase().indexOf(" " + G);
            while (D > -1) {
                var H = B.indexOfAttrMarks(J, D + 2);
                if (B.isHTML(J.substring(0, D + 2)) && H[0] > -1 && H[1] > -1) {
                    var I = J.substring(H[0] + 1, H[1]);
                    if (!I.startWith("/") && !I.startWith("#") && B.parseUri(I).protocol == "") {
                        J = J.substring(0, H[0] + 1) + F + J.substring(H[0] + 1)
                    }
                }
                D = J.toLowerCase().indexOf(G, D + 2)
            }
            return J
        }, arrayIndexOf:function (D, G, I) {
            var H = -1;
            for (var F = (I || 0); F < D.length; F++) {
                if (D[F] == G) {
                    H = F;
                    break
                }
            }
            return H
        }, toSource:function (G) {
            switch (typeof G) {
                case"function":
                    return G.toString();
                case"string":
                    return'"' + G.replaceAll('"', '\\"') + '"';
                case"object":
                    var H = "";
                    if (G instanceof Array) {
                        for (var F = 0, D = G.length; F < D; F++) {
                            H += "," + B.toSource(G[F])
                        }
                        if (H.length > 0) {
                            H = H.substring(1)
                        }
                        return"[" + H + "]"
                    }
                    for (var F in G) {
                        H += "," + F + ":" + B.toSource(G[F])
                    }
                    return"{" + (H.length > 0 ? H.substring(1) : H) + "}"
            }
            return G
        }, arrayRemoveOf:function (D, G, H) {
            if (H) {
                G = B.toSource(G)
            }
            for (var F = 0; F < D.length; F++) {
                if ((H && G == B.toSource(D[F])) || G == D[F]) {
                    D.splice(F, 1)
                }
            }
            return D
        }, collectionToArray:function (G) {
            var F = [];
            for (var H = 0, D = G.length; H < D; H++) {
                F[H] = G[H]
            }
            return F
        }, indexOfCacheSrc:function (D, G) {
            var F = B.arrayIndexOf(D, G);
            if (F == -1) {
                G = G.startWith(location.protocol) ? G.replace(location.protocol + "//" + location.host, "") : location.protocol + "//" + location.host + G;
                F = B.arrayIndexOf(D, G)
            }
            return F
        }, parsingText:function (S) {
            if (!S) {
                S = {}
            }
            var I = S.owner;
            if (B.Html.fireEvent(S.id, "beforeload", S) === false) {
                I.inprocess = 0;
                return
            }
            var Q = S.text, P = S.id, F = S.url, R = S.add, K = "relativeCorrection";
            Q = B.Include.fix(Q);
            if (S.rc == null ? C.RELATIVE_CORRECTION : S.rc) {
                Q = B[K](Q, F, "src");
                Q = B[K](Q, F, "href");
                Q = B[K](Q, F, "action")
            }
            Q = B.parsingLinkAndStyle(Q, P, S.seal);
            Q = B.parsingFrameset(Q);
            K = "substring";
            var G = Q.toLowerCase().indexOf("<head>"), H = "";
            if (G > -1) {
                H += Q[K](0, G);
                Q = Q[K](G)
            } else {
                H = Q;
                Q = ""
            }
            var D = Q.toLowerCase().indexOf("</head>"), L = "";
            if (D > -1) {
                L += Q[K](D + 7);
                Q = Q[K](0, D + 7)
            }
            var O = B.Html.thread[P], M = O ? O.getOptions().notitle : 0, J = B.parsingTitle(Q, P, M);
            Q = H + J.text + L;
            if (!R) {
                Q = B.parsingLoadUnload(Q, P)
            }
            var N = B.parsingScript(Q, P, I && I[E("noax")]);
            new B.loadHtml(P, N.scripts, N.html, F, R, I, S.onload, S.scope, J.title)
        }, parsingLoadUnload:function (I, L) {
            var H, G, F = I.toLowerCase().indexOf("<body");
            if (F > -1) {
                var K = I.indexOf(">", F + 1);
                if (K > -1) {
                    var D = I.substring(F, K + 1);
                    H = B.getParam(D, "onload");
                    G = B.getParam(D, "onunload");
                    I = I.substring(0, F) + D.replaceAll("load", "") + I.substring(K + 1)
                }
            }
            var J = "LoadUnloadContainer";
            if (!B[J][L]) {
                B[J][L] = {}
            }
            B[J][L].onload = H;
            B[J][L].onunload = B[J][L].nextonunload;
            B[J][L].nextonunload = G;
            return I
        }, parsingTitle:function (I, K, F) {
            var G = I.toLowerCase(), D = G.indexOf("<title>"), J = G.indexOf("</title>", D + 1), H;
            while (D > -1 && J > -1) {
                if (!B.isHTMLComment(I.substring(0, D)) && !H) {
                    H = I.substring(D + 7, J);
                    if (!F) {
                        B.titleChange(H, K)
                    }
                }
                I = I.substring(0, D) + I.substring(J + 8);
                G = I;
                D = G.indexOf("<title>", D + 1);
                J = G.indexOf("</title>", D + 1)
            }
            return{text:I, title:H}
        }, titleChange:function (F, G) {
            var D = document.title;
            if (B.Html.fireEvent(G, "beforetitlechange", {oldTitle:D, newTitle:F}) !== false) {
                document.title = F;
                B.Html.fireEvent(G, "titlechange", {oldTitle:D, newTitle:F});
                return F
            }
            return false
        }, parsingFrameset:function (I) {
            var D = I.toLowerCase().indexOf("<frameset");
            if (D > -1) {
                var J = I.toLowerCase().indexOf(">", D), H = I.toLowerCase().indexOf("</frameset>");
                if (J > -1 && H > -1) {
                    var F = I.substring(D, H + 11), G = B.genId();
                    F = "<iframe style='height:100%;width:100%;border:0' href='javascript:true' id='" + G + "'></iframe><script>var obj = SRAX.get('" + G + "');var doc = obj[obj.contentWindow ? 'contentWindow' : 'contentDocument'].document;doc.open();doc.write('" + F.replaceAll("\n", "").replaceAll("\r", "").trim() + "');doc.close()<\/script>";
                    I = I.substring(0, D) + F + I.substring(H + 11)
                }
            }
            return I
        }, parsingLinkAndStyle:function (J, L, I) {
            var F = J.toLowerCase().indexOf("<link"), H = J.toLowerCase().indexOf("<style"), G = "", D = -1, K = -1;
            if ((F < H && F > -1) || H == -1) {
                D = F;
                K = J.indexOf(">", D + 1)
            } else {
                D = H;
                K = J.toLowerCase().indexOf("</style>", D + 1)
            }
            while (D > -1 && K > -1) {
                if (D > 0) {
                    G += J.substring(0, D)
                }
                if ((F < H && F > -1) || H == -1) {
                    if (!B.isHTMLComment(J.substring(0, D))) {
                        B.addLink(J.substring(D, K + 1), L, I)
                    }
                    J = J.substring(K + 1)
                } else {
                    if (!B.isHTMLComment(J.substring(0, D))) {
                        B.addStyle(J.substring(D, K + 8), L, I)
                    }
                    J = J.substring(K + 8)
                }
                F = J.toLowerCase().indexOf("<link");
                H = J.toLowerCase().indexOf("<style");
                if ((F < H && F > -1) || H == -1) {
                    D = F;
                    K = J.indexOf(">", D + 1)
                } else {
                    D = H;
                    K = J.toLowerCase().indexOf("</style>", D + 1)
                }
            }
            if (J.length > 0) {
                G += J
            }
            return G
        }, parsingScript:function (Q, G, I) {
            var P = Q.toLowerCase().indexOf("<script"), N = Q.toLowerCase().indexOf("<\/script>", P + 1), R = 9, M = Q.indexOf(">", P + 1), K = Q.indexOf("/>", P + 1);
            if (M > -1 && K != -1 && M == K + 1) {
                N = K;
                R = 2
            }
            var O = [], F = [], D = 0;
            while (P > -1 && N > -1) {
                if (P > 0) {
                    O.push(Q.substring(0, P))
                }
                var W = B.makeScript(Q.substring(P, N + R));
                if (I) {
                    W[E("noax")] = 1
                }
                Q = Q.substring(N + R);
                P = Q.toLowerCase().indexOf("<script");
                N = Q.toLowerCase().indexOf("<\/script>", P + 1);
                R = 9;
                M = Q.indexOf(">", P + 1);
                K = Q.indexOf("/>", P + 1);
                if (M > -1 && K != -1 && M == K + 1) {
                    N = K;
                    R = 2
                }
                if (O.length == 0 || !B.isHTMLComment(O.join(""))) {
                    if (true || Q.toLowerCase().indexOf("<body") == -1) {
                        if (O.length == 0 || O[O.length - 1].indexOf("_place_of_script_") == -1) {
                            O.push('<span id="' + G + "_place_of_script_" + D + '" style="display:none"><!--place of script # ' + D + "//--></span>");
                            D++
                        }
                        W.place = G + "_place_of_script_" + (D - 1);
                        var V = B.get(W.place);
                        if (V) {
                            V.id += "old"
                        }
                    }
                    var S = W[E("skip")];
                    if (S == "true" || S == "1") {
                        continue
                    }
                    if (W.src) {
                        if (W.src.indexOf("fullajax.js") > -1 || B.indexOfCacheSrc(B.LIST_NO_LOAD_SCRIPTS, W.src) > -1) {
                            continue
                        }
                        var J = B.indexOfCacheSrc(B.scriptsCache[0], W.src);
                        if (J > -1) {
                            var L = W[E("repeat")];
                            if ((L == null || (L != "false" && L != "0")) && C.SCRIPT_SRC_REPEAT_APPLY) {
                                B.scriptsCache[1][J].place = W.place;
                                W = B.cloneScript(B.scriptsCache[1][J])
                            } else {
                                W = B.makeScript('<script>//no repeat ' + W.src + "<\/script>")
                            }
                        } else {
                            try {
                                if (B.Data.thread[W.src] && B.Data.thread[W.src].isProcess()) {
                                    W = B.Data.thread[W.src].options.cbo
                                } else {
                                    if (C.SCRIPT_NOAX || W[E("noax")]) {
                                        W.xss = 1
                                    } else {
                                        new B.startLoadScript(W)
                                    }
                                }
                            } catch (U) {
                                error(U)
                            }
                        }
                    }
                    var T = E("head"), H = W[T];
                    W[T] = H == null ? Q.toLowerCase().indexOf("</head>") > -1 : (H == "1" || H == "true");
                    F.push(W)
                }
            }
            if (Q.length > 0) {
                O.push(Q)
            }
            return{scripts:F, html:O}
        }, finishLoadScript:function (J, K, F, D) {
            var I = F ? J.responseText : "", G = B.makeScript('<script>' + I + "<\/script>");
            G.place = D.place;
            G.id = D.id ? D.id : K;
            var H = B.indexOfCacheSrc(B.scriptsTemp[0], K);
            if (H == -1) {
                H = B.scriptsTemp[0].length
            }
            B.scriptsTemp[0][H] = K;
            B.scriptsTemp[1][H] = G;
            if (C.USE_SCRIPT_CACHE && B.indexOfCacheSrc(B.LIST_NO_CACHE_SCRIPTS, K) == -1 && !D[E("nocache")]) {
                H = B.indexOfCacheSrc(B.scriptsCache[0], K);
                if (H == -1) {
                    H = B.scriptsCache[0].length
                }
                B.scriptsCache[0][H] = K;
                B.scriptsCache[1][H] = B.cloneScript(G)
            }
        }, startLoadScript:function (D) {
            try {
                dax(D.src, {cb:B.finishLoadScript, id:D.src, cbo:D, anticache:D[E("nocache")]})
            } catch (F) {
                if (!D.id) {
                    D.id = D.src
                }
                D.xss = D.src
            }
        }, cloneScript:function (F, H) {
            if (!H) {
                H = {}
            }
            var G = document.createElement("script"), L = ["src", "type", "language", "defer", "text", "id", "place", E("repeat"), E("noax"), E("skip"), E("head"), E("noblock")];
            for (var J = 0, D = L.length; J < D; J++) {
                try {
                    var K = F[L[J]];
                    if (H[L[J]] != null) {
                        K = H[L[J]]
                    }
                    if (K != null && K != "") {
                        G[L[J]] = K
                    }
                } catch (I) {
                }
            }
            return G
        }, serialApplyScripts:function (D, I, F, H) {
            var G = 0;
            this.checkload = function () {
                if (G >= D.length) {
                    B.docWriteTraper.apply(I);
                    if (!B.xssLoading && !(G >= 1 ? (D[G - 1].inprocess || D[G - 1].countproc) : 0)) {
                        return H ? H() : null
                    }
                } else {
                    if (D[G].src) {
                        var K = B.indexOfCacheSrc(B.scriptsTemp[0], D[G].src);
                        if (K > -1 && !(D[G][E("noax")] && D[G][E("nocache")])) {
                            var J = D[G].place;
                            D[G] = B.cloneScript(B.scriptsTemp[1][K]);
                            D[G].place = J
                        }
                    }
                    if (!D[G].src && (G > 0 ? !D[G - 1].inprocess : 1)) {
                        new B.addScript(D[G], I, F);
                        B.docWriteTraper.apply(I);
                        G++
                    } else {
                        if (D[G].src && !B.xssLoading) {
                            if (D[G].loaded) {
                                B.docWriteTraper.apply(I);
                                G++
                            } else {
                                if (D[G].xss) {
                                    D[G].xss = 0;
                                    new B.addScript(D[G], I, F)
                                }
                            }
                        }
                    }
                }
                var L = this;
                this.recall = function () {
                    L.checkload()
                };
                setTimeout(this.recall, 10)
            };
            this.checkload()
        }, loadHtml:function (P, I, L, D, Q, F, H, R, O) {
            B.removeScripts(I);
            var G = {id:P, scripts:I, html:L, url:D, add:Q, owner:F, scope:R, title:O};
            B.Html.fireEvent(P, "unload", G);
            if (!Q) {
                B.execUnloadBody(P)
            }
            var N = [], M = [];
            for (var J = 0; J < I.length; J++) {
                var K = I[J][E("head")] ? N : M;
                K.push(I[J])
            }
            new B.serialApplyScripts(N, P, D, function () {
                B[B.Model2Blocks[P] ? "paintHtml2" : "paintHtml"](L.join(""), P, D, Q);
                if (!Q) {
                    B.Effect.use(P)
                }
                new B.serialApplyScripts(M, P, D, function () {
                    if (C.USE_FILTER_WRAP) {
                        var U = B.Model2Blocks[P];
                        if (U) {
                            for (var V in U) {
                                var T = B.get(U[V]);
                                if (T) {
                                    B.Filter.wrap(T, D)
                                }
                            }
                        } else {
                            B.Filter.wrap(P, D)
                        }
                    }
                    B.Include.parse();
                    if (F) {
                        F.inprocess = 0;
                        if (F.countproc) {
                            F.countproc--
                        }
                    }
                    if (!Q) {
                        B.execLoadBody(P, D);
                        B.execFunc(H, [G], R)
                    }
                    B.Html.fireEvent(P, "load", G);
                    var S = B.Html.thread[P];
                    if (!B.Html.ASYNCHRONOUS && B.Html.storage[0] == P) {
                        B.Html.storage.splice(0, 1);
                        if (B.Html.storage.length > 0) {
                            S.request()
                        }
                    }
                    if (S) {
                        B.showLoading(0, S.getLoader())
                    }
                })
            })
        }, execLoadBody:function (F, D) {
            if (B.LoadUnloadContainer[F].onload) {
                B.parsingText({id:F, url:D, text:'<script id="' + E("script" + C.sprt + "temp") + '">' + B.LoadUnloadContainer[F].onload + "<\/script>", add:1})
            }
            if (B.isCOL) {
                window._onload()
            }
        }, captureOnLoad:function () {
            window.onloadHandlers = [];
            window._onload = function () {
                var F = window.onloadHandlers;
                window.onloadHandlers = [];
                F.push(window.onload);
                window.onload = null;
                for (var H = 0, D = F.length; H < D; H++) {
                    try {
                        if (F[H]) {
                            F[H]()
                        }
                    } catch (G) {
                        error(G)
                    }
                }
            };
            window.onloadHandlers.push(window.onload);
            window.onload = function () {
                window.onload = null;
                window._onload()
            };
            window._addEvent = window[window.attachEvent ? "attachEvent" : "addEventListener"];
            window.addEventListener = window.attachEvent = function (F, G, D) {
                if (F == "load") {
                    window.onloadHandlers.push(G)
                } else {
                    window._addEvent(F, G, D)
                }
            };
            B.isCOL = 1
        }, execUnloadBody:function (H, F) {
            var G = B.LoadUnloadContainer[H], D = F ? "nextonunload" : "onunload";
            B.execFunc(G[D]);
            G[D] = null
        }, paintHtml:function (G, I, F, H) {
            var D = {html:G, id:I, url:F, add:H};
            if (H) {
                if (B.Html.fireEvent(I, "beforepaintadd", D) !== false) {
                    B.addTo(G, I);
                    B.Html.fireEvent(I, "afterpaintadd", D)
                }
            } else {
                if (B.Html.fireEvent(I, "beforepaint", D) !== false) {
                    B.writeTo(G, I);
                    B.Html.fireEvent(I, "afterpaint", D)
                }
            }
        }, paintHtml2:function (J, N, F, P) {
            var D = B.Model2Blocks[N], H = C.model2Marker, M = J.indexOf(H.ax), L = J.indexOf(H.begin, M + 1), K = J.indexOf(H.ax, L + 1), I = J.indexOf(H.end, K + 1);
            while (M > -1 && L > -1 && K > -1 && I > -1) {
                var G = J.substring(M + H.ax.length, L), O = J.substring(L + H.begin.length, K);
                if (D[G]) {
                    var Q = {html:O, id:G, url:F, block:D[G], add:P};
                    if (P) {
                        if (B.Html.fireEvent(N, "beforepaintadd", Q) !== false) {
                            B.addTo(O, D[G]);
                            B.Html.fireEvent(N, "afterpaintadd")
                        }
                    } else {
                        if (B.Html.fireEvent(N, "beforepaint", Q) !== false) {
                            B.writeTo(O, D[G]);
                            B.Html.fireEvent(N, "afterpaint")
                        }
                    }
                }
                M = J.indexOf(H.ax, I + 1);
                L = J.indexOf(H.begin, M + 1);
                K = J.indexOf(H.ax, L + 1);
                I = J.indexOf(H.end, K + 1)
            }
        }, docWriteTraper:new function () {
            var D = {}, G = {}, F = {};
            this.add = function (J, K, I, H) {
                if (H.inprocessTO) {
                    clearTimeout(H.inprocessTO)
                }
                H.inprocess = 1;
                D[K] = H;
                G[K] = I;
                if (!F[K]) {
                    F[K] = ""
                }
                F[K] += J;
                this.checkMutiLine(K)
            };
            this.checkMutiLine = function (O) {
                var L = F[O], I = L.indexOf("<");
                while (I > -1) {
                    var N = 1, J = L.charAt(I + N).trim();
                    while (J != "" && J != ">") {
                        if (J == "/" && L.charAt(I + N + 1) == ">") {
                            this.apply(O);
                            return
                        }
                        J = L.charAt(I + (++N)).trim()
                    }
                    var H = L.substring(I + 1, I + N), M = L.indexOf("</" + H + ">", I);
                    if (M > -1) {
                        this.apply(O);
                        break
                    } else {
                        var K = L.indexOf(">", I + 1 + H.length);
                        if (K > -1 && (H == "img" || H == "input" || H == "br" || H == "hr")) {
                            this.apply(O);
                            return
                        }
                        I = L.indexOf("<", I + 1)
                    }
                }
            };
            this.apply = function (I) {
                if (!F[I]) {
                    return
                }
                var H = F[I];
                delete F[I];
                if (!D[I].countproc) {
                    D[I].countproc = 1
                } else {
                    D[I].countproc++
                }
                A(B.get(D[I].place), 1);
                B.parsingText({text:H, id:D[I].place, url:G[I], add:1, owner:D[I]})
            };
            this.applyAll = function () {
                for (var H in F) {
                    if (F[H]) {
                        B.docWriteTraper.apply(H)
                    }
                }
            }
        }, addScript:function (P, R, F, N, H, L, T) {
            if (typeof P == "object" && P.nodeName != "SCRIPT") {
                R = P.callback || P.cb;
                F = P.noax;
                H = P.place;
                N = P.anticache == null ? P.nocache : P.anticache;
                L = P.storage;
                T = P.noblock;
                P = P.src ? P.src : P.url
            }
            if (B.Storage && (L == null ? C.USE_STORAGE : L) && B.Storage.isPosible() && !B.Storage.isReady) {
                B.Storage.onReady(function () {
                    B.addScript(P, R, F, N, H, L)
                });
                return
            }
            if (typeof P == "string") {
                var Q = document.createElement("span");
                Q.cb = R ? R : function () {
                };
                Q.id = B.genId();
                Q.style.display = "none";
                A(Q, 1);
                var G = document.getElementsByTagName("script");
                H = B.get(H);
                if (H) {
                    H.innerHTML = "";
                    H = H.appendChild(Q)
                } else {
                    for (var I = 0, K = G.length; I < K; I++) {
                        var S = G[I].innerHTML, M = S.indexOf("SRAX.addScript");
                        if (M > -1) {
                            var J = S.indexOf(P);
                            if (J > M) {
                                H = G[I].place ? B.get(G[I].place) : G[I];
                                break
                            }
                        }
                    }
                }
                if (H) {
                    H.parentNode.insertBefore(Q, H)
                } else {
                    document.body.appendChild(Q)
                }
                hax({id:Q.id, url:P, html:"<body onload=\"SRAX.get('" + Q.id + '\').cb()"><script src="' + P + '"' + (F ? " " + E("noax") + '="1"' : "") + (N ? " " + E("nocache") + '="1"' : "") + (T ? " " + E("noblock") + '="1"' : "") + "><\/script></body>", nohistory:1, storage:L});
                return
            }
            B.docWriteTraper.apply(R);
            document.write = function (U) {
                B.docWriteTraper.add(U, R, F, P)
            };
            document.writeln = function (U) {
                document.write(U + "\n")
            };
            if (C.DEBUG_SCRIPT) {
                var D = P.id;
                if (!D || D == "") {
                    D = P.innerHTML.trim().substring(0, 100) + "\n..."
                }
                log("append script -> " + D)
            }
            if (P.src) {
                P.inprocess = 1;
                B.xssLoading = !P[E("noblock")];
                P.onerror = P.onload = P.onreadystatechange = function () {
                    var U = this;
                    if (!U.loaded && (!U.readyState || U.readyState == "loaded" || U.readyState == "complete")) {
                        U.loaded = 1;
                        U.onerror = U.onload = U.onreadystatechange = null;
                        B.xssLoading = 0;
                        U.inprocessTO = setTimeout(function () {
                            U.inprocess = 0
                        }, 100)
                    }
                }
            }
            var O = document.getElementsByTagName("head")[0];
            O.appendChild(P)
        }, evalScript:function (F) {
            try {
                if (B.browser.safari) {
                    window._evalCode = F;
                    new B.addScript(B.makeScript('<script>eval(window._evalCode)<\/script>'))
                } else {
                    if (window.execScript) {
                        window.execScript(F)
                    } else {
                        window["eval"](F)
                    }
                }
            } catch (D) {
                error(D);
                return 0
            }
            return 1
        }, removeScripts:function (G) {
            var J = document.getElementsByTagName("head")[0], K = J.getElementsByTagName("script"), F = [];
            for (var I = 0, D = G.length; I <= D; I++) {
                if (I < G.length && typeof G[I] == "string") {
                    continue
                }
                var L = I < G.length ? G[I].id : E("script" + C.sprt + "temp");
                for (var H = 0, D = K.length; H < D; H++) {
                    if (L ? K[H].id == L : K[H].innerHTML == G[I].innerHTML) {
                        F.push(K[H]);
                        break
                    }
                }
            }
            for (var I = 0, D = F.length; I < D; I++) {
                if (F[I].parentNode) {
                    if (C.DEBUG_SCRIPT) {
                        log("remove script " + (F[I].id ? F[I].id : F[I].innerHTML))
                    }
                    F[I].parentNode.removeChild(F[I])
                }
            }
        }, execFunc:function (J, F, I) {
            if (J instanceof Array) {
                for (var H = 0, D = J.length; H < D; H++) {
                    B.execFunc(J[H], F, I)
                }
            } else {
                if (J) {
                    try {
                        if (!I) {
                            I = window
                        }
                        if (typeof J == "string") {
                            J = J.trim();
                            if (J.startWith("function") && J.endWith("}")) {
                                J = B.browser.msie ? "SRAX.tmp=" + J : "(" + J + ")"
                            }
                            (function () {
                                J = window["eval"](J)
                            }).call(I);
                            if (typeof J != "function") {
                                return
                            }
                        }
                        J.apply(I, F)
                    } catch (G) {
                        error(G)
                    }
                }
            }
        }, HTMLThread:function (K) {
            var I, H, J = this, G = this.options = {};
            this.inprocess = 0;
            this.id = K;
            B.Html.thread[K] = this;
            B.Html.register(this);
            this.repeat = function (M, L, N) {
                G.form = M;
                G.nohistory = L;
                G.params = N;
                J.request()
            };
            this.setOptions = function (M, L) {
                if (!M.url) {
                    M.url = M.src
                }
                if (!M.cb) {
                    M.cb = M.callback
                }
                if (M.cbo == null) {
                    M.cbo = M.callbackOps
                }
                if (M.anticache == null) {
                    M.anticache = M.nocache
                }
                if (L) {
                    G = {}
                }
                B.extend(G, M);
                if (G.async == null) {
                    G.async = true
                }
                G.url = B.delHost(G.url);
                this.options = G
            };
            this.getOptions = function () {
                return G
            };
            this.isProcess = function () {
                return J.inprocess
            };
            this.request = function () {
                var L = G.method ? G.method : (G.form ? G.form.method : "get"), P = (L && L.toLowerCase() == "post") ? "post" : "get";
                try {
                    var M = {url:G.url, id:K, options:G, xhr:J};
                    if (J.fireEvent("beforerequest", M) !== false) {
                        var O = function () {
                            H = B.getTime();
                            var Q = B.createQuery(G.form);
                            if (G.params) {
                                if (Q != "" && !G.params.startWith("&")) {
                                    Q += "&"
                                }
                                Q += G.params
                            }
                            if (P != "post" && Q != "") {
                                if (G.url.indexOf("?") == -1) {
                                    G.url += "?" + Q
                                } else {
                                    G.url += ((G.url.endWith("?") || G.url.endWith("&")) ? "" : "&") + Q
                                }
                            }
                            if (J.inprocess) {
                                J.abort()
                            }
                            J.inprocess = 1;
                            var W = location.href.indexOf("#"), S = (W == -1) ? location.href : location.href.substring(0, W), X = G.html != null || (S.endWith(G.url) || (G.anticache != null ? G.anticache : C.HAX_ANTICACHE));
                            W = D.getIndex(G.url);
                            if (!X && W > -1 && P != "post") {
                                G.html = D.storage[W][1]
                            }
                            if (G.html) {
                                F({readyState:4, status:200, responseText:G.html});
                                G.html = null
                            } else {
                                if (!I) {
                                    I = B.getXHR()
                                }
                                try {
                                    I.onprogress = function (Y) {
                                        J.fireEvent("progress", {id:K, xhr:J, event:Y, position:Y.position, total:Y.totalSize, percent:Math.round(100 * Y.position / Y.totalSize)})
                                    }
                                } catch (V) {
                                }
                                try {
                                    var T = (B.browser.msie && location.protocol == "file:" && G.url.startWith("/") ? "file://" : "") + G.url;
                                    if (G.user) {
                                        I.open(P.toUpperCase(), T, G.async, G.user, G.pswd)
                                    } else {
                                        I.open(P.toUpperCase(), T, G.async)
                                    }
                                } catch (V) {
                                    B.Effect.use(K);
                                    throw V
                                }
                                I.onreadystatechange = G.async ? F : function () {
                                };
                                var R = "setRequestHeader";
                                if (G.cut) {
                                    I[R]("AJAX_CUT_BLOCK", G.cut)
                                }
                                if (X) {
                                    I[R]("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT")
                                }
                                I[R]("AJAX_ENGINE", "Fullajax");
                                I[R]("HTTP_X_REQUESTED_WITH", "XMLHttpRequest");
                                if (G.headers) {
                                    for (var U in G.headers) {
                                        I[R](U, G.headers[U])
                                    }
                                }
                                if (P == "post") {
                                    I[R]("Content-Type", "application/x-www-form-urlencoded; Charset=" + C.CHARSET)
                                }
                                I.send((P == "post") ? Q : null);
                                if (!G.async) {
                                    F()
                                }
                            }
                            B.showLoading(J.inprocess, J.getLoader());
                            if (C.DEBUG_AJAX) {
                                log(P + " " + G.url + " params:" + Q + " id:" + K)
                            }
                        };
                        if (!B.Effect.use(K, 1, O)) {
                            O()
                        }
                        J.fireEvent("afterrequest", M)
                    }
                } catch (N) {
                    J.abort();
                    error(N);
                    throw N
                }
            };
            this.getLoader = function () {
                if (!J.loader) {
                    J.loader = G.loader == null ? B.getLoader(K) : B.get(G.loader)
                }
                return J.loader
            };
            this.abort = function () {
                J.inprocess = 0;
                if (!I) {
                    return
                }
                try {
                    I.isAbort = 1;
                    I.abort()
                } catch (L) {
                }
                I = null;
                B.showLoading(0, J.getLoader())
            };
            this.destroy = function () {
                B.Html.thread[K] = null;
                delete B.Html.thread[K]
            };
            function F(P) {
                if (!P || !P.readyState) {
                    P = I
                }
                try {
                    if (P.readyState == 4) {
                        var O = P.isAbort ? -1 : P.status, W = (O >= 200 && O < 300) || O == 304 || (O == 0 && location.protocol == "file:"), X = P.responseText;
                        try {
                            var V = P.getAllResponseHeaders().split("\n"), N = {};
                            for (var Q = 0, T = V.length; Q < T; Q++) {
                                var M = V[Q].indexOf(":");
                                if (M > -1) {
                                    N[V[Q].substring(0, M).toLowerCase()] = V[Q].substring(M + 2)
                                }
                            }
                            var S = N["content-type"];
                            if (S) {
                                var R = ["application/x-javascript", "application/javascript", "text/javascript", "application/json", "text/json"];
                                for (var Q = 0, T = R.length; Q < T; Q++) {
                                    if (S.indexOf(R[Q]) > -1) {
                                        X = "<script>" + X + "<\/script>";
                                        G.add = 1;
                                        break
                                    }
                                }
                            }
                        } catch (U) {
                        }
                        var L = {xhr:P, url:G.url, id:K, status:O, success:W, cbo:G.cbo, callbackOps:G.cbo, options:G, text:X, thread:J, responseText:X, time:B.getTime() - H};
                        J.fireEvent("response", L);
                        if (O > -1 && B.HtmlPreprocessor(L) !== false) {
                            if (G.cb) {
                                B.execFunc(G.cb, [L, K, W, G.cbo], G.scope);
                                if (C.DEBUG_AJAX) {
                                    log("callback id:" + K)
                                }
                            }
                            J.inprocess = 0;
                            if (W) {
                                if (L.text) {
                                    D.add(G.url, L.text, G);
                                    J.inprocess = 1;
                                    B.parsingText({owner:J, text:L.text, id:K, url:G.url, add:G.add, rc:G.rc, seal:G.seal, onload:G.onload, scope:G.scope})
                                } else {
                                    warn("empty response: " + K + " => " + G.url);
                                    B.Effect.use(K)
                                }
                                if (C.DEBUG_AJAX) {
                                    log("response ok:" + G.url)
                                }
                            } else {
                                B.execFunc(G.onerror, [G], G.scope);
                                B.showMessage(G.url, P.status, P.statusText);
                                B.Effect.use(K)
                            }
                        }
                        B.showLoading(J.inprocess, J.getLoader());
                        if ((G.destroy != null) ? G.destroy : C.HAX_AUTO_DESTROY) {
                            J.destroy()
                        }
                    }
                } catch (U) {
                    error(U);
                    J.fireEvent("exception", {xhr:P, url:G.url, id:K, exception:U, options:G});
                    B.Effect.use(K);
                    J.inprocess = 0;
                    B.showLoading(J.inprocess, J.getLoader());
                    if ((G.destroy != null) ? G.destroy : C.HAX_AUTO_DESTROY) {
                        J.destroy()
                    }
                }
            }

            var D = this.history = {storage:[], startPageHtml:null, startPageOps:null, startPageUrl:null, current:0, currentUrl:function () {
                if (this.storage.length == 0 || this.current <= 0) {
                    return null
                }
                return this.storage[D.current][0]
            }, add:function (R, O, L) {
                R = decodeURIComponent(R);
                if (R.href) {
                    R = R.href
                }
                this.current++;
                var U = location.host, M = R.indexOf(U);
                if (M > -1) {
                    R = R.substring(M + U.length)
                }
                R = B.replaceLinkEqual(R);
                if (G.startpage) {
                    G.startpage = 0;
                    D.startPageHtml = O;
                    D.startPageUrl = R;
                    D.startPageOps = B.extend({}, G);
                    B.History.setCurrent(B.getHash())
                }
                var S = !(G.nohistory != null ? G.nohistory : C.NO_HISTORY);
                if (D.startPageHtml == null) {
                    var P = ["<head><title>" + document.title + "</title></head>"], T = B.Model2Blocks[K];
                    if (T) {
                        for (var N in T) {
                            var Q = B.get(T[N]);
                            if (Q) {
                                P.push(C.model2Marker.ax + N + C.model2Marker.begin + Q.innerHTML + C.model2Marker.ax + N + C.model2Marker.end)
                            }
                        }
                    } else {
                        var Q = B.get(K);
                        if (!Q) {
                            Q = document.body
                        }
                        P.push(Q.innerHTML)
                    }
                    D.startPageHtml = P.join("");
                    D.startPageUrl = location.href
                }
                if (S) {
                    B.History.add(K, R)
                }
                if (this.current > C.LENGTH_HISTORY_CACHE) {
                    this.current--;
                    this.storage.splice(0, 1)
                }
                this.storage.length = this.current;
                this.storage.push([B.replaceLinkEqual(R, 1), O, L])
            }, get:function (L) {
                return this.storage[L]
            }, getIndex:function (O, N) {
                for (var M = N || 0, L = this.storage.length; M < L; M++) {
                    if (this.storage[M] != null && O == this.storage[M][0]) {
                        return M
                    }
                }
                return -1
            }};
            this.go2History = function (O) {
                if (D.currentUrl() != O) {
                    var L = G.historycache != null ? G.historycache : C.USE_HISTORY_CACHE;
                    if (!L || !this.go2UrlHistory(O)) {
                        O = B.replaceLinkEqual(O, 1);
                        var M = D.getIndex(O, 2), N = {url:O, nohistory:1};
                        if (M > -1) {
                            B.extend(N, D.storage[M][2], 1)
                        }
                        this.setOptions(N, M > -1);
                        this.request()
                    }
                }
            };
            this.go2UrlHistory = function (M) {
                var L = D.getIndex(M);
                if (L > -1) {
                    this.go(L - D.current);
                    B.History.setCurrent(B.getHash());
                    return true
                }
            };
            this.go = function (Q) {
                var N = D.current + Q;
                if (N < 0) {
                    N = 0
                } else {
                    if (N > D.storage.length - 1) {
                        N = D.storage.length - 1
                    }
                }
                if (N == 0) {
                    return D.go2StartPage()
                }
                D.current = N;
                var L = D.storage[N], M = L[0], P = L[1], O = L[2] || G;
                if (M && P) {
                    B.parsingText({owner:J, text:P, id:K, url:D.storage[N][0], add:O.add, rc:O.rc, seal:O.seal, onload:O.onload, scope:O.scope})
                }
            }, this.go2StartPage = function () {
                var L = D;
                if (L.startPageHtml) {
                    var M = B.extend({startpage:1, owner:J, text:L.startPageHtml, id:K, url:L.startPageUrl}, L.startPageOps || G, 1);
                    B.parsingText(M)
                }
                D.current = 0
            };
            this.getSrartPageUrl = function () {
                return D.startPageUrl
            }
        }, replaceHref:function () {
            var D = location, F = D.href, G = F.indexOf("#");
            if (G > -1 && F.length > G + 1) {
                D.replace(F.substring(0, G) + B.replaceLinkEqual(F.substring(G)))
            }
        }, go:function (G, F) {
            var D = B.parseAxHash(G);
            for (var H in D) {
                hax(B.extend({id:H, url:D[H]}, F))
            }
        }, directLink:function () {
            B.replaceHref();
            var D = B.getHash();
            B.History.setCurrent(D);
            return B.go2Hax(1, D)
        }, go2Hax:function (K, F) {
            var M = B.parseAxHash(B.History.previous);
            if (!F) {
                F = B.History.current
            }
            var L = B.parseAxHash(F), J = 0, N = {oldHash:B.History.previous, newHash:B.History.current};
            for (var G in L) {
                J++;
                if (M[G] == L[G]) {
                    M[G] = null;
                    continue
                }
                M[G] = null;
                N.id = G;
                N.url = L[G];
                if (B.Html.fireEvent(G, "beforehistorychange", N) === false) {
                    continue
                }
                if (B.Html.thread[G]) {
                    var H = function () {
                        B.Html.thread[G].go2History(L[G])
                    };
                    if (!B.Effect.use(G, 1, H)) {
                        H()
                    }
                } else {
                    var D = B.replaceLinkEqual(L[G], 1), I = B.parseUri(D), N = B.Filter.getOptions(I.path, I.query);
                    if (!N) {
                        N = {}
                    }
                    hax(D, {id:G, nohistory:K, startPage:K, rc:N.rc})
                }
            }
            for (var G in M) {
                if (M[G] && B.Html.thread[G]) {
                    N.id = G;
                    N.url = B.Html.thread[G].getSrartPageUrl();
                    N.startpage = 1;
                    if (B.Html.fireEvent(G, "beforehistorychange", N) === false) {
                        continue
                    }
                    var H = B.Html.thread[G].go2StartPage;
                    if (!B.Effect.use(G, 1, H)) {
                        H()
                    }
                }
            }
            L.size = J;
            return L
        }, makeAxHash:function (I, F, D, H) {
            if (!H) {
                H = "ax"
            }
            var K = ":" + H + ":" + (F.id ? F.id : F) + ":", L = I.indexOf(K);
            if (L > -1) {
                var G = I.substring(L), J = G.indexOf(":", L + K.length + 1);
                while (J > -1 && G.substring(J, J + 2) == ":/") {
                    J = G.indexOf(":", J + 1)
                }
                if (J > -1) {
                    G = G.substring(0, J)
                }
                I = I.replace(G, K + D)
            } else {
                I += K + D
            }
            return(I.startWith("#") ? "" : "#") + I
        }, attrs:["id", "src", "url", "method", "form", "params", "callback", "cb", "callbackOps", "cbo", "nohistory", "cut", "rc", "overwrite", "destroy", "html", "anticache", "nocache", "startpage", "async", "historycache", "seal", "user", "pswd", "storage", "etag", "headers", "add", "target", "onload", "loader"], parseAttr:function (H, J) {
            var F = null, M = H.attributes;
            if (!M) {
                return F
            }
            if (!J) {
                J = ""
            }
            for (var I = 0, K = (B.browser.msie ? B.attrs : M).length; I < K; I++) {
                var L = B.browser.msie ? M[J + B.attrs[I]] : M[I];
                if (L && L.nodeName.startWith(J)) {
                    var D = L.nodeName.substring(J.length), G = L.nodeValue;
                    G = (G == "1" || G == "true") ? 1 : ((G == "0" || G == "false") ? 0 : G);
                    if (!F) {
                        F = {}
                    }
                    F[D] = G
                }
            }
            return F
        }, parseAxHash:function (F, H) {
            if (!H) {
                H = "ax"
            }
            var G = {};
            if (!F) {
                return G
            }
            F = B.replaceLinkEqual(F, 1);
            var D = F.indexOf(":" + H + ":");
            while (D > -1) {
                var L, K = F.indexOf(":", D + H.length + 2);
                if (K > -1) {
                    L = F.substring(D + H.length + 2, K)
                } else {
                    K = D
                }
                D = F.indexOf(":" + H + ":", K + 1);
                var J = F.substring(K + 1), I = J.indexOf(":");
                while (I > -1 && J.substring(I, I + 2) == ":/") {
                    I = J.indexOf(":", I + 1)
                }
                if (I > -1) {
                    J = J.substring(0, I)
                }
                if (J && L) {
                    G[L] = J
                }
            }
            return G
        }, getHash:function () {
            return location.hash2 || location.hash
        }, setHash:function (F) {
            var D = location;
            D.hash = F;
            if (D.hash2 || decodeURIComponent(D.hash) != decodeURIComponent(F)) {
                D.hash2 = F
            }
        }, History:{previous:null, current:null, setCurrent:function (D) {
            B.History.previous = B.History.current;
            B.History.current = D
        }, prefixListener:{}, check:function () {
            var H = B.getHash();
            var G = B.History.current;
            if (B.browser.msie && B.History.frame) {
                var D = B.replaceLinkEqual(B.History.frame.contentWindow.document.body.innerText);
                if (D != G && "#" + D != G) {
                    H = D;
                    B.setHash(H)
                }
            }
            var H = B.replaceLinkEqual(H);
            if (G != null && H != G) {
                B.History.setCurrent(H);
                for (var F in B.History.prefixListener) {
                    B.History.prefixListener[F]()
                }
            }
        }, add:function (D, J, I) {
            var G = B.replaceLinkEqual(B.getHash(), 1);
            G = B.makeAxHash(G, D, J, I);
            var H = B.replaceLinkEqual(G), L = B.History.fireEvent("beforeadd", {hash:G, rhash:H, id:D, url:J, loc:J, prefix:I});
            if (L === false) {
                return
            } else {
                if (typeof L == "string") {
                    H = B.replaceLinkEqual(L)
                }
            }
            B.setHash(H);
            if (B.browser.msie || B.browser.safari) {
                var F = B.History.frame;
                if (!F) {
                    if (B.browser.msie) {
                        F = document.createElement("iframe");
                        F.style.display = "none";
                        F.src = "javascript:true";
                        document.body.appendChild(F);
                        var K = F.contentWindow ? F.contentWindow : F.contentDocument, M = K.document, N = B.History.previous || "";
                        M.open();
                        M.write(N);
                        M.close()
                    }
                    B.History.frame = F
                }
                if (B.browser.msie) {
                    var K = F.contentWindow ? F.contentWindow : F.contentDocument, M = K.document;
                    M.open();
                    M.write(H);
                    M.close()
                }
            }
            B.History.setCurrent(H)
        }}, Effect:{effects:{}, add:function (F) {
            if (!F) {
                F = {}
            }
            if (!F.id) {
                F.id = "document.body"
            }
            var D = B.Effect.effects[F.id];
            if (!D) {
                D = []
            }
            D.push(F);
            B.Effect.effects[F.id] = D
        }, get:function (F) {
            if (!F) {
                F = "document.body"
            }
            for (var D in B.Effect.effects) {
                if (D == F || D == "*") {
                    return B.Effect.effects[D]
                }
            }
        }, use:function (L, K, F) {
            try {
                var I = B.Effect.get(L);
                if (I) {
                    for (var H = 0, D = I.length; H < D; H++) {
                        var J = (H == I.length - 1) ? F : null;
                        if (!I[H]) {
                            continue
                        }
                        if (K) {
                            if (I[H].start) {
                                I[H].start(L, J)
                            }
                        } else {
                            if (I[H].end) {
                                I[H].end(L, J)
                            }
                        }
                    }
                }
                return !!I
            } catch (G) {
                error(G)
            }
        }}, Filter:{schema:{}, add:function (F) {
            if (!F) {
                F = {}
            }
            if (!F.id) {
                F.id = "document.body"
            }
            this.remove(F);
            var D = this.schema[F.id];
            if (!D) {
                D = []
            }
            D.push(F);
            this.schema[F.id] = D;
            return this
        }, remove:function (F) {
            if (!F) {
                F = {}
            }
            if (!F.id) {
                F.id = "document.body"
            }
            var D = this.schema[F.id];
            if (!D) {
                return
            }
            B.arrayRemoveOf(D, F, 1);
            this.schema[F.id] = D
        }, clear:function (D) {
            this.schema[D ? D : "document.body"] = null
        }, clearAll:function () {
            for (var D in this.schema) {
                delete this.schema[D]
            }
        }, getOptions:function (F, P, G) {
            var U = null, S = 0;
            for (var I in this.schema) {
                var N = this.schema[I];
                if (!N) {
                    continue
                }
                function R(V, c, a) {
                    var Z = 0;
                    for (var Y = 0, W = V.length; Y < W; Y++) {
                        var b = V[Y], X = b && c && (b == "*" || ((!a || a == "contain") && c.indexOf(b) > -1) || (a == "start" && c.startWith(b)) || (a == "end" && c.endWith(b)));
                        if (X && Z < b.length) {
                            Z = b.length
                        }
                    }
                    return Z
                }

                for (var L = 0, O = N.length; L < O; L++) {
                    var D = N[L].url instanceof Array ? N[L].url : [N[L].url], T = R(D, F, N[L].urlType), M = N[L].query instanceof Array ? N[L].query : [N[L].query], Q = R(M, P, N[L].queryType), K = N[L].join || N[L].joinLogic, H = K == "and" ? T + Q : (T > Q ? T : Q);
                    if (S < H) {
                        S = H;
                        U = {};
                        for (var J in N[L]) {
                            U[J] = N[L][J]
                        }
                        U.filterSchemaId = I;
                        if (G && G.nodeName == "FORM") {
                            if (G.attributes.method) {
                                U.method = G.attributes.method.nodeValue
                            }
                            U.form = G
                        }
                    }
                }
            }
            return U
        }, parseStartUrl:function (D) {
            return D.substring(0, D.indexOf("/", 1))
        }, getParentPath:function () {
            var F = location.pathname, D = F.lastIndexOf("/");
            return D > -1 ? F.substring(0, D + 1) : ""
        }, parseAxAttr:function (D) {
            if (D.iswrapped) {
                return
            }
            var F = B.parseAttr(D, E(""));
            if (F) {
                if (D.nodeName == "FORM") {
                    F.method = D.getAttribute("method");
                    F.form = D
                }
                F.scope = D
            }
            return F
        }, wrapAnchor:function (D, H) {
            if (D.protocol == "mailto:" || D.protocol == "javascript:") {
                return
            }
            if (D.iswrapped) {
                return
            }
            var G, K;
            if (D.nodeName == "FORM") {
                if (D.attributes.action) {
                    G = D.attributes.action.value
                }
                if (!G) {
                    G = location.href
                }
                var F = document.createElement("a");
                F.href = G;
                var J = B.parseUri(F.href);
                G = J.path;
                K = J.query;
                delete F
            } else {
                if (!D.href) {
                    return
                }
                var J = B.parseUri(D.href);
                G = J.path;
                K = J.query
            }
            if (K && K.startWith("?")) {
                K = K.substring(1)
            }
            if (B.browser.opera || B.browser.msie) {
                G = "/" + G
            }
            var I = this.getOptions(G, K, D);
            if (!I && !H) {
                return
            }
            if (!I) {
                I = {}
            }
            if (!H) {
                H = {}
            }
            B.extend(H, I, 1);
            if (H.type == "skip" || H.type == "nowrap" || (H.wrap != null && !H.wrap) || H.nowrap) {
                return
            }
            if (!H.target && D.attributes.target && D.attributes.target.nodeValue != "") {
                return
            }
            if (H.id == null) {
                return
            }
            this.wrapOps(D, H)
        }, wrapSharp:function (G, M, D) {
            if (G.iswrapped) {
                return
            }
            var K = location.protocol, L = location.host, I = K + "//" + L + location.pathname + location.search + "#", F = G.nodeName == "FORM" ? (G.attributes.action ? G.attributes.action.value : 0) : G.href;
            if (B.browser.opera && F + "#" == I) {
                F += "#"
            }
            if (F && F.endWith("#")) {
                if (!F.startWith(K)) {
                    F = K + "//" + L + F
                }
                if (D) {
                    var J = document.createElement("a");
                    J.href = D + "#";
                    D = J.href;
                    delete J;
                    if (!D.startWith(K)) {
                        var H = D.startWith("/") ? "" : B.parseUri(location.href).directory;
                        D = K + "//" + L + H + D
                    }
                }
                if (F == I || F == D) {
                    if (!M) {
                        M = {}
                    }
                    G.sharp = M.sharp = 1;
                    this.wrapOps(G, M)
                }
            }
        }, wrapOps:function (G, O) {
            if (!O) {
                return
            }
            G.options = O;
            G.iswrapped = 1;
            var M = element.setAttribute("iswrapped");
            M.nodeValue = 1;
            G.setAttributeNode(M);
            var D = G.nodeName == "FORM" ? "submit" : "click", H = "onprev" + D, F = "on" + D;
            if (!O.overwrite && !C.OVERWRITE) {
                if (B.browser.msie) {
                    if (G[F]) {
                        var N = element.setAttribute(H);
                        N.nodeValue = G.attributes[F].nodeValue || G[F];
                        G.setAttributeNode(N)
                    }
                } else {
                    G[H] = G[F]
                }
            }
            if (D == "submit") {
                var K = G.getElementsByTagName("input");
                for (var J = 0, I = K.length; J < I; J++) {
                    var L = K[J].type;
                    if (L != "image" && L != "submit") {
                        continue
                    }
                    SRAX.addEvent(K[J], "click", L == "image" ? function (U) {
                        if (!U) {
                            U = window.event
                        }
                        var Q = U.target || U.srcElement, P = U.offsetX != null ? U.offsetX : U.pageX - Q.offsetLeft + 1, W = U.offsetY != null ? U.offsetY : U.pageY - Q.offsetTop + 1, V = "", R = Q.getAttribute("name"), T = Q.getAttribute("value"), S = R || "";
                        if (S) {
                            S += "."
                        }
                        if (T && R != null) {
                            V += R + "=" + T + "&"
                        }
                        V = "&" + V + S + "x=" + P + "&" + S + "y=" + W;
                        G.submitValue = V
                    } : function (S) {
                        if (!S) {
                            S = window.event
                        }
                        var P = S.target || S.srcElement, Q = P.getAttribute("name"), R = P.getAttribute("value"), T = "";
                        if (Q != null) {
                            T += "&" + Q + "=" + R
                        }
                        G.submitValue = T
                    })
                }
            }
            G[F] = function (T) {
                try {
                    var U = null;
                    if (B.browser.msie) {
                        if (this.attributes[H]) {
                            var S = this.attributes[H].nodeValue;
                            if (S) {
                                if (typeof S == "string") {
                                    S = window["eval"]("SRAX.tmp=function(e){" + S + "}")
                                }
                                U = S.call(this, T)
                            }
                        }
                    } else {
                        if (this[H] && (typeof this[H] == "function")) {
                            U = this[H](T)
                        }
                    }
                    if (U === false) {
                        return false
                    }
                } catch (V) {
                    error(V)
                }
                var Q = this.options;
                if (this.nodeName == "FORM" && this.enctype == "multipart/form-data") {
                    if (Q.multipart) {
                        Q.multipart(this)
                    }
                    return true
                } else {
                    if (!Q.sharp) {
                        try {
                            var P = this.getAttribute("action") || this.href;
                            if (!P) {
                                P = location.href
                            }
                            if (this.nodeName == "FORM" && (!Q.method || Q.method.toLowerCase() != "post")) {
                                var R = B.parseUri(P);
                                P = P.replace("?" + R.query, "").replace("#" + R.anchor, "")
                            }
                            P = B.delHost(P);
                            var W = Q.changer || Q.urlChanger, X = W ? W(P, this) : 0;
                            if (Q.handler) {
                                Q.handler(this, Q)
                            } else {
                                window[Q.type == "data" ? "dax" : "hax"](X ? X : P, Q)
                            }
                        } catch (V) {
                            error(V)
                        }
                    }
                }
                return false
            };
            if (D == "submit") {
                G.submit = G.onsubmit
            }
        }, wrap:function (I, D) {
            if (!I) {
                I = document;
                for (var F in this.schema) {
                    this.wrap(F, D)
                }
            }
            var L, Q = I.nodeName;
            if (Q == "A" || Q == "FORM" || Q == "AREA") {
                L = [I]
            } else {
                I = B.get(I);
                if (!I) {
                    return
                }
                if (A(I)) {
                    I = document
                }
                var P = B.collectionToArray, O = "getElementsByTagName";
                L = P(I[O]("a")).concat(P(I[O]("form")), P(I[O]("area")))
            }
            for (var H = 0, J = L.length; H < J; H++) {
                var G = L[H], N = G.attributes[E("wrap")], M = N == null || (N.nodeValue != "false" && N.nodeValue != "0" && N.nodeValue != false);
                if (G.iswrapped) {
                    G.iswrapped = !!(G.onclick || G.onsubmit)
                }
                if (!G.iswrapped && M) {
                    var R = this.parseAxAttr(G), K = this.fireEvent("beforewrap", {el:G, ops:R, layer:I, url:D});
                    if (K === false) {
                        continue
                    }
                    this.wrapSharp(G, R, D);
                    this.wrapAnchor(G, R)
                }
                G = null
            }
            L = null
        }}, Include:{parse:function (F) {
            if (F) {
                F = B.get(F)
            } else {
                F = document
            }
            var D = F.getElementsByTagName("include");
            while (D.length > 0) {
                B.Include.apply(D[0])
            }
        }, apply:function (H) {
            H = B.get(H);
            var G = B.parseAttr(H), I = B.parseAttr(H, E(""));
            B.extend(G, I);
            if (G && (G.url || G.src)) {
                var D = document.createElement("a");
                if (!G.url) {
                    G.url = G.src
                }
                D.href = G.url;
                I = B.Filter.getOptions(D.pathname, D.search);
                delete D;
                if (I) {
                    B.extend(G, I, 1)
                }
                var F = document.createElement("span");
                F.style.display = "none";
                F.id = G.id = H.id ? H.id : B.genId();
                A(F, 1);
                H.parentNode.replaceChild(F, H);
                if (G.nohistory == null) {
                    G.nohistory = 1
                }
                hax(G)
            }
        }, fix:function (D) {
            if (B.browser.msie && /<include/i.test(D)) {
                D = '<div style="display:none">&nbsp;</div>' + D
            } else {
                if (B.browser.mozilla) {
                    D = D.replaceAll("<INCLUDE", "<include")
                }
            }
            return D
        }}, Uploader:function (I, K, J, G, F) {
            if (typeof I == "object" && I.nodeName != "FORM") {
                K = I.beforeStart;
                J = I.afterFinish;
                G = I.manual;
                F = I.html;
                I = from.form
            }
            var D, H = null, L = this;
            this.init = function () {
                I = B.get(I);
                var N = B.genId();
                I.setAttribute("target", N);
                D = document.createElement("div");
                D.innerHTML = '<iframe style="display:none" src="javascript:true" onload="this._onload()" id="' + N + '" name="' + N + '"></iframe>';
                this.iframe = H = D.firstChild;
                this.setAfterFinish = setAfterFinish = function (O) {
                    H._onload = function () {
                        var Q = this.contentWindow ? this.contentWindow : this.contentDocument, P = Q.document.body, R = P[F ? "innerHTML" : (B.browser.msie ? "innerText" : "textContent")];
                        O(R, L)
                    }
                };
                if (J) {
                    var M = function () {
                        setAfterFinish(J);
                        if (G) {
                            I.submit()
                        }
                    };
                    if (G) {
                        H._onload = M
                    } else {
                        M()
                    }
                } else {
                    H._onload = function () {
                    }
                }
                I.appendChild(D);
                I.setAttribute("target", N);
                if (K) {
                    K(L)
                }
            };
            this.init();
            this.getIframe = function () {
                return H
            };
            this.cancel = function () {
                I.reset();
                L.destroy()
            };
            this.destroy = function () {
                H.src = "javascript:true";
                SRAX.remove(D);
                D = null
            }
        }, addEventsListener:function (D) {
            if (D.prototype) {
                D = D.prototype
            }
            D.on = function (F, J, K) {
                if (!(F instanceof Array)) {
                    F = [F]
                }
                for (var H = 0, G = F.length; H < G; H++) {
                    var I = F[H];
                    if (!K) {
                        this.un(I, J)
                    }
                    if (!this.events) {
                        this.events = {}
                    }
                    if (!this.events[I]) {
                        this.events[I] = []
                    }
                    this.events[I].push(J)
                }
            };
            D.un = function (F, K, I) {
                if (!(F instanceof Array)) {
                    F = [F]
                }
                for (var H = 0, G = F.length; H < G; H++) {
                    var J = F[H];
                    if (!K) {
                        return this.unall(J)
                    }
                    var L = this.events ? this.events[J] : null;
                    if (L) {
                        B.arrayRemoveOf(L, K, !I);
                        this.events[J] = L
                    }
                }
            };
            D.unall = function (F) {
                if (this.events) {
                    if (F) {
                        delete this.events[F]
                    } else {
                        delete this.events
                    }
                }
            };
            D.fireEvent = function (M, H) {
                var F = this.events ? this.events[M] : null;
                if (F) {
                    var K = null, G = [].slice.call(arguments);
                    G.shift();
                    G.push(M);
                    for (var J = 0; J < F.length; J++) {
                        try {
                            var L = F[J].apply(this, G);
                            if (K !== false && L != null) {
                                K = L
                            }
                        } catch (I) {
                            error(I)
                        }
                    }
                    return K
                }
            };
            return D
        }, addContainerListener:function (F) {
            if (F.prototype) {
                F = F.prototype
            }
            var G = {}, D = {};
            F.register = function (I) {
                var L = G[I.id];
                if (L) {
                    for (var K in L) {
                        for (var J = 0, H = L[K].length; J < H; J++) {
                            I.on(K, L[K][J])
                        }
                    }
                }
                for (var K in D) {
                    var L = D[K];
                    for (var J = 0, H = L.length; J < H; J++) {
                        I.on(K, L[J])
                    }
                }
            };
            F.on = function (H, L, K, N) {
                if (!(H instanceof Array)) {
                    H = [H]
                }
                for (var J = 0, I = H.length; J < I; J++) {
                    var M = H[J];
                    if (!G[M]) {
                        G[M] = {}
                    }
                    if (!G[M][L]) {
                        G[M][L] = []
                    }
                    G[M][L].push(K);
                    if (this.thread[M]) {
                        this.thread[M].on(L, K, N)
                    }
                }
            };
            F.onall = function (K, J, L) {
                if (!D[K]) {
                    D[K] = []
                }
                D[K].push(J);
                var I = this.thread;
                for (var H in I) {
                    if (I[H]) {
                        I[H].on(K, J, L)
                    }
                }
            };
            F.unall = function (M, L, J) {
                if (M) {
                    if (L) {
                        var H = D[M];
                        B.arrayRemoveOf(H, L, !J);
                        D[M] = H
                    } else {
                        D[M] = []
                    }
                } else {
                    D = {}
                }
                var K = this.thread;
                for (var I in K) {
                    if (K[I]) {
                        K[I].un(M, L, J)
                    }
                }
            };
            F.un = function (O, H, J, Q) {
                if (!(O instanceof Array)) {
                    O = [O]
                }
                for (var N = 0, K = O.length; N < K; N++) {
                    var I = O[N];
                    if (!J) {
                        if (I) {
                            if (G[I]) {
                                if (H) {
                                    delete G[I][H]
                                } else {
                                    delete G[I]
                                }
                            }
                        } else {
                            G = {}
                        }
                        var P = {};
                        if (I) {
                            P[I] = this.thread[I]
                        } else {
                            P = this.thread
                        }
                        for (var M in P) {
                            if (P[M]) {
                                P[M].unall(H)
                            }
                        }
                    } else {
                        var L = G[I] ? G[I][H] : null;
                        if (L) {
                            B.arrayRemoveOf(L, J, !Q);
                            G[I][H] = L
                        }
                        if (this.thread[I]) {
                            this.thread[I].un(H, J, Q)
                        }
                    }
                }
            };
            F.fireEvent = function (J, I, H) {
                if (this.thread[J]) {
                    return this.thread[J].fireEvent(I, H)
                }
            };
            return F
        }, Html:{thread:{}, ASYNCHRONOUS:1, storage:[]}, Data:{thread:{}}, playsound:function (G, D) {
            var H = document.createElement("div");
            if (D == null) {
                D = 10
            }
            H.setAttribute("style", "position:absolute;top:-1000px;left:-1000px");
            if (window.ActiveXObject) {
                var F = document.createElement("bgsound");
                F.src = G;
                H.appendChild(F)
            } else {
                H.innerHTML = '<embed src="' + G + '" loop="false" autostart="true" hidden="true" mastersound>'
            }
            document.body.appendChild(H);
            if (D > 0) {
                setTimeout(function () {
                    H.firstChild.src = "";
                    document.body.removeChild(H)
                }, D * 1000)
            }
        }, enableUBR:function () {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead")
        }, Loader:{show:function () {
            B.showLoading(1, B.getLoader())
        }, hide:function () {
            B.showLoading(0, B.getLoader())
        }}, parseUri:function (J, G) {
            var D = {strictMode:0, key:["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"], q:{name:"queryKey", parser:/(?:^|&)([^&=]*)=?([^&]*)/g}, parser:{strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/, loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/}};
            var K = G ? G : D, I = K.parser[K.strictMode ? "strict" : "loose"].exec(J);
            for (var F = 0, H = {}; F < 14; F++) {
                H[K.key[F]] = I[F] || ""
            }
            H[K.q.name] = {};
            H[K.key[12]].replace(K.q.parser, function (M, L, N) {
                if (L) {
                    H[K.q.name][L] = N
                }
            });
            return H
        }, showMessage:function (F, D, G) {
            if (D == 0) {
                return
            }
            alert("Error " + D + " : " + F + "\n" + G)
        }, replaceHtml:function (G, F) {
            var D = (typeof G === "string" ? document.getElementById(G) : G);
            var H = D.cloneNode(false);
            H.innerHTML = F;
            D.parentNode.replaceChild(H, D);
            return H
        }, addTo:function (F, G) {
            var D = G ? B.get(G) : document.body;
            if (!D) {
                return warn("Warning => addTo : element = " + G + " not found")
            }
            var I = document.createElement("div");
            I.innerHTML = F.join ? F.join("") : F;
            var H = A(D);
            while (I.childNodes.length > 0) {
                if (H) {
                    D.parentNode.insertBefore(I.childNodes[0], D)
                } else {
                    D.appendChild(I.childNodes[0])
                }
            }
            return D
        }, writeTo:function (F, G) {
            var D = G ? B.get(G) : document.body;
            if (!D) {
                return warn("Warning => writeTo : element = " + G + " not found")
            }
            if (A(D)) {
                B.addTo(F, D)
            } else {
                D.innerHTML = F.join ? F.join("") : F
            }
            return D
        }, remove:function (D) {
            D = D instanceof Array ? D : [D];
            for (var G = 0, F = D.length; G < F; G++) {
                var H = B.get(D[G]);
                if (H) {
                    H.parentNode.removeChild(H)
                }
            }
        }, replace:function (F, D) {
            F = B.get(F);
            D = B.get(D);
            return D.parentNode.replaceChild(F, D)
        }, genId:function () {
            return E("genid" + C.sprt) + (B.lastGenId ? ++B.lastGenId : B.lastGenId = 1)
        }});
        var C = B.Default;
        var E = function (D) {
            return C.prefix + C.sprt + D
        };
        var A = B.placeMark = function (G, D) {
            var F = E("place" + C.sprt + "mark");
            if (G && D != null) {
                G[F] = D
            }
            return G ? (D == null ? G[F] : G) : F
        };
        B.addEventsListener(B.Filter);
        B.escape = B.encode;
        B.appendScript = B.addScript;
        B.appendLink = B.addLink;
        B.appendStyle = B.addStyle;
        arrayIndexOf = B.arrayIndexOf;
        arrayRemoveOf = B.arrayRemoveOf
    })(SRAX);
    SRAX.init()
}
;