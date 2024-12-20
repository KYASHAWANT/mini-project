
function travellingSalesman(graph, start) {
    const numNodes = graph.length;
    const visited = new Array(numNodes).fill(false);
    let path = [];
    let totalDistance = 0;

    let currentNode = start;
    visited[currentNode] = true;
    path.push(currentNode);

    for (let i = 1; i < numNodes; i++) {
        let nearestNode = -1;
        let minDistance = Infinity;

        for (let j = 0; j < numNodes; j++) {
            if (!visited[j] && graph[currentNode][j] < minDistance && graph[currentNode][j] > 0) {
                minDistance = graph[currentNode][j];
                nearestNode = j;
            }
        }

        if (nearestNode !== -1) {
            visited[nearestNode] = true;
            totalDistance += minDistance;
            path.push(nearestNode);
            currentNode = nearestNode;
        }
    }

    // Return to starting point
    totalDistance += graph[currentNode][start];
    path.push(start);

    return { path, totalDistance };
}

// Modify your submit event to use the TSP function
document.getElementById("routeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let numStops = document.getElementById("numStops").value;
    let addresses = document.getElementById("addresses").value.split(",");

    // Generate distance matrix
    let distanceMatrix = generateDistanceMatrix(addresses);

    // Apply TSP algorithm
    let startPoint = 0; // Starting from depot
    let tspResult = travellingSalesman(distanceMatrix, startPoint);

    // Output the result
    let output = "Shortest Route (TSP):\n";
    tspResult.path.forEach((node, index) => {
        output += `${index + 1}. ${node === startPoint ? "Depot" : addresses[node]}\n`;
    });
    output += `Total Distance: ${tspResult.totalDistance} km`;

    document.getElementById("output").textContent = output;

    // Initialize Google Map after computing the route
    initMap(addresses, tspResult.path);
});

// Initialize Google Map (updated to use the TSP path)
function initMap(addresses, path) {
    let map = new google.maps.Map(document.getElementById("map"), {
        zoom: 10,
        center: { lat: 37.7749, lng: -122.4194 }
    });

    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    let depotLocation = { lat: 37.7749, lng: -122.4194 };
    let geocoder = new google.maps.Geocoder();
    let waypoints = [];

    path.forEach((node, index) => {
        if (node !== 0) { // Skip depot for waypoints
            geocoder.geocode({ address: addresses[node] }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    waypoints.push({
                        location: results[0].geometry.location,
                        stopover: true
                    });

                    if (index === path.length - 2) { // Last point before depot
                        let request = {
                            origin: depotLocation,
                            destination: depotLocation,
                            waypoints: waypoints,
                            travelMode: google.maps.TravelMode.DRIVING
                        };

                        directionsService.route(request, function(response, status) {
                            if (status === google.maps.DirectionsStatus.OK) {
                                directionsRenderer.setDirections(response);
                            } else {
                                alert("Directions request failed due to " + status);
                            }
                        });
                    }
                } else {
                    alert("Geocode failed: " + status);
                }
            });
        }
    });
}
function generateDistanceMatrix(addresses) {
    const numAddresses = addresses.length;
    let matrix = Array.from({ length: numAddresses }, () => Array(numAddresses).fill(0));
    
    // Generate mock distances
    for (let i = 0; i < numAddresses; i++) {
        for (let j = i + 1; j < numAddresses; j++) {
            matrix[i][j] = Math.floor(Math.random() * 10) + 1; // Random distances
            matrix[j][i] = matrix[i][j]; // Symmetric matrix
        }
    }

    return matrix;
}
