// 创建空日志函数替代console.log，保留原始console.log的引用以备不时之需
const originalConsoleLog = console.log;
console.log = function() {
    // 完全静默或者可以根据需要选择性地使用原始console.log
    // originalConsoleLog.apply(console, arguments);
};

document.addEventListener("DOMContentLoaded", function () {
    let headerContentWidth, $nav;
    let mobileSidebarOpen = false;

    const adjustMenu = (init) => {
        const getAllWidth = (ele) => {
            let width = 0;
            ele.length &&
                Array.from(ele).forEach((i) => {
                    width += i.offsetWidth;
                });
            return width;
        };

        if (init) {
            const blogInfoWidth = getAllWidth(
                document.querySelector("#blog-info > a").children
            );
            const menusWidth = getAllWidth(
                document.getElementById("menus").children
            );
            headerContentWidth = blogInfoWidth + menusWidth;
            $nav = document.getElementById("nav");
        }

        let hideMenuIndex = "";
        if (window.innerWidth <= 768) hideMenuIndex = true;
        else hideMenuIndex = headerContentWidth > $nav.offsetWidth - 120;

        if (hideMenuIndex) {
            $nav.classList.add("hide-menu");
        } else {
            $nav.classList.remove("hide-menu");
        }
    };

    // 初始化header
    const initAdjust = () => {
        adjustMenu(true);
        $nav.classList.add("show");
    };

    // sidebar menus
    const sidebarFn = {
        open: () => {
            btf.sidebarPaddingR();
            document.body.style.overflow = "hidden";
            btf.animateIn(document.getElementById("menu-mask"), "to-show");
            document.getElementById("sidebar-menus").classList.add("open");
            mobileSidebarOpen = true;
        },
        close: () => {
            const $body = document.body;
            $body.style.overflow = "";
            $body.style.paddingRight = "";
            btf.animateOut(
                document.getElementById("menu-mask"),
                "to-hide"
            );
            document.getElementById("sidebar-menus").classList.remove("open");
            mobileSidebarOpen = false;
        },
    };

    /**
     * 首頁top_img底下的箭頭
     */
    const scrollDownInIndex = () => {
        const $scrollDownEle = document.getElementById("scroll-down");
        $scrollDownEle &&
            $scrollDownEle.addEventListener("click", function () {
                btf.scrollToDest(
                    document.getElementById("content-inner").offsetTop,
                    300
                );
            });
    };

    /**
     * 代碼
     * 只適用於Hexo默認的代碼渲染
     */
    const addHighlightTool = function () {
        const highLight = GLOBAL_CONFIG.highlight;
        if (!highLight) return;

        const isHighlightCopy = highLight.highlightCopy;
        const isHighlightLang = highLight.highlightLang;
        const isHighlightShrink = GLOBAL_CONFIG_SITE.isHighlightShrink;
        const highlightHeightLimit = highLight.highlightHeightLimit;
        const isShowTool =
            isHighlightCopy ||
            isHighlightLang ||
            isHighlightShrink !== undefined;
        const $figureHighlight =
            highLight.plugin === "highlighjs"
                ? document.querySelectorAll("figure.highlight")
                : document.querySelectorAll('pre[class*="language-"]');

        if (!((isShowTool || highlightHeightLimit) && $figureHighlight.length))
            return;

        const isPrismjs = highLight.plugin === "prismjs";

        let highlightShrinkEle = "";
        let highlightCopyEle = "";
        const highlightShrinkClass = isHighlightShrink === true ? "closed" : "";

        if (isHighlightShrink !== undefined) {
            highlightShrinkEle = `<i class="fas fa-angle-down expand ${highlightShrinkClass}"></i>`;
        }

        if (isHighlightCopy) {
            highlightCopyEle =
                '<div class="copy-notice"></div><i class="fas fa-paste copy-button"></i>';
        }

        const copy = (text, ctx) => {
            if (
                document.queryCommandSupported &&
                document.queryCommandSupported("copy")
            ) {
                document.execCommand("copy");
                if (GLOBAL_CONFIG.Snackbar !== undefined) {
                    btf.snackbarShow(GLOBAL_CONFIG.copy.success);
                } else {
                    const prevEle = ctx.previousElementSibling;
                    prevEle.innerText = GLOBAL_CONFIG.copy.success;
                    prevEle.style.opacity = 1;
                    setTimeout(() => {
                        prevEle.style.opacity = 0;
                    }, 700);
                }
            } else {
                if (GLOBAL_CONFIG.Snackbar !== undefined) {
                    btf.snackbarShow(GLOBAL_CONFIG.copy.noSupport);
                } else {
                    ctx.previousElementSibling.innerText =
                        GLOBAL_CONFIG.copy.noSupport;
                }
            }
        };

        // click events
        const highlightCopyFn = (ele) => {
            const $buttonParent = ele.parentNode;
            $buttonParent.classList.add("copy-true");
            const selection = window.getSelection();
            const range = document.createRange();
            if (isPrismjs)
                range.selectNodeContents(
                    $buttonParent.querySelectorAll("pre code")[0]
                );
            else
                range.selectNodeContents(
                    $buttonParent.querySelectorAll("table .code pre")[0]
                );
            selection.removeAllRanges();
            selection.addRange(range);
            const text = selection.toString();
            copy(text, ele.lastChild);
            selection.removeAllRanges();
            $buttonParent.classList.remove("copy-true");
        };

        const highlightShrinkFn = (ele) => {
            const $nextEle = [...ele.parentNode.children].slice(1);
            ele.firstChild.classList.toggle("closed");
            if (btf.isHidden($nextEle[$nextEle.length - 1])) {
                $nextEle.forEach((e) => {
                    e.style.display = "block";
                });
            } else {
                $nextEle.forEach((e) => {
                    e.style.display = "none";
                });
            }
        };

        const highlightToolsFn = function (e) {
            const $target = e.target.classList;
            if ($target.contains("expand")) highlightShrinkFn(this);
            else if ($target.contains("copy-button")) highlightCopyFn(this);
        };

        const expandCode = function () {
            this.classList.toggle("expand-done");
        };

        function createEle(lang, item, service) {
            const fragment = document.createDocumentFragment();

            if (isShowTool) {
                const hlTools = document.createElement("div");
                hlTools.className = `highlight-tools ${highlightShrinkClass}`;
                hlTools.innerHTML =
                    highlightShrinkEle + lang + highlightCopyEle;
                hlTools.addEventListener("click", highlightToolsFn);
                fragment.appendChild(hlTools);
            }

            if (
                highlightHeightLimit &&
                item.offsetHeight > highlightHeightLimit + 30
            ) {
                const ele = document.createElement("div");
                ele.className = "code-expand-btn";
                ele.innerHTML = '<i class="fas fa-angle-double-down"></i>';
                ele.addEventListener("click", expandCode);
                fragment.appendChild(ele);
            }

            if (service === "hl") {
                item.insertBefore(fragment, item.firstChild);
            } else {
                item.parentNode.insertBefore(fragment, item);
            }
        }

        if (isHighlightLang) {
            if (isPrismjs) {
                $figureHighlight.forEach(function (item) {
                    const langName = item.getAttribute("data-language")
                        ? item.getAttribute("data-language")
                        : "Code";
                    const highlightLangEle = `<div class="code-lang">${langName}</div>`;
                    btf.wrap(item, "figure", { class: "highlight" });
                    createEle(highlightLangEle, item);
                });
            } else {
                $figureHighlight.forEach(function (item) {
                    let langName = item.getAttribute("class").split(" ")[1];
                    if (langName === "plain" || langName === undefined)
                        langName = "Code";
                    const highlightLangEle = `<div class="code-lang">${langName}</div>`;
                    createEle(highlightLangEle, item, "hl");
                });
            }
        } else {
            if (isPrismjs) {
                $figureHighlight.forEach(function (item) {
                    btf.wrap(item, "figure", { class: "highlight" });
                    createEle("", item);
                });
            } else {
                $figureHighlight.forEach(function (item) {
                    createEle("", item, "hl");
                });
            }
        }
    };

    /**
     * PhotoFigcaption
     */
    function addPhotoFigcaption() {
        document
            .querySelectorAll("#article-container img")
            .forEach(function (item) {
                const parentEle = item.parentNode;
                const altValue = item.title || item.alt;
                if (
                    altValue &&
                    !parentEle.parentNode.classList.contains(
                        "justified-gallery"
                    )
                ) {
                    const ele = document.createElement("div");
                    ele.className = "img-alt is-center";
                    ele.textContent = altValue;
                    parentEle.insertBefore(ele, item.nextSibling);
                }
            });
    }

    /**
     * Lightbox
     */
    const runLightbox = () => {
        btf.loadLightbox(
            document.querySelectorAll(
                "#article-container img:not(.no-lightbox)"
            )
        );
    };

    /**
     * justified-gallery 圖庫排版
     */
    const runJustifiedGallery = function (ele) {
        const htmlStr = (arr) => {
            let str = "";
            const replaceDq = (str) => str.replace(/"/g, "&quot;"); // replace double quotes to &quot;
            arr.forEach((i) => {
                const alt = i.alt ? `alt="${replaceDq(i.alt)}"` : "";
                const title = i.title ? `title="${replaceDq(i.title)}"` : "";
                str += `<div class="fj-gallery-item"><img src="${i.url}" ${
                    alt + title
                }"></div>`;
            });
            return str;
        };

        const lazyloadFn = (i, arr) => {
            const loadItem = i.getAttribute("data-limit");
            const arrLength = arr.length;
            if (arrLength > loadItem)
                i.insertAdjacentHTML(
                    "beforeend",
                    htmlStr(arr.splice(0, loadItem))
                );
            else {
                i.insertAdjacentHTML("beforeend", htmlStr(arr));
                i.classList.remove("lazyload");
            }
            return arrLength > loadItem ? loadItem : arrLength;
        };

        ele.forEach((item) => {
            const arr = JSON.parse(
                item.querySelector(".gallery-data").textContent
            );
            if (!item.classList.contains("lazyload"))
                item.innerHTML = htmlStr(arr);
            else {
                lazyloadFn(item, arr);
                const limit = item.getAttribute("data-limit");
                const clickBtnFn = () => {
                    const lastItemLength = lazyloadFn(item, arr);
                    fjGallery(
                        item,
                        "appendImages",
                        item.querySelectorAll(
                            `.fj-gallery-item:nth-last-child(-n+${lastItemLength})`
                        )
                    );
                    btf.loadLightbox(item.querySelectorAll("img"));
                    lastItemLength < limit &&
                        item.nextElementSibling.removeEventListener(
                            "click",
                            clickBtnFn
                        );
                };
                item.nextElementSibling.addEventListener("click", clickBtnFn);
            }
        });

        if (window.fjGallery) {
            setTimeout(() => {
                btf.initJustifiedGallery(ele);
            }, 100);
            return;
        }

        getCSS(`${GLOBAL_CONFIG.source.justifiedGallery.css}`);
        getScript(`${GLOBAL_CONFIG.source.justifiedGallery.js}`).then(() => {
            btf.initJustifiedGallery(ele);
        });
    };

    /**
     * rightside scroll percent
     */
    const rightsideScrollPercent = (currentTop) => {
        const perNum = btf.getScrollPercent(currentTop, document.body);
        const $goUp = document.getElementById("go-up");
        if (perNum < 95) {
            $goUp.classList.add("show-percent");
            $goUp.querySelector(".scroll-percent").textContent = perNum;
        } else {
            $goUp.classList.remove("show-percent");
        }
    };

    /**
     * 滾動處理
     */
    const scrollFn = function () {
        const $rightside = document.getElementById("rightside");
        const innerHeight = window.innerHeight + 56;

        // 當滾動條小于 56 的時候
        if (document.body.scrollHeight <= innerHeight) {
            $rightside.style.cssText =
                "opacity: 1; transform: translateX(-58px)";
            return;
        }

        // find the scroll direction
        function scrollDirection(currentTop) {
            const result = currentTop > initTop; // true is down & false is up
            initTop = currentTop;
            return result;
        }

        let initTop = 0;
        let isChatShow = true;
        const $header = document.getElementById("page-header");
        const isChatBtnHide = typeof chatBtnHide === "function";
        const isChatBtnShow = typeof chatBtnShow === "function";
        const isShowPercent = GLOBAL_CONFIG.percent.rightside;

        const scrollTask = btf.throttle(() => {
            const currentTop =
                window.scrollY || document.documentElement.scrollTop;
            const isDown = scrollDirection(currentTop);
            if (currentTop > 56) {
                if (isDown) {
                    if ($header.classList.contains("nav-visible"))
                        $header.classList.remove("nav-visible");
                    if (isChatBtnShow && isChatShow === true) {
                        chatBtnHide();
                        isChatShow = false;
                    }
                } else {
                    if (!$header.classList.contains("nav-visible"))
                        $header.classList.add("nav-visible");
                    if (isChatBtnHide && isChatShow === false) {
                        chatBtnShow();
                        isChatShow = true;
                    }
                }
                $header.classList.add("nav-fixed");
                if (
                    window
                        .getComputedStyle($rightside)
                        .getPropertyValue("opacity") === "0"
                ) {
                    $rightside.style.cssText =
                        "opacity: 0.8; transform: translateX(-58px)";
                }
            } else {
                if (currentTop === 0) {
                    $header.classList.remove("nav-fixed", "nav-visible");
                }
                $rightside.style.cssText = "opacity: ''; transform: ''";
            }

            isShowPercent && rightsideScrollPercent(currentTop);

            if (document.body.scrollHeight <= innerHeight) {
                $rightside.style.cssText =
                    "opacity: 0.8; transform: translateX(-58px)";
            }
        }, 200);

        window.scrollCollect = scrollTask;

        window.addEventListener("scroll", scrollCollect);
    };

    /**
     * toc,anchor
     */
    const scrollFnToDo = function () {
        const isToc = GLOBAL_CONFIG_SITE.isToc;
        const isAnchor = GLOBAL_CONFIG.isAnchor;
        const $article = document.getElementById("article-container");

        if (!($article && (isToc || isAnchor))) return;

        let $tocLink, $cardToc, autoScrollToc, $tocPercentage, isExpand;

        if (isToc) {
            const $cardTocLayout = document.getElementById("card-toc");
            $cardToc = $cardTocLayout.getElementsByClassName("toc-content")[0];
            $tocLink = $cardToc.querySelectorAll(".toc-link");
            $tocPercentage = $cardTocLayout.querySelector(".toc-percentage");
            isExpand = $cardToc.classList.contains("is-expand");

            window.mobileToc = {
                open: () => {
                    $cardTocLayout.style.cssText =
                        "animation: toc-open .3s; opacity: 1; right: 55px";
                },

                close: () => {
                    $cardTocLayout.style.animation = "toc-close .2s";
                    setTimeout(() => {
                        $cardTocLayout.style.cssText =
                            "opacity:''; animation: ''; right: ''";
                    }, 100);
                },
            };

            // toc元素點擊
            $cardToc.addEventListener("click", (e) => {
                e.preventDefault();
                const target = e.target.classList;
                if (target.contains("toc-content")) return;
                const $target = target.contains("toc-link")
                    ? e.target
                    : e.target.parentElement;
                btf.scrollToDest(
                    btf.getEleTop(
                        document.getElementById(
                            decodeURI($target.getAttribute("href")).replace(
                                "#",
                                ""
                            )
                        )
                    ),
                    300
                );
                if (window.innerWidth < 900) {
                    window.mobileToc.close();
                }
            });

            autoScrollToc = (item) => {
                const activePosition = item.getBoundingClientRect().top;
                const sidebarScrollTop = $cardToc.scrollTop;
                if (
                    activePosition >
                    document.documentElement.clientHeight - 100
                ) {
                    $cardToc.scrollTop = sidebarScrollTop + 150;
                }
                if (activePosition < 100) {
                    $cardToc.scrollTop = sidebarScrollTop - 150;
                }
            };
        }

        // find head position & add active class
        const list = $article.querySelectorAll("h1,h2,h3,h4,h5,h6");
        let detectItem = "";
        const findHeadPosition = function (top) {
            if (top === 0) {
                return false;
            }

            let currentId = "";
            let currentIndex = "";

            list.forEach(function (ele, index) {
                if (top > btf.getEleTop(ele) - 80) {
                    const id = ele.id;
                    currentId = id ? "#" + encodeURI(id) : "";
                    currentIndex = index;
                }
            });

            if (detectItem === currentIndex) return;

            if (isAnchor) btf.updateAnchor(currentId);

            detectItem = currentIndex;

            if (isToc) {
                $cardToc.querySelectorAll(".active").forEach((i) => {
                    i.classList.remove("active");
                });

                if (currentId === "") {
                    return;
                }

                const currentActive = $tocLink[currentIndex];
                currentActive.classList.add("active");

                setTimeout(() => {
                    autoScrollToc(currentActive);
                }, 0);

                if (isExpand) return;
                let parent = currentActive.parentNode;

                for (; !parent.matches(".toc"); parent = parent.parentNode) {
                    if (parent.matches("li")) parent.classList.add("active");
                }
            }
        };

        // main of scroll
        window.tocScrollFn = btf.throttle(() => {
            const currentTop =
                window.scrollY || document.documentElement.scrollTop;
            if (isToc && GLOBAL_CONFIG.percent.toc) {
                $tocPercentage.textContent = btf.getScrollPercent(
                    currentTop,
                    $article
                );
            }
            findHeadPosition(currentTop);
        }, 100);

        window.addEventListener("scroll", tocScrollFn);
    };

    /**
     * Rightside
     */
    const rightSideFn = {
        switchReadMode: () => {
            // read-mode
            $(".code-toolbar").addClass("read-fa");
            const $body = document.body;
            $body.classList.add("read-mode");
            const newEle = document.createElement("button");
            newEle.type = "button";
            newEle.className = "fas fa-sign-out-alt exit-readmode";
            $body.appendChild(newEle);

            function clickFn() {
                $body.classList.remove("read-mode");
                $(".code-toolbar").removeClass("read-fa");
                newEle.remove();
                newEle.removeEventListener("click", clickFn);
            }

            newEle.addEventListener("click", clickFn);
        },
        switchDarkMode: () => {
            // Switch Between Light And Dark Mode
            const nowMode =
                document.documentElement.getAttribute("data-theme") === "dark"
                    ? "dark"
                    : "light";
            if (nowMode === "light") {
                activateDarkMode();
                console.log("s!");
                saveToLocal.set("theme", "dark", 2);
                GLOBAL_CONFIG.Snackbar !== undefined &&
                    btf.snackbarShow(GLOBAL_CONFIG.Snackbar.day_to_night);
            } else {
                activateLightMode();
                console.log("s!");
                saveToLocal.set("theme", "light", 2);
                GLOBAL_CONFIG.Snackbar !== undefined &&
                    btf.snackbarShow(GLOBAL_CONFIG.Snackbar.night_to_day);
            }
            // handle some cases
            typeof utterancesTheme === "function" && utterancesTheme();
            typeof changeGiscusTheme === "function" && changeGiscusTheme();
            typeof FB === "object" && window.loadFBComment();
            window.DISQUS &&
                document.getElementById("disqus_thread").children.length &&
                setTimeout(() => window.disqusReset(), 200);
            typeof runMermaid === "function" && window.runMermaid();
        },
        showOrHideBtn: (e) => {
            // rightside 點擊設置 按鈕 展開
            const rightsideHideClassList = document.getElementById(
                "rightside-config-hide"
            ).classList;
            rightsideHideClassList.toggle("show");
            if (e.classList.contains("show")) {
                rightsideHideClassList.add("status");
                setTimeout(() => {
                    rightsideHideClassList.remove("status");
                }, 300);
            }
            e.classList.toggle("show");
        },
        scrollToTop: () => {
            // Back to top
            btf.scrollToDest(0, 500);
        },
        hideAsideBtn: () => {
            // Hide aside
            const $htmlDom = document.documentElement.classList;
            $htmlDom.contains("hide-aside")
                ? saveToLocal.set("aside-status", "show", 2)
                : saveToLocal.set("aside-status", "hide", 2);
            $htmlDom.toggle("hide-aside");
        },

        runMobileToc: () => {
            if (
                window
                    .getComputedStyle(document.getElementById("card-toc"))
                    .getPropertyValue("opacity") === "0"
            )
                window.mobileToc.open();
            else window.mobileToc.close();
        },

        adjustFontSize: (plus) => {
            const fontSizeVal = parseInt(
                window
                    .getComputedStyle(document.documentElement)
                    .getPropertyValue("--global-font-size")
            );
            let newValue = "";
            detectFontSizeChange = true;
            if (plus) {
                if (fontSizeVal >= 20) return;
                newValue = fontSizeVal + 1;
                document.documentElement.style.setProperty(
                    "--global-font-size",
                    newValue + "px"
                );
                !document
                    .getElementById("nav")
                    .classList.contains("hide-menu") && adjustMenu();
            } else {
                if (fontSizeVal <= 10) return;
                newValue = fontSizeVal - 1;
                document.documentElement.style.setProperty(
                    "--global-font-size",
                    newValue + "px"
                );
                document
                    .getElementById("nav")
                    .classList.contains("hide-menu") && adjustMenu();
            }

            saveToLocal.set("global-font-size", newValue, 2);
            // document.getElementById('font-text').innerText = newValue
        },
    };

    document
        .getElementById("rightside")
        .addEventListener("click", function (e) {
            const $target = e.target.id ? e.target : e.target.parentNode;
            switch ($target.id) {
                case "go-up":
                    rightSideFn.scrollToTop();
                    break;
                case "rightside_config":
                    rightSideFn.showOrHideBtn($target);
                    break;
                case "mobile-toc-button":
                    rightSideFn.runMobileToc();
                    break;
                case "readmode":
                    rightSideFn.switchReadMode();
                    break;
                case "darkmode":
                    rightSideFn.switchDarkMode();
                    break;
                case "hide-aside-btn":
                    rightSideFn.hideAsideBtn();
                    break;
                case "font-plus":
                    rightSideFn.adjustFontSize(true);
                    break;
                case "font-minus":
                    rightSideFn.adjustFontSize();
                    break;
                default:
                    break;
            }
        });

    /**
     * menu
     * 側邊欄sub-menu 展開/收縮
     */
    const clickFnOfSubMenu = () => {
        document
            .querySelectorAll("#sidebar-menus .site-page.group")
            .forEach(function (item) {
                item.addEventListener("click", function () {
                    this.classList.toggle("hide");
                });
            });
    };

    /**
     * 複製時加上版權信息
     */
    const addCopyright = () => {
        const copyright = GLOBAL_CONFIG.copyright;
        document.body.oncopy = (e) => {
            e.preventDefault();
            let textFont;
            const copyFont = window.getSelection(0).toString();
            if (copyFont.length > copyright.limitCount) {
                textFont =
                    copyFont +
                    "\n" +
                    "\n" +
                    "\n" +
                    copyright.languages.author +
                    "\n" +
                    copyright.languages.link +
                    window.location.href +
                    "\n" +
                    copyright.languages.source +
                    "\n" +
                    copyright.languages.info;
            } else {
                textFont = copyFont;
            }
            if (e.clipboardData) {
                return e.clipboardData.setData("text", textFont);
            } else {
                return window.clipboardData.setData("text", textFont);
            }
        };
    };

    /**
     * 網頁運行時間
     */
    const addRuntime = () => {
        const $runtimeCount = document.getElementById("runtimeshow");
        if ($runtimeCount) {
            const publishDate = $runtimeCount.getAttribute("data-publishDate");
            $runtimeCount.innerText =
                btf.diffDate(publishDate) + " " + GLOBAL_CONFIG.runtime;
        }
    };

    /**
     * 最後一次更新時間
     */
    const addLastPushDate = () => {
        const $lastPushDateItem = document.getElementById("last-push-date");
        if ($lastPushDateItem) {
            const lastPushDate =
                $lastPushDateItem.getAttribute("data-lastPushDate");
            $lastPushDateItem.innerText = btf.diffDate(lastPushDate, true);
        }
    };

    /**
     * table overflow
     */
    const addTableWrap = () => {
        const $table = document.querySelectorAll(
            "#article-container :not(.highlight) > table, #article-container > table"
        );
        if ($table.length) {
            $table.forEach((item) => {
                btf.wrap(item, "div", { class: "table-wrap" });
            });
        }
    };

    /**
     * tag-hide
     */
    const clickFnOfTagHide = function () {
        const $hideInline = document.querySelectorAll(
            "#article-container .hide-button"
        );
        if ($hideInline.length) {
            $hideInline.forEach(function (item) {
                item.addEventListener("click", function (e) {
                    const $this = this;
                    $this.classList.add("open");
                    const $fjGallery =
                        $this.nextElementSibling.querySelectorAll(
                            ".fj-gallery"
                        );
                    $fjGallery.length && btf.initJustifiedGallery($fjGallery);
                });
            });
        }
    };

    const tabsFn = {
        clickFnOfTabs: function () {
            document
                .querySelectorAll("#article-container .tab > button")
                .forEach(function (item) {
                    item.addEventListener("click", function (e) {
                        const $this = this;
                        const $tabItem = $this.parentNode;

                        if (!$tabItem.classList.contains("active")) {
                            const $tabContent =
                                $tabItem.parentNode.nextElementSibling;
                            const $siblings = btf.siblings(
                                $tabItem,
                                ".active"
                            )[0];
                            $siblings && $siblings.classList.remove("active");
                            $tabItem.classList.add("active");
                            const tabId = $this
                                .getAttribute("data-href")
                                .replace("#", "");
                            const childList = [...$tabContent.children];
                            childList.forEach((item) => {
                                if (item.id === tabId)
                                    item.classList.add("active");
                                else item.classList.remove("active");
                            });
                            const $isTabJustifiedGallery =
                                $tabContent.querySelectorAll(
                                    `#${tabId} .fj-gallery`
                                );
                            if ($isTabJustifiedGallery.length > 0) {
                                btf.initJustifiedGallery(
                                    $isTabJustifiedGallery
                                );
                            }
                        }
                    });
                });
        },
        backToTop: () => {
            document
                .querySelectorAll("#article-container .tabs .tab-to-top")
                .forEach(function (item) {
                    item.addEventListener("click", function () {
                        btf.scrollToDest(
                            btf.getEleTop(btf.getParents(this, ".tabs")),
                            300
                        );
                    });
                });
        },
    };

    const toggleCardCategory = function () {
        const $cardCategory = document.querySelectorAll(
            "#aside-cat-list .card-category-list-item.parent i"
        );
        if ($cardCategory.length) {
            $cardCategory.forEach(function (item) {
                item.addEventListener("click", function (e) {
                    e.preventDefault();
                    const $this = this;
                    $this.classList.toggle("expand");
                    const $parentEle = $this.parentNode.nextElementSibling;
                    if (btf.isHidden($parentEle)) {
                        $parentEle.style.display = "block";
                    } else {
                        $parentEle.style.display = "none";
                    }
                });
            });
        }
    };

    const switchComments = function () {
        let switchDone = false;
        const $switchBtn = document.querySelector(
            "#comment-switch > .switch-btn"
        );
        $switchBtn &&
            $switchBtn.addEventListener("click", function () {
                this.classList.toggle("move");
                document
                    .querySelectorAll("#post-comment > .comment-wrap > div")
                    .forEach(function (item) {
                        if (btf.isHidden(item)) {
                            item.style.cssText =
                                "display: block;animation: tabshow .5s";
                        } else {
                            item.style.cssText = "display: none;animation: ''";
                        }
                    });

                if (!switchDone && typeof loadOtherComment === "function") {
                    switchDone = true;
                    loadOtherComment();
                }
            });
    };

    const addPostOutdateNotice = function () {
        const data = GLOBAL_CONFIG.noticeOutdate;
        const diffDay = btf.diffDate(GLOBAL_CONFIG_SITE.postUpdate);
        if (diffDay >= data.limitDay) {
            const ele = document.createElement("div");
            ele.className = "post-outdate-notice";
            ele.textContent =
                data.messagePrev + " " + diffDay + " " + data.messageNext;
            const $targetEle = document.getElementById("article-container");
            if (data.position === "top") {
                $targetEle.insertBefore(ele, $targetEle.firstChild);
            } else {
                $targetEle.appendChild(ele);
            }
        }
    };

    const lazyloadImg = () => {
        window.lazyLoadInstance = new LazyLoad({
            elements_selector: "img",
            threshold: 0,
            data_src: "lazy-src",
        });
    };

    const relativeDate = function (selector) {
        selector.forEach((item) => {
            const $this = item;
            const timeVal = $this.getAttribute("datetime");
            $this.innerText = btf.diffDate(timeVal, true);
            $this.style.display = "inline";
        });
    };

    const unRefreshFn = function () {
        window.addEventListener("resize", () => {
            adjustMenu(false);
            btf.isHidden(document.getElementById("toggle-menu")) &&
                mobileSidebarOpen &&
                sidebarFn.close();
        });

        document.getElementById("menu-mask").addEventListener("click", (e) => {
            sidebarFn.close();
        });

        clickFnOfSubMenu();
        GLOBAL_CONFIG.islazyload && lazyloadImg();
        GLOBAL_CONFIG.copyright !== undefined && addCopyright();
    };

    window.refreshFn = function () {
        initAdjust();

        if (GLOBAL_CONFIG_SITE.isPost) {
            GLOBAL_CONFIG.noticeOutdate !== undefined && addPostOutdateNotice();
            GLOBAL_CONFIG.relativeDate.post &&
                relativeDate(document.querySelectorAll("#post-meta time"));
        } else {
            GLOBAL_CONFIG.relativeDate.homepage &&
                relativeDate(document.querySelectorAll("#recent-posts time"));
            GLOBAL_CONFIG.runtime && addRuntime();
            addLastPushDate();
            toggleCardCategory();
        }

        scrollFnToDo();
        GLOBAL_CONFIG_SITE.isHome && scrollDownInIndex();
        addHighlightTool();
        GLOBAL_CONFIG.isPhotoFigcaption && addPhotoFigcaption();
        scrollFn();

        const $jgEle = document.querySelectorAll(
            "#article-container .fj-gallery"
        );
        $jgEle.length && runJustifiedGallery($jgEle);

        runLightbox();
        addTableWrap();
        clickFnOfTagHide();
        tabsFn.clickFnOfTabs();
        tabsFn.backToTop();
        switchComments();
        document.getElementById("toggle-menu").addEventListener("click", () => {
            sidebarFn.open();
        });
    };

    refreshFn();
    unRefreshFn();

    // 主要功能初始化和加载处理
    initializeAll();
});

/**
 * 检查元素是否存在
 * @param {string} selector 元素选择器
 * @returns {boolean} 元素是否存在
 */
function elementExists(selector) {
    return document.querySelector(selector) !== null;
}

/**
 * 安全地获取元素
 * @param {string} selector 元素选择器
 * @param {string} fallbackId 如果元素不存在，创建一个隐藏的替代元素的ID
 * @returns {Element} 元素或替代元素
 */
function safeGetElement(selector, fallbackId) {
    const element = document.querySelector(selector);
    if (element) {
        return element;
    }
    
    // 如果元素不存在，创建一个隐藏的替代元素以防止错误
    console.log(`元素未找到: ${selector} (${fallbackId})`);
    const fallback = document.createElement('div');
    fallback.id = fallbackId;
    fallback.style.display = 'none';
    document.body.appendChild(fallback);
    console.log(`为 ${fallbackId} 创建了替代元素防止错误`);
    return fallback;
}

/**
 * 初始化所有功能
 */
function initializeAll() {
    try {
        console.log('正在初始化所有功能...');
        
        // 初始化打字效果
        initTypingEffect();
        
        // 初始化主题切换
        initThemeToggle();
        
        // 初始化图片懒加载
        initLazyLoad();
        
        // 初始化滚动处理
        initScrollHandling();
        
        // 初始化文章目录
        initTableOfContents();
        
        console.log('所有功能初始化完成');
    } catch (error) {
        console.error('初始化功能时出错:', error);
    }
}

/**
 * 初始化打字效果
 */
function initTypingEffect() {
    console.log('初始化打字效果...');
    try {
        // 确保typed.js已加载
        if (typeof Typed !== 'function') {
            console.warn('Typed.js未加载，跳过打字效果初始化');
            return;
        }
        
        // 获取需要打字效果的元素
        const subtitleEl = document.getElementById('subtitle');
        if (!subtitleEl) {
            console.log('打字效果目标元素不存在: #subtitle');
            return;
        }
        
        // 从data-typed属性获取打字字符串
        const typedStrings = subtitleEl.getAttribute('data-typed');
        if (!typedStrings) {
            console.warn('打字元素没有data-typed属性');
            return;
        }
        
        // 初始化打字效果
        new Typed('#subtitle', {
            strings: typedStrings.split(','),
            startDelay: 300,
            typeSpeed: 150,
            loop: true,
            backSpeed: 50
        });
        
        console.log('打字效果初始化完成');
    } catch (error) {
        console.error('初始化打字效果时出错:', error);
    }
}

/**
 * 初始化主题切换功能
 */
function initThemeToggle() {
    console.log('初始化主题切换功能...');
    try {
        // 主题切换按钮
        const themeToggle = document.getElementById('darkmode-toggle');
        if (!themeToggle) {
            console.log('主题切换按钮不存在: #darkmode-toggle');
            return;
        }
        
        themeToggle.addEventListener('click', function() {
            // 获取当前主题
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            
            // 切换主题
            if (currentTheme === 'light') {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // 初始化主题（基于本地存储或系统偏好）
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        
        console.log('主题切换功能初始化完成');
    } catch (error) {
        console.error('初始化主题切换功能时出错:', error);
    }
}

/**
 * 初始化图片懒加载
 */
function initLazyLoad() {
    console.log('初始化图片懒加载...');
    try {
        // 获取所有需要懒加载的图片
        const lazyImages = document.querySelectorAll('img[data-lazy-src]');
        
        if (lazyImages.length === 0) {
            console.log('没有找到需要懒加载的图片');
            return;
        }
        
        // 检查浏览器是否支持IntersectionObserver
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        const src = lazyImage.getAttribute('data-lazy-src');
                        
                        lazyImage.src = src;
                        lazyImage.classList.add('loaded');
                        imageObserver.unobserve(lazyImage);
                    }
                });
            });
            
            lazyImages.forEach(function(lazyImage) {
                imageObserver.observe(lazyImage);
            });
        } else {
            // 降级处理：简单地加载所有图片
            lazyImages.forEach(function(lazyImage) {
                const src = lazyImage.getAttribute('data-lazy-src');
                lazyImage.src = src;
            });
            console.log('浏览器不支持 IntersectionObserver，已降级处理懒加载');
        }
        
        console.log(`初始化了 ${lazyImages.length} 张懒加载图片`);
    } catch (error) {
        console.error('初始化图片懒加载时出错:', error);
    }
}

/**
 * 初始化滚动处理
 */
function initScrollHandling() {
    console.log('初始化滚动处理...');
    try {
        // 获取回到顶部按钮
        const backToTopButton = document.getElementById('back-to-top');
        if (!backToTopButton) {
            console.log('回到顶部按钮不存在: #back-to-top');
            return;
        }
        
        // 滚动事件处理
        window.addEventListener('scroll', function() {
            // 如果滚动超过300px，显示回到顶部按钮
            if (window.scrollY > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        // 点击回到顶部
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        console.log('滚动处理初始化完成');
    } catch (error) {
        console.error('初始化滚动处理时出错:', error);
    }
}

/**
 * 初始化文章目录
 */
function initTableOfContents() {
    console.log('初始化文章目录...');
    try {
        // 检查是否在文章页面
        const articleContent = document.querySelector('.article-content');
        const tocContainer = document.querySelector('.table-of-contents');
        
        if (!articleContent || !tocContainer) {
            console.log('目录容器不存在，跳过目录初始化');
            return;
        }
        
        // 获取所有标题
        const headings = articleContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
        
        if (headings.length === 0) {
            console.log('文章中没有找到标题，跳过目录生成');
            return;
        }
        
        // 创建目录HTML
        let tocHTML = '<ul>';
        const idMap = {};
        
        headings.forEach(function(heading, index) {
            // 为没有ID的标题添加ID
            if (!heading.id) {
                // 创建唯一ID
                let id = heading.textContent.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]/g, '');
                
                // 处理ID冲突
                if (idMap[id]) {
                    id = `${id}-${index}`;
                }
                idMap[id] = true;
                
                heading.id = id;
            }
            
            // 添加目录项
            const level = parseInt(heading.tagName.substring(1));
            const indent = '  '.repeat(level - 1);
            tocHTML += `${indent}<li><a href="#${heading.id}">${heading.textContent}</a></li>\n`;
        });
        
        tocHTML += '</ul>';
        tocContainer.innerHTML = tocHTML;
        
        // 添加滚动事件，高亮当前可见的标题
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            
            // 遍历所有标题，找到当前位置最近的标题
            let currentHeading = null;
            let minDistance = Infinity;
            
            headings.forEach(function(heading) {
                const distance = Math.abs(heading.offsetTop - scrollPosition);
                if (distance < minDistance) {
                    minDistance = distance;
                    currentHeading = heading;
                }
            });
            
            if (currentHeading) {
                // 移除之前的活动类
                const allLinks = tocContainer.querySelectorAll('a');
                allLinks.forEach(function(link) {
                    link.classList.remove('active');
                });
                
                // 添加活动类到当前标题的链接
                const activeLink = tocContainer.querySelector(`a[href="#${currentHeading.id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
        
        // 添加平滑滚动
        const tocLinks = tocContainer.querySelectorAll('a');
        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70, // 减去标题栏高度
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        console.log('文章目录初始化完成，生成了 ' + headings.length + ' 个目录项');
    } catch (error) {
        console.error('初始化文章目录时出错:', error);
    }
}
