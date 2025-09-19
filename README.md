# No, Bad Ideas! - The Game

"No, Bad Ideas!" is a collaborative and competitive board game for 2-4 players. It simulates the chaotic, challenging, and ultimately rewarding process of taking a client brief from initial concept to a full campaign launch.

This web application serves as the digital companion for the physical board game, providing the "Campaign Twist" and "Media Boost" cards needed to play.

**[Live Demo](https://badideas.help)**

## Objective

The game is played over five phases. The player who wins a phase by being the first to reach the end of the board is awarded a prestigious NZ advertising award. The ultimate objective is to win the most awards and take home the coveted Cannes Lion.

## Customization & Self-Hosting

The primary reason to host your own version of this app is to **create your own custom card sets**. You can tailor the "Campaign Twist" and "Media Boost" cards to reflect the unique challenges and inside jokes of your own agency or workplace.

This project also includes a "Suggestion Box" feature, located in the `/suggestions/` directory. You can give this link to colleagues, allowing them to submit their own ideas for cards, which you can then approve and add to the game via the admin panel.

## Getting Started

To get the game running on your own web server, you will need two components: the web app and a physical game board.

### 1. The Web App

Follow these steps to set up the application:

1.  **Download Files:** Clone or download this repository.
2.  **Create `config.php`:** Find `config-example.php`, make a copy, and rename it to `config.php`.
3.  **Edit `config.php`:** Open your new `config.php` and fill in your database details. Set the `BASE_URL` to the full URL where you will host the app (e.g., `https://your-domain.com/nobadideas`).
4.  **Set Up Database:** Use the `database_setup.sql` file to create and populate the necessary tables in your database.
5.  **Upload Images:** Unzip `uploads.zip` and place its contents into the `uploads/` directory.
6.  **Upload to Server:** Upload all project files to your web server.
7.  **Play!** Navigate to your `BASE_URL` to start a game.

### 2. The Physical Game Board

This web app is designed to be used with a physical game board. You will need to **create a game board for each of the five phases**.

Each board should be a track of spaces, like a subway map line. The track should include named spaces for the key tasks and deliverables of that phase (e.g., "Client Briefing," "Creative Ideation") with several blank spaces in between them. Players move their tokens along this track, drawing a card from the app whenever they land on a blank space.

## How to Play

*   **The Goal:** Be the first to complete each of the five campaign phases and win an award. The player with the most awards at the end wins!
*   **Setup:** All players place their chosen Role token on the starting space of the current phase board.
*   **On Your Turn:** Roll the dice and move forward.
    *   If you land on a **named space**, you are safe.
    *   If you land on a **blank space**, you must draw a "Campaign Twist" card from this web app.
*   **Winning a Phase:** The first player to land on or pass the final space of a board wins that phase. The game then moves to the next phase for all players.# nobadideas
No, Bad Ideas! The Boardgame.
