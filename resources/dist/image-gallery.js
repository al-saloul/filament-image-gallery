// Image Gallery Plugin - Viewer.js initialization
// This script handles SPA navigation and dynamic content loading

(function () {
    const VIEWER_JS_URL = 'https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.js';
    const VIEWER_CSS_URL = 'https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.css';

    let loadingPromise = null;

    // Load Viewer.js CSS dynamically
    function loadViewerCSS() {
        if (document.querySelector('link[href="' + VIEWER_CSS_URL + '"]')) {
            return;
        }
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = VIEWER_CSS_URL;
        document.head.appendChild(link);
    }

    // Load Viewer.js JS dynamically
    function loadViewerJS() {
        if (loadingPromise) {
            return loadingPromise;
        }

        if (typeof window.Viewer !== 'undefined') {
            return Promise.resolve();
        }

        if (document.querySelector('script[src="' + VIEWER_JS_URL + '"]')) {
            // Script tag exists but Viewer may not be loaded yet
            return new Promise(function (resolve) {
                const checkInterval = setInterval(function () {
                    if (typeof window.Viewer !== 'undefined') {
                        clearInterval(checkInterval);
                        resolve();
                    }
                }, 50);
            });
        }

        loadingPromise = new Promise(function (resolve, reject) {
            const script = document.createElement('script');
            script.src = VIEWER_JS_URL;
            script.onload = function () {
                loadingPromise = null;
                resolve();
            };
            script.onerror = function () {
                loadingPromise = null;
                reject(new Error('Failed to load Viewer.js'));
            };
            document.head.appendChild(script);
        });

        return loadingPromise;
    }

    // Check if Viewer.js is available
    function isViewerAvailable() {
        return typeof window.Viewer !== 'undefined';
    }

    function initOne(el) {
        if (!el || el._viewer || !isViewerAvailable()) return;

        let instanceOptions = {};

        try {
            instanceOptions = el.dataset.viewerOptions ? JSON.parse(el.dataset.viewerOptions) : {};
        } catch (e) {
            console.error('ImageGallery: Invalid JSON in data-viewer-options', e);
        }

        el._viewer = new Viewer(el, Object.assign(
            {},
            window.filamentData.GalleryViewerDefaultOptions || {},
            window.GalleryViewerDefaultOptions || {},
            instanceOptions
        ));
    }

    function destroyOne(el) {
        if (el && el._viewer) {
            try {
                el._viewer.destroy();
            } catch (e) { }
            el._viewer = null;
        }
    }

    function isAnyViewerOpen() {
        return Array.from(document.querySelectorAll('[data-viewer-gallery]')).some(el => el._viewer && el._viewer.isShown);
    }

    function scan() {
        const galleries = document.querySelectorAll('[data-viewer-gallery]');
        if (galleries.length === 0) return;

        // Skip reinitialization while a viewer is open to avoid closing it
        // (e.g. during Livewire morph updates from notification polling)
        if (isAnyViewerOpen()) return;

        // Ensure Viewer.js is loaded before initializing
        loadViewerCSS();
        loadViewerJS().then(function () {
            galleries.forEach(function (el) {
                // Destroy and reinitialize to handle SPA navigation
                destroyOne(el);
                initOne(el);
            });
        }).catch(function (err) {
            console.error('ImageGallery:', err);
        });
    }

    // Run on various lifecycle events
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scan);
    } else {
        // DOM is already ready
        scan();
    }

    // Filament/Livewire 3.x SPA navigation
    document.addEventListener('livewire:navigated', function () {
        setTimeout(scan, 100);
    });

    // Livewire 3.x morph updates
    document.addEventListener('livewire:init', function () {
        if (window.Livewire) {
            Livewire.hook('morph.updated', function ({ el }) {
                setTimeout(scan, 100);
            });
        }
    });

    // For Livewire 2.x compatibility
    document.addEventListener('livewire:load', scan);
    if (window.Livewire && window.Livewire.hook) {
        try {
            window.Livewire.hook('message.processed', function () {
                setTimeout(scan, 100);
            });
        } catch (e) { }
    }

    // Turbolinks/Turbo compatibility
    document.addEventListener('turbo:load', scan);
    document.addEventListener('turbolinks:load', scan);

    // Alpine.js x-init hook
    document.addEventListener('alpine:init', function () {
        if (window.Alpine) {
            Alpine.directive('image-gallery-init', function (el) {
                loadViewerCSS();
                loadViewerJS().then(function () {
                    initOne(el);
                });
            });
        }
    });

    // MutationObserver for dynamic content
    const observer = new MutationObserver(function (mutations) {
        let shouldScan = false;
        mutations.forEach(function (mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType === 1) {
                        if (node.hasAttribute && node.hasAttribute('data-viewer-gallery')) {
                            shouldScan = true;
                        }
                        if (node.querySelectorAll) {
                            const galleries = node.querySelectorAll('[data-viewer-gallery]');
                            if (galleries.length) {
                                shouldScan = true;
                            }
                        }
                    }
                });
            }
        });
        if (shouldScan) {
            setTimeout(scan, 100);
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Expose scan function globally for manual triggering if needed
    window.ImageGallery = {
        scan: scan,
        init: initOne,
        destroy: destroyOne
    };
})();
