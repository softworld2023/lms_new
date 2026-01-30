<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loan Management System</title>
        <link rel="stylesheet" href="include/css/bootstrap.min.css">
        <link rel="stylesheet" href="include/css/style.css">
        <link rel="stylesheet" href="include/fontawesome/css/fontawesome-all.css">
        <link rel="stylesheet" href="include/css/tempusdominus-bootstrap-4.min.css">
        <link rel="manifest" href="manifest.json" />

        <style>
            main {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
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

            #logout-btn {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>
    <body>
        <div class="app-bar p-3 pl-3 bg-primary">
            <span id="app-bar-title"></span>
            <a id="logout-btn" onclick="logout();">
                <svg xmlns="http://www.w3.org/2000/svg" style="fill: white" width="24" height="24" viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
            </a>
        </div>
        <main class="p-3">
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
        </main>

        <script src="include/js/jquery-2.1.3.min.js"></script>
        <script src="include/js/functions.js"></script>
        <script src="include/js/moment-with-locales.min.js"></script>
        <script src="include/js/tempusdominus-bootstrap-4.min.js"></script>

        <script>
            const baseUrl = 'https://linxpet.com/lms_pwa_server';
            const uid = getCookie('uid') ?? '';
            const branch = getCookie('branch') ?? '';

            let pendingTimer;
            let historyTimer;
            let isPendingTimerRunning = false;     // to control timer in async condition
            let isHistoryTimerRunning = false;

            $(document).ready(function () {
                $('#app-bar-title').html(`Staff login list - ${branch.toUpperCase()}`);

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

                // if (typeof(EventSource) !== 'undefined') {
                //     console.log('Yes! Server-sent events support!');
                //     let source = new EventSource(`${baseUrl}/api/server_stream_ajax.php`);
                //     source.onmessage = function(event) {
                //         console.log('data: ' + event.data);
                //     }; 
                // } else {
                //     console.log('Sorry! No server-sent events support..');
                // } 
            });

            // $(document).on('click', function() {
            //     showSnackbar('uid: ' + uid);
            // });

            // Function to populate the items list
            function fetchPending() {
                let container = $('#pending-container');

                if (isPendingTimerRunning) {
                    $.ajax({
                        url: `${baseUrl}/api/get_staff_login_list.php`,
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
                        url: `${baseUrl}/api/get_login_history.php`,
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
                    url: `${baseUrl}/api/update_approval_status_action.php`,
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
                    url: `${baseUrl}/api/logout_action.php`,
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

                        window.location.href = 'index.php';
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending POST request:", error);
                    }
                });
            }
        </script>
    </body>
</html>