const displaySongName = function(name) {
    const songNameElement = document.querySelector("#song-name");
    songNameElement.textContent = `Song: ${name}`;
}

const displayAuthorName = function(name) {
    const authorNameElement = document.querySelector("#author-name");
    authorNameElement.textContent = `Author name: ${name}`;
}

const displayMusicVideo = function(videoId) {
    const iframeElement = document.querySelector("#music-video");
    const src = `https://www.youtube.com/embed/${videoId}`;
    iframeElement.setAttribute("src", src);
}

// Gets todays song name
const fetchSongName = function() {
    getJSON("GetSongName")
        .then((json) => {
            displaySongName(json.name);
            let clueObj = {
                elementId: "song-name",
                value: json.name,
            }
            addClueToLocalStorage(clueObj);
        })
        .catch(() => {
            displayError("There was an error while fetching song name... Please try again later.");
        });
}

// Gets songs author name
const fetchAuthorName = function() {
    getJSON("GetAuthorName")
        .then((json) => {
            displayAuthorName(json.name);
            let clueObj = {
                elementId: "author-name",
                value: json.name,
            }
            addClueToLocalStorage(clueObj);
        })
        .catch(() => {
            displayError("There was an error while fetching author name... Please try again later.");
        });
}

// Gets youtube video id
const fetchVideoId = function() {
    getJSON("GetVideoId")
        .then((json) => {
            displayMusicVideo(json.video_id);
            let clueObj = {
                elementId: "music-video",
                value: json.name,
            }
            addClueToLocalStorage(clueObj);
        })
        .catch(() => {
            displayError("There was an error while fetching author name... Please try again later.");
        });
}

// Get list a list of radio stations and render them in select element
const fetchRadioStations = function() {
    renderSelectWrapper();
    getJSON("GetRadioStations")
        .then((json) => {
            renderRadioOptions(json);
        })
        .catch(() => {
            displayError("There was an error while fetching radio stations... Please try again later.");
        });
}

const submitRadioGuess = function() {
    const select = document.querySelector("#radio-select");
    const radioId = select.value;
    const selectedOption = select.selectedOptions[0];
    const data = {
        "id": radioId,
        "guess_number": getGuessesFromLocalStorage().length + 1,
    }
    postJSON("RadioGuess", data)
        .then((json) => {
            addGuessToLocalStorage({
                "id": radioId,
                "name": selectedOption.textContent,
                "correct": json.correct,
            });
            if (json.correct) {
                const select = document.querySelector("#select-wrapper");
                select.remove();
                setGameStatus(true);
            } else {
                removeRadioOption(radioId);
            }
            renderGuess(selectedOption.textContent, json.correct);
            for (const clueObj of json.clues) {
                addClueToLocalStorage();
                renderClue(clueObj);
            }
        })
        .catch(() => {
            displayError("There was an error while submitting guess... Please try again later.");
        });
}

const renderSelectWrapper = function() {
    const wrapper = document.querySelector("#select-wrapper");

    const select = document.createElement("select");
    select.id = "radio-select";
    select.classList.add("form-select", "w-auto");
    const button = document.createElement("input");
    button.setAttribute("type", "button");
    button.classList.add("btn", "btn-success", "mx-2");
    button.value = "Submit";
    button.addEventListener("click", submitRadioGuess);

    wrapper.append(select, button);
}

const renderRadioOptions = function(stationsArray) {
    const radioSelect = document.querySelector("#radio-select");
    const guessesRadioIds = getGuessesFromLocalStorage().map((x) => Number(x.id));
    for (const station of stationsArray) {
        if (guessesRadioIds.includes(station.id)) {
            continue;
        }
        const option = document.createElement("option");
        option.value = station.id;
        option.textContent = station.name;

        radioSelect.append(option);
    }
}

const removeRadioOption = function(optionValue) {
    const radioSelect = document.querySelector("#radio-select");
    for (const option of radioSelect.children) {
        if (option.value == optionValue) {
            option.remove();
        }
    }
}

const renderGuess = function(text, isCorrect) {
    const footer = document.querySelector("footer");
    const previousGuess = document.querySelector(".guess");
    const guess = document.createElement("div");
    guess.classList.add("container-sm", "text-light", "my-2", "guess");
    isCorrect ? guess.classList.add("bg-success") : guess.classList.add("bg-danger");
    guess.textContent = text;

    previousGuess ? document.body.insertBefore(guess, previousGuess) : document.body.insertBefore(guess, footer);
}

const renderClue = function(clueObj) {
    const element = document.querySelector(`#${clueObj.elementId}`);
    switch (element.tagName) {
        case "P":
            if (element.textContent) {
                return;
            }
            if (element.id == "song-name") {
                displaySongName(clueObj.value);
            } else {
                displayAuthorName(clueObj.value);
            }
            break;

        case "IFRAME":
            if (element.hasAttribute("src")) {
                return;
            }
            displayMusicVideo(clueObj.value);
            break;
    }
}

// Displays all the current available clues to the player
const clues = getCluesFromLocalStorage();
for (const clueObj of clues) {
    renderClue(clueObj);
}

// Display all the guesses that player made
const guesses = getGuessesFromLocalStorage();
for (const guessObj of guesses) {
    renderGuess(guessObj.name, guessObj.correct);
}

// Checks if player has beaten the mini game for today
if (!getGameStatus()) {
    fetchRadioStations();
    if (getCluesFromLocalStorage().length == 0) {
        fetchSongName();
    }
}
