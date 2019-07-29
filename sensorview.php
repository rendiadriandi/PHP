<?php

//restrictedpage.php :Init session
session_start();

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: index.php');
}

?>
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: center;
}
.btn {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.btn:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}
</style>
</head>
<script>
    function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}
</script>
<center>
<a href=admin/index.php class="btn">Admin Panel</a>
<a href=logout.php class="btn">Keluar</a>
<br/><br/>
<?php
$conn = new mysqli("localhost", "root", "", "u3nv1r0r_ewsi");
$sqldate = "select max(status_date) from sensor_status_live";
$resdate = $conn->query($sqldate);
$rowdate = $resdate->fetch_array();
print "<img src=/ewsi/images/logo.png></img>";
print "<h2>PT. EAST WEST SEED INDONESIA</h2>";
print "<h3>Monitoring Status Date: ".$rowdate[0]."</h3><br>";
$sql="select a.*,(select sensor_desc from sensor_lists where sensor_id=a.sensor_id) sensor_desc, (select round(a.temperature+temp_calibrate,2) from sensor_calibration where sensor_id=a.sensor_id) temp_calibrated,(select round(a.humidity+humi_calibrate,2) from sensor_calibration where sensor_id=a.sensor_id) humi_calibrated from sensor_status_history a,
    (SELECT sensor_id, max(status_date) status_date FROM sensor_status_history where 
    humidity is not null and humidity between 0 and 100 and 
    light <> 'unknown' 
    group by sensor_id) b where a.sensor_id=b.sensor_id and a.status_date=b.status_date order by 1";
$result = $conn->query($sql);
?>
<table style="width:80%">
<tr>
<?php
while ($row = $result->fetch_array()) {
if ($row[0] == 'S06' || $row[0] == 'S11')
{
    print "</tr></table>";
    print "<br><br>";
    print "<table style=\"width:80%\">";
    print "<tr>";
}
print "<td width=20%><br><bold>";
print $row[0]." - ".$row[7]."</bold><br><br>";
print "<a href=\"#\" onclick=\"PopupCenter('http://ewsi.envirora.com/chart/?idsensor=".$row[0]."','".$row[7]."','900','500'); \"><img src=/images/icon_temperature.png height=20 width=20>Temperature</img></a><br>".$row[8]."<sup>O</sup>C<br><br>";
print "<a href=\"#\" onclick=\"PopupCenter('http://ewsi.envirora.com/chart/humi.php?idsensor=".$row[0]."','".$row[7]."','900','500'); \"><img src=/images/icon_humidity.png height=20 width=20>Humidity</img></a><br>".$row[9]."%<br><br>";
print "<img src=/images/icon_light.png height=20 width=20>Light</img><br>".$row[2]."<br><br>";
print "Best Status Date<br>".$row[6]."<br><br>";
print "</td>";
}
?> 
</tr></table>
<br><br>
