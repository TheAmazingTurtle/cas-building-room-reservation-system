function filterRoomOptions(){
    const selectedRoomType = new Set();
    const selectedRoomFloor = new Set();
    const headCountInput = document.querySelector(".capacity-input").value;

    const isHeadCountEmpty = (headCountInput === "" || isNaN(headCountInput)) 

    document.querySelectorAll(".room-type-option:checked").forEach(checkbox => {
        selectedRoomType.add(checkbox.value);
        
    });


    document.querySelectorAll(".room-floor-option:checked").forEach(checkbox => {
        selectedRoomFloor.add(checkbox.value);
    });

    let roomAvailable = false;
    document.querySelectorAll('.room-option').forEach(roomOption => {   

        roomType = roomOption.querySelector('.room-type').textContent;
        roomFloor = roomOption.querySelector('.room-floor').textContent;
        roomCapacity = parseInt(roomOption.querySelector('.room-capacity').textContent, 10);

        const roomTypeCheck = selectedRoomType.has(roomType) || selectedRoomType.size === 0;
        const roomFloorCheck = selectedRoomFloor.has(roomFloor) || selectedRoomFloor.size === 0;
        const roomCapacityCheck = isHeadCountEmpty || roomCapacity >= headCountInput;

        const shouldShow = roomTypeCheck && roomFloorCheck && roomCapacityCheck;

        roomOption.classList.toggle("hidden", !shouldShow);

        if (shouldShow) roomAvailable = true;
    });

    document.querySelector('.no-room-option').classList.toggle("hidden", roomAvailable);
}