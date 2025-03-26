const optionMap = {
    "title": "title",
    "origin": "name",
    "giver": "name",
};

const getMissionStatus = function(attributeName) {
    return localStorage.getItem(`mission-${attributeName}-Beaten`) === "true";
}

const setMissionStatus = function(attributeName, value) {
    localStorage.setItem(`mission-${attributeName}-Beaten`, value);
}

const renderSearch = function(dataArr, attributeName) {
    const missionElement = document.querySelector(`#mission-${attributeName} .${attributeName}`);
    const input = document.createElement("input");
    input.setAttribute("type", "text");
    input.classList.add("search-input", "form-control");
    input.addEventListener("keyup", handleSearchInput.bind(input, dataArr, attributeName));
    input.addEventListener("keydown", (event) => {
        if (event.code === "Enter") {
            const selectedOption = missionElement.querySelector(".selected-option");
            if (selectedOption) {
                const attribute = selectedOption.dataset.attribute;
                const value = selectedOption.dataset.value;
                const text = selectedOption.textContent;
                handleSubmit(attribute, value, text);
            }
        }
    });

    missionElement.textContent = "";
    missionElement.append(input);
    missionElement.classList.add("position-relative");
}

const handleSearchInput = function(dataArr, attributeName) {
    let searchOptions = this.nextSibling;
    const text = this.value;
    if (text === "") {
        if (searchOptions) {
            searchOptions.remove();
        }
        return;
    }

    if (!searchOptions) {
        searchOptions = document.createElement("div");
        searchOptions.classList.add("search-options", "bg-white");
        this.after(searchOptions);
    }

    searchOptions.innerHTML = "";
    const guessedOptions = getGameGuesses().filter(x => x.attributeToGuess === attributeName).map(y => y.id);
    const remainingOptions = dataArr.filter(x => !guessedOptions.includes(x.id));
    for (const option of remainingOptions) {
        const optionValue = option.id;
        const optionText = option[optionMap[attributeName]];

        if (optionText.toLowerCase().includes(text.toLowerCase())) {
            const optionElement = document.createElement("div");
            optionElement.classList.add("search-option");
            optionElement.addEventListener("mouseenter", () => { handleMouseOver(optionElement) });
            optionElement.addEventListener("click", () => { handleSubmit(attributeName, optionValue, optionText) });

            optionElement.textContent = optionText;
            optionElement.dataset.value = optionValue;
            optionElement.dataset.attribute = attributeName;

            searchOptions.append(optionElement);
        }
    }

    if (searchOptions.children.length != 0) {
        searchOptions.children[0].classList.add("selected-option");
    } else {
        searchOptions.remove();
    }
}

const handleMouseOver = function(optionElement) {
    const selected = optionElement.parentElement.querySelector(".selected-option");
    selected.classList.remove("selected-option");
    optionElement.classList.add("selected-option");
}

const handleSubmit = function(attributeName, value, text) {
    const missionElement = document.querySelector(`#mission-${attributeName}`);
    missionElement.querySelector("input").value = "";

    const searchOptions = missionElement.querySelector(".search-options");
    searchOptions.parentElement.previousSibling.value = "";
    searchOptions.remove();

    const guessNumber = getGameGuesses().filter(x => x.attributeToGuess === attributeName).length;
    const data = {
        id: Number(value),
        guessNumber: guessNumber + 1,
    };

    const endpoint = `Mission${attributeName.charAt(0).toUpperCase() + attributeName.slice(1)}Guess`;

    postJSON(endpoint, data)
        .then((json) => {
            renderGuess(attributeName, text, json.correct);
            if (json.correct) {
                const attribute = missionElement.querySelector(`.${attributeName}`);
                attribute.innerHTML = "";
                setMissionStatus(attributeName, true);
            }

            const guessObj = {
                "attributeToGuess": attributeName,
                "id": Number(value),
                "text": text,
                "correct": json.correct,
            };
            addGameGuess(guessObj);

            for (const clueObj of json.clues) {
                addGameClue(clueObj);
                renderSingleClue(clueObj);
            }
        })
        .catch(() => {
            displayError("Error while submitting mission guess... Please try again later.");
        });
}

async function missionGetInitialClues() {
    try {
        const clues = await getJSON("GetFirstMissionClues");
        for (const element of clues) {
            const clueObj = {
                attributeToGuess: element.attributeToGuess,
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

const renderTitleSelect = async function(missionTitlesArr) {
    renderSearch(missionTitlesArr, "title");
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
    renderSearch(missionOriginsArr, "origin");
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
    renderSearch(missionGiversArr, "giver");
}

function missionRenderClues(cluesArr) {
    for (const clueObj of cluesArr) {
        renderSingleClue(clueObj);
    }
}

const renderSingleClue = function(clueObj) {
    const missionElement = document.querySelector(`#mission-${clueObj.attributeToGuess}`);
    const missionAttributeElement = missionElement.querySelector(`.${clueObj.elementClass}`);
    missionAttributeElement.textContent = clueObj.value;
}

function missionRenderGuesses(guessesArr) {
    for (const guessObj of guessesArr) {
        renderGuess(guessObj.attributeToGuess, guessObj.text, guessObj.correct);
    }
}

function renderGuess(attributeName, text, isCorrect) {
    const missionElement = document.querySelector(`#mission-${attributeName}`);

    if (isCorrect) {
        const missionAttributeElement = missionElement.querySelector(`.${attributeName}`);
        missionAttributeElement.textContent = text;
        missionAttributeElement.classList.add("bg-success");
    } else {
        const previousGuess = document.querySelector(`.guess-${attributeName}`);
        const guess = document.createElement("div");
        guess.classList.add("container-sm", "bg-danger", "text-light", "my-2", "guess", `guess-${attributeName}`);
        guess.textContent = text;

        previousGuess ? missionElement.parentElement.insertBefore(guess, previousGuess) : missionElement.after(guess);
    }
}

async function missionStart() {
    if (!getMissionStatus("title")) {
        const missionTitles = await fetchMissionTitles();
        renderTitleSelect(missionTitles);
    }

    if (!getMissionStatus("origin")) {
        const missionOrigins = await fetchMissionOrigins();
        renderOriginsSelect(missionOrigins);
    }

    if (!getMissionStatus("giver")) {
        const missionGivers = await fetchMissionGivers();
        renderGiversSelect(missionGivers);
    }
}

function missionReset() {
    const missionTitle = document.querySelector("#mission-title");
    const missionOrigin = document.querySelector("#mission-origin");
    const missionGiver = document.querySelector("#mission-giver");

    const missionTitleElements = missionTitle.querySelectorAll("tbody tr:nth-child(2) td");
    for (const element of missionTitleElements) {
        element.classList.remove("bg-success");
        element.textContent = "?";
    }

    const missionOriginElements = missionOrigin.querySelectorAll("tbody tr:nth-child(2) td");
    for (const element of missionOriginElements) {
        element.classList.remove("bg-success");
        element.textContent = "?";
    }

    const missionGiverElements = missionGiver.querySelectorAll("tbody tr:nth-child(2) td");
    for (const element of missionGiverElements) {
        element.classList.remove("bg-success");
        element.textContent = "?";
    }

    const guesses = document.querySelectorAll(".guess");
    for (const guess of guesses) {
        guess.remove();
    }
}
