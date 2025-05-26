const textarea = document.getElementById("purpose");
const charCount = document.getElementById("charCount");
const facultyNames = [];
const isFacultyAvailable = [];
const roomSchedule = [];

document.addEventListener("DOMContentLoaded", initializePage);



function initializePage() {
    getFacultyNames();
    addAutocompleteFeature();
    getRoomSchedule();
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
        roomSched.forEach(schedule => {
            roomSchedule.push(schedule);
        });
    });
}
