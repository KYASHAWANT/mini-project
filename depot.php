<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Routing and Scheduling System</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color=white;
            background: url('office.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        .container {
            width: 50%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        #map { height: 400px; width: 100%; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Routing and Scheduling System</h1>
        
        <form id="routeForm">
            <label for="depot">Depot Location: </label>
            <input type="text" id="depot" name="depot" placeholder="Enter depot address" required><br>

            <label for="numVehicles">Number of Vehicles: </label>
            <input type="number" id="numVehicles" name="numVehicles" min="1" required><br>

            <label for="addresses">Enter Addresses (comma separated): </label>
            <textarea id="addresses" name="addresses" rows="4" cols="50" required></textarea><br>

            <label for="timeWindows">Delivery Time Windows (comma separated, e.g., "08:00-10:00,10:30-12:00"): </label>
            <textarea id="timeWindows" name="timeWindows" rows="4" cols="50" required></textarea><br>

            <label for="driverShifts">Driver Shift Timings (e.g., "07:00-15:00,15:00-23:00"): </label>
            <input type="text" id="driverShifts" name="driverShifts" required><br>

            <button type="submit">Calculate Routes</button>
        </form>

        <h2>Scheduled Routes:</h2>
        <pre id="output"></pre>

        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>

    <script>
        const map = L.map('map');
        const markersLayer = L.layerGroup().addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const form = document.getElementById('routeForm');
        const output = document.getElementById('output');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const depotAddress = document.getElementById('depot').value.trim();
            const numVehicles = parseInt(document.getElementById('numVehicles').value);
            const addresses = document.getElementById('addresses').value.split(',').map(addr => addr.trim());
            const timeWindows = document.getElementById('timeWindows').value.split(',').map(tw => tw.trim());
            const driverShifts = document.getElementById('driverShifts').value.split(',').map(shift => {
                const [start, end] = shift.split('-');
                return { start: start.trim(), end: end.trim() };
            });

            if (addresses.length < numVehicles) {
                output.textContent = "Number of vehicles exceeds the number of stops.";
                return;
            }

            const coords = [];
            markersLayer.clearLayers();

            // Geocode depot location
            let depotCoords = null;
            try {
                const depotRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(depotAddress)}`);
                const depotData = await depotRes.json();

                if (depotData.length > 0) {
                    depotCoords = {
                        address: depotAddress,
                        lat: parseFloat(depotData[0].lat),
                        lon: parseFloat(depotData[0].lon)
                    };
                    L.marker([depotCoords.lat, depotCoords.lon], { icon: L.icon({ iconUrl: 'https://leafletjs.com/examples/custom-icons/leaf-green.png', iconSize: [38, 38] }) })
                        .bindPopup(`Depot: ${depotAddress}`)
                        .addTo(markersLayer);
                } else {
                    output.textContent = "Invalid depot address.";
                    return;
                }
            } catch {
                output.textContent = "Error geocoding depot address.";
                return;
            }

            // Geocode stops
            for (const address of addresses) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
                    const data = await res.json();

                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        coords.push({ address, lat, lon });
                        L.marker([lat, lon]).bindPopup(`Address: ${address}`).addTo(markersLayer);
                    } else {
                        output.textContent += `Skipping address: ${address} (Geocoding failed)\n`;
                    }
                } catch {
                    output.textContent += `Error geocoding address: ${address}\n`;
                }
            }

            if (coords.length < 2) {
                output.textContent += "\nNot enough valid locations for routing.";
                return;
            }

            map.fitBounds(L.latLngBounds(coords.map(c => [c.lat, c.lon])));

            // Add depot to bounds
            map.fitBounds(L.latLngBounds([...coords.map(c => [c.lat, c.lon]), [depotCoords.lat, depotCoords.lon]]));

            // Split stops among vehicles and schedule deliveries
            const routes = Array.from({ length: numVehicles }, () => []);
            const schedule = [];

            coords.forEach((stop, index) => {
                routes[index % numVehicles].push(stop);
            });

            routes.forEach((route, vehicleIndex) => {
                const vehicleSchedule = [];
                let currentTime = driverShifts[vehicleIndex]?.start || "07:00";

                route.forEach((stop, stopIndex) => {
                    const timeWindow = timeWindows[stopIndex];
                    if (timeWindow && !isWithinTimeWindow(currentTime, timeWindow)) {
                        currentTime = adjustToNextAvailableTime(currentTime, timeWindow);
                    }

                    vehicleSchedule.push({
                        stop: stop.address,
                        arrival: currentTime,
                        departure: addBufferTime(currentTime, 15)
                    });

                    currentTime = addBufferTime(currentTime, 30);
                });

                schedule.push({ vehicle: vehicleIndex + 1, route: vehicleSchedule });
            });

            // Display schedule
            output.textContent = "Scheduled Routes:\n";
            schedule.forEach(({ vehicle, route }) => {
                output.textContent += `Vehicle ${vehicle}:\n`;
                route.forEach(({ stop, arrival, departure }) => {
                    output.textContent += `  - Stop: ${stop}, Arrival: ${arrival}, Departure: ${departure}\n`;
                });
                output.textContent += '\n';
            });

            // Draw routes on the map
            const routeColors = ['blue', 'red', 'green', 'purple', 'orange'];

            routes.forEach((route, index) => {
                const waypoints = [
                    L.latLng(depotCoords.lat, depotCoords.lon),
                    ...route.map(stop => L.latLng(stop.lat, stop.lon)),
                    L.latLng(depotCoords.lat, depotCoords.lon)
                ];
                if (waypoints.length > 2) {
                    L.Routing.control({
                        waypoints,
                        routeWhileDragging: false,
                        lineOptions: {
                            styles: [{ color: routeColors[index % routeColors.length], weight: 4 }]
                        },
                        createMarker: function () { return null; }
                    }).addTo(map);
                }
            });
        });

        function isWithinTimeWindow(currentTime, timeWindow) {
            const [start, end] = timeWindow.split('-');
            return currentTime >= start && currentTime <= end;
        }

        function adjustToNextAvailableTime(currentTime, timeWindow) {
            const [start] = timeWindow.split('-');
            return start > currentTime ? start : currentTime;
        }

        function addBufferTime(currentTime, minutes) {
            const [hours, mins] = currentTime.split(':').map(Number);
            const newMinutes = mins + minutes;
            return `${hours + Math.floor(newMinutes / 60)}:${newMinutes % 60}`.padStart(5, '0');
        }
    </script>
</body>
</html>
