async function displayData(site) {
    let referrers = {};

    for (const r of await fetch(`/api/sites/${site.id}/referrers`).then(r => r.json()))
    {
        if (r.referrer in referrers) {
            referrers[r.referrer] += r.count;
        } else {
            referrers[r.referrer] = r.count;
        }
    }

    var items = Object.keys(referrers).map(function(key) {
        return [key, referrers[key]];
    });

    items.sort(function(first, second) {
        return second[1] - first[1];
    });

    const ctx = document.getElementById('referrers');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: items.map(x => x[0]),
            datasets: [{
                label: "Nb. of Visits",
                data: items.map(x => x[1])
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