document.addEventListener('DOMContentLoaded', () => {
    // --- Element Selectors ---
    const cardOverlay = document.getElementById('card-display-overlay');
    const deckCards = document.querySelectorAll('.deck-card');
    const doneBtn = document.getElementById('done-btn');
    const shuffleSound = document.getElementById('shuffle-sound');
    const reshuffleBtn = document.getElementById('reshuffle-btn');
    
    const drawnCardTitle = document.getElementById('drawn-card-title');
    const drawnCardDescription = document.getElementById('drawn-card-description');
    const drawnCardImage = document.getElementById('drawn-card-image');

    // --- State Variables ---
    let currentlyFlippedCard = null;
    let isOverlayVisible = false;

    // --- Event Listeners ---
    deckCards.forEach(card => {
        card.addEventListener('click', () => {
            if (!isOverlayVisible) {
                drawCard(card);
            }
        });
    });

    doneBtn.addEventListener('click', hideCard);

    reshuffleBtn.addEventListener('click', () => {
        if (currentlyFlippedCard) {
            const deckId = currentlyFlippedCard.dataset.deckId;
            reshuffleDeck(deckId);
        }
    });

    cardOverlay.addEventListener('click', (e) => {
        if (e.target === cardOverlay) {
            hideCard();
        }
    });

    // --- Core Functions ---
    function drawCard(deckElement) {
        currentlyFlippedCard = deckElement;
        const deckId = deckElement.dataset.deckId;
        isOverlayVisible = true;

        if (shuffleSound) {
            shuffleSound.currentTime = 0;
            shuffleSound.play();
        }

        deckElement.classList.add('is-flipping');

        setTimeout(() => {
            fetch(`api.php?action=draw&deck_id=${deckId}&t=${new Date().getTime()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        displayErrorMessage(data.error, true);
                    } else {
                        displayCard(data);
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    displayErrorMessage('An unexpected error occurred. Please try again.', false);
                });
        }, 250);
    }

    function reshuffleDeck(deckId) {
        fetch(`api.php?action=reshuffle_deck&deck_id=${deckId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideCard(); // Silently hide the overlay on success
                } else {
                    alert('Sorry, something went wrong and the deck could not be reshuffled.');
                }
            })
            .catch(error => console.error('Reshuffle Error:', error));
    }

    // --- UI Display Functions ---
    function displayCard(cardData) {
        reshuffleBtn.style.display = 'none';
        drawnCardTitle.style.display = 'block';
        drawnCardImage.style.display = 'block';
        doneBtn.style.display = 'block';
        drawnCardDescription.style.textAlign = 'left';

        drawnCardTitle.textContent = cardData.title;
        drawnCardDescription.innerHTML = cardData.description.replace(/\n/g, '<br>');

        if (cardData.image_url) {
            drawnCardImage.src = cardData.image_url;
            drawnCardImage.style.display = 'block';
        } else {
            drawnCardImage.style.display = 'none';
        }

        cardOverlay.style.display = 'flex';
    }

    function displayErrorMessage(message, showReshuffleBtn) {
        drawnCardTitle.style.display = 'none';
        drawnCardImage.style.display = 'none';
        doneBtn.style.display = 'block';
        drawnCardDescription.style.textAlign = 'center';

        drawnCardDescription.textContent = message;

        if (showReshuffleBtn) {
            reshuffleBtn.style.display = 'inline-block';
        } else {
            reshuffleBtn.style.display = 'none';
        }

        cardOverlay.style.display = 'flex';
    }

    function hideCard() {
        cardOverlay.style.display = 'none';
        isOverlayVisible = false;

        if (currentlyFlippedCard) {
            currentlyFlippedCard.classList.remove('is-flipping');
            currentlyFlippedCard = null;
        }
    }
});
