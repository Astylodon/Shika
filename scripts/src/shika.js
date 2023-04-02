function shika_send() {
    const url = document.currentScript.dataset.url; // data-url
    const siteKey = document.currentScript.dataset.siteKey; // data-site-key

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

    const blob = new Blob([JSON.stringify(body)], {type: "application/json"});

    navigator.sendBeacon(url, blob);
}

document.addEventListener("load", shika_send);