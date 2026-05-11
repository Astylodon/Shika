import { Chart, BarController, PieController, BarElement, ArcElement, CategoryScale, LinearScale, Colors, Tooltip, Legend } from "chart.js"
import { ChoroplethController, ProjectionScale, ColorScale, GeoFeature, topojson } from 'chartjs-chart-geo';
import countries50m from "world-atlas/countries-50m.json" with { type: "json" };

// Bundle optimization
Chart.register(BarController, PieController, ChoroplethController, BarElement, ArcElement, CategoryScale, LinearScale, ProjectionScale, ColorScale, GeoFeature, Colors, Tooltip, Legend)

// Current charts
let charts = []

async function displayData(site, time) {
    // Destroy the old charts
    for (let chart of charts) {
        chart.destroy()
    }
    charts = []

    // Calculate the from time
    const from = (Date.now() / 1000) - time

    // Fetch the numbers
    const pages = await fetch(`/api/sites/${site}/pages?from=${from}`).then(x => x.json())
    const referrers = await fetch(`/api/sites/${site}/referrers?from=${from}`).then(x => x.json())
    const browsers = await fetch(`/api/sites/${site}/browsers?from=${from}`).then(x => x.json())
    const systems = await fetch(`/api/sites/${site}/operating-systems?from=${from}`).then(x => x.json())
    const devices = await fetch(`/api/sites/${site}/device-types?from=${from}`).then(x => x.json())
    const countries = await fetch(`/api/sites/${site}/countries?from=${from}`).then(x => x.json())

    const pageCount = pages.map(x => x.count).reduce((a, b) => a + b, 0)
    const referrerCount = referrers.map(x => x.count).reduce((a, b) => a + b, 0)
    if (pageCount > referrerCount) {
        referrers.push({ referrer: "direct / unknown", count:  pageCount - referrerCount })
        referrers.sort((a, b) => { return b.count - a.count })
    }

    // Display the charts
    const region = new Intl.DisplayNames(['en'], { type: 'region' })
    charts.push(displayLineChart("pages", pages.map(x => [x.path, x.count])))
    charts.push(displayLineChart("referrers", referrers.map(x => [x.referrer, x.count])))
    charts.push(displayPieChart("browsers", browsers.map(x => [x.browser, x.count])))
    charts.push(displayPieChart("systems", systems.map(x => [x.operating_system, x.count])))
    charts.push(displayPieChart("devices", devices.map(x => [x.device_type, x.count])))
    charts.push(displayCountryChart("countries", Object.fromEntries(countries.map(x => [ region.of(x.country), x.count ]))));
}

function displayChartInternal(type, options, id, values) {
    // Map the data
    const data = {
        labels: values.map(x => x[0]),
        datasets: [
            {
                label: "Visits",
                data: values.map(x => x[1])
            }
        ]
    }

    // Display the chart
    const chart = new Chart(document.getElementById(id), {
        type: type,
        data,
        options
    })

    return chart
}

function displayCountryChart(id, values) {
    const countries = topojson.feature(countries50m, countries50m.objects.countries).features;

    const chart = new Chart(document.getElementById(id).getContext("2d"), {
        type: 'choropleth',
        data: {
            labels: countries.map((d) => d.properties.name),
            datasets: [{
            label: 'Countries',
            data: countries.map((d) => {
                    const country = d.properties.name;
                    return ({feature: d, value: values[country] ? values[country] : 0})
                }),
            }]
        },
        options: {
            scales: {
                projection: {
                    axis: 'x',
                    projection: 'equalEarth'
                }
            }
        }
    });

    return chart;
}

function displayLineChart(id, values) {
    const options = {
        indexAxis: "y"
    }
    return displayChartInternal("bar", options, id, values)
}

function displayPieChart(id, values) {
    const options = {
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
    return displayChartInternal("pie", options, id, values)
}

function startDisplayData() {
    // Get the site and time
    const site = document.getElementById("site-select").value
    const time = document.getElementById("time-select").value

    if (!site) return

    // Display the data
    displayData(site, Number(time))
}

// Listeners
window.addEventListener("load", startDisplayData)

document.getElementById("site-select").addEventListener("change", startDisplayData)
document.getElementById("time-select").addEventListener("change", startDisplayData)