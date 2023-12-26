import { Chart, BarController, BarElement, CategoryScale, LinearScale, Colors } from "chart.js"

// Bundle optimization
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Colors)

// Current charts
let pagesChart = null
let referrersChart = null

async function displayData(site, time) {
    // Destroy the old charts
    if (pagesChart !== null) pagesChart.destroy()
    if (referrersChart !== null) referrersChart.destroy()

    // Calculate the from time
    const from = (Date.now() / 1000) - time

    // Fetch the numbers
    const pages = await fetch(`/api/sites/${site}/pages?from=${from}`).then(x => x.json())
    const referrers = await fetch(`/api/sites/${site}/referrers?from=${from}`).then(x => x.json())

    // Display the charts
    pagesChart = displayLineChart("pages", pages.map(x => [x.path, x.count]))
    referrersChart = displayLineChart("referrers", referrers.map(x => [x.referrer, x.count]))
}

function displayLineChart(id, values) {
    const options = {
        indexAxis: "y"
    }

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
        type: "bar",
        data,
        options
    })

    return chart
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