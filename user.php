<?php
include 'template/header.php';
?>
    <div id="calendar">
    </div>
<?php
$scripts = array(
    'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js',
    'https://code.jquery.com/jquery-3.2.1.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js',
    '../js/calendar.js'
);
include 'template/footer.php';
?>