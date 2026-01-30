<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loan Management System</title>
        <link rel="icon" type="image/x-icon" href="include/favicon.ico">
        <link rel="stylesheet" href="include/css/bootstrap.min.css">
        <link rel="stylesheet" href="include/css/style.css">
        <link rel="stylesheet" href="include/fontawesome/css/fontawesome-all.css" />
        <link rel="manifest" href="manifest.json">

        <style>
            /* Custom CSS for label and input alignment */
            .form-group {
                display: flex;
                flex-direction: column;
                margin-bottom: 20px; /* Add spacing between form groups */
                width: 100%;
            }

            .form-group label {
                font-size: 16px; /* Adjust the font size */
                transition: transform 0.2s ease, color 0.2s ease; /* Add animation */
            }

            .form-group input[type="text"],
            .form-group input[type="password"],
            .form-group input[type="text"]:focus,
            .form-group input[type="password"]:focus {
                border: none; /* Remove default border */
                border-bottom: 1px solid #ccc; /* Add bottom border */
                font-size: 16px; /* Adjust the font size */
                padding: 5px; /* Add padding to inputs */
                transition: border-color 0.2s ease; /* Add border animation */
            }

            /* Highlight label and input on focus */
            .form-group input[type="text"]:focus,
            .form-group input[type="password"]:focus {
                border: none;
            }

            .form-group label.focused {
                transform: translateY(0); /* Reset label position */
                font-size: 14px; /* Shrink label font size */
                color: #007bff; /* Change label color on focus */
            }

            label {
                margin-bottom: 3px;
            }

            .password-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #toggleBtn {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-left: 5px;
                padding: 0;
                width: 35px;
            }

            #login-page, #staff-login-listing {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 100vw;
                height: 100vh;
            }

            #login-form {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            #content {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 100%;
                height: 100%;
            }

            #tab-container {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .tab {
                width: 100%;
            }

            .tab:focus{
                box-shadow: none;
            }

            #pending-container, #history-container {
                display: flex;
                flex-direction: column;
                justify-items: flex-start;
                align-items: center;
                width: 100%;
                height: 100%;
                overflow-y: auto;
            }
            
            #history-container {
                height: 80%;
            }

            #history-container::-webkit-scrollbar {
                width: 8px;               /* width of the entire scrollbar */
            }

            #history-container::-webkit-scrollbar-track {
                background: transparent;        /* color of the tracking area */
            }

            #history-container::-webkit-scrollbar-thumb {
                opacity: 0; /* Initially hide the scrollbar thumb */
                transition: opacity 0.3s ease; /* Add a transition effect for opacity */
            }

            #history-container:hover::-webkit-scrollbar-thumb {
                opacity: 1; /* Show the scrollbar thumb when hovered */
                background-color: rgba(211, 211, 211, 0.5);    /* color of the scroll thumb */
                border-radius: 20px;       /* roundness of the scroll thumb */
                border: 2px solid white;  /* creates padding around scroll thumb */
            }

            #date-container {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            #date {
                background-color: white;
            }

            .input-group-text {
                height: 100%;
                background-color: white;
            }

            #btn-reset {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                margin-left: 5px;
            }

            #spinner {
                margin: auto;
            }

            .card {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            }

            .card-body {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            #btn-logout {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>
    <body>
        <div id="login-page" style="display: none;">
            <div class="app-bar p-3 pl-3 bg-primary">
                <span id="login-page-title" class="app-bar-title">Loan Management System</span>
                <a id="install-btn" onclick="addToHomeScreen();">
                    <svg height="24" width="24" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            viewBox="0 0 512 512"  xml:space="preserve">
                        <g>
                            <path fill="#ffffff" d="M426.537,0H179.641c-22.243,0-40.376,18.175-40.376,40.401v129.603h25.221V69.106h277.206V424.46H164.485
                                v-83.887h-25.221v131.034c0,22.192,18.133,40.392,40.376,40.392h246.896c22.192,0,40.375-18.2,40.375-40.392v-129.03V40.401
                                C466.912,18.175,448.728,0,426.537,0z M303.08,478.495c-9.174,0-16.636-7.47-16.636-16.661c0-9.183,7.462-16.653,16.636-16.653
                                c9.158,0,16.686,7.47,16.686,16.653C319.766,471.025,312.247,478.495,303.08,478.495z"/>
                            <polygon fill="#ffffff" points="225.739,335.774 358.778,255.289 225.739,174.804 225.739,221.11 45.088,221.11 45.088,289.468 
                                225.739,289.468 	"/>
                        </g>
                    </svg>
                </a>
            </div>
            <form id="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="password-container">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control mr-3" id="password" name="password" required>
                    </div>
                    <span id="toggleBtn" class="btn text-primary" onclick="togglePasswordVisibility();">
                        <i id="eye-icon" class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" style="text-transform: uppercase;" required>
                </div>
                <button type="submit" class="btn btn-primary" id="btn-login" style="width: 100%;">Login</button>
            </form>
        </div>
        <div id="staff-login-listing" style="display: none;">
            <div class="app-bar p-3 pl-3 bg-primary">
                <span id="staff-login-listing-title" class="app-bar-title"></span>
                <a id="btn-logout" onclick="logout();">
                    <svg xmlns="http://www.w3.org/2000/svg" style="fill: white" width="24" height="24" viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                </a>
            </div>
            <div id="content" class="p-3">
                <div id="tab-container" class="btn-group mb-3">
                    <button id="btnPending" type="button" class="btn btn-primary tab" ontouchend="viewPending();" onmouseup="viewPending();">Pending</button>
                    <button id="btnHistory" type="button" class="btn btn-outline-primary tab" ontouchend="viewHistory();" onmouseup="viewHistory();">History</button>
                </div>
                <div id="date-container" class="mb-3" style="display: none;">
                    <label for="date">Date:&nbsp;&nbsp;</label>
                    <div class="input-group date" id="date-filter-picker" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#date-filter-picker" id="date" name="date" value="<?php echo date('d/m/Y'); ?>" readonly/>
                        <div class="input-group-append" data-target="#date-filter-picker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <div id="btn-reset" class="btn btn-primary" onclick="reset();">
                        <i class="fa fa-undo"></i>
                    </div>
                </div>
                <div id="pending-container" style="display: none;"></div>
                <div id="history-container" style="display: none;"></div>
                <div id="spinner" class="spinner-border text-primary" role="status" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        
        <div id="snackbar">Snackbar Message</div>

        <script src="include/js/jquery-2.1.3.min.js"></script>
        <script src="include/js/functions.js"></script>
        <script src="include/js/moment-with-locales.min.js"></script>
        <script src="include/js/tempusdominus-bootstrap-4.min.js"></script>

        <script>
            let deferredPrompt;     // Store the deferred prompt for later use
            let pendingTimer;
            let historyTimer;
            let isPendingTimerRunning = false;     // to control timer in async condition
            let isHistoryTimerRunning = false;

            let uid = getCookie('uid') ?? '';
            let branch = getCookie('branch') ?? '';

            $(document).ready(function() {
                // Add focused class to label when input is focused
                $('.form-group input[type="text"], .form-group input[type="password"]').on('focus', function() {
                    $(this).parent().find('label').addClass('focused');
                });
                $('.form-group input[type="text"], .form-group input[type="password"]').on('blur', function() {
                    if ($(this).val() === '') {
                        $(this).parent().find('label').removeClass('focused');
                    }
                });

                if (uid != '') {
                    goToStaffLoginListing();
                } else {
                    goToLogin();
                }

                // showSnackbar('uid: ' + uid);
            });

            // Check if the PWA is running in standalone mode (installed)
            if (window.matchMedia('(display-mode: standalone)').matches || checkDevicePlatform() == 'iOS') {
                $('#install-btn').hide(); // Hide the "Add to Home Screen" button
            }

            $(document).on('click', function() {
                // showSnackbar('Platform: ' + checkDevicePlatform());
                if ('Notification' in window) {
                    Notification.requestPermission();
                }
            });

            // Listen for the beforeinstallprompt event
            window.addEventListener('beforeinstallprompt', (event) => {
                // Prevent the browser's default install prompt
                event.preventDefault();

                // Store the event for later use
                deferredPrompt = event;

                // Show your custom "Add to Home Screen" button
                $('#install-btn').show();
            });

            // Prevent form submission on Enter key press
            $('#login-form').on("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission
            });

            // Prevent form submission on button click
            $('#btn-login').on("click", function(event) {
                event.preventDefault(); // Prevent the default form submission
                // console.log('pressed button');
                // showSnackbar('login...');

                let username = $('#username').val();
                let branch = $('#branch').val();
                let uid = '<?php echo uniqid(); ?>';

                $.ajax({
                    url: 'login.php',
                    type: 'POST',
                    data: {
                        username: username,
                        password: $('#password').val(),
                        branch: branch,
                        uid: uid,
                        device_platform: checkDevicePlatform()
                    },
                    dataType: 'text',
                    success: function(response) {
                        console.log(response);
                        // showSnackbar(resposnse);
                        let status = response.trim();
                        if (status == 'success') {
                            // Set a cookie with an expiry date (e.g., 20 years from now)
                            let expires = new Date(Date.now() + 20 * 365 * 24 * 60 * 60 * 1000).toUTCString();
                            document.cookie = `uid=${uid}; expires=${expires}`;
                            document.cookie = `branch=${branch}; expires=${expires}`;

                            console.log('permission: ' + Notification.permission);

                            // showSnackbar(Notification.permission);
                            if (Notification.permission == 'granted') {
                                // Permission granted, you can now subscribe to push notifications
                                // Notification.permission returns false in some case in iOS, therefore just subscribe notification
                                subscribePushNotifications(uid, branch);
                                // showSnackbar('Hi');
                            }
                        } else {
                            showSnackbar('Login failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending POST request:", error);
                        // showSnackbar(error);
                    }
                });
            });

            function goToLogin() {
                $('#staff-login-listing').hide();
                $('#login-page').show();
            }

            function goToStaffLoginListing() {
                $('#login-page').hide();
                $('#staff-login-listing').show(0, function() {
                    let uid = getCookie('uid') ?? '';
                    let branch = getCookie('branch') ?? '';
    
                    $('#staff-login-listing-title').html(`Staff login list - ${branch.toUpperCase()}`);

                    $('#date-filter-picker').datetimepicker({
                        format: 'DD/MM/YYYY',
                        ignoreReadonly: true
                    });

                    $("#date-filter-picker").on("change.datetimepicker", function (e) {
                        showSpinner();
                        viewHistory();
                    });

                    // Set timer to fetch pending list
                    pendingTimer = setInterval(() => {
                        fetchPending();
                    }, 3000);

                    // Set timer to fetch history list
                    historyTimer = setInterval(() => {
                        fetchHistory();
                    }, 3000);

                    // Populate the listing when the page loads
                    viewPending();
                    initDate();
                });
            }

            function togglePasswordVisibility() {
                var x = document.getElementById('password');
                let toggleBtn = $('#toggleBtn');
                let eyeIcon = $('#eye-icon');
                if (x.type === 'password') {
                    x.type = 'text';
                    eyeIcon.removeClass('fa-eye');
                    eyeIcon.addClass('fa-eye-slash');
                } else {
                    x.type = 'password';
                    eyeIcon.removeClass('fa-eye-slash');
                    eyeIcon.addClass('fa-eye');
                }
            }

            function addToHomeScreen() {
                console.log('addToHomeScreen');
                // Show the browser's install prompt
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                        } else {
                        console.log('User dismissed the install prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            }

            function subscribePushNotifications(uid, branch) {
                const publicKey = 'BLTcBSpIX05ltriAEXaYqP07pV0pXdRhWB-n0CkQeMZuLeKpnPWWlvNlZLQe37u3WtpcspkRsKp6al0yMcXir_k';

                // Register service worker
                if ('serviceWorker' in navigator) {
                    // Register a service worker hosted at the root of the
                    // site using the default scope.
                    navigator.serviceWorker.register('serviceWorker.js').then(
                        (registration) => {
                            console.log('Service worker registration succeeded:', registration);
                            // showSnackbar(registration);

                            // Subscribe to web push server
                            navigator.serviceWorker.ready
                                .then(async (serviceWorkerRegistration) => {
                                    // Service worker is ready for use
                                    console.log('Service Worker is ready:', serviceWorkerRegistration);

                                    // You can perform operations with the service worker here
                                    // For example, you can use it to handle fetch events or push notifications
                                    // Subscribe to push notifications
                                    try {
                                        const subscription = await serviceWorkerRegistration.pushManager.subscribe({
                                            userVisibleOnly: true, // Show notifications only when the user is active
                                            applicationServerKey: publicKey, // Your VAPID public key
                                        });

                                        // Send the subscription data to your server for further use
                                        console.log('Push Subscription:', JSON.stringify(subscription));

                                        // You can now send this subscription data to your server
                                        // Your server can then use this subscription to send push notifications to the client
                                        var data = new FormData();
                                        data.append('uid', uid);
                                        data.append('branch', branch);
                                        data.append('subscription', JSON.stringify(subscription));
                                        fetch('web_push_subscription.php', { method: 'POST', body : data })
                                            .then(res => res.text())
                                            .then(txt => {
                                                console.log(txt);
                                                goToStaffLoginListing();
                                            });

                                    } catch (error) {
                                        console.error('Error subscribing to push notifications:', error);
                                    }
                                })
                                .catch((error) => {
                                    console.error('Error getting Service Worker ready:', error);
                                });
                        },
                        (error) => {
                            console.error(`Service worker registration failed: ${error}`);
                            // showSnackbar(error);
                        },
                    );
                    // showSnackbar('register service worker');
                } else {
                    let error = 'Service workers are not supported.';
                    console.error(error);
                    // showSnackbar(error);
                }
            }

            // Function to populate the items list
            function fetchPending() {
                let container = $('#pending-container');

                if (isPendingTimerRunning) {
                    $.ajax({
                        url: 'get_staff_login_list.php',
                        type: 'POST',
                        data: {
                            'branch': branch
                        },
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            let html = '';

                            if (Array.isArray(response) && response.length > 0) {
                                container.html('');
                                response.forEach(function (item) {
                                    let card = `
                                        <div class="card mb-2">
                                            <div class="card-body py-2 px-3">
                                                <div class="col">
                                                    <div class="row">
                                                        <span>${item.name}</span>
                                                    </div>
                                                    <div class="row">
                                                        <span>${item.ipAddress}</span>
                                                    </div>
                                                </div>
                                                <button class="btn btn-success" onclick="updateApprovalStatus('${item.ipAddress}', 'APPROVED')">Approve</button>
                                            </div>
                                        </div>
                                    `;
                
                                    html += card;
                                });
                                container.html(html);
                            } else {
                                container.html('<div style="margin: auto;">No results found.</div>');
                            }
                            hideSpinner();
                            $('#pending-container').show();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error sending POST request:", error);
                        }
                    });
                }
            }

            function fetchHistory() {
                let container = $('#history-container');
                let date = $('#date').val();
                let formattedDate = '';

                if (date != '') {
                    let dateParts = date.split('/');
                    if (dateParts.length === 3) {
                        let day = dateParts[0];
                        let month = dateParts[1];
                        let year = dateParts[2];
                        
                        // Reorder the components as yyyy-mm-dd
                        formattedDate = year + '-' + month + '-' + day;
                    }
                }

                if (isHistoryTimerRunning) {
                    $.ajax({
                        url: 'get_login_history.php',
                        type: 'POST',
                        data: {
                            'uid': uid,
                            'branch': branch,
                            'date': formattedDate
                        },
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            let html = '';
                            
                            if (Array.isArray(response) && response.length > 0) {
                                container.html('');
                                response.forEach(function (item) {
                                    let card = `
                                        <div class="card mb-2">
                                            <div class="card-body py-2 px-3">
                                                <div class="col">
                                                    <div class="row">
                                                        <span>${item.name}</span>
                                                    </div>
                                                    <div class="row">
                                                        <span>Login time: ${item.login_datetime}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    
                                    html += card;
                                });
                                container.html(html);
                            } else {
                                container.html('<div style="margin: auto;">No results found.</div>');
                            }
                            hideSpinner();
                            $('#history-container').show();
                            $('#date-container').show();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error sending POST request:", error);
                        }
                    });
                }
            }

            function viewPending() {
                showSpinner();
                isHistoryTimerRunning = false;
                isPendingTimerRunning = true;
                $('#history-container').hide();
                $('#date-container').hide();
                $('#pending-container').hide();
                $('#btnPending').removeClass('btn-outline-primary').addClass('btn-primary');
                $('#btnHistory').removeClass('btn-primary').addClass('btn-outline-primary');
            }
            
            function viewHistory() {
                showSpinner();
                isPendingTimerRunning = false;
                isHistoryTimerRunning = true;
                $('#history-container').hide();
                $('#pending-container').hide();
                $('#btnHistory').removeClass('btn-outline-primary').addClass('btn-primary');
                $('#btnPending').removeClass('btn-primary').addClass('btn-outline-primary');
            }

            function initDate() {
                let currentDate = new Date();

                let day = currentDate.getDate().toString().padStart(2, '0'); // Ensure two digits
                let month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-indexed
                let year = currentDate.getFullYear();
                let formattedDate = `${day}/${month}/${year}`;

                $('#date').val(formattedDate);
            }

            function reset() {
                initDate();
                showSpinner();
                viewHistory();
            }

            function showSpinner() {
                $('#spinner').show();
                $('#listing-container').hide();
                $('#no-results').hide();
            }

            function hideSpinner() {
                $('#spinner').hide();
            }

            function updateApprovalStatus(ipAddress, status) {
                $.ajax({
                    url: 'update_approval_status.php',
                    type: 'POST',
                    data: {
                        'branch': branch,
                        'ipAddress': ipAddress,
                        'status': status
                    },
                    dataType: 'text',
                    success: function(response) {
                        console.log(response);
                        // let status = response.trim();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending POST request:", error);
                    }
                });
            }

            function logout() {
                $.ajax({
                    url: 'logout.php',
                    type: 'POST',
                    data: {
                        'branch': branch,
                        'uid': uid
                    },
                    dataType: 'text',
                    success: function(response) {
                        console.log(response);

                        // Clear cookies
                        document.cookie = 'uid=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                        document.cookie = 'branch=; expires=Thu, 01 Jan 1970 00:00:00 UTC';

                        goToLogin();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending POST request:", error);
                    }
                });
            }
        </script>
    </body>
</html>