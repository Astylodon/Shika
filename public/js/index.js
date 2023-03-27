async function displayData(site) {
    let referrers = (await fetch(`/api/sites/${site.id}/referrers?from=1`).then(r => r.json()))
        .map(x => [x.referrer, x.count]);
    let pages = (await fetch(`/api/sites/${site.id}/pages?from=1`).then(r => r.json()))
        .map(x => [x.path, x.count]);

    new Chart(document.getElementById('referrers'), {
        type: 'bar',
        data: {
            labels: referrers.map(x => x[0]),
            datasets: [{
                label: "Nb. of Visits",
                data: referrers.map(x => x[1])
            }]
        }
    });
    new Chart(document.getElementById('pages'), {
        type: 'bar',
        data: {
            labels: pages.map(x => x[0]),
            datasets: [{
                label: "Nb. of Visits",
                data: pages.map(x => x[1])
            }]
        }
    });
}

addEventListener("load", async (_) => {
    const sites = await fetch("/api/sites").then(r => r.json());

    const selection = document.getElementById("site-selection");
    for (const site of sites)
    {
        const div = document.createElement("div");
        div.innerHTML = site.name;
        div.onclick = async (_) => {
            for (const elem of document.querySelectorAll("#site-selection > div")) {
                elem.classList.remove("selected");
                div.classList.add("selected")
                await displayData(site);
            }
        };
        selection.appendChild(div);
    }

    document.querySelector("#site-selection > div")?.click();
});