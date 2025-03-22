const displaySongName = function(name) {
    const songNameElement = document.querySelector("#song-name");
    if (!songNameElement.textContent) {
        songNameElement.textContent = `Song: ${name}`;
    }
}

const displayAuthorName = function(name) {
    const authorNameElement = document.querySelector("#author-name");
    if (!authorNameElement.textContent) {
        authorNameElement.textContent = `Author name: ${name}`;
    }
}

const displayMusicVideo = function(videoId) {
    const iframeElement = document.querySelector("#music-video");
    const src = `https://www.youtube.com/embed/${videoId}`;
    if (!iframeElement.getAttribute("src")) {
        iframeElement.setAttribute("src", src);
    }
}

function radioRenderClues(cluesArr) {
    for (const clueObj of cluesArr) {
        radioRenderSingleClue(clueObj);
    }
}

const radioRenderSingleClue = function(clueObj) {
    switch (clueObj.elementId) {
        case "song-name":
            displaySongName(clueObj.value);
            break;
        case "author-name":
            displayAuthorName(clueObj.value);
            break;
        case "music-video":
            displayMusicVideo(clueObj.value);
            break;
    }
}

// Gets todays song name
async function radioGetInitialClues() {
    try {
        const clue = await getJSON("GetSongName");
        const clueObj = {
            elementId: clue.elementId,
            value: clue.name,
        }
        addGameClue(clueObj);
    }
    catch (error) {
        displayError("There was an error while fetching song name... Please try again later.");
    }
}

// Get list a list of radio stations and render them in select element
const fetchRadioStations = async function() {
    try {
        return await getJSON("GetRadioStations");
    }
    catch (error) {
        displayError("There was an error while fetching radio stations... Please try again later.");
    }
}

const renderRadioSelect = function(radioStationsArr) {
    const guessedRadioIds = getGameGuesses().map(x => Number(x.id));
    const wrapper = document.querySelector("#select-wrapper");

    const select = document.createElement("select");
    select.id = "radio-select";
    select.classList.add("form-select", "w-auto");
    for (const radioObj of radioStationsArr) {
        if (guessedRadioIds.includes(radioObj.id)) {
            continue;
        }
        const option = document.createElement("option");
        option.value = radioObj.id;
        option.textContent = radioObj.name;
        select.append(option);
    }

    const button = document.createElement("button");
    button.textContent = "Submit";
    button.classList.add("btn", "btn-success", "mx-2");
    button.addEventListener("click", submitRadioGuess);

    wrapper.append(select);
    wrapper.append(button);
}

const removeRadioSelectOption = function(optionValue) {
    const radioSelect = document.querySelector("#radio-select");
    for (const option of radioSelect.children) {
        if (option.value == optionValue) {
            option.remove();
        }
    }
}

const submitRadioGuess = function() {
    const radioSelect = document.querySelector("#radio-select");
    const radioId = radioSelect.value;
    const selectedRadioName = radioSelect.selectedOptions[0].textContent
    removeRadioSelectOption(radioId);

    const data = {
        id: Number(radioId),
        guessNumber: 10 - radioSelect.children.length,
    };

    postJSON("RadioGuess", data)
        .then((json) => {
            addGameGuess({
                id: radioId,
                name: selectedRadioName,
                correct: json.correct,
            });
            renderGuess(selectedRadioName, json.correct);
            if (json.correct) {
                const selectWrapper = document.querySelector("#select-wrapper");
                selectWrapper.innerHTML = "";
                setGameStatus(true);
            }

            // Additional clues we get from the game
            for (const clueObj of json.clues) {
                addGameClue(clueObj);
                radioRenderSingleClue(clueObj);
            }
        })
        .catch(() => {
            displayError("There was an error while submitting guess... Please try again later.");
        });
}

function radioRenderGuesses(guessesArr) {
    for (const guessObj of guessesArr) {
        renderGuess(guessObj.name, guessObj.correct);
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

// Game start function - called from main.js
// Prepares the interface for playing the game
async function radioStart() {
    const radioStationsArr = await fetchRadioStations();
    renderRadioSelect(radioStationsArr);
}

// Game reset function - called from main.js
// Resets the page to the state recieved from the server
function radioReset() {
    const songName = document.querySelector("#song-name");
    songName.textContent = "";

    const authorName = document.querySelector("#author-name");
    authorName.textContent = "";

    const musicVideo = document.querySelector("#music-video");
    musicVideo.setAttribute("src", "");

    const selectWrapper = document.querySelector("#select-wrapper");
    selectWrapper.innerHTML = "";

    const guessDivs = document.querySelectorAll(".guess");
    for (const element of guessDivs) {
        element.remove();
    }
}
