addEventListener("load", async (_) => {

    const sites = await fetch("/api/sites").then(r => r.json());

    let referrers = {};

    for (const site of sites)
    {
        for (const r of await fetch(`/api/sites/${site.id}/referrers?from=1`).then(r => r.json()))
        {
            console.log(r);
            if (r.referrer in referrers) {
                referrers[r.referrer] += r.count;
            } else {
                referrers[r.referrer] = r.count;
            } 
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
});