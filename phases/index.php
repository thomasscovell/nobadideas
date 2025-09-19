<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Cards - No Bad Ideas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .card { transform-style: preserve-3d; transition: transform 0.7s; }
        .card.is-flipped { transform: rotateY(180deg); }
        .card-face { backface-visibility: hidden; -webkit-backface-visibility: hidden; }
        .card-back { transform: rotateY(180deg); }
        .perspective-1000 { perspective: 1000px; }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen">

    <div class="container mx-auto p-4 md:p-8 w-full max-w-md">
        <header class="text-center mb-8">
            <h1 id="deck-title" class="text-4xl font-bold tracking-tight">Phase Cards</h1>
            
            <a href="../" class="mt-4 inline-block text-green-400 hover:text-green-300">&larr; Back to Main Game</a>
        </header>
        
        <!-- Card Container -->
        <div id="card-container" class="perspective-1000">
            <div id="card" class="card relative w-full" style="aspect-ratio: 3/4;">
                <!-- Card Front -->
                <div id="card-front" class="card-face absolute w-full h-full rounded-lg shadow-lg flex flex-col items-center justify-center p-6">
                    <h2 id="card-title-front" class="text-2xl font-bold text-white hidden"></h2>
                    <img id="card-image-front" src="" alt="Phase Image" class="w-full h-full object-contain">
                </div>
                <!-- Card Back -->
                <div id="card-back" class="card-face card-back absolute w-full h-full bg-gray-800 rounded-lg shadow-lg p-6 overflow-y-auto">
                    <h2 id="card-title-back" class="text-xl font-bold text-white mb-3"></h2>
                    <div id="card-details-back" class="text-gray-300 text-left"></div>
                </div>
            </div>
        </div>

        <!-- Navigation and Controls -->
        <div class="flex justify-between items-center mt-6">
            <button id="prev-card" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
            <button id="flip-card" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors">See Details</button>
            <button id="next-card" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
        </div>
        
        
    </div>

    <script src="../assets/js/deck_viewer.js"></script>
    <script>
        // Initialize the deck viewer for the Phases deck
        document.addEventListener('DOMContentLoaded', () => {
            initializeDeck('phases');

            // Fetch the list of image URLs for pre-caching
            fetch('../get_image_urls.php')
                .then(response => response.json())
                .then(imageUrls => {
                    console.log(`Pre-caching ${imageUrls.length} images...`);
                    imageUrls.forEach(url => {
                        const img = new Image(); // Create a new image object
                        img.src = `../${url}`; // Set the source, which triggers the browser to download and cache it
                    });
                })
                .catch(error => console.error('Error fetching image list for pre-caching:', error));
        });
    </script>

</body>
</html>
