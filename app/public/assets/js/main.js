const getGameName = function() {
    let path = window.location.pathname;
    if (path == '/') {
        path = "/radio";
    }

    return path.split('/')[1];
}

const GAME = getGameName();

// These are game functions for fetching the game specific data and starting it
const STARTFUNCTIONS = {
    "radio": radioStart,
};

// These are game functions that reset the
// page stateto the one originally sent by the server
const RESETFUNCTIONS = {
    "radio": radioReset,
};

// These fucntions are for rendering the clues that player got so far
const RENDERCLUESFUNCTIONS = {
    "radio": radioRenderClues,
};

const GETINITIALCLUESFUNCTIONS = {
    "radio": radioGetInitialClues,
};

const RENDERGUESSESFUNCTIONS = {
    "radio": radioRenderGuesses,
};

const getJSON = async function(url) {
    try {
        const request = await fetch(url, {
            method: "GET",
            headers: { "Content-type": "application/json" },
        });

        if (request.status != 200) {
            return null;
        }

        return request.json();
    }
    catch (error) {
        // Display message to user
    }
}

const postJSON = async function(url, data) {
    try {
        const request = await fetch(url, {
            method: "POST",
            headers: { "Content-type": "application/json" },
            body: JSON.stringify(data),
        });

        if (request.status != 200) {
            return null;
        }

        return request.json();
    }
    catch (error) {
        // Display message to user
    }
}

const displayError = function(errorText) {
    let errorElement = document.querySelector("#error");
    if (errorElement) {
        errorElement.textContent = errorText;
    }
    else {
        errorElement = document.createElement("p");
        errorElement.id = "error";
        errorElement.classList.add("text-danger", "text-center");
        errorElement.textContent = errorText;

        const body = document.querySelector("body");
        body.append(errorElement);
    }
}

const markActiveLink = function() {
    const linksUl = document.querySelector("header div ul");
    for (const li of linksUl.children) {
        const anchor = li.querySelector("a");
        if (anchor.textContent.toLowerCase() == GAME) {
            anchor.classList.add("active");
        }
    }
}

const initializeTimer = async function(initialTime) {
    await countdownTimer(initialTime);
    initializePageRefresh();
}

const countdownTimer = async function(time) {
    if (time <= 0) {
        return;
    }
    renderTimer(time);

    await new Promise(resolve => setTimeout(resolve, 1000));
    await countdownTimer(--time);
}

const renderTimer = async function(time) {
    const hours = Math.floor(time / 3600);
    const minutes = Math.floor(time / 60) % 60;
    const seconds = time % 60;

    const hoursString = String(hours).padStart(2, '0');
    const minutesString = String(minutes).padStart(2, '0');
    const secondsString = String(seconds).padStart(2, '0');

    const text = `${hoursString}:${minutesString}:${secondsString}`;
    const timerElement = document.querySelector("#timer");
    timerElement.textContent = text;
}

const getServerDate = async function() {
    try {
        return getJSON("GetCurrentDate");
    }
    catch (error) {
        displayError("There was an error while getting the server date... Please try again later.");
    }
}

const getGameDate = function() {
    return localStorage.getItem("gameDate");
}

const setGameDate = function(dateStr) {
    localStorage.setItem("gameDate", dateStr);
}

const setGameStatus = function(value) {
    localStorage.setItem(`${GAME}Beaten`, JSON.stringify(value));
}

const getGameStatus = function() {
    return localStorage.getItem(`${GAME}Beaten`) === "true";
}

const getGameClues = function() {
    const value = localStorage.getItem(`${GAME}Clues`);
    return value == null ? [] : JSON.parse(value);
}

const setGameClues = function(cluesArr) {
    localStorage.setItem(`${GAME}Clues`, JSON.stringify(cluesArr));
}

const addGameClue = function(clueObj) {
    let cluesArr = getGameClues();
    cluesArr.push(clueObj);
    setGameClues(cluesArr);
}

const getGameGuesses = function() {
    const value = localStorage.getItem(`${GAME}Guesses`);
    return value == null ? [] : JSON.parse(value);
}

const setGameGuesses = function(guessesArr) {
    localStorage.setItem(`${GAME}Guesses`, JSON.stringify(guessesArr));
}

const addGameGuess = function(guessObj) {
    let guessesArr = getGameGuesses();
    guessesArr.push(guessObj);
    setGameGuesses(guessesArr);
}

const resetGameData = function() {
    localStorage.removeItem(`${GAME}Clues`);
    localStorage.removeItem(`${GAME}Guesses`);
    localStorage.removeItem(`${GAME}Beaten`);
}

const initializePageRefresh = function() {
    RESETFUNCTIONS[GAME]();
    setupGame();
}

const setupGame = async function() {
    const gameDate = new Date(getGameDate());
    const serverDateJSON = await getServerDate();
    const serverDate = new Date(serverDateJSON.date);
    const timeToNextDay = serverDateJSON.timeToNextDay;

    // If it's the new game day then just reset the previous data about the game
    if (serverDate > gameDate) {
        resetGameData();
    }
    setGameDate(serverDateJSON.date);
    initializeTimer(timeToNextDay);

    // If the player just started the game
    if (getGameClues().length == 0) {
        await GETINITIALCLUESFUNCTIONS[GAME]();
    }

    RENDERCLUESFUNCTIONS[GAME](getGameClues());
    RENDERGUESSESFUNCTIONS[GAME](getGameGuesses());
    if (!getGameStatus()) {
        STARTFUNCTIONS[GAME]();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Initialize Basic setup and call the start game function
    setupGame();

    // Mark the active link in header
    markActiveLink();
});
