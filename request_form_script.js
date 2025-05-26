class Schedule {
  constructor(start, end) {
    this.start = start;
    this.end = end;
  }
}

const maxDurationMs = 12 * 60 * 60 * 1000;

const textarea = document.getElementById("purpose");
const charCount = document.getElementById("charCount");
const facultyNames = [];
const isFacultyAvailable = [];

let roomSchedule = [];
let hasConflict = true;

document.addEventListener("DOMContentLoaded", initializePage);

function initializePage() {
    getFacultyNames();
    addAutocompleteFeature();
    getRoomSchedule();

    document.getElementById('reservation-start').addEventListener('input', checkScheduleConflict);
    document.getElementById('reservation-end').addEventListener('input', checkScheduleConflict);

    document.getElementById("student-request-form").addEventListener("submit", verifySubmission);

    checkScheduleConflict()
}

function getFacultyNames() {
    fetch('faculty_names.php')
    .then(response => response.json())
    .then(facultyData => {
        facultyData.forEach(faculty => {
            facultyNames.push(faculty.name);
            isFacultyAvailable.push(faculty.is_available);
        });
    });
}

function addAutocompleteFeature() {
    const input = document.getElementById('faculty-input');
    const suggestionsBox = document.getElementById('suggestions');

    input.addEventListener('input', () => {
        const query = input.value.toLowerCase();
        suggestionsBox.innerHTML = ""; // Clear previous suggestions

        if (!query) return;

        const filtered = facultyNames.filter(name => name.toLowerCase().includes(query));

        filtered.forEach(name => {
            const item = document.createElement('div');
            item.classList.add('suggestion-item');
            item.textContent = name;
            item.addEventListener('click', () => {
                input.value = name;
                suggestionsBox.innerHTML = ""; // Clear suggestions after selection
            });
            suggestionsBox.appendChild(item);
        });
    });
}

function getRoomSchedule(){
    fetch('room_schedule.php')
    .then(response => response.json())
    .then(roomSched => {
        roomSchedule = roomSched;
    });
}

function checkScheduleConflict() {
    const room = document.getElementById("room-name").value;
    const time_start = document.getElementById("reservation-start").value;
    const time_end = document.getElementById("reservation-end").value;
    const outputPrompt = document.getElementById("conflict-message");

    outputPrompt.innerHTML = "Input Error:";
    
    let hasSufficientInput = true;
    if (!room) {
        outputPrompt.innerHTML += "<br>- Please select a room.";
        hasSufficientInput = false;
    }

    if (!time_start) {
        outputPrompt.innerHTML += "<br>- Please select a start schedule.";
        hasSufficientInput = false;
    }

    if (!time_end) {
        outputPrompt.innerHTML += "<br>- Please select an end schedule.";
        hasSufficientInput = false;
    }

    if (hasSufficientInput && time_start >= time_end) {
        outputPrompt.innerHTML += "<br>- Start time must be earlier than end time.";
        hasSufficientInput = false;
    }

    if (hasSufficientInput){
        const durationMs = new Date(time_end) - new Date(time_start);

        if (durationMs > maxDurationMs) {
            outputPrompt.innerHTML += "<br>- The reservation duration exceeds 12 hours.";
            hasSufficientInput = false;
        }
    }

    if (!hasSufficientInput) {
        return;
    }

    outputPrompt.innerHTML = "";

    const schedule = new Schedule(new Date(time_start), new Date(time_end));
    const dateStart = time_start.substring(0, 10);
    const dateEnd = time_end.substring(0, 10);

    const dayStart = schedule.start.getDay();
    const dayEnd = schedule.end.getDay();

    hasConflict = false;
    roomSchedule[dayStart].forEach(daySchedule => {
        dayScheduleStart = new Date(dateStart + " " + daySchedule.time_start);
        dayScheduleEnd = new Date(dateStart + " " + daySchedule.time_end);

        if (dayScheduleStart < schedule.end && dayScheduleEnd > schedule.start) {
            hasConflict = true;
        }

    });

    if (!hasConflict && dayStart !== dayEnd) {
        roomSchedule[dayEnd].forEach(daySchedule => {
            dayScheduleStart = new Date(dateEnd + " " + daySchedule.time_start);
            dayScheduleEnd = new Date(dateEnd + " " + daySchedule.time_end);

            if (dayScheduleStart < schedule.end && dayScheduleEnd > schedule.start) {
                hasConflict = true;
            }
        });
    }



    if (hasConflict) {
        outputPrompt.innerHTML = "Conflict Found";
    }
    else {
        outputPrompt.innerHTML = "No Conflict Found";
    }
}

function verifySubmission(event) {
    event.preventDefault();

    const time_start = document.getElementById("reservation-start").value;
    const time_end = document.getElementById("reservation-end").value;
    const facultyInput = document.getElementById("faculty-input").value;
    const purpose = document.getElementById("purpose").value;

    if (!time_start || !time_end || !facultyInput || !purpose) {
        alert("Please fill in all fields.");
        return;
    }

    if (!facultyNames.includes(facultyInput)) {
        alert("Please select a valid faculty member from the suggestions.");
        return;
    }

    if (hasConflict) {
        alert("There is a scheduling conflict. Please resolve it before submitting.");
        return;
    }

    // If all checks pass, submit the form
    document.getElementById("student-request-form").submit();
}
