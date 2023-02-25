function shika_send() {
    const url = document.currentScript.getAttribute("data-url");
    if (url === null) {
        console.error("URL is not set");
        return;
    }
    const siteKey = document.currentScript.getAttribute("data-siteKey") ?? "0";
    const body = {
        lang: navigator.language,
        referrer: document.referrer,
        href: location.href,
        siteKey: siteKey
    };
    const headers = {
        type: 'application/json',
    };
    const blob = new Blob([JSON.stringify(body)], headers);
    navigator.sendBeacon(url, blob);
}

shika_send();