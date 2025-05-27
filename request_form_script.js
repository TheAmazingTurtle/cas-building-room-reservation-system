const maxDurationMs = 12 * 60 * 60 * 1000;
const minPreparationTimeMs = 2 * 24 * 60 * 60 * 1000;

const textarea = document.getElementById("purpose");
const charCount = document.getElementById("charCount");
const facultyNames = [];
const isFacultyAvailable = [];

let roomSchedule = [];
let approvedReservationSchedule = [];
let pendingReservationSchedule = [];
let hasConflict = true;

let pageInitialized = false;

document.addEventListener("DOMContentLoaded", initializePage);

function initializePage() {
    getFacultyNames();
    addAutocompleteFeature();
    getRoomSchedule();
    getReservationSchedule();

    document.getElementById('reservation-start').addEventListener('change', checkScheduleConflict);
    document.getElementById('reservation-end').addEventListener('change', checkScheduleConflict);

    document.getElementById("student-request-form").addEventListener("submit", verifySubmission);

    checkScheduleConflict();
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
        roomSched.forEach(daySchedule => {
            roomSchedule.push(daySchedule);
        });
    });
}

function getReservationSchedule() {
    fetch('reservation_schedule.php')
    .then(response => response.json())
    .then(reservationSched => {
        reservationSched.forEach(reservation => {
            reservation.start = new Date(reservation.start);
            reservation.end = new Date(reservation.end);

            if (reservation.isFacultyApproved === 1 && reservation.isAdminApproved === 1) {
                approvedReservationSchedule.push(reservation);
            } else {
                pendingReservationSchedule.push(reservation);
            }
        });
    });
}

function checkScheduleConflict() {
    class Schedule {
        constructor(start, end) {
            this.start = start;
            this.end = end;
        }
    }
    const room = document.getElementById("room-name").value;
    const time_start = document.getElementById("reservation-start").value;
    const time_end = document.getElementById("reservation-end").value;
    const outputPrompt = document.getElementById("error-prompt");
    const conflictContainer = document.getElementById("conflict-container");

    conflictContainer.innerHTML = "";
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

    if (!hasSufficientInput) {
        return;
    }

    const schedule = new Schedule(new Date(time_start), new Date(time_end));
    const now = new Date();

    if (schedule.start >= schedule.end) {
        outputPrompt.innerHTML += "<br>- Start time must be earlier than end time.";
        hasSufficientInput = false;
    }

    if (schedule.start < now) {
        outputPrompt.innerHTML += "<br>- Start time must be in the future.";
        hasSufficientInput = false;
    }

    if (!hasSufficientInput) {
        return;
    }

    const preparationTime = schedule.start - now;
    if (preparationTime < minPreparationTimeMs) {
        outputPrompt.innerHTML += "<br>- Users cannot reserve room less than 2 days in advance.";
        hasSufficientInput = false;
    }
    
    const durationMs = schedule.end - schedule.start;
    if (durationMs > maxDurationMs) {
        outputPrompt.innerHTML += "<br>- The reservation duration exceeds 12 hours.";
        hasSufficientInput = false;
    }

    if (!hasSufficientInput) {
        return;
    }

    outputPrompt.innerHTML = "";

    
    const dateStart = time_start.substring(0, 10);
    const dateEnd = time_end.substring(0, 10);

    const dayStart = schedule.start.getDay();
    const dayEnd = schedule.end.getDay();

    
    
    hasConflict = false;
    roomSchedule[dayStart].forEach(daySchedule => {
        const dayScheduleStart = new Date(dateStart + " " + daySchedule.time_start);
        const dayScheduleEnd = new Date(dateStart + " " + daySchedule.time_end);

        if (dayScheduleStart < schedule.end && dayScheduleEnd > schedule.start) {
            const sheduleDetailContainer = document.createElement("details");
            const containerTitle = document.createElement("summary");

            const instructor = document.createElement("p");
            instructor.innerText = `Instructor: ${daySchedule.faculty}`;

            const subject = document.createElement("p");
            subject.innerText = `Subject: ${daySchedule.subject}`;

            const timeStart = document.createElement("p");
            timeStart.innerText = `Time Start: ${convertTo12Hour(daySchedule.time_start)}`;

            const timeEnd = document.createElement("p");
            timeEnd.innerText = `Time End: ${convertTo12Hour(daySchedule.time_end)}`;

            containerTitle.innerText = `Conflict with ${daySchedule.subject} on ${dateEnd}`;
            
            sheduleDetailContainer.appendChild(containerTitle);
            sheduleDetailContainer.appendChild(instructor);
            sheduleDetailContainer.appendChild(subject);
            sheduleDetailContainer.appendChild(timeStart);
            sheduleDetailContainer.appendChild(timeEnd);

            conflictContainer.appendChild(sheduleDetailContainer);
            hasConflict = true;
        }

    });

    if (dayStart !== dayEnd) {
        roomSchedule[dayEnd].forEach(daySchedule => {
            const dayScheduleStart = new Date(dateEnd + " " + daySchedule.time_start);
            const dayScheduleEnd = new Date(dateEnd + " " + daySchedule.time_end);

            if (dayScheduleStart < schedule.end && dayScheduleEnd > schedule.start) {
                const sheduleDetailContainer = document.createElement("details");
                const containerTitle = document.createElement("summary");

                containerTitle.innerText = `Conflict with ${daySchedule.room_name} on ${dateEnd}`;

                const instructor = document.createElement("p");
                instructor.innerText = `Instructor: ${daySchedule.faculty}`;

                const subject = document.createElement("p");
                subject.innerText = `Subject: ${daySchedule.subject}`;

                const timeStart = document.createElement("p");
                timeStart.innerText = `Time Start: ${convertTo12Hour(daySchedule.time_start)}`;

                const timeEnd = document.createElement("p");
                timeEnd.innerText = `Time End: ${convertTo12Hour(daySchedule.time_end)}`;
                
                sheduleDetailContainer.appendChild(containerTitle);
                sheduleDetailContainer.appendChild(instructor);
                sheduleDetailContainer.appendChild(subject);
                sheduleDetailContainer.appendChild(timeStart);
                sheduleDetailContainer.appendChild(timeEnd);

                conflictContainer.appendChild(sheduleDetailContainer);
                hasConflict = true;
            }
        });
    }

    approvedReservationSchedule.forEach(resSchedule => {
        if (resSchedule.start < schedule.end && resSchedule.end > schedule.start) {
            const roomDetailsLink = `reservation_details.php?res_id=${resSchedule.resId}&role=${resSchedule.role}`;
            outputPrompt.innerHTML += `<br>- Conflict with approved reservation by ${resSchedule.requestee}: <a href="${roomDetailsLink}">View Details</a>`;
            hasConflict = true;
        }
    });

    pendingReservationSchedule.forEach(resSchedule => {
        if (resSchedule.start < schedule.end && resSchedule.end > schedule.start) {
            const roomDetailsLink = `reservation_details.php?res_id=${resSchedule.resId}&role=${resSchedule.role}`;
            outputPrompt.innerHTML += `<br>- Warning Conflict with pending reservation by ${resSchedule.requestee}: <a href="${roomDetailsLink}">View Details</a>`;
        }
    });

    if (!hasConflict) {
        outputPrompt.innerHTML = "No Conflict Found";
    }
    else {
        outputPrompt.innerHTML = "Conflict Detected Error";
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

function convertTo12Hour(timeStr) {
    let [hour, minute, second] = timeStr.split(":").map(Number);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12;
    return `${hour}:${minute.toString().padStart(2, '0')} ${ampm}`;
}

function convertToMonthDayYear(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}