! function(e) {
    function t(r) {
        if (n[r]) return n[r].exports;
        var o = n[r] = {
            i: r,
            l: !1,
            exports: {}
        };
        return e[r].call(o.exports, o, o.exports, t), o.l = !0, o.exports
    }
    var n = {};
    t.m = e, t.c = n, t.i = function(e) {
        return e
    }, t.d = function(e, n, r) {
        t.o(e, n) || Object.defineProperty(e, n, {
            configurable: !1,
            enumerable: !0,
            get: r
        })
    }, t.n = function(e) {
        var n = e && e.__esModule ? function() {
            return e.default
        } : function() {
            return e
        };
        return t.d(n, "a", n), n
    }, t.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, t.p = "", t(t.s = 8)
}([function(e, t, n) {
    "use strict";

    function r(e, t) {
        t = t || {}, (t.debug || d.debug) && console.log("[sw-toolbox] " + e)
    }

    function o(e) {
        var t;
        return e && e.cache && (t = e.cache.name), t = t || d.cache.name, caches.open(t)
    }

    function a(e, t) {
        t = t || {};
        var n = t.successResponses || d.successResponses;
        return fetch(e.clone()).then(function(r) {
            return "GET" === e.method && n.test(r.status) && o(t).then(function(n) {
                n.put(e, r).then(function() {
                    var r = t.cache || d.cache;
                    (r.maxEntries || r.maxAgeSeconds) && r.name && c(e, n, r)
                })
            }), r.clone()
        })
    }

    function c(e, t, n) {
        var r = i.bind(null, e, t, n);
        p = p ? p.then(r) : r()
    }

    function i(e, t, n) {
        var o = e.url,
            a = n.maxAgeSeconds,
            c = n.maxEntries,
            i = n.name,
            s = Date.now();
        return r("Updating LRU order for " + o + ". Max entries is " + c + ", max age is " + a), m.getDb(i).then(function(e) {
            return m.setTimestampForUrl(e, o, s)
        }).then(function(e) {
            return m.expireEntries(e, c, a, s)
        }).then(function(e) {
            r("Successfully updated IDB.");
            var n = e.map(function(e) {
                return t.delete(e)
            });
            return Promise.all(n).then(function() {
                r("Done with cache cleanup.")
            })
        }).catch(function(e) {
            r(e)
        })
    }

    function s(e, t, n) {
        return r("Renaming cache: [" + e + "] to [" + t + "]", n), caches.delete(t).then(function() {
            return Promise.all([caches.open(e), caches.open(t)]).then(function(t) {
                var n = t[0],
                    r = t[1];
                return n.keys().then(function(e) {
                    return Promise.all(e.map(function(e) {
                        return n.match(e).then(function(t) {
                            return r.put(e, t)
                        })
                    }))
                }).then(function() {
                    return caches.delete(e)
                })
            })
        })
    }

    function u(e, t) {
        return o(t).then(function(t) {
            return t.add(e)
        })
    }

    function f(e, t) {
        return o(t).then(function(t) {
            return t.delete(e)
        })
    }

    function h(e) {
        e instanceof Promise || l(e), d.preCacheItems = d.preCacheItems.concat(e)
    }

    function l(e) {
        var t = Array.isArray(e);
        if (t && e.forEach(function(e) {
                "string" == typeof e || e instanceof Request || (t = !1)
            }), !t) throw new TypeError("The precache method expects either an array of strings and/or Requests or a Promise that resolves to an array of strings and/or Requests.");
        return e
    }
    var p, d = n(1),
        m = n(12);
    e.exports = {
        debug: r,
        fetchAndCache: a,
        openCache: o,
        renameCache: s,
        cache: u,
        uncache: f,
        precache: h,
        validatePrecacheInput: l
    }
}, function(e, t, n) {
    "use strict";
    var r;
    r = self.registration ? self.registration.scope : self.scope || new URL("./", self.location).href, e.exports = {
        cache: {
            name: "$$$toolbox-cache$$$" + r + "$$$",
            maxAgeSeconds: null,
            maxEntries: null
        },
        debug: !1,
        networkTimeoutSeconds: null,
        preCacheItems: [],
        successResponses: /^0|([123]\d\d)|(40[14567])|410$/
    }
}, function(e, t, n) {
    "use strict";

    function r(e) {
        return e.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
    }
    var o = n(14),
        a = n(0),
        c = function(e, t) {
            for (var n = e.entries(), r = n.next(), o = []; !r.done;) {
                new RegExp(r.value[0]).test(t) && o.push(r.value[1]), r = n.next()
            }
            return o
        },
        i = function() {
            this.routes = new Map, this.routes.set(RegExp, new Map), this.default = null
        };
    ["get", "post", "put", "delete", "head", "any"].forEach(function(e) {
        i.prototype[e] = function(t, n, r) {
            return this.add(e, t, n, r)
        }
    }), i.prototype.add = function(e, t, n, c) {
        c = c || {};
        var i;
        t instanceof RegExp ? i = RegExp : (i = c.origin || self.location.origin, i = i instanceof RegExp ? i.source : r(i)), e = e.toLowerCase();
        var s = new o(e, t, n, c);
        this.routes.has(i) || this.routes.set(i, new Map);
        var u = this.routes.get(i);
        u.has(e) || u.set(e, new Map);
        var f = u.get(e),
            h = s.regexp || s.fullUrlRegExp;
        f.has(h.source) && a.debug('"' + t + '" resolves to same regex as existing route.'), f.set(h.source, s)
    }, i.prototype.matchMethod = function(e, t) {
        var n = new URL(t),
            r = n.origin,
            o = n.pathname;
        return this._match(e, c(this.routes, r), o) || this._match(e, [this.routes.get(RegExp)], t)
    }, i.prototype._match = function(e, t, n) {
        if (0 === t.length) return null;
        for (var r = 0; r < t.length; r++) {
            var o = t[r],
                a = o && o.get(e.toLowerCase());
            if (a) {
                var i = c(a, n);
                if (i.length > 0) return i[0].makeHandler(n)
            }
        }
        return null
    }, i.prototype.match = function(e) {
        return this.matchMethod(e.method, e.url) || this.matchMethod("any", e.url)
    }, e.exports = new i
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        return o.debug("Strategy: cache only [" + e.url + "]", n), o.openCache(n).then(function(t) {
            return t.match(e)
        })
    }
    var o = n(0);
    e.exports = r
}, function(e, t, n) {
    "use strict";
    var r = n(10),
        o = n.n(r),
        a = o.a.canUseDOM,
        c = "undefined" != typeof navigator ? navigator.userAgent : "StandardUA",
        i = {
            "X-user-agent": c + " FKUA/website/41/website/Desktop",
            "Content-Type": "application/json"
        };
    a || Object.assign(i, {
        compress: !0,
        Connection: "keep-alive",
        "Keep-Alive": "timeout=600"
    });
    var s = a ? "https" : "http",
        u = Object.assign({}, {
            headers: i
        }, {
            protocol: s,
            hostname: "friendstub.com",
            credentials: "include",
            fk_api_timeout: a ? 3e4 : 4e3
        });
    t.a = u
}, function(e, t, n) {
    "use strict";

    function r(e) {
        for (var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [], n = arguments[2], r = e, o = 0; o < t.length;) {
            if (!r) return n;
            r = r[t[o]], o++
        }
        return r
    }
    t.a = r
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n, e
    }

    function o(e, t, n) {
        var r = e.url.replace(/sqid=([^&]*)/, "").replace(/ssid=([^&]*)/, "");
        return l.a.openCache(n).then(function(t) {
            return s(r, t, n, "get", e)
        }).catch(function(e) {
            throw new Error(e)
        })
    }

    function a(e, t, n) {
        return i(e.clone()).then(function(t) {
            return l.a.openCache(n).then(function(r) {
                return s(t, r, n, "post", e)
            })
        }).catch(function(e) {
            throw new Error(e)
        })
    }

    function c(e) {
        return i(e.clone()).then(function(t) {
            return fetch(e).then(function(e) {
                return {
                    url: t,
                    response: e.clone()
                }
            })
        })
    }

    function i(e) {
        return e.json().then(function(t) {
            var r = JSON.stringify(t),
                o = r.replace(/"(ssid|sqid)":".*?"/g, ""),
                a = n.i(p.a)(e.url + o);
            return e.url + "?payload=" + a
        })
    }

    function s(e, t, n) {
        var o = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : "get",
            a = arguments[4],
            i = 1e3 * n.cache.maxAgeSeconds,
            s = new Request(e + (e.indexOf("?") > -1 ? "&" : "?") + "$cached$timestamp$");
        return Promise.all([t.match(e), t.match(s)]).then(function(n) {
            var u = n[0],
                f = n[1];
            return u && f && Date.now() < parseInt(f.headers.get("created-time"), 10) + i ? u : "get" === o ? fetch(a).then(function(n) {
                return 200 === n.status && (t.put(e, n.clone()), t.put(s, new Response(null, {
                    headers: r({}, "created-time", Date.now())
                }))), n
            }) : c(a).then(function(n) {
                return n.response.ok && (t.put(s, new Response(null, {
                    headers: r({}, "created-time", Date.now())
                })), t.put(e, n.response.clone())), n.response
            })
        })
    }

    function u(e, t) {
        return function() {
            l.a.uncache(e, t)
        }
    }

    function f(e, t, n) {
        var r = {
                type: "PN",
                eventType: e,
                timestamp: (new Date).getTime(),
                messageId: t.messageId,
                contextId: t.contextId,
                cargo: n
            },
            o = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "x-api-key": "KrWcJnCSZFBLFR39DtHYySjcDCHg2LeC3sxdx7646n7iy7oy"
                },
                body: JSON.stringify(r)
            };
        return fetch("https://connekt.flipkart.net/v1/push/callback/openweb/fkwebsite/" + t.deviceId, o)
    }
    var h = n(0),
        l = n.n(h),
        p = n(9);
    t.a = {
        cacheFirst: o,
        cachePost: a,
        webpushCallBack: f,
        uncache: u
    }
}, function(e, t, n) {
    "use strict";
    var r = n(1),
        o = n(2),
        a = n(0),
        c = n(17),
        i = n(13);
    a.debug("Service Worker Toolbox is loading"), self.addEventListener("install", i.installListener), self.addEventListener("activate", i.activateListener), self.addEventListener("fetch", i.fetchListener), e.exports = {
        networkOnly: c.networkOnly,
        networkFirst: c.networkFirst,
        cacheOnly: c.cacheOnly,
        cacheFirst: c.cacheFirst,
        fastest: c.fastest,
        router: o,
        options: r,
        cache: a.cache,
        uncache: a.uncache,
        precache: a.precache
    }
}, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var r = n(7),
        o = n.n(r),
        a = n(4),
        c = n(6),
        i = n(5),
        s = ["static", "mainBundles", "layouts", "pincodes", "fonts", "widgets", "sherlock", "facets", "summary", "swatches", "autosuggest", "searchSummary", "product", "reco", "lc", "self-serve"],
        u = {};
    s.forEach(function(e) {
        u[e] = e + 5
    });
    self.addEventListener("install", function(e) {
        e.waitUntil(self.skipWaiting())
    }), self.addEventListener("activate", function(e) {
        e.waitUntil(self.clients.claim()), e.waitUntil(caches.keys().then(function(e) {
            var t = Object.keys(u).map(function(e) {
                return u[e]
            });
            return Promise.all(e.map(function(e) {
                return t.indexOf(e) === -1 && e.indexOf("$$$inactive$$$") === -1 ? caches.delete(e) : Promise.resolve()
            }))
        }))
    }), self.addEventListener("push", function(e) {
        if (e.data) try {
            var t = e.data.json(),
                r = t.payload;
            if (r) {
                var o = n.i(i.a)(r, ["title"]),
                    a = {
                        body: r.body,
                        icon: r.icon,
                        image: r.image,
                        tag: "notification",
                        data: t
                    };
                r.actions && r.actions.length > 0 && (a.actions = [], r.actions.forEach(function(e) {
                    a.actions.push({
                        icon: e.icon,
                        title: e.title,
                        action: e.action
                    })
                })), e.waitUntil(Promise.all([self.registration.showNotification(o, a), c.a.webpushCallBack("RECEIVED", t)]))
            }
        } catch (e) {}
    }), self.addEventListener("notificationclick", function(e) {
        e.notification.close();
        var t = void 0;
        if (e.action) {
            var r = n.i(i.a)(e, ["notification", "data", "payload", "actions"]);
            if (r && Array.isArray(r)) {
                var o = r.filter(function(t) {
                    return e.action === t.action
                });
                1 == o.length && (t = n.i(i.a)(o, [0, "landingUrl"]))
            }
        } else t = n.i(i.a)(e, ["notification", "data", "payload", "landingUrl"]);
        t ? e.waitUntil(Promise.all([clients.openWindow(t), c.a.webpushCallBack("READ", e.notification.data)])) : e.waitUntil(self.skipWaiting())
    }), self.addEventListener("notificationclose", function(e) {
        e.waitUntil(c.a.webpushCallBack("DISMISS", e.notification.data))
    }), navigator.userAgent.indexOf("Firefox/44.0") > -1 && self.addEventListener("fetch", function(e) {
        e.respondWith(fetch(e.request))
    });
    var f = {
        cache: {
            maxEntries: 15
        },
        origin: "https://" + a.a.hostname
    };
    o.a.router.get("/(.*)", o.a.cacheFirst, Object.assign({}, f, {
        origin: "https://cdn.jsdelivr.net",
        cache: {
            name: u.mainBundles,
            maxEntries: 5,
            maxAgeSeconds: 604800
        }
    })), o.a.router.get("/ajax/(.*)", o.a.cacheFirst, Object.assign({}, f, {
        origin: "https://cdnjs.cloudflare.com",
        cache: {
            name: u.mainBundles,
            maxEntries: 5,
            maxAgeSeconds: 604800
        }
    })), o.a.router.get("/friendstub.com/(.*)", o.a.cacheFirst, {
        origin: "https://i1.wp.com",
        cache: {
            name: u.static,
            maxEntries: 50
        }
    }), o.a.router.get("/ajax/(.*)", o.a.cacheFirst, Object.assign({}, f, {
        origin: "https://cdnjs.cloudflare.com",
        cache: {
            name: u.mainBundles,
            maxEntries: 5,
            maxAgeSeconds: 604800
        }
    })), o.a.router.get("/uploads/(.*)", o.a.cacheFirst, Object.assign({}, f, {
        origin: "https://static.giantbomb.com",
        cache: {
            name: u.mainBundles,
            maxEntries: 5,
            maxAgeSeconds: 604800
        }
    })), o.a.router.get("/s/(.*)", o.a.fastest, {
        origin: "https://fonts.gstatic.com",
        cache: {
            name: u.fonts,
            maxEntries: 5
        }
    })
}, function(e, t, n) {
    "use strict";

    function r(e) {
        var t = 0;
        if (0 === e.length) return t;
        for (var n = 0; n < e.length; n++) {
            t = (t << 5) - t + e.charCodeAt(n), t &= t
        }
        return t
    }
    t.a = r;
    ! function() {
        function e(e) {
            for (var n = void 0, r = void 0, o = void 0, a = void 0, c = [], i = unescape(encodeURI(e)), s = i.length, u = [n = 1732584193, r = -271733879, ~n, ~r], f = 0; f <= s;) c[f >> 2] |= (i.charCodeAt(f) || 128) << f++ % 4 * 8;
            for (c[e = 16 * (s + 8 >> 6) + 14] = 8 * s, f = 0; f < e; f += 16) {
                for (s = u, a = 0; a < 64;) s = [o = s[3], (n = 0 | s[1]) + ((o = s[0] + [n & (r = s[2]) | ~n & o, o & n | ~o & r, n ^ r ^ o, r ^ (n | ~o)][s = a >> 4] + (t[a] + (0 | c[[a, 5 * a + 1, 3 * a + 5, 7 * a][s] % 16 + f]))) << (s = [7, 12, 17, 22, 5, 9, 14, 20, 4, 11, 16, 23, 6, 10, 15, 21][4 * s + a++ % 4]) | o >>> 32 - s), n, r];
                for (a = 4; a;) u[--a] = u[a] + s[a]
            }
            for (e = ""; a < 32;) e += (u[a >> 3] >> 4 * (1 ^ 7 & a++) & 15).toString(16);
            return e
        }
        for (var t = [], n = 0; n < 64;) t[n] = 0 | 4294967296 * Math.abs(Math.sin(++n));
        e
    }()
}, function(e, t, n) {
    "use strict";
    var r = !("undefined" == typeof window || !window.document || !window.document.createElement),
        o = {
            canUseDOM: r,
            canUseWorkers: "undefined" != typeof Worker,
            canUseEventListeners: r && !(!window.addEventListener && !window.attachEvent),
            canUseViewport: r && !!window.screen,
            isInWorker: !r
        };
    e.exports = o
}, function(e, t) {
    ! function() {
        var e = Cache.prototype.addAll,
            t = navigator.userAgent.match(/(Firefox|Chrome)\/(\d+\.)/);
        if (t) var n = t[1],
            r = parseInt(t[2]);
        e && (!t || "Firefox" === n && r >= 46 || "Chrome" === n && r >= 50) || (Cache.prototype.addAll = function(e) {
            function t(e) {
                this.name = "NetworkError", this.code = 19, this.message = e
            }
            var n = this;
            return t.prototype = Object.create(Error.prototype), Promise.resolve().then(function() {
                if (arguments.length < 1) throw new TypeError;
                return e = e.map(function(e) {
                    return e instanceof Request ? e : String(e)
                }), Promise.all(e.map(function(e) {
                    "string" == typeof e && (e = new Request(e));
                    var n = new URL(e.url).protocol;
                    if ("http:" !== n && "https:" !== n) throw new t("Invalid scheme");
                    return fetch(e.clone())
                }))
            }).then(function(r) {
                if (r.some(function(e) {
                        return !e.ok
                    })) throw new t("Incorrect response status");
                return Promise.all(r.map(function(t, r) {
                    return n.put(e[r], t)
                }))
            }).then(function() {})
        }, Cache.prototype.add = function(e) {
            return this.addAll([e])
        })
    }()
}, function(e, t, n) {
    "use strict";

    function r(e) {
        return new Promise(function(t, n) {
            var r = indexedDB.open(u + e, f);
            r.onupgradeneeded = function() {
                r.result.createObjectStore(h, {
                    keyPath: l
                }).createIndex(p, p, {
                    unique: !1
                })
            }, r.onsuccess = function() {
                t(r.result)
            }, r.onerror = function() {
                n(r.error)
            }
        })
    }

    function o(e) {
        return e in d || (d[e] = r(e)), d[e]
    }

    function a(e, t, n) {
        return new Promise(function(r, o) {
            var a = e.transaction(h, "readwrite");
            a.objectStore(h).put({
                url: t,
                timestamp: n
            }), a.oncomplete = function() {
                r(e)
            }, a.onabort = function() {
                o(a.error)
            }
        })
    }

    function c(e, t, n) {
        return t ? new Promise(function(r, o) {
            var a = 1e3 * t,
                c = [],
                i = e.transaction(h, "readwrite"),
                s = i.objectStore(h);
            s.index(p).openCursor().onsuccess = function(e) {
                var t = e.target.result;
                if (t && n - a > t.value[p]) {
                    var r = t.value[l];
                    c.push(r), s.delete(r), t.continue()
                }
            }, i.oncomplete = function() {
                r(c)
            }, i.onabort = o
        }) : Promise.resolve([])
    }

    function i(e, t) {
        return t ? new Promise(function(n, r) {
            var o = [],
                a = e.transaction(h, "readwrite"),
                c = a.objectStore(h),
                i = c.index(p),
                s = i.count();
            i.count().onsuccess = function() {
                var e = s.result;
                e > t && (i.openCursor().onsuccess = function(n) {
                    var r = n.target.result;
                    if (r) {
                        var a = r.value[l];
                        o.push(a), c.delete(a), e - o.length > t && r.continue()
                    }
                })
            }, a.oncomplete = function() {
                n(o)
            }, a.onabort = r
        }) : Promise.resolve([])
    }

    function s(e, t, n, r) {
        return c(e, n, r).then(function(n) {
            return i(e, t).then(function(e) {
                return n.concat(e)
            })
        })
    }
    var u = "sw-toolbox-",
        f = 1,
        h = "store",
        l = "url",
        p = "timestamp",
        d = {};
    e.exports = {
        getDb: o,
        setTimestampForUrl: a,
        expireEntries: s
    }
}, function(e, t, n) {
    "use strict";

    function r(e) {
        var t = s.match(e.request);
        t ? e.respondWith(t(e.request)) : s.default && "GET" === e.request.method && 0 === e.request.url.indexOf("http") && e.respondWith(s.default(e.request))
    }

    function o(e) {
        i.debug("activate event fired");
        var t = u.cache.name + "$$$inactive$$$";
        e.waitUntil(i.renameCache(t, u.cache.name))
    }

    function a(e) {
        return e.reduce(function(e, t) {
            return e.concat(t)
        }, [])
    }

    function c(e) {
        var t = u.cache.name + "$$$inactive$$$";
        i.debug("install event fired"), i.debug("creating cache [" + t + "]"), e.waitUntil(i.openCache({
            cache: {
                name: t
            }
        }).then(function(e) {
            return Promise.all(u.preCacheItems).then(a).then(i.validatePrecacheInput).then(function(t) {
                return i.debug("preCache list: " + (t.join(", ") || "(none)")), e.addAll(t)
            })
        }))
    }
    n(11);
    var i = n(0),
        s = n(2),
        u = n(1);
    e.exports = {
        fetchListener: r,
        activateListener: o,
        installListener: c
    }
}, function(e, t, n) {
    "use strict";
    var r = new URL("./", self.location),
        o = r.pathname,
        a = n(21),
        c = function(e, t, n, r) {
            t instanceof RegExp ? this.fullUrlRegExp = t : (0 !== t.indexOf("/") && (t = o + t), this.keys = [], this.regexp = a(t, this.keys)), this.method = e, this.options = r, this.handler = n
        };
    c.prototype.makeHandler = function(e) {
        var t;
        if (this.regexp) {
            var n = this.regexp.exec(e);
            t = {}, this.keys.forEach(function(e, r) {
                t[e.name] = n[r + 1]
            })
        }
        return function(e) {
            return this.handler(e, t, this.options)
        }.bind(this)
    }, e.exports = c
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        return o.debug("Strategy: cache first [" + e.url + "]", n), o.openCache(n).then(function(t) {
            return t.match(e).then(function(t) {
                return t ? t : o.fetchAndCache(e, n)
            })
        })
    }
    var o = n(0);
    e.exports = r
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        return o.debug("Strategy: fastest [" + e.url + "]", n), new Promise(function(r, c) {
            var i = !1,
                s = [],
                u = function(e) {
                    s.push(e.toString()), i ? c(new Error('Both cache and network failed: "' + s.join('", "') + '"')) : i = !0
                },
                f = function(e) {
                    e instanceof Response ? r(e) : u("No result returned")
                };
            o.fetchAndCache(e.clone(), n).then(f, u), a(e, t, n).then(f, u)
        })
    }
    var o = n(0),
        a = n(3);
    e.exports = r
}, function(e, t, n) {
    e.exports = {
        networkOnly: n(19),
        networkFirst: n(18),
        cacheOnly: n(3),
        cacheFirst: n(15),
        fastest: n(16)
    }
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        n = n || {};
        var r = n.successResponses || o.successResponses,
            c = n.networkTimeoutSeconds || o.networkTimeoutSeconds;
        return a.debug("Strategy: network first [" + e.url + "]", n), a.openCache(n).then(function(t) {
            var o, i, s = [];
            if (c) {
                var u = new Promise(function(n) {
                    o = setTimeout(function() {
                        t.match(e).then(function(e) {
                            e && n(e)
                        })
                    }, 1e3 * c)
                });
                s.push(u)
            }
            var f = a.fetchAndCache(e, n).then(function(e) {
                if (o && clearTimeout(o), r.test(e.status)) return e;
                throw a.debug("Response was an HTTP error: " + e.statusText, n), i = e, new Error("Bad response")
            }).catch(function(r) {
                return a.debug("Network or response error, fallback to cache [" + e.url + "]", n), t.match(e).then(function(e) {
                    if (e) return e;
                    if (i) return i;
                    throw r
                })
            });
            return s.push(f), Promise.race(s)
        })
    }
    var o = n(1),
        a = n(0);
    e.exports = r
}, function(e, t, n) {
    "use strict";

    function r(e, t, n) {
        return o.debug("Strategy: network only [" + e.url + "]", n), fetch(e)
    }
    var o = n(0);
    e.exports = r
}, function(e, t) {
    e.exports = Array.isArray || function(e) {
        return "[object Array]" == Object.prototype.toString.call(e)
    }
}, function(e, t, n) {
    function r(e, t) {
        for (var n, r = [], o = 0, a = 0, c = "", i = t && t.delimiter || "/"; null != (n = w.exec(e));) {
            var f = n[0],
                h = n[1],
                l = n.index;
            if (c += e.slice(a, l), a = l + f.length, h) c += h[1];
            else {
                var p = e[a],
                    d = n[2],
                    m = n[3],
                    g = n[4],
                    v = n[5],
                    x = n[6],
                    y = n[7];
                c && (r.push(c), c = "");
                var b = null != d && null != p && p !== d,
                    E = "+" === x || "*" === x,
                    k = "?" === x || "*" === x,
                    S = n[2] || i,
                    C = g || v;
                r.push({
                    name: m || o++,
                    prefix: d || "",
                    delimiter: S,
                    optional: k,
                    repeat: E,
                    partial: b,
                    asterisk: !!y,
                    pattern: C ? u(C) : y ? ".*" : "[^" + s(S) + "]+?"
                })
            }
        }
        return a < e.length && (c += e.substr(a)), c && r.push(c), r
    }

    function o(e, t) {
        return i(r(e, t))
    }

    function a(e) {
        return encodeURI(e).replace(/[\/?#]/g, function(e) {
            return "%" + e.charCodeAt(0).toString(16).toUpperCase()
        })
    }

    function c(e) {
        return encodeURI(e).replace(/[?#]/g, function(e) {
            return "%" + e.charCodeAt(0).toString(16).toUpperCase()
        })
    }

    function i(e) {
        for (var t = new Array(e.length), n = 0; n < e.length; n++) "object" == typeof e[n] && (t[n] = new RegExp("^(?:" + e[n].pattern + ")$"));
        return function(n, r) {
            for (var o = "", i = n || {}, s = r || {}, u = s.pretty ? a : encodeURIComponent, f = 0; f < e.length; f++) {
                var h = e[f];
                if ("string" != typeof h) {
                    var l, p = i[h.name];
                    if (null == p) {
                        if (h.optional) {
                            h.partial && (o += h.prefix);
                            continue
                        }
                        throw new TypeError('Expected "' + h.name + '" to be defined')
                    }
                    if (v(p)) {
                        if (!h.repeat) throw new TypeError('Expected "' + h.name + '" to not repeat, but received `' + JSON.stringify(p) + "`");
                        if (0 === p.length) {
                            if (h.optional) continue;
                            throw new TypeError('Expected "' + h.name + '" to not be empty')
                        }
                        for (var d = 0; d < p.length; d++) {
                            if (l = u(p[d]), !t[f].test(l)) throw new TypeError('Expected all "' + h.name + '" to match "' + h.pattern + '", but received `' + JSON.stringify(l) + "`");
                            o += (0 === d ? h.prefix : h.delimiter) + l
                        }
                    } else {
                        if (l = h.asterisk ? c(p) : u(p), !t[f].test(l)) throw new TypeError('Expected "' + h.name + '" to match "' + h.pattern + '", but received "' + l + '"');
                        o += h.prefix + l
                    }
                } else o += h
            }
            return o
        }
    }

    function s(e) {
        return e.replace(/([.+*?=^!:${}()[\]|\/\\])/g, "\\$1")
    }

    function u(e) {
        return e.replace(/([=!:$\/()])/g, "\\$1")
    }

    function f(e, t) {
        return e.keys = t, e
    }

    function h(e) {
        return e.sensitive ? "" : "i"
    }

    function l(e, t) {
        var n = e.source.match(/\((?!\?)/g);
        if (n)
            for (var r = 0; r < n.length; r++) t.push({
                name: r,
                prefix: null,
                delimiter: null,
                optional: !1,
                repeat: !1,
                partial: !1,
                asterisk: !1,
                pattern: null
            });
        return f(e, t)
    }

    function p(e, t, n) {
        for (var r = [], o = 0; o < e.length; o++) r.push(g(e[o], t, n).source);
        return f(new RegExp("(?:" + r.join("|") + ")", h(n)), t)
    }

    function d(e, t, n) {
        return m(r(e, n), t, n)
    }

    function m(e, t, n) {
        v(t) || (n = t || n, t = []), n = n || {};
        for (var r = n.strict, o = n.end !== !1, a = "", c = 0; c < e.length; c++) {
            var i = e[c];
            if ("string" == typeof i) a += s(i);
            else {
                var u = s(i.prefix),
                    l = "(?:" + i.pattern + ")";
                t.push(i), i.repeat && (l += "(?:" + u + l + ")*"), l = i.optional ? i.partial ? u + "(" + l + ")?" : "(?:" + u + "(" + l + "))?" : u + "(" + l + ")", a += l
            }
        }
        var p = s(n.delimiter || "/"),
            d = a.slice(-p.length) === p;
        return r || (a = (d ? a.slice(0, -p.length) : a) + "(?:" + p + "(?=$))?"), a += o ? "$" : r && d ? "" : "(?=" + p + "|$)", f(new RegExp("^" + a, h(n)), t)
    }

    function g(e, t, n) {
        return v(t) || (n = t || n, t = []), n = n || {}, e instanceof RegExp ? l(e, t) : v(e) ? p(e, t, n) : d(e, t, n)
    }
    var v = n(20);
    e.exports = g, e.exports.parse = r, e.exports.compile = o, e.exports.tokensToFunction = i, e.exports.tokensToRegExp = m;
    var w = new RegExp(["(\\\\.)", "([\\/.])?(?:(?:\\:(\\w+)(?:\\(((?:\\\\.|[^\\\\()])+)\\))?|\\(((?:\\\\.|[^\\\\()])+)\\))([+*?])?|(\\*))"].join("|"), "g")
}]);