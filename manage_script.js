function hideOtherUser(){
    document.querySelectorAll("#manage-body > div").forEach(user => {
        user.classList.add('hidden');
    });
}

function switchToStudent(){
    hideOtherUser();
    document.getElementById('manage-student-container').classList.remove('hidden');
}

function switchToFaculty(){
    hideOtherUser();
    document.getElementById('manage-faculty-container').classList.remove('hidden');
}

function switchToAdmin(){
    hideOtherUser();
    document.getElementById('manage-admin-container').classList.remove('hidden');
}

function filterStudents(){
    const selectedDegreeProgram = new Set();
    const selectedYearLevel = new Set();
    const selectedCollege = new Set();

    document.querySelectorAll(".degree-option:checked").forEach(checkbox => {
        selectedDegreeProgram.add(checkbox.value);
    });

    document.querySelectorAll(".year-level-option:checked").forEach(checkbox => {
        selectedYearLevel.add(checkbox.value);
    });

    document.querySelectorAll(".college-option:checked").forEach(checkbox => {
        selectedCollege.add(checkbox.value);
    });

    let studentFound = false;
    document.querySelectorAll('#manage-student-container .manage-body').forEach(student => {   

        const degree = student.querySelector('.profile-degree-program').textContent;
        const year = parseInt(student.querySelector('.profile-year-level').textContent, 10);
        const college = student.querySelector('.profile-college').textContent;

        const degreeCheck = selectedDegreeProgram.has(degree) || selectedDegreeProgram.size === 0;
        const yearCheck = selectedYearLevel.has(year) || selectedYearLevel.size === 0;
        const collegeCheck = selectedCollege.has(college) || selectedCollege.size === 0;

        const shouldShow = degreeCheck && yearCheck && collegeCheck;

        student.classList.toggle("hidden", !shouldShow);

        if (shouldShow) studentFound = true;
    });

    document.querySelector('.no-user-found').classList.toggle("hidden", studentFound);
}

function filterFaculty(){
    const selectedDivision = new Set();

    document.querySelectorAll(".division:checked").forEach(checkbox => {
        selectedDegreeProgram.add(checkbox.value);
    });

    let facultyFound = false;
    document.querySelectorAll('#manage-faculty-container .manage-body').forEach(faculty => {   

        const division = student.querySelector('.profile-division').textContent;

        const divisionCheck = selectedDivision.has(division) || selectedDivision.size === 0;

        const shouldShow = divisionCheck;

        faculty.classList.toggle("hidden", !shouldShow);

        if (shouldShow) facultyFound = true;
    });

    document.querySelector('.no-user-found').classList.toggle("hidden", facultyFound);
}

function filterFaculty(){
    const selectedDesignation = new Set();

    document.querySelectorAll(".designation:checked").forEach(checkbox => {
        selectedDegreeProgram.add(checkbox.value);
    });

    let adminFound = false;
    document.querySelectorAll('#manage-admin-container .manage-body').forEach(admin => {   

        const designation = student.querySelector('.profile-designation').textContent;

        const designationCheck = selectedDivision.has(designation) || selectedDesignation.size === 0;

        const shouldShow = divisionCheck;

        admin.classList.toggle("hidden", !shouldShow);

        if (shouldShow) adminFound = true;
    });

    document.querySelector('.no-user-found').classList.toggle("hidden", adminFound);
}