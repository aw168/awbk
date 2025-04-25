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