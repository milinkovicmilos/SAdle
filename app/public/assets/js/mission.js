const getMissionStatus = function(missionNumber) {
    return localStorage.getItem(`mission-${missionNumber}-Beaten`) === "true";
}

const setMissionStatus = function(missionNumber, value) {
    localStorage.setItem(`mission-${missionNumber}-Beaten`, value);
}

async function missionGetInitialClues() {
    try {
        const clues = await getJSON("GetFirstMissionClues");
        for (const element of clues) {
            const clueObj = {
                missionNumber: element.missionNumber,
                elementClass: element.elementClass,
                value: element.value,
            }
            addGameClue(clueObj);
        }
    }
    catch (error) {
        displayError("There was an error while fetching initial mission clues... Please try again later.");
    }
}

const fetchMissionTitles = async function() {
    try {
        return await getJSON("GetMissionTitles");
    }
    catch (error) {
        displayError("There was an error while fetching mission titles... Please try again later.");
    }
}

const renderTitleSelect = function(missionTitlesArr) {
}

const fetchMissionOrigins = async function() {
    try {
        return await getJSON("GetMissionOrigins");
    }
    catch (error) {
        displayError("There was an error while fetching mission origins... Please try again later.");
    }
}

const renderOriginsSelect = function(missionOriginsArr) {
}

const fetchMissionGivers = async function() {
    try {
        return await getJSON("GetMissionGivers");
    }
    catch (error) {
        displayError("There was an error while fetching mission givers... Please try again later.");
    }
}

const renderGiversSelect = function(missionGiversArr) {
}

function missionRenderClues(cluesArr) {
    for (const clueObj of cluesArr) {
        renderSingleClue(clueObj);
    }
}

const renderSingleClue = function(clueObj) {
    const missionElement = document.querySelector(`#mission-${clueObj.missionNumber}`);
    const missionAttributeElement = missionElement.querySelector(`.${clueObj.elementClass}`);
    missionAttributeElement.textContent = clueObj.value;
}

function missionRenderGuesses(guessesArr) {
    for (const guessObj of guessesArr) {
        renderGuess(guessObj.missionNumber, guessObj.text, guessObj.isCorrect);
    }
}

function renderGuess(missionNumber, text, isCorrect) {
    const missionElement = document.querySelector(`#mission-${clueObj.missionNumber}`);
    const map = {
        1: "title",
        2: "origin",
        3: "giver",
    };

    if (isCorrect) {
        const missionAttributeElement = missionElement.querySelector(`.${map[missionNumber]}`);
        missionAttributeElement.textContent = text;
        missionAttributeElement.classList.add("bg-success");
    } else {
        const previousGuess = document.querySelector(`.guess-${missionNumber}`);
        const guess = document.createElement("div");
        guess.classList.add("container-sm", "bg-danger", "text-light", "my-2", `guess-${missionNumber}`);
        guess.textContent = text;

        previousGuess ? document.body.insertBefore(guess, previousGuess) : missionElement.append(guess);
    }
}

async function missionStart() {
    const missionTitles = await fetchMissionTitles();
    renderTitleSelect(missionTitles);

    const missionOrigins = await fetchMissionOrigins();
    renderOriginsSelect(missionOrigins);

    const missionGivers = await fetchMissionGivers();
    renderGiversSelect(missionGivers);
}

function missionReset() {

}
