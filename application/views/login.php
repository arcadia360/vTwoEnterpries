<!DOCTYPE html>
<html lang="en" class="Gibbu_@hotmail.com">

<head>
    <meta charset="UTF-8">
    <title>Sign In - vTwoEnterpries Business Management System</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.1/js/all.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.transit/0.9.12/jquery.transit.js" integrity="sha256-mkdmXjMvBcpAyyFNCVdbwg4v+ycJho65QLDwVE3ViDs=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

    <style>
        /* @import url("https://fonts.googleapis.com/css?family=Montserrat:400,600,700|Work+Sans:300,400,700,900"); */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        * {
            outline-width: 0;
            /* font-family: 'Montserrat' !important; */
            font-family: 'Montserrat', sans-serif;
        }

        body {
            /* background: #343a40; */

            margin: 0;
            padding: 0;
            /* height: 100%; */
            background-color: #ffffff;
            /* background-image:linear-gradient(#434343, #282828); */

            /* 
    background-image: linear-gradient(0deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent);
    height:100%;
    background-size:50px 50px; */
        }

        input:focus,
        select:focus,
        textarea:focus,
        button:focus {
            outline: none;
        }

        #container {
            height: 100vh;
            background-size: cover !important;
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            align-items: center;
        }

        #inviteContainer {
            display: -webkit-box;
            display: flex;
            overflow: hidden;
            position: relative;
            /* border-radius: 100px; */
            /* box-shadow: 10px 10px 5px grey; */
        }

        #inviteContainer .acceptContainer {
            padding: 45px 30px;
            box-sizing: border-box;
            width: 400px;
            margin-left: -400px;
            overflow: hidden;
            height: 0;
            opacity: 0;
        }

        #inviteContainer .acceptContainer.loadIn {
            opacity: 1;
            margin-left: 0;
            -webkit-transition: 0.5s ease;
            transition: 0.5s ease;
        }

        #inviteContainer .acceptContainer:before {
            content: "";
            background-size: cover !important;
            box-shadow: inset 0 0 0 3000px rgba(40, 43, 48, 1);
            /* background-color: rgba(40, 43, 48, 0.75);; */
            /* -webkit-filter: blur(10px);
          filter: blur(10px); */
            position: absolute;
            width: 150%;
            height: 150%;
            top: -50px;
            left: -50px;
        }

        form {
            position: relative;
            text-align: center;
            height: 100%;
        }

        form h1 {
            margin: 0 0 15px 0;
            /* font-family: 'Work Sans' !important; */
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.4em;
            color: #fff;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            opacity: 0;
            left: -30px;
            position: relative;
            -webkit-transition: 0.5s ease;
            transition: 0.5s ease;
        }

        form h1.loadIn {
            left: 0;
            opacity: 1;
        }

        .formContainer {
            text-align: left;
        }

        .formContainer .formDiv {
            margin-bottom: 30px;
            left: -25px;
            opacity: 0;
            -webkit-transition: 0.5s ease;
            transition: 0.5s ease;
            position: relative;
        }

        .formContainer .formDiv.loadIn {
            opacity: 1;
            left: 0;
        }

        .formContainer .formDiv:last-child {
            padding-top: 10px;
            margin-bottom: 0;
        }

        .formContainer p {
            margin: 0;
            font-weight: 700;
            color: #aaa;
            font-size: 0.7em;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .formContainer input[type=password],
        .formContainer input[type=text] {
            background: transparent;
            border: none;
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.15);
            padding: 15px 0;
            box-sizing: border-box;
            color: #fff;
            width: 100%;
        }

        .formContainer input[type=password]:focus,
        .formContainer input[type=text]:focus {
            box-shadow: inset 0 -2px 0 #fff;
        }

        .logoContainer {
            padding: 45px 35px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
            position: relative;
            overflow: hidden;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            flex-direction: column;
            -webkit-transform: scale(0, 0);
            transform: scale(0, 0);
        }

        .logoContainer img,
        .logoContainer p {
            /* width: 150px; */
            margin-bottom: -5px;
            display: block;
            position: relative;
        }

        .logoContainer a {
            color: #0172e2;
            position: relative;
        }

        .logoContainer img:first-child {
            width: 150px;
        }

        .logoContainer .text {
            padding: 25px 0 10px 0;
            margin-top: -70px;
            opacity: 0;
            font-weight: 700;
            color: #FFFFFF;
        }

        .logoContainer .text.loadIn {
            margin-top: 0;
            opacity: 1;
            -webkit-transition: 0.8s ease;
            transition: 0.8s ease;
        }

        .logoContainer .web {
            color: #FFFFFF;
            position: absolute;
            opacity: 0;
            font-size: 0.7em;
            /* font-weight: 700; */
            text-decoration: none;
        }

        .logoContainer .web.loadIn {
            /* position: absolute; */
            bottom: 20px;
            opacity: 1;
            -webkit-transition: 0.8s ease;
            transition: 0.8s ease;
        }

        .logoContainer .logo {
            position: relative;
            top: -100px;
            opacity: 0;
            color: #FFFFFF;
            font-size: 4em;
            font-weight: 700;
        }

        .logoContainer .logo.loadIn {
            top: -20px;
            opacity: 1;
            -webkit-transition: 0.8s ease;
            transition: 0.8s ease;
        }

        .logoContainer:before {
            content: "";
            background-size: cover !important;
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150%;
            height: 150%;
            -webkit-filter: blur(10px);
            filter: blur(10px);
            /* box-shadow: inset 0 0 0 3000px rgba(107, 15, 15, 0.8); */

        }

        .forgotPas {
            color: #707070;
            opacity: .8;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.7em;
            margin-top: 15px;
            display: block;
            -webkit-transition: 0.2s ease;
            transition: 0.2s ease;
        }

        .forgotPas:hover {
            opacity: 1;
            color: #fff;
        }

        .acceptBtn {
            width: 100%;
            box-sizing: border-box;
            background: #D24A58;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 20px 0;
            /* border-radius: 3px; */
            cursor: pointer;
            -webkit-transition: 0.2s ease;
            transition: 0.2s ease;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .acceptBtn:hover {
            background: #B74451;
        }

        .register {
            color: #aaa;
            font-size: 12px;
            padding-top: 15px;
            display: block;
        }

        .register a {
            color: #fff;
            text-decoration: none;
            margin-left: 5px;
            box-shadow: inset 0 -2px 0 transparent;
            padding-bottom: 5px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .register a:hover {
            box-shadow: inset 0 -2px 0 #fff;
        }




        /* ssaasa */

        .logoContainer {

            background-repeat: no-repeat;
            background-size: cover;
            /* background: linear-gradient(120deg, rgb(52, 58, 64, 0.9), rgba(0, 0, 0, 0.9)), url(https://www.setaswall.com/wp-content/uploads/2019/08/Whatsapp-Wallpaper-111.jpg); */

            background: linear-gradient(0deg, rgb(52, 58, 64, 1), rgba(0, 0, 0, 1));
            background-image: url(https://www.setaswall.com/wp-content/uploads/2019/08/Whatsapp-Wallpaper-111.jpg);

            /* background-color: #bad0b8;
            background-image: -webkit-linear-gradient(bottom, #BF6D6F, #DA7B81);
            background-image: -moz-linear-gradient(right, #3E2229, #C43C52);
            background-image: -o-linear-gradient(right, #3E2229, #C43C52);
            background-image: -ms-linear-gradient(right, #3E2229, #C43C52);
            background-image: linear-gradient(right, #3E2229, #C43C52); */
        }

        h1 {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            filter: hue-rotate(0deg);
            /* background:linear-gradient(45deg,#0f8,#08f) !important; */
            background: linear-gradient(45deg, #0f8, #08f);
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            animation: hue 10000ms infinite linear;

        }

        @keyframes spinify {
            0% {
                transform: translate(0px, 0px);

            }

            33% {
                transform: translate(0px, 24px);
                border-radius: 100%;
                width: 10px;
                height: 10px;

            }

            66% {
                transform: translate(0px, -16px);
            }

            88% {
                transform: translate(0px, 4px);

            }

            100% {
                transform: translate(0px, 0px);
            }
        }

        @keyframes hue {
            0% {
                filter: hue-rotate(0deg);
            }

            100% {
                filter: hue-rotate(360deg);
            }
        }



        .Glow {
            position: relative;
            text-align: center;
            /* color: #252B37; */
            /* animation: animateGlow 10s ease infinite; */
            transition-delay: 1.25s;
            -webkit-transition-delay: 1.25s;
        }

        .Glow:after {
            transition-delay: 1.25s;
            -webkit-transition-delay: 1.25s;
        }

        .Glow.loadIn {
            transition-delay: 1.25s;
            -webkit-transition-delay: 1.25s;

        }

        .Glow.loadIn:before {
            content: "";
            background-size: cover !important;
            position: absolute;
            top: 30px;
            left: 5%;
            width: 90%;
            height: 100%;
            -webkit-filter: blur(30px);
            filter: blur(30px);
            background: linear-gradient(180deg, #8f8f8f, #333135);
            -webkit-animation-duration: 2s;
            -webkit-animation-name: fadeIn;
        }


        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes animateGlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes textColor {
            0% {
                color: #333135;
            }

            50% {
                color: #8f8f8f;
            }

            100% {
                color: #333135;
            }
        }
    </style>
</head>

<body>

    <div id="container">

        <div class="Glow">
            <div id="inviteContainer">
                <div class="logoContainer">
                    <p class="logo loader">VTwo<br>Enterprises</p>
                    <p class="text">
                        BUSINESS MANAGEMENT SYSTEM <br>
                    </p>
                    <a href="#" target="new" class="web">www.arcadia360.lk</a>
                </div>
                <div class="acceptContainer">
                    <form action="<?= base_url('auth/login') ?>" method="post">
                        <h1>W E L C O M E</h1>
                        <div class="formContainer">
                            <div class="formDiv" style="transition-delay: 0.2s">
                                <p>USER NAME</p>
                                <input type="text" name="username" id="username" required>
                            </div>
                            <div class="formDiv" style="transition-delay: 0.4s">
                                <p>PASSWSORD</p>
                                <input type="password" name="password" id="password" required>
                                <a class="forgotPas" href="#">FORGOT YOUR PASSWORD?</a>
                            </div>
                            <div class="formDiv" style="transition-delay: 0.6s">
                                <button class="acceptBtn" type="submit">SIGN IN</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>

<script>
    // JQUERY
    $(function() {

        // 	var images = ['https://wallpapersite.com/images/pages/pic_w/1063.jpg', 'http://www.hdwallpaper.nu/wp-content/uploads/2017/04/PLAYERUNKNOWNS-BATTLEGROUNDS-12937710.jpg', 'https://www.hdwallpapers.in/walls/overwatch_4k-HD.jpg', 'https://images.alphacoders.com/186/186993.jpg', 'https://images4.alphacoders.com/602/thumb-1920-602788.png'];

        //    $('#container').append('<style>#container, .acceptContainer:before, #logoContainer:before {background: url(' + images[Math.floor(Math.random() * images.length)] + ') center fixed }');


        setTimeout(function() {
            $('.logoContainer').transition({
                scale: 1
            }, 700, 'ease');
            setTimeout(function() {
                $('.logoContainer .logo').addClass('loadIn');
                setTimeout(function() {
                    $('.logoContainer .text').addClass('loadIn');
                    setTimeout(function() {
                        $('.logoContainer .web').addClass('loadIn');
                        setTimeout(function() {
                            $('.acceptContainer').transition({
                                height: '431.5px'
                            });
                            setTimeout(function() {
                                $('.acceptContainer').addClass('loadIn');
                                setTimeout(function() {
                                    $('.formDiv, form h1').addClass('loadIn');
                                    setTimeout(function() {
                                        $('.formDiv, form h1').addClass('loadIn');
                                        setTimeout(function() {
                                            $('.Glow').addClass('loadIn');
                                        }, 500)
                                    }, 500)
                                }, 500)
                            }, 500)
                        }, 500)
                    }, 500);
                }, 500)
            }, 1000)
        }, 10)

    });
</script>

</html>