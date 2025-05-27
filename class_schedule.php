<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require "db_connector.php";

$stmt = $conn->prepare("SELECT * FROM academic_schedule WHERE room_name = ?");
$stmt->bind_param("s", $roomName);
$stmt->execute();
$scheduleResult = $stmt->get_result();

$conn -> close();

$schedEachDay = [];
for ($i = 0; $i < 7; $i++){
    $schedEachDay[] = [];
}

$dayIndex = ["Monday" => 0, "Tuesday" => 1, "Wednesday" => 2, "Thursday" => 3, "Friday" => 4, "Saturday" => 5, "Sunday" => 6];

if ($scheduleResult -> num_rows > 0){
    while ($schedule = $scheduleResult -> fetch_assoc()){
        $subject = $schedule['subject'];
        $day = $schedule['day'];
        $timeStart = $schedule['time_start'];
        $timeEnd = $schedule['time_end'];

        list($hourStart, $minuteStart, $_) = explode(":", $timeStart);
        list($hourEnd, $minuteEnd, $_) = explode(":", $timeEnd);

        $intervalStart = DateTime::createFromFormat('H:i', "$hourStart:$minuteStart");
        $intervalEnd = DateTime::createFromFormat('H:i', "$hourEnd:$minuteEnd");

        $interval = $intervalStart->diff($intervalEnd);
        $minuteDuration = ($interval->h * 60) + $interval->i;
        $rowsOccupied = (int)($minuteDuration / 15);

        $schedEachDay[$dayIndex[$day]][] = ["subject" => $subject, "hour-start" => $hourStart, "minute-start" => $minuteStart, "rows-occupied" => $rowsOccupied];
    }
}

$numRow = 14 * 4;
$rowInterval = 15;

$rowTimeRest = [];
for ($i = 0; $i < 7; $i++){
    $rowTimeRest[] = 0;
}

for ($timePassed = 0; $timePassed < $numRow*$rowInterval; $timePassed += $rowInterval){
    $hour = 6 + (int)($timePassed / 60);
    $minute = $timePassed % 60;

    echo "<tr>";

    if ($minute == 0){
        $hourCur = $hour - ($hour > 12 ? 12 : 0);


        $hourNext = $hour + 1 - ($hour > 11 ? 12 : 0);
        echo "<td class='has-content' rowspan='4'>$hourCur:00-$hourNext:00</td>";
    }

    for($day = 0; $day < 7; $day++){
        $daySched = $schedEachDay[$day];

        if ($rowTimeRest[$day] > 0){
            $rowTimeRest[$day]--;
            continue;
        }

        $toEcho = "<td></td>";
        for ($i = 0; $i < count($daySched); $i++) { 
            if ($daySched[$i]["hour-start"] == $hour && $daySched[$i]["minute-start"] == $minute){
                $toEcho = "<td class='has-content' rowspan='".$daySched[$i]["rows-occupied"]."'>".$daySched[$i]["subject"]."</td>";
                $rowTimeRest[$day] = $daySched[$i]["rows-occupied"] - 1;
                break;
            }
        }

        echo $toEcho;
    }

    echo "</tr>";
}

?>