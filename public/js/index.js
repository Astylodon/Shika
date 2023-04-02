let referrersChart = null;
let pagesChart = null;

async function displayData(id, time) {
    if (referrersChart !== null) referrersChart.destroy();
    if (pagesChart !== null) pagesChart.destroy();

    var epoch = (Date.now() / 1000) - time;
    let referrers = (await fetch(`/api/sites/${id}/referrers?from=${epoch}`).then(r => r.json()))
        .map(x => [x.referrer, x.count]);
    let pages = (await fetch(`/api/sites/${id}/pages?from=${epoch}`).then(r => r.json()))
        .map(x => [x.path, x.count]);

    referrersChart = new Chart(document.getElementById('referrers'), {
        type: 'bar',
        data: {
            labels: referrers.map(x => x[0]),
            datasets: [{
                label: "Nb. of Visits",
                data: referrers.map(x => x[1])
            }]
        }
    });
    pagesChart = new Chart(document.getElementById('pages'), {
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
                await displayData(site.id, document.querySelector("#date-selection > div.selected").dataset.time);
            }
        };
        div.dataset.id = site.id;
        selection.appendChild(div);
    }

    for (const e of document.querySelectorAll("#date-selection > div")) {
        e.onclick = async (_) => {
            document.querySelector("#date-selection > div.selected").classList.remove("selected");
            e.classList.add("selected");
            epochTarget = parseInt(e.dataset.time);
            await displayData(document.querySelector("#site-selection > div.selected").dataset.id, epochTarget);
        };
    }

    document.querySelector("#date-selection > div").classList.add("selected");
    document.querySelector("#site-selection > div")?.click();
});