/**
 * @author @NoelClick
 * @copyright 2022 by Noel Kayabasli
 */
document.addEventListener("DOMContentLoaded", e => {
    const element = document.querySelector("#frontendDebuggerOffcanvas");
    const resizer = element.querySelector('.offcanvas-resizer');
    if (resizer) {
        resizer.setAttribute("data-ext-bs-offcanvas-startHeight", parseInt(document.defaultView.getComputedStyle(element).height, 10));
        resizer.addEventListener('mousedown', offcanvasResizableInitDrag, false);
    }

    function offcanvasResizableInitDrag(e) {
        console.debug("Start drag");
        document.body.setAttribute("data-ext-bs-offcanvas-current-element", e.target.closest("#frontendDebuggerOffcanvas").id);
        e.target.setAttribute("data-ext-bs-offcanvas-startY", e.clientY);
        document.documentElement.addEventListener('mousemove', offcanvasResizableDoDrag, false);
        document.documentElement.addEventListener('mouseup', offcanvasResizeableStopDrag, false);
    }

    function offcanvasResizableDoDrag(e) {
        console.debug("Dragging");
        let resizer = document.querySelector("#" + document.body.getAttribute("data-ext-bs-offcanvas-current-element") + " .offcanvas-resizer");
        let element = document.querySelector("#" + document.body.getAttribute("data-ext-bs-offcanvas-current-element"));
        if (resizer.getAttribute("data-ext-bs-offcanvas-startHeight") <= e.clientY) {
            element.style.height = (element.getAttribute("data-ext-bs-offcanvas-startHeight") + e.clientY - element.getAttribute("data-ext-bs-offcanvas-startY")) + 'px';
        }
    }

    function offcanvasResizeableStopDrag(e) {
        console.debug("Stop drag");
        document.documentElement.removeEventListener('mousemove', offcanvasResizableDoDrag, false);
        document.documentElement.removeEventListener('mouseup', offcanvasResizeableStopDrag, false);
        document.body.removeAttribute("data-ext-bs-offcanvas-current-element");
    }

    document.querySelector("#frontendDebuggerCloseButton").addEventListener("click", toggleFrontendDebuggerOffcanvas);

    document.querySelector("#frontendDebuggerButton").addEventListener("click", toggleFrontendDebuggerOffcanvas);

    function toggleFrontendDebuggerOffcanvas() {
        document.querySelector("#frontendDebuggerOffcanvas").classList.toggle("show");
    }
});
