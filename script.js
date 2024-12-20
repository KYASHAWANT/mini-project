document.getElementById("routeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let numStops = document.getElementById("numStops").value;
    let addresses = document.getElementById("addresses").value.split(",");
    
    
    // Sample distance matrix (in kilometers), real use case would need real distances
    let distanceMatrix = generateDistanceMatrix(addresses);
    
    // Apply Dijkstra's algorithm to find the shortest path
    let startPoint = 0; // Starting from depot
    let shortestPaths = dijkstra(distanceMatrix, startPoint);

    // Output the result as the calculated shortest paths
    let output = "Shortest Route (Spanning Tree):\n";
    for (let i = 0; i < addresses.length; i++) {
        output += `From Depot to ${addresses[i]}: ${shortestPaths[i]} km\n`;
    }
    
    document.getElementById("output").textContent = output;
});

// Dijkstra's Algorithm for finding the shortest path in a graph
function dijkstra(graph, start) {
    const distances = Array(graph.length).fill(Infinity);
    distances[start] = 0;
    
    const visited = new Array(graph.length).fill(false);
    const prev = new Array(graph.length).fill(null);
    
    for (let count = 0; count < graph.length - 1; count++) {
        let minDistance = Infinity;
        let u;
        
        for (let v = 0; v < graph.length; v++) {
            if (!visited[v] && distances[v] <= minDistance) {
                minDistance = distances[v];
                u = v;
            }
        }
        
        visited[u] = true;
        
        for (let v = 0; v < graph.length; v++) {
            if (!visited[v] && graph[u][v] !== 0 && distances[u] !== Infinity) {
                let newDist = distances[u] + graph[u][v];
                if (newDist < distances[v]) {
                    distances[v] = newDist;
                    prev[v] = u;
                }
            }
        }
    }
    
    return distances;
}

// Function to simulate distance between addresses (real case will use APIs for actual distances)
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