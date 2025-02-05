import { Chart, BarController, PieController, BarElement, ArcElement, CategoryScale, LinearScale, Colors, Tooltip, Legend } from "chart.js"
import { ChoroplethController, ProjectionScale, ColorScale, GeoFeature, topojson } from 'chartjs-chart-geo';

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

    // Display the charts
    const region = new Intl.DisplayNames(['en'], { type: 'region' })
    charts.push(displayLineChart("pages", pages.map(x => [x.path, x.count])))
    charts.push(displayLineChart("referrers", referrers.map(x => [x.referrer, x.count])))
    charts.push(displayPieChart("browsers", browsers.map(x => [x.browser, x.count])))
    charts.push(displayPieChart("systems", systems.map(x => [x.operating_system, x.count])))
    charts.push(displayPieChart("devices", devices.map(x => [x.device_type, x.count])))
    displayCountryChart("countries", Object.fromEntries(countries.map(x => [ region.of(x.country), x.count ])), charts)
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

function displayCountryChart(id, values, charts) {

    fetch('https://unpkg.com/world-atlas/countries-50m.json').then((r) => r.json()).then((data) => {
        const countries = topojson.feature(data, data.objects.countries).features;

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

        charts.push(chart);
    });
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