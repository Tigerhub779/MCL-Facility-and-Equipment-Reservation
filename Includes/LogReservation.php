<?php
include_once('ConnectDB.php');
    $sql="SELECT reserve.*, facility.facility, facility.room_no FROM reservation_information AS reserve
            INNER JOIN reserve_facility AS facility
            ON  reserve.ref_no=facility.ref_no
    WHERE reserve.reserve_type='Facility' OR reserve.reserve_type='Both'
    ORDER BY DATE(reserve.date_start) ASC
    ";

    $result =mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){
        $count=1;
        while($row = mysqli_fetch_assoc($result)){
            $dateStart =date_format(date_create($row['date_start']),'M d, Y');
            $dateEnd =date_format(date_create($row['date_end']),'M d, Y');
            $timeStart =date_format(date_create($row['time_start']),'h:i a');
            $timeEnd =date_format(date_create($row['time_end']),'h:i a');

            $room="None";
            if($row['room_no']!=Null)
                $room=$row['room_no'];
            $template="<tr><th scope='row'>$count</th>
            <td>{$row['facility']}</td>
            <td>$room</td>
            <td>$dateStart </td>
            <td>$dateEnd </td>
            <td>$timeStart</td>
            <td>$timeEnd</td>
            </tr>";

            echo $template;
            $count++;
        }
    }
    else{
        echo '<tr><div class="jumbotron">
        <h1 class="display-4">0 Facility Reservation</h1>
        <p class="lead">There are zero correspoing reservation regarding the facilities. </p>
    </div></tr>';
    }
?>