<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>PEERHOOD</title>
</head>

<body>
    <div class="block ml-auto mr-auto md:w-72 lg:w-80 w-64">
        <h2 class="font-medium text-center text-2xl my-10">Leaderboard</h2>

        <div class="flex items-end justify-center space-x-3">
            <div class="text-center">
                <p class="mb-1">Team 6</p>
                <p class="w-14 md:w-20 h-20 pt-4 text-3xl font-bold text-white rounded-tl-xl rounded-tr-xl bg-green-400">2</p>
            </div>

            <div class="text-center">
                <p class="mb-1">Wombats</p>
                <p class="w-14 md:w-20 h-24 pt-4 text-3xl font-bold text-white rounded-tl-xl rounded-tr-xl bg-green-500">1</p>
            </div>

            <div class="text-center">
                <p class="mb-1">Team 2</p>
                <p class="w-14 md:w-20 h-16 pt-4 text-3xl font-bold text-white rounded-tl-xl rounded-tr-xl bg-green-400">3</p>
            </div>
        </div>

        <div class="py-5 bg-green-400 text-white">
            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="w-10 pl-5 md:pl-0">4</p>
                    <p class="w-40">The Winners</p>
                    <p class="w-20 font-bold">413</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">5</p>
                    <p class="w-40">Quizzers</p>
                    <p class="w-20 font-bold">386</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">6</p>
                    <p class="w-40">Laura & Jan</p>
                    <p class="w-20 font-bold">374</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">7</p>
                    <p class="w-40">The Boyz</p>
                    <p class="w-20 font-bold">370</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">8</p>
                    <p class="w-40">The A Team</p>
                    <p class="w-20 font-bold">355</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">9</p>
                    <p class="w-40">The Champions</p>
                    <p class="w-20 font-bold">324</p>
                </div>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center">
                <div class="flex justify-center w-64 mb-5 space-x-12">
                    <p class="pl-5 md:pl-0 w-10">10</p>
                    <p class="w-40">FC De Kampioen</p>
                    <p class="w-20 font-bold">298</p>
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>