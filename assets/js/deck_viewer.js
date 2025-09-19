let cards = [];
let currentCardIndex = 0;
let deckType = '';

// DOM Elements
const card = document.getElementById('card');
const cardTitleFront = document.getElementById('card-title-front');
const cardImageFront = document.getElementById('card-image-front');
const cardTitleBack = document.getElementById('card-title-back');
const cardDetailsBack = document.getElementById('card-details-back');
const prevButton = document.getElementById('prev-card');
const nextButton = document.getElementById('next-card');
const flipButton = document.getElementById('flip-card');
const cardCounter = document.getElementById('card-counter');
const deckTitle = document.getElementById('deck-title');

/**
 * Initializes the deck by fetching card data from the API.
 * @param {string} type - The type of deck to fetch ('roles' or 'phases').
 */
async function initializeDeck(type) {
    deckType = type;
    if (card) {
        card.style.cursor = 'pointer'; // Make the card feel clickable
    }
    try {
        const response = await fetch(`../api.php?action=get_${deckType}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        cards = await response.json();
        if (cards.length > 0) {
            displayCard();
        } else {
            deckTitle.textContent = `No cards found in this deck.`;
        }
    } catch (error) {
        console.error('Error fetching card data:', error);
        deckTitle.textContent = 'Error loading deck.';
    }
}

/**
 * Displays the current card based on the currentCardIndex.
 */
function displayCard() {
    if (currentCardIndex < 0 || currentCardIndex >= cards.length) return;

    const currentCard = cards[currentCardIndex];

    // Reset flip state
    if (card.classList.contains('is-flipped')) {
        card.classList.remove('is-flipped');
        flipButton.textContent = 'See Details';
    }

    // --- Update Front of Card ---
    cardTitleFront.textContent = currentCard.title;
    // We'll use a placeholder image for now. You can update the path in the database.
    cardImageFront.src = currentCard.image_front ? '../uploads/' + currentCard.image_front : `https://via.placeholder.com/150/92c950/FFFFFF?text=${deckType.slice(0, -1)}`;

    // --- Update Back of Card ---
    cardTitleBack.textContent = currentCard.title;
    cardDetailsBack.innerHTML = ''; // Clear previous details

    if (deckType === 'roles') {
        cardDetailsBack.innerHTML = `
            <p class="mb-2"><strong class="font-semibold">Loves:</strong> ${currentCard.loves}</p>
            <p class="mb-2"><strong class="font-semibold">Hates:</strong> ${currentCard.hates}</p>
            <p><strong class="font-semibold">Key Phase:</strong> ${currentCard.key_phase}</p>
        `;
    } else if (deckType === 'phases') {
        cardDetailsBack.innerHTML = `
            <p class="mb-2"><strong class="font-semibold">What goes in:</strong> ${currentCard.what_goes_in}</p>
            <p class="mb-2"><strong class="font-semibold">What comes out:</strong> ${currentCard.what_comes_out}</p>
            <p class="mb-2"><strong class="font-semibold">Pain Points:</strong> ${currentCard.pain_points}</p>
            <p><strong class="font-semibold">Lead Roles:</strong> ${currentCard.lead_roles}</p>
        `;
    }

    updateControls();
}

/**
 * Updates the state of the navigation buttons and counter.
 */
function updateControls() {
    if (cardCounter) {
        cardCounter.textContent = `Card ${currentCardIndex + 1} of ${cards.length}`;
    }

    // Toggle button disabled state
    prevButton.disabled = currentCardIndex === 0;
    nextButton.disabled = currentCardIndex === cards.length - 1;

    // Apply styles for disabled buttons
    prevButton.classList.toggle('opacity-50', prevButton.disabled);
    prevButton.classList.toggle('cursor-not-allowed', prevButton.disabled);
    nextButton.classList.toggle('opacity-50', nextButton.disabled);
    nextButton.classList.toggle('cursor-not-allowed', nextButton.disabled);
}

/**
 * Toggles the flip state of the card.
 */
function toggleFlip() {
    card.classList.toggle('is-flipped');
    if (card.classList.contains('is-flipped')) {
        flipButton.textContent = 'See Front';
    } else {
        flipButton.textContent = 'See Details';
    }
}

// --- Event Listeners ---

// Flips the card when the main card element is clicked
card.addEventListener('click', () => {
    toggleFlip();
});

// Also flip when the button is clicked, but stop it from bubbling to the card
flipButton.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleFlip();
});

nextButton.addEventListener('click', () => {
    if (currentCardIndex < cards.length - 1) {
        currentCardIndex++;
        displayCard();
    }
});

prevButton.addEventListener('click', () => {
    if (currentCardIndex > 0) {
        currentCardIndex--;
        displayCard();
    }
});
