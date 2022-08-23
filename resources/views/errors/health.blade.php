<!DOCTYPE html>
<html lang="zxx" class="js">

@php 

$title = "Installation Error";
$heading = "Application is not installed properly";
$message = "We are very sorry for inconvenience. Unfortunately we cannot figure out the issues. You may contact us our support team.";
$recheck =  true;

$err = [
	'checkDatabaseConnection' => "Sorry, we are unable to connect with database. Please check your database connection and try again.",
	'checkFilePermissions' => "Seems like you have not set folder permission as per installation guideline. Please check folder permission and try again.",
	'checkAllMandatoryTableExists' => "Seems like you have connected with database. But we have not found necessary database tables for the application. You should import the correct database and try again.",
];

if (session()->has('installation_error')) {
    $errpull = session()->pull('installation_error');
    if (isset($err[$errpull])) {
	   $message = $err[$errpull];
    }
}

if (isset($db_error) && $db_error===true) {
    $title = "Internal System Error";
    $heading = "Sorry, something went wrong!";
    $message = "We're sorry for inconvenience. Please try again later or feel free to contact us if the problem persists.";
    $recheck = false;
}

@endphp 

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/apps.css?ver=130') }}">
</head>


<body class="nk-body npc-cryptlite">
<div class="nk-app-root">
    <div class="nk-wrap ">
        <div class="nk-block nk-block-middle wide-xs mx-auto">
            <div class="nk-block-content nk-error-ld text-center">
                <div class="nk-error-icon text-center"><em class="icon ni ni-alert-c"></em></div>
                <div class="wide-xs mx-auto">
                    <h4 class="nk-error-title fw-medium">{{ $heading }}</h4>
                    @if($message)
                    <p class="nk-error-text">{{ $message }}</p>
                    @endif
                    <p class="mt-5"><a href="{{ url('/') }}" class="btn btn-primary">{{ ($recheck) ? __("Check again") : __("Back to Home") }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>