const getGameName = function() {
    let path = window.location.pathname;
    if (path == '/') {
        path = "/radio";
    }

    return path.split('/')[1];
}

const GAME = getGameName();

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
        console.log(`Get request error : ${error}`);
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
        console.log(`Get request error : ${error}`);
    }
}

const getLocalStorage = function(key) {
    return localStorage.getItem(key);
}

const setLocalStorage = function(key, value) {
    localStorage.setItem(key, value);
}

// Sets the game status in local storage
const setGameStatus = function(value) {
    setLocalStorage(`${getGameName()}Beaten`, value);
}

const getGameStatus = function() {
    return getLocalStorage(`${getGameName()}Beaten`) == "true";
}

const setupGame = function() {
    getJSON("GetCurrentDate")
        .then((json) => {
            const date = json.date;
            const serverDate = new Date(date);
            const localStorageDate = getLocalStorageGameDate();

            if (serverDate > localStorageDate || localStorageDate == null) {
                resetGame(date);
            }

            renderTimer(json.timeToNextDay);
            setTimeout(() => { location.reload(); }, json.timeToNextDay * 1000);
        })
        .catch(() => {
            displayError("Error while fetching game data... Please try again later.");
            return;
        });
}

const getLocalStorageGameDate = function() {
    return new Date(getLocalStorage("gameDate"));
}

const setLocalStorageGameDate = function(date) {
    setLocalStorage("gameDate", date);
}

const getCluesFromLocalStorage = function() {
    const clues = getLocalStorage(`${GAME}Clues`);
    if (clues == null) {
        return [];
    }
    return JSON.parse(clues);
}

const addClueToLocalStorage = function(clueObj) {
    let clues = getCluesFromLocalStorage();
    clues.push(clueObj);
    setLocalStorage(`${GAME}Clues`, JSON.stringify(clues));
}

const resetCluesInLocalStorage = function() {
    setLocalStorage(`${GAME}Clues`, "[]");
}

const getGuessesFromLocalStorage = function() {
    const guesses = getLocalStorage(`${GAME}Guesses`);
    if (guesses == null) {
        return [];
    }
    return JSON.parse(guesses);
}

const addGuessToLocalStorage = function(guessObj) {
    const guesses = getGuessesFromLocalStorage();
    guesses.push(guessObj);
    setLocalStorage(`${GAME}Guesses`, JSON.stringify(guesses));
}

const resetGuessesInLocalStorage = function() {
    setLocalStorage(`${GAME}Guesses`, "[]");
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

const resetGame = function(gameDate) {
    setGameStatus(false);
    setLocalStorageGameDate(gameDate);
    resetCluesInLocalStorage();
    resetGuessesInLocalStorage();
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

const renderTimer = function(time) {
    if (time <= 0) {
        return;
    }

    const hours = Math.floor(time / 3600) % 60;
    const minutes = Math.floor(time / 60) % 60;
    const seconds = time % 60;

    const hoursString = String(hours).padStart(2, '0');
    const minutesString = String(minutes).padStart(2, '0');
    const secondsString = String(seconds).padStart(2, '0');

    const text = `${hoursString}:${minutesString}:${secondsString}`;
    const timerElement = document.querySelector("#timer");
    timerElement.textContent = text;

    setTimeout(() => { renderTimer(--time); }, 1000);
}

// Basic setup for every minigame
setupGame();

// Mark the active link in header
markActiveLink();
