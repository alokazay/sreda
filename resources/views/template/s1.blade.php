<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans;
        }

        .certificate {
            border: #ff4800 6px solid;
            height: 450px;
            position: relative;
        }

        .certificate__border {
            border: #ff4800 2px solid;
            margin: 6px;
            height: 414px;
            padding: 10px;
        }

        .course_title {
            text-transform: uppercase;
            font-size: 33px;
            font-weight: 700;
            margin-top: 40px;
            text-align: center;
        }

        .course_user_name {
            font-style: italic;
            font-size: 55px;
            color: #ff4800;
            text-align: center;
        }

        .course_number {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 11px;
        }

        .qr {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

    </style>
</head>
<body>
<div class="certificate">
    <div class="certificate__border">
        <div id="header">
            <img src="https://sreda.biz/img/logo.png?315532801" class="logo" alt="">
        </div>
        <div id="container">
            <div class="course_title">{{$title}}</div>
            <div class="course_user_name">{{$user_name}}</div>
        </div>
        <div id="footer">

            <div class="course_number">№{{$number}} от {{$date_finished_course}}</div>

            <img src="https://secure.co.ua/sreda/public/qr/{{$id}}.jpg" class="qr" alt="">
        </div>


    </div>
</div>

</body>
</html>
