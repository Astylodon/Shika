const currentScript = document.currentScript

function send() {
    if (window.location.hostname == "localhost") {
        return; // Debug environment
    }

    const url = currentScript.dataset.url; // data-url
    const siteKey = currentScript.dataset.siteKey; // data-site-key

    if (!url || !siteKey) {
        console.error("The Shika script tag is missing a data-url or data-site-key attribute, make sure you copied the full code for your site.");
        return;
    }

    const body = {
        lang: navigator.language,
        referrer: document.referrer,
        href: location.href,
        siteKey
    };

    const headers = { type: 'application/json' };

    const blob = new Blob([JSON.stringify(body)], headers);
    navigator.sendBeacon(url, blob);
}

window.addEventListener("load", send)