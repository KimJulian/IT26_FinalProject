<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - HealthFile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background-color: #f4f4f4; }
        .sidebar { width: 220px; background-color: #9A6F77; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { font-size: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 10px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
        
        .container { margin-left: 260px; padding: 40px; width: 100%; }
        .info-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #9A6F77; }
        h3 { color: #7d5a61; border-left: 5px solid #9A6F77; padding-left: 10px; }
        p { line-height: 1.6; color: #444; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HealthFile</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="about.php" style="background: rgba(255,255,255,0.2); padding-left: 10px; border-radius: 5px;">ℹ️ About Us</a></li>
        <li><a href="#">📅 Appointments</a></li>
        <li><a href="#">💊 Available Meds</a></li>
    </ul>
</div>

<div class="container">
    <div class="info-card">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum et semper mauris. Aenean dictum, nisi nec porttitor maximus, nunc eros fermentum erat, ac gravida nisl magna at sem. Duis ornare porttitor scelerisque. Donec sapien tellus, facilisis non scelerisque id, ultricies sit amet tortor. Nam id mattis libero, ac malesuada tortor. Praesent nec sagittis massa. Nullam nec metus ipsum. Nam nec pretium velit.

        Aenean vehicula fringilla eros, vitae auctor erat molestie sit amet. Ut velit ante, aliquet ut malesuada in, maximus at magna. Curabitur vulputate consequat auctor. Pellentesque venenatis quam nisi, a interdum sapien mollis sed. Curabitur quis interdum purus. Nunc sit amet ex a dui ultricies aliquet eu a velit. Etiam viverra, dolor auctor ullamcorper dictum, nisl velit tempus quam, quis mattis leo leo eget odio. Aliquam placerat vulputate leo a accumsan. Donec diam diam, efficitur vitae massa in, ullamcorper mattis risus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Praesent eu aliquam enim. Mauris eu magna ante.

        Morbi mattis cursus tortor at cursus. Mauris ac ligula neque. Aliquam enim nulla, molestie nec malesuada ac, posuere non elit. Nullam finibus purus et varius rutrum. Sed vel velit ut dolor condimentum ultrices nec ut nisi. Morbi luctus eros ipsum. Nam aliquet nisi tortor, non dictum nibh ullamcorper at. Aenean rutrum cursus ipsum sit amet vulputate. Morbi neque sapien, dapibus at scelerisque eget, finibus at nisi. Integer vulputate erat dui, at mollis arcu tempus quis. Pellentesque volutpat ipsum eget dolor molestie facilisis. Vestibulum orci lacus, iaculis a bibendum id, viverra eu nibh. Sed bibendum odio sed sodales viverra. Sed egestas pharetra molestie. Maecenas velit nulla, imperdiet nec ante id, sodales tempor nisi.

        Aliquam erat volutpat. Donec rhoncus pellentesque congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pellentesque magna nec eros sagittis, sit amet interdum diam vulputate. Donec vitae felis ultrices, elementum ante feugiat, lacinia mi. Phasellus sed nibh ac tortor tempus malesuada at quis diam. Praesent sed nunc luctus, sagittis urna vitae, vulputate erat. Vivamus ultrices mauris ut arcu faucibus ullamcorper. Vivamus lacinia pellentesque mauris ut vulputate. Donec bibendum sapien nibh, sed rhoncus augue lobortis vitae. Sed eu tristique ex. Vestibulum lacinia ipsum ac sapien placerat, dapibus venenatis nulla placerat.

        Etiam sit amet lorem ac enim interdum ornare ut eget nibh. Sed malesuada tortor id semper ullamcorper. Morbi mattis augue at venenatis bibendum. Donec ante eros, rhoncus vel tempus vitae, ullamcorper quis leo. In eleifend dui sed eros egestas varius. Proin felis lectus, venenatis at pharetra non, scelerisque nec massa. Nulla fermentum sit amet ligula vel commodo. Quisque ac felis sagittis, iaculis metus quis, bibendum mi. In finibus mollis lacus ac elementum. Suspendisse rhoncus sodales facilisis. Ut aliquam risus sed est eleifend interdum. Cras pharetra ipsum vitae tortor mattis pretium. Etiam finibus ligula a tellus ornare, id finibus neque facilisis. Donec et nibh eu nibh malesuada dictum. Vivamus neque lacus, tempor sit amet ex id, accumsan feugiat mauris.
    </div>
</div>

</body>
</html>